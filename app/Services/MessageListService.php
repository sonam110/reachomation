<?php
namespace App\Services;
set_time_limit(0);
ini_set('memory_limit',-1);
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google_Client;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\SendCampaignMail;
use Krizalys\Onedrive\Onedrive;
use Exception;
use Illuminate\Support\Carbon;
class MessageListService {
  protected  $client;
  protected $clientId;
  protected $redirectUrl;
  protected $clientsecret;
  protected $outlookclient;


  public function __construct()
  {
   
      parent::__construct();
          /*----------Gmail----------------------*/
        $destinationPath    = storage_path('credential/');
        if(!File::isDirectory($destinationPath)){
            File::makeDirectory($destinationPath, 0755, true, true);
        }
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Reachomation');
        $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
        $this->client->setAuthConfig(storage_path('credential/'.env('CREDENTIAL_FILE_NAME','credential-prod').'.json'));
       
        $this->client->setAccessType('offline');      
        $this->client->setPrompt('select_account consent');   

        /*----Outlook-------------------*/
        $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
        $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

        $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
        $this->outlookclient = Onedrive::client($this->clientId);

  }
  /**
 * Get list of Messages in user's mailbox.
 *
 * @param  Google_Service_Gmail $service Authorized Gmail API instance.
 * @param  string $userId User's email address. The special value 'me'
 * can be used to indicate the authenticated user.
 * @return array Array of Messages.
 */
public function listMessages($service, $userId, $optArr = []) {
  $pageToken = NULL;
  $messages = array();
  do {
    try {
      if ($pageToken) {
        $optArr['pageToken'] = $pageToken;
      }
      $messagesResponse = $service->users_messages->listUsersMessages($userId, $optArr);
      if ($messagesResponse->getMessages()) {
        $messages = array_merge($messages, $messagesResponse->getMessages());
        $pageToken = $messagesResponse->getNextPageToken();
      }
    } catch (Exception $e) {
      print 'An error occurred: ' . $e->getMessage();
    }
  } while ($pageToken);

  return $messages;
}

public function getHeaderArr($dataArr) {
    $outArr = [];
    foreach ($dataArr as $key => $val) {
        $outArr[$val->name] = $val->value;
    }
    return $outArr;
}

public function getBody($dataArr) {
    $outArr = [];
    foreach ($dataArr as $key => $val) {
        $outArr[] = base64url_decode($val->getBody()->getData());
        break; // we are only interested in $dataArr[0]. Because $dataArr[1] is in HTML.
    }
    return $outArr;
}

public function base64url_decode($data) {
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

public function getMessage($service, $userId, $messageId) {
  try {
    $message = $service->users_messages->get($userId, $messageId);
   // print 'Message with ID: ' . $message->getId() . ' retrieved.' . "\n";

    return $message;
  } catch (Exception $e) {
    print 'An error occurred: ' . $e->getMessage();
  }
}

public function listLabels($service, $userId, $optArr = []) {
    $results = $service->users_labels->listUsersLabels($userId);

    if (count($results->getLabels()) == 0) {
      print "No labels found.\n";
    } else {
      print "Labels:\n";
      foreach ($results->getLabels() as $label) {
        printf("- %s\n", $label->getName());
      }
    }
}
public function checkToken($user_id,$id){
    if ($this->client->isAccessTokenExpired()) {
        //echo "<PRE>";print_R($this->client);die;
        if ($this->client->getRefreshToken()) {
            $accessToken = $this->client-> fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());

            $gmailauthData = EmailCollection::where('user_id',$user_id)->where('id',$id)->first();
           
            $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
            $gmailauthData->refreshtoken = $this->client->getRefreshToken();
            $gmailauthData->expired_in = $accessToken['expires_in'];
            $gmailauthData->save();

            return $accessToken;
        }
    }else{
        $gmailauthData = EmailCollection::where('user_id',$user_id)->where('id',$id)->first();
        $accessToken = $gmailauthData->accesstoken;
        return $accessToken;
    }

}

public function MessageResponse($from,$user_id){
    // Get the API client and construct the service object.
    $gmailauthData = EmailCollection::where('email',$from)->first();
    if(!is_object($gmailauthData)){
        return false;
    }
    //echo "<PRE>";print_R($gmailauthData);die;
    $accessToken = $gmailauthData->accesstoken;
    $this->client->setAccessToken($accessToken);
    $accessToken = self::checkToken($user_id,$gmailauthData->id);
    $service = new \Google_Service_Gmail($this->client);
    $this->client->setAccessToken($accessToken);
    $user = 'me';
    $msg_body =  [];
    $msg_id = '181dd437be393cab';
   
    $mailData = SendCampaignMail::where(['msg_id'=>$msg_id,'user_id'=>$user_id])->first();

    if(!empty($mailData)){

        $msgObj = $this->getMessage($service, $user,$msg_id);
        $threadId = $msgObj->threadId ;
 
        $thread = $service->users_threads->get($user,$threadId);

        if($thread) {
            $opt_param['threadId'] = $threadId;
            $thread_messages = $thread->getMessages($opt_param);
            //print_r($thread_messages);
            //echo Carbon::now() . '------Sync  Start------'  . PHP_EOL;
            $i = 0;
            
            foreach ($thread_messages as $key => $msg) {
                $mid = $msg->getId();
                if($msg_id != $mid){
                     $i++;
                     $mdetail = $this->getMessage($service, $user,$mid);
                $body = $this->getBody($mdetail->getPayload()->getParts());
              
               // echo 'Body: ' . (empty($body[0]) ? '' : $body[0]); 
                print_r($mid);
                print_r('--dd--');
                }
                
              
            }
            die;
            if($i >= '1'){
            if($thread_messages) {
                $messageId = $thread_messages[$i]->getId();
                $messageDetails = $this->getMessage($service, $user,$messageId);
                $headerArr1 = $this->getHeaderArr($messageDetails->getPayload()->getHeaders());
                $bodyArr1 = $this->getBody($messageDetails->getPayload()->getParts());
                // 'Body: ' . (empty($bodyArr1[0]) ? '' : $bodyArr1[0]); 
              //  print_r($headerArr1);
               // print_r($headerArr1['Subject']);


              
            }
           }
            print_r('-----------tttttt------');
            print_r($i);
          

          
          //  echo Carbon::now() . '------Sync  Start------'  . PHP_EOL;
            die;
           /* if($thread_messages) {
                $messageId = $thread_messages[0]->getId();
                $messageDetails = $this->getMessage($service, $user,$messageId);
                $headerArr1 = $this->getHeaderArr($messageDetails->getPayload()->getHeaders());
                $bodyArr1 = $this->getBody($messageDetails->getPayload()->getParts());
                //print_r($bodyArr1[0]);
                print_r('-----------');
                //print_r($threadId);
                // get the subject here from the headers of $messageDetails. You will use it below as $subject.
            }*/
        }
        
      
    }
                
            
    //return  $msg_body;
            
        
    
}

public function getOutlookReply($from,$user_id){
    $outlookuser = EmailCollection::where(['user_id'=>$user_id,'email'=>$from])->first();
    $subject = ' TWITTER MAIL&nbsp; sonam.patel@nrt.co.in &nbsp; ';
    $accessToken = self::getToken($user_id,$outlookuser->id);
    $conversionId = 'AQQkADAwATM3ZmYAZS01MGI4LTY1ZjgtMDACLTAwCgAQAN3uempC29hAtaCOaEFiPlY=';
    $id = 'AQMkADAwATM3ZmYAZS01MGI4LTY1ZjgtMDACLTAwCgBGAAADOML4xmC0_E2wYjMxZk9RkwcAIwnEY-JMSkGkViY3Ua7wbQAAAgEJAAAAIwnEY-JMSkGkViY3Ua7wbQAAAolAAAAA';
    $msgData = self::__curl('GET','https://graph.microsoft.com/v1.0/me/mailfolders/INBOX/messages?$search= subject eq '.trim($subject).'&orderby=ReceivedDateTime desc',$accessToken);
    $i =0;
    if(isset($msgData['value'])){
        foreach (@$msgData['value'] as $key => $msg) {
            if($conversionId== $msg['conversationId']){
                print_r($msg['bodyPreview']);
                print_r($msg['subject']);
                 $i++;
            }

        }
        die;
        print_r($i);
    }
    
        
}
public function getToken($userid,$id){
        $outlookuser = EmailCollection::where('user_id',$userid)->where('id',$id)->first();
        if($outlookuser){
            $accessToken = $outlookuser->accesstoken;
            $refreshtoken = $outlookuser->refreshtoken;
            $expired_time = $outlookuser->expired_in;
            $time = time();
            if($time >= $expired_time){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'client_id='.$this->clientId.'&redirect_uri='.$this->redirectUrl.'&client_secret='.$this->clientsecret.'&refresh_token='.$refreshtoken.'&grant_type=refresh_token',
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
                ),
              ));
            $response = curl_exec($curl);
            if(!empty($tokenData)){
                $accessToken = $tokenData->access_token;
                $resfreshToken = $tokenData->refresh_token;
                $endtime = strtotime("+60 minutes");

                $outlookuser->accesstoken = $accessToken;
                $outlookuser->refreshtoken = $resfreshToken;
                $outlookuser->expired_in = $endtime;
                $outlookuser->save();

                return $accessToken;
            } else{
                return null;
            }

            }else{
                return $accessToken;
            }
        }
    }
     public function __curl($method,$url,$accessToken){
        //echo "<br/>".str_replace(' ','%20',$url);die;
        $curlmess = curl_init();
        curl_setopt_array($curlmess, array(
        CURLOPT_URL => str_replace(' ','%20',$url),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$accessToken,
            'Content-Type: application/json'
        ),
      ));
      $response_mess = curl_exec($curlmess);
      //echo "<PRE>";print_R($response_mess);die;
      $msgData = json_decode($response_mess, true);
      return $msgData;
    }

   
}
