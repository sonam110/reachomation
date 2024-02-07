<?php

namespace App\Http\Controllers;
set_time_limit(0);
ini_set('memory_limit',-1);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google_Client;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\SubscriptionPlan;
use Krizalys\Onedrive\Onedrive;
use Mail;
use Exception;
use App\Mail\EmailAcoundAddedMail;
class AddEmailController extends Controller
{
    private $client;
    private $clientId;
    private $redirectUrl;
    private $redirectUriGmail;
    private $clientsecret;
    private $outlookclient;
    public function __construct()
    {
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
        $this->redirectUriGmail = env('GOOGLE_REDIRECT_URI','https://reachomation.com/callback_gmail');

        /*----Outlook-------------------*/
        $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
        $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

        $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
        $this->outlookclient = Onedrive::client($this->clientId);
          
    }
    public function rerirectToAccount(Request $request){
        try {
            
            $checkEmailCount = EmailCollection::where('user_id',auth()->user()->id)->where('status','1')->count();
            $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
            if(empty($plan)){
                return response()->json(['type'=> 'error','message'=>'You can not add emails! you have no purchase plan']);
            }
            $mail_connect = ($plan->mail_connect == 'Unlimited') ? '10000000000000' :$plan->mail_connect ;
            if($checkEmailCount >= $mail_connect ){
                $message = ' With a '.$plan->name.' plan, you can only have '.$mail_connect.' active email(s) connected to your account. To add more email accounts';
                return response()->json(['type'=> 'error','message'=>$message]);
                
            }
            if($request->account_type=='gmail'){
                $google_login_url = self::getauthurl();
                $redirectUrl = $google_login_url;
            } else {
                $outlook_login_url = $this->outlookclient->getLogInUrl([
                             // 'files.read',
                              //'files.read.all',
                              //'files.readwrite',
                              //'files.readwrite.all',
                              //'Mail.Read',
                              //'Mail.Read.Shared',
                              //'Mail.ReadBasic',
                              'Mail.ReadWrite',
                              //'Mail.ReadWrite.Shared',
                              'Mail.Send',
                              //'Mail.Send.Shared',
                              'User.Read',
                              'profile',
                              'openid',
                              'offline_access',
                          ], $this->redirectUrl);
                $redirectUrl = $outlook_login_url;
            }
             return response()->json(['type'=> 'success','redirectUrl'=>$redirectUrl]);

        }
        catch(Exception $exception) {
           
            return response()->json(['type'=> 'failed']);
            
        }   


    }
    public function getauthurl(){
        $authUrl = $this->client->createAuthUrl();
        return $authUrl;
    }

    
    public function callbackGmail(Request $request){
        // dd($request);
        try{

            if(!empty($_GET['code'])){
              
                if ($this->client->isAccessTokenExpired()) {
                    
                    // Refresh the token if possible, else fetch a new one.
                    $checkAccount = EmailCollection::where('user_id',auth()->user()->id)->where('is_default','1')->first();
                    if ($this->client->getRefreshToken()) {
                        $accessToken = $this->client-> fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                        $user = 'me';
                        $service = new \Google_Service_Gmail($this->client);
                        $oauth2 = new  \Google_Service_Oauth2($this->client);
                        $userInfo = $oauth2->userinfo_v2_me->get();
                       
                        $email = @$userInfo->email;
                        $picture = @$userInfo->picture;
                        $name = @$userInfo->name;
                        $eid = @$userInfo->id;

                        $checkAlready = EmailCollection::where('user_id',auth()->user()->id)->where('email',$email)->first();
                        if(!empty($checkAlready)){
                            $gmailauthData = EmailCollection::find($checkAlready->id);
                            $gmailauthData->daily_limit =  $checkAlready->daily_limit;
                            $gmailauthData->is_default =  $checkAlready->is_default;
                            $gmailauthData->name =  $checkAlready->name;
                        } else{
                            $gmailauthData = new EmailCollection();
                            $gmailauthData->daily_limit  = '1500';
                            $gmailauthData->is_default  = (empty($checkAccount)) ? 1 :0;
                            $gmailauthData->name =  $name;
                        }
                        $gmailauthData->user_id = auth()->user()->id;
                        $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                        $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                        $gmailauthData->expired_in = $accessToken['expires_in'];
                        $gmailauthData->email  = $email;
                        $gmailauthData->eid = $eid;
                        $gmailauthData->picture = $picture;
                        $gmailauthData->account_type = '1';
                        $gmailauthData->status  = '0';

                        $gmailauthData->save();
                    } else {
                        $accessToken = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
                        $this->client->setAccessToken($accessToken);
                        $user = 'me';
                        $service = new \Google_Service_Gmail($this->client);
                        $oauth2 = new  \Google_Service_Oauth2($this->client);
                        $userInfo = $oauth2->userinfo_v2_me->get();
                        $email = @$userInfo->email;
                        $picture = @$userInfo->picture;
                        $name = @$userInfo->name;
                        $eid = @$userInfo->id;

                        $checkAlready = EmailCollection::where('user_id',auth()->user()->id)->where('email',$email)->first();

                        if(!empty($checkAlready)){
                            $gmailauthData = EmailCollection::find($checkAlready->id);
                            $gmailauthData->daily_limit =  $checkAlready->daily_limit;
                            $gmailauthData->is_default =  $checkAlready->is_default;
                            $gmailauthData->name =  $checkAlready->name;
                        } else{
                            $gmailauthData = new EmailCollection();
                            $gmailauthData->daily_limit  = '1500';
                            $gmailauthData->is_default  = (empty($checkAccount)) ? 1 :0;
                            $gmailauthData->name =  $name;
                        }
                        $gmailauthData->user_id = auth()->user()->id;
                        $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                        $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                        $gmailauthData->expired_in = $accessToken['expires_in'];
                        $gmailauthData->status  = '0';
                        $gmailauthData->reconnect  = '0';
                        $gmailauthData->email  = $email;
                        $gmailauthData->eid = $eid;
                        $gmailauthData->picture = $picture;
                        $gmailauthData->account_type = '1';
                        $gmailauthData->save();

                        if (array_key_exists('error', $accessToken)) {
                            throw new Exception(join(', ', $accessToken));
                        }
                    }

                    
                   
                    $gmailauthData = EmailCollection::find($gmailauthData->id);
                    $gmailauthData->status  = '1';
                    $gmailauthData->account_type  = '1';
                    $gmailauthData->save();
                    $content = [
                        'name' => auth()->user()->name,
                        'email' => $email,

                    ];
                    if (env('IS_MAIL_ENABLE', false) == true) {
                        Mail::to(auth()->user()->email)->send(new EmailAcoundAddedMail($content));
                    }


                }
                return redirect()->route('settings')->with('success','A new email has been added!');
                
            } else {
                return redirect()->route('settings')
                ->with('error','Email account was not added successfully ');
            }
        }catch(Exception $exception) {
           
            return  redirect()->route('settings')->with('error',$exception->getMessage());
            
        } 
    }

     public function callback_outlook(Request $request){
        try{
       
            $curl = curl_init();
            if(isset($_GET['code'])){
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'client_id='.$this->clientId.'&redirect_uri='.$this->redirectUrl.'&client_secret='.$this->clientsecret.'&code='.$_GET['code'].'&grant_type=authorization_code',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/x-www-form-urlencoded'
                ),
              ));

              $response = curl_exec($curl);

              $tokenData = json_decode($response);
                
              $accessToken = $tokenData->access_token;
              $resfreshToken = $tokenData->refresh_token;
              $endtime = strtotime("+60 minutes");

              $userData = self::__curl('GET','https://graph.microsoft.com/v1.0/me',$accessToken);
             
              $surname = @$userData['surname'];
              $givenName = @$userData['givenName'];
              $userPrincipalName = @$userData['userPrincipalName'];
              $eid = @$userData['id'];

              $checkAlready = EmailCollection::where('user_id',auth()->user()->id)->where('email',$userPrincipalName)->first();
              $checkAccount = EmailCollection::where('user_id',auth()->user()->id)->where('is_default','1')->first();
              if(!empty($checkAlready)){
                    $OutlookAuthUser = EmailCollection::find($checkAlready->id);
                    $OutlookAuthUser->daily_limit =  $checkAlready->daily_limit;
                    $OutlookAuthUser->is_default =  $checkAlready->is_default;
                    $OutlookAuthUser->name =  $checkAlready->name;
                } else{
                    $OutlookAuthUser = new EmailCollection();
                    $OutlookAuthUser->daily_limit  = '1500';
                    $OutlookAuthUser->is_default  = (empty($checkAccount)) ? 1 :0;
                  
                }
             
              $OutlookAuthUser->user_id = auth()->user()->id;
              $OutlookAuthUser->accesstoken = $accessToken;
              $OutlookAuthUser->refreshtoken = $resfreshToken;
              $OutlookAuthUser->expired_in = $endtime;
              $OutlookAuthUser->name = $givenName.' '.$surname;
              $OutlookAuthUser->email = $userPrincipalName;
              $OutlookAuthUser->eid = $eid;
              $OutlookAuthUser->account_type = '2';
              $OutlookAuthUser->status = '1';
              $OutlookAuthUser->save();
            
              $content = [
                    'name' => auth()->user()->name,
                    'email' => $userPrincipalName ,

                ];
                if (env('IS_MAIL_ENABLE', false) == true) {
                    Mail::to(auth()->user()->email)->send(new EmailAcoundAddedMail($content));
                }
                return redirect()->route('settings')
                ->with('success','A new email has been added!');

            } else {
                return redirect()->route('settings')
                ->with('error','Email account was not added successfully ');
            }
        }catch(Exception $exception) {
           
            return  redirect()->route('settings')->with('error',$exception->getMessage());
            
        } 

    }

    public function __curl($method,$url,$accessToken){
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

    public function editMailAccount(Request $request){
        $this->validate($request, [
            'name'              => ['required','max:25'],
        ]);
       
        if($request->edit_id !=''){
           
          $editMailAccount = EmailCollection::find($request->edit_id);
          $editMailAccount->name = $request->name;
          $editMailAccount->is_default = ($request->is_default =='1') ? true : false;
          $editMailAccount->save();
          if ($request->is_default) {
              $update_is_default = EmailCollection::where('user_id', auth()->user()->id)->where('id', '!=', $editMailAccount->id)->update(['is_default' => false]);
          }
          $checkAccount = EmailCollection::where('user_id',auth()->user()->id)->where('is_default','1')->first();
          if(is_object($checkAccount )){
           
             return redirect()->back()->with('success','Successfully Updated!');
           } else {
              $updateOld = EmailCollection::where('id',$request->edit_id)->update(['is_default'=>'1']);
              
              return redirect()->back()
              ->with('error','Action not done You can set alteast one default account');
          }
         
        } else {
            return redirect()->back()
            ->with('error','Opps! Something went wrong');
        }




    }

     public function accountdelete(Request $request)
    {  
        $id = $request->account_id;
        $checkAccount = EmailCollection::where('user_id',auth()->user()->id)->where('id', $id)->first();
        if(!is_object($checkAccount))
        {
           return redirect()->back()
            ->with('error','Email Account not found');
        }
        $campaign = Campaign::where('from_email',$checkAccount->email)->where('user_id',auth()->user()->id)->whereNotIn('status',['6','8'])->count();
        if($campaign > 0){
            return redirect()->back()
            ->with('error','You cannot delete this email account, the campaign is linked with this email account');
        }
        if ($checkAccount->is_default == '1') {
            return redirect()->back()
            ->with('error','You Cant Delete this email account please set other email as defult email');
        }

        EmailCollection::where('id', $id)->update(['status'=>'2']);
       return redirect()->back()->with('Success, Account successfully deleted.');
        
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
            $tokenData = json_decode($response);

            $accessToken = $tokenData->access_token;
            $resfreshToken = $tokenData->refresh_token;
            $endtime = strtotime("+60 minutes");

            $outlookuser->accesstoken = $accessToken;
            $outlookuser->refreshtoken = $resfreshToken;
            $outlookuser->expired_in = $endtime;
            $outlookuser->save();

            return $accessToken;

            }else{
                return $accessToken;
            }
        }
    }
    public function checkToekn($user_id,$id){
        if ($this->client->isAccessTokenExpired()) {
            //echo "<PRE>";print_R($this->client);die;
            if ($this->client->getRefreshToken()) {
                $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());

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



   
}
