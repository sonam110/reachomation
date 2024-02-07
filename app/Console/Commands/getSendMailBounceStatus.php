<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google_Client;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\SendCampaignMail;
use App\Models\BlackListEmail;
use Krizalys\Onedrive\Onedrive;
use Exception;
use DateTime;
use DateTimeZone;
use DateInterval;
use DatePeriod;
use Log;
use Illuminate\Support\Carbon;
use App\Models\Inbox;
use App\Models\Feed;
use Mail;
use App\Mail\CampaignStopMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CampiagnNotification;
class getSendMailBounceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:mail-bounce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Mail Bounce status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $client;
    private $clientId;
    private $redirectUrl;
    private $clientsecret;
    private $outlookclient;
    public function __construct()
    {
        parent::__construct();
        /*----------Gmail----------------------*/
        $destinationPath    = storage_path('credential/');
        if(!File::isDirectory($destinationPath)){
            File::makeDirectory($destinationPath, 0777, true, true);
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sendMailData = Campaign::select('campaigns.*','send_campaign_mails.*','send_campaign_mails.id as send_id','campaigns.id as camp_id',)
            ->whereIn('campaigns.status',['0','2','3','4','8'])
            ->join('send_campaign_mails','campaigns.id','=','send_campaign_mails.camp_id')
            ->where('send_campaign_mails.status','1')
            ->orderby('campaigns.id','ASC')
            ->whereBetween('send_campaign_mails.default_timezone_date', [now()->subMinutes(1), now()])
            ->with('fromUser')->get();
            \Log::info('SendMailBounceStatus----- Start----');
            if(count($sendMailData) > 0) {
                echo Carbon::now()->format('Y-m-d H:i:s') . '------check mail bounce  Start------';  
                \Log::info('SendMailBounceStatus----- Data available to process----');
                foreach ($sendMailData as $key => $mail) {

                   // self::mailClient();
                    $this->client = new \Google_Client();
                    $this->client->setApplicationName('Reachomation');
                    $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
                    $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
                    $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
                    //$this->client->setScopes(array('https://www.googleapis.com/auth/drive','https://www.googleapis.com/auth/drive.file','https://www.googleapis.com/auth/spreadsheets'));
                    
                    $this->client->setAuthConfig(storage_path('credential/'.env('CREDENTIAL_FILE_NAME','credential-prod').'.json'));
                    
                    $this->client->setAccessType('offline');  
                   // $this->client->addScope('email');
                   // $this->client->addScope('https://mail.google.com');             
                    $this->client->setApprovalPrompt('force');               
                    $this->client->setPrompt('select_account consent');   

                     /*----Outlook-------------------*/
                    $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
                    $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

                    $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
                    $this->outlookclient = Onedrive::client($this->clientId);
                    if($mail->type=='1'){
                        
                        $DataToUpdateInSystem['bounce_mail_res'][] = self::getGmailBounceStatus($mail->send_id,$mail->msg_id,@$mail->fromUser,$mail->mail_send_date,$mail->camp_id,$mail->from_email,$mail->to_email);
                        \Log::info('SendMailBounceStatus----- Data Sent to gmail function to check bounce----');
                    } else{
                        $DataToUpdateInSystem['bounce_mail_res'][] = self::getOutLookBounceStatus($mail->send_id,@$mail->fromUser,$mail->mail_send_date,$mail->subject,$mail->camp_id);
                        \Log::info('SendMailBounceStatus----- Data Sent to outlook function to check bounce----');
                    }
                
                
            }
            if(isset($DataToUpdateInSystem['bounce_mail_res']) && !empty($DataToUpdateInSystem['bounce_mail_res'])){
                SendCampaignMail::massUpdate(values:@$DataToUpdateInSystem['bounce_mail_res']);
                \Log::info('SendMailBounceStatus-----Data Sent To Update----');
            }
            \Log::info('SendMailBounceStatus----- End----');
            echo Carbon::now()->format('Y-m-d H:i:s') . '------check mail bounce  end------';
        }
            

    }
    function mailClient(){
        
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Reachomation');
        $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
        
        $this->client->setAuthConfig(storage_path('credential/'.env('CREDENTIAL_FILE_NAME','credential-prod').'.json'));
        
        $this->client->setAccessType('offline');  
        $this->client->setApprovalPrompt('force');               
        $this->client->setPrompt('select_account consent');   

         /*----Outlook-------------------*/
        $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
        $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

        $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
        $this->outlookclient = Onedrive::client($this->clientId);


    }

    public function getGmailBounceStatus($send_id,$msg_id,$gmailauthData,$send_date,$camp_id,$from,$to) {
      
        $resultArr =[];
        try {
            \Log::info('SendMailBounceStatus----- inside gmail bounce check function----');
            $status = '1';
            $nowDate = now();
            $this->client->setAccessToken($gmailauthData->accesstoken);
        
            $checkToken = $this->checkToken($gmailauthData);
            if (@$checkToken['accessToken'] == NULL) {
                $is_account_inactive = true;
                $reason = @$checkToken['reason'].'FROM-GOOGLE';
                $this->campaignPauseMail($camp_id, $reason);
                \Log::info('SendMailBounceStatus---token-issue----'.$reason);
            }
            $service = new \Google_Service_Gmail($this->client);
            $this->client->setAccessToken($checkToken['accessToken']);

            $oauth2 = new \Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo_v2_me->get();
            if ($from != @$userInfo->email) {
                $this->campaignPauseMail($camp_id, 'Email disconnect due to server error');
                \Log::info('SendMailBounceStatus-----token not refresh and auth email not same as from email');
            }

            $bouncedMail = array();
          
            $messageDetails = $this->getMessage($service, 'me', $msg_id);
            $header = $this->getHeaderArr($messageDetails->getPayload()->getHeaders());
            $body = $this->getBody($messageDetails->getPayload()->getParts());
            
            $timestamp = strtotime($header['Date']);
            Log::info('SendMailBounceStatus----'.$header['Date']);
            $beforetime = strtotime("-1 minutes",$timestamp);


            $list = $service->users_messages->listUsersMessages('me', [
                'maxResults' => 1,
             'q' => "Your message wasn't delivered to ".$to.""
             ]);

            $messageList = $list->getMessages();

            if(!empty($messageList)){
                foreach($messageList as $list){
                    if($msg_id == $list->threadId){
                        $status = '3';
                    }
                }
            }
            //print_r($messageList);
           // die;

            $bounce_messages = self::listMessages($service, 'me', [
                //'q' => "from:mailer-daemon  after:".$timestamp." before:".strtotime($before),
                //'q'=> "rfc822msgid:".@$header['Message-Id']
                 //'q'=> "from:mailer-daemon rfc822msgid:".@$header['Message-Id']
                'q' => "from:mailer-daemon after:".$timestamp
            ]);
            
            $mSubject = strtolower(preg_replace('/\s+/', '', @$header['Subject']));
            $from_mail = strtolower(preg_replace('/\s+/', '', @$header['From']));
            //print_r($from_mail);
            // Log::info($from_mail);
             // print_r($header);
            Log::info('SendMailBounceStatus----'.'msg_id');
            Log::info('SendMailBounceStatus----'.$msg_id);
            if(!empty($bounce_messages)){
                foreach($bounce_messages as $bounce){
                    Log::info('SendMailBounceStatus----'.'bounce_id');
                    Log::info('SendMailBounceStatus----'.$bounce->id);
                    $bounceMsg = $this->getMessage($service, 'me', @$bounce->id);
                    $bounceBody = $this->getBody(@$bounceMsg->getPayload()->getParts());
                    $bounceheader = $this->getHeaderArr(@$bounceMsg->getPayload()->getHeaders()); 
                    $bounceMsgs = (empty(@$bounceBody[0])) ? '' : substr(@$bounceBody[0],0,41);
                    $bmsg = strtolower(preg_replace('/\s+/', '', @$bounceMsgs));
                    $bSubject = strtolower(preg_replace('/\s+/', '', @$bounceheader['Subject']));
                    $bfrom_mail = strtolower(preg_replace('/\s+/', '', @$bounceheader['To']));
                    $bsub = substr(@$bSubject,'3');
                       //print_r($bounceheader);
                     //print_r('--subs----');
                     //print_r($bfrom_mail);
                    
                    if( trim(env('LIMIT_REACH_MSG')) == trim(strtolower(@$bmsg))  && $from_mail == $bfrom_mail){
                        Log::info('SendMailBounceStatus----'.$mSubject);
                        Log::info('SendMailBounceStatus----'.$bsub);
                        $status = '3';
                        \Log::info('SendMailBounceStatus----- youhavereachedalimitforsendingmail----');
                        $this->campaignPauseMail($camp_id, 'youhavereachedalimitforsendingmail');

                    } 
                   

                }
            }
            
            $resultArr =[
                'id'=>$send_id,
                'status'=> $status,
                'mail_send_date'=> ($status =='3') ? $nowDate : $send_date,
            ];
            Log::info('SendMailBounceStatus------------Bounce Check--------');
            Log::info('SendMailBounceStatus----'.json_encode($resultArr));
           
          
            return $resultArr;
        } catch(Exception $exception) {
            Log::info('SendMailBounceStatus----'.'bounce excep');
            Log::error('SendMailBounceStatus----'.json_encode($exception->getMessage()));
            
        }
    }
    public function getOutLookBounceStatus($send_id,$outlookuser,$send_date,$subject,$to) {
        try {
            $nowDate = now();
            $status = '1';
            $resultArr =[];
            $accessToken = $outlookuser->accesstoken;
            $accessToken = $this->getToken($outlookuser);
            $formateDate = date('Y-m-d',strtotime($send_date));
            $formateDate = $formateDate.'T'.date('h:m:s').'Z';
            $bounceData = self::__curl('GET','https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages?$search="from:postmaster"&?$filter=receivedDateTime ge '.$formateDate,$accessToken);
            //echo "<PRE>";print_R($bounceData);die;


            $bounceItem = array();
            if(!empty($bounceData)){
                if(isset($bounceData['value'])) {
                    foreach($bounceData['value'] as $bounceG){
                        if(trim(str_replace('Undeliverable:','',$bounceG['subject'])) == trim($subject)){
                            foreach($bounceG['toRecipients'] as $emailvalarr){
                                if($emailvalarr['emailAddress']['address'] == $to){
                                   $status ='3';
                                  
                                } 
                               
                            }
                        }
                    }
                }
    
            }
            $resultArr =[
                'id'=>$send_id,
                'status'=> $status,
                'mail_send_date'=> ($status =='3') ? $nowDate : $send_date,
            ];
            Log::info('SendMailBounceStatus----'.'--------Bounce Check--------');
            Log::info('SendMailBounceStatus----'.json_encode($resultArr));
            return $resultArr;
        } catch(Exception $exception) {
            Log::info('SendMailBounceStatus----'.'excep bounce');
            Log::error('SendMailBounceStatus----'.json_encode($exception->getMessage()));
            
        }

    }
    public  function campaignPauseMail($camp_id, $reason = 'You have reached a limit for sending mail') { 

        $updateCamp = Campaign::find($camp_id);
        $updateCamp->status = '5';
        $updateCamp->reason = $reason.'.SMB';
        $updateCamp->save();

        $updateAccount = EmailCollection::find($updateCamp->mail_account_id);
        $updateAccount->daily_limit ='0';
        $updateAccount->save();

        $fname =  ($updateCamp->is_parent==NULL)  ? 'outreach'  :'followup' ; 
        $content = [
            'name' => ($updateCamp->user) ? $updateCamp->user->name :'User',
            'camp_name' => $updateCamp->name,
            'reason' => $reason,
            'stage' => $fname,
            'attemp' => $updateCamp->attemp,
           
        ];
        if (env('IS_NOTIFY_ENABLE', false) == true) {
            Notification::send($updateCamp->user, new CampiagnNotification($updateCamp));
        }
        if (env('IS_MAIL_ENABLE', false) == true) {
            Mail::to($updateCamp->user->email)->send(new CampaignStopMail($content));
        }

    }
    public function getToken($outlookuser) {
        if($outlookuser){
            $accessToken = $outlookuser->accesstoken;
            $refreshtoken = $outlookuser->refreshtoken;
            $expired_time = $outlookuser->expired_in;
            $time = time();
            if ($time >= $expired_time) {
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
                    CURLOPT_POSTFIELDS => 'client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUrl . '&client_secret=' . $this->clientsecret . '&refresh_token=' . $refreshtoken . '&grant_type=refresh_token',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));
                $response = curl_exec($curl);
                $tokenData = json_decode($response);
                if (!empty($tokenData)) {
                    $accessToken = $tokenData->access_token;
                    $resfreshToken = $tokenData->refresh_token;
                    $endtime = strtotime("+60 minutes");
                    $outlookuser->accesstoken = $accessToken;
                    $outlookuser->refreshtoken = $resfreshToken;
                    $outlookuser->expired_in = $endtime;
                    $outlookuser->save();

                    return $accessToken;
                }
            } else {
                return $accessToken;
            }
        }
    }

    public function checkToken($gmailauthData) {
        \Log::info('SendMailBounceStatus----- Inside check access token----');
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                if (@$accessToken['error']) {
                    if (@$accessToken['error'] == 'invalid_grant') {
                        $reason = 'Error in connecting your Email account';
                    } elseif (@$accessToken['error'] == 'unauthorized_client ') {
                        $reason = 'Email disconnect unauthorized client';
                    } else {
                        $reason = 'Multiple email block';
                    }
                    $tokenResponse = [
                        'accessToken' => NULL,
                        'reason' => $reason,
                    ];
                    \Log::error('SendMailBounceStatus----'.$accessToken['error']);
                    \Log::info('SendMailBounceStatus----- token error----');
                    return $tokenResponse;
                }else{
                $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                $gmailauthData->expired_in = @$accessToken['expires_in'];
                $gmailauthData->save();
                \Log::info('New acessToken in getSendMailBounceStatus');
                $tokenResponse = [
                    'accessToken' => $gmailauthData->accesstoken,
                    'reason' => '',
                ];
                $this->client->setAccessToken($gmailauthData->accesstoken);
                return $tokenResponse;
                }
            }else{
                $tokenResponse = [
                    'accessToken' => NULL,
                    'reason' => 'Email disconnect',
                ];
                \Log::info('SendMailBounceStatus----- email disconnected token expired----');
                return $tokenResponse;
            }
        } else {
            \Log::info('old acessToken used in getSendMailBounceStatus');
            $this->client->setAccessToken($gmailauthData->accesstoken);
            $tokenResponse = [
                'accessToken' => $gmailauthData->accesstoken,
                'reason' => '',
            ];
            return $tokenResponse;
        }
        
    }
     public function getMessage($service, $userId, $messageId) {
      try {
        $message = $service->users_messages->get($userId, $messageId);
        return $message;
      } catch (Exception $e) {
        \Log::error('SendMailBounceStatus----');
        \Log::error($e->getMessage());
      }
    }

    public function listMessages($service, $userId, $optArr = []) {
        $pageToken = NULL;
        $messages = array();
        $optArr['labelIds'] = 'INBOX';
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
                \Log::error('SendMailBounceStatus----');
                \Log::error($e->getMessage());
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
    public function getBody($dataArr) {
    $outArr = [];
    foreach ($dataArr as $key => $val) {
        $outArr[] = base64url_decode($val->getBody()->getData());
        break; // we are only interested in $dataArr[0]. Because $dataArr[1] is in HTML.
    }
    return $outArr;
}
}
