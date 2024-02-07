<?php

namespace App\Http\Controllers;
set_time_limit(0);
ini_set('memory_limit',-1);
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google_Client;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\User;
use App\Models\SendCampaignMail;
use App\Mail\CampaignCompleteMail;
use App\Mail\CampaignStopMail;
use App\Mail\CampaignStartMail;
use App\Mail\LimitExceedMail;
use App\Models\BlackListEmail;
use Krizalys\Onedrive\Onedrive;
use Exception;
use DateTime;
use Mail;
use DateInterval;
use DatePeriod;
use Str;
use Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CampiagnNotification;
use App\Exports\ReportExport;
use Excel;
use App\Models\BounceMail;
use Illuminate\Http\Request;

class RefreshTokenController extends Controller
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
        
    }
    public function refreshtoken(Request $request){
        $currentDate = Carbon::now()->timezone('Asia/Kolkata')->format("Y-m-d");
        $EmailCollection = EmailCollection::select('id','email','accesstoken','refreshtoken','expired_in')->where('account_type','1')->where('status','1')->get();
            foreach ($EmailCollection as $key => $email) {
                $this->client = new \Google_Client();
                $this->client->setApplicationName('Reachomation');
                $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
                $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
                $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
                $this->client->setAuthConfig(storage_path('credential/'.env('CREDENTIAL_FILE_NAME','credential-prod').'.json'));
            
                $this->client->setAccessType('offline');    
                $this->client->addScope('email');
                $this->client->addScope('https://mail.google.com');             
                $this->client->addScope('https://www.googleapis.com/auth/drive');             
                $this->client->addScope('https://www.googleapis.com/auth/drive.file');             
                $this->client->addScope('https://www.googleapis.com/auth/spreadsheets');             
                $this->client->setPrompt('select_account consent');   
                $this->redirectUriGmail = env('GOOGLE_REDIRECT_URI','https://reachomation.com/callback_gmail');

                /*----Outlook-------------------*/
                $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
                $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

                $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
                $this->outlookclient = Onedrive::client($this->clientId);
                
                $checkToken = $this->checkToken($email);
                if(empty($checkToken['accessToken'])){
                    $accessToken = $email->accesstoken;
                } else{
                    $accessToken = $checkToken['accessToken']; 
                }
                $service = new \Google_Service_Gmail($this->client);
                $this->client->setAccessToken($accessToken);
                $user = 'me';
                $service = new \Google_Service_Gmail($this->client);
                $oauth2 = new  \Google_Service_Oauth2($this->client);
                $userInfo = $oauth2->userinfo_v2_me->get();
                \Log::info(@$email->id);
                \Log::info(@$userInfo->email);
    
               // print_r($this->client);

              
            }
            

    }

    public function checkToken($gmailauthData){
        //dd($gmailauthData);
        $tokenResponse =[];
        try {

            if(!empty($gmailauthData)){

                if ($this->client->getRefreshToken()) {
                    $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    
                    if(@$accessToken['error']){
                        \Log::info(' error 108 ln');
                        if(@$accessToken['error'] =='invalid_grant'){
                            $reason = 'Error in connecting your Email account';
        
                        }elseif(@$accessToken['error'] =='unauthorized_client '){ 
                            $reason = 'Email disconnect unauthorized client';
                            
                        } else{
                            $reason = 'Multiple email block';
                        }
                        $tokenResponse =[
                            'accessToken' =>NULL,
                            'reason' =>$reason,

                        ];
                        return $tokenResponse;
                    } else{
                        $oldToken = $gmailauthData->accesstoken;
                        $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());

                        $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                        $gmailauthData->expired_in =  $accessToken['expires_in'];
                        $gmailauthData->save();
                         \Log::info('New acessToken in new sendemail');
                       
                        $tokenResponse =[
                            'id' => $gmailauthData->id,
                            'email' => $gmailauthData->email,
                            'accessToken' => $gmailauthData->accesstoken,
                            'oldToken' => $oldToken,
                            'refreshtoken' => $this->client->getRefreshToken(),
                            'reason' =>'',

                        ];
                        \Log::info($tokenResponse);
                        return $tokenResponse;
                    }
                    
                }
                
               
            } else{
                $tokenResponse =[
                    'accessToken' => NULL,
                    'reason' => 'Email disconnect',

                ];
                return $tokenResponse;
            }
            
        }catch(Exception $exception) {
            Log::info('gmail token generated exp');
            \Log::error($exception->getMessage());
           $tokenResponse =[
                'accessToken' => NULL,
                'reason' => 'Email disconnect',

            ];
            return $tokenResponse;
            
        }
        
    }
}
