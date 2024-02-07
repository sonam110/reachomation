<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
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
class sendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Campaign Mail to all users ';

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
       // $type = $this->argument('type');
       DB::enableQueryLog();
        
        $currentNow = Carbon::now()->format("Y-m-d");
        $send_mail_ids = Campaign::select('send_campaign_mails.id')
            ->join('send_campaign_mails','campaigns.id','=','send_campaign_mails.camp_id')
            ->where('send_campaign_mails.status','0')
            ->whereIn('campaigns.status',['0','2','3'])
            ->whereDate('campaigns.timezone_start_date','<=',$currentNow)
            ->groupBy('campaigns.from_email')
            ->pluck('send_campaign_mails.id');
            \Log::info('1st query to extract mail ids to send --'.$send_mail_ids);

            // dd($currentNow);

        //    dd($send_mail_ids);

            $campaigns = Campaign::select('campaigns.*','send_campaign_mails.*','campaigns.status as camp_status','campaigns.name as name','send_campaign_mails.status as send_status','campaigns.id as camp_id','campaigns.user_id as user_id','send_campaign_mails.id as send_id', DB::raw("(SELECT count(id) from send_campaign_mails WHERE send_campaign_mails.camp_id = campaigns.id and send_campaign_mails.status = '3' ) bounceMailCount"), DB::raw("(SELECT count(id) from send_campaign_mails WHERE send_campaign_mails.camp_id = campaigns.id and send_campaign_mails.status IN ('1','2','3','4','5') ) CompleteCount"), DB::raw("(SELECT count(id) from send_campaign_mails WHERE send_campaign_mails.camp_id = campaigns.id and send_campaign_mails.status = '1') deliveredCount"),DB::raw('(CASE 
                        WHEN campaigns.stage = "1"  THEN campaigns.id 
                        ELSE campaigns.is_parent
                        END) AS follow_is_parent'),DB::raw('(CASE 
                        WHEN campaigns.stage = "1" and campaigns.attemp ="1"  THEN campaigns.id 
                        ELSE campaigns.is_parent
                        END) AS attempt_is_parent'), DB::raw("(SELECT count(*) from campaigns WHERE campaigns.is_parent = follow_is_parent and campaigns.is_followup = '1' and campaigns.status = '1' ) followpCount"), DB::raw("(SELECT count(*) from campaigns WHERE campaigns.is_parent = attempt_is_parent and campaigns.is_attempt = '1' and campaigns.status = '1' ) attemptCount"))
            ->join('send_campaign_mails','campaigns.id','=','send_campaign_mails.camp_id')
            ->whereIn('send_campaign_mails.id',$send_mail_ids)
            ->orderBy('send_campaign_mails.id','ASC')
            ->with('fromUser','sendMails','blackList');
            
        /*if($type=='campaign'){
            $campaigns->where('is_parent',NULL);
        }
        if($type=='followup'){
            $campaigns->where('is_followup','1');
        }
        if($type=='attempt'){
            $campaigns->where('is_attempt','1');
        }*/
        $allCampaign = $campaigns->get();
        $DataToUpdateInSystem = [];
        // dd($allCampaign);
        // \Log::info('2st query to extract all campaigns ------'.$allCampaign);
        $sql = NULL;
        try{
            echo Carbon::now()->format('Y-m-d H:i:s') . '------campaign  Start------'.PHP_EOL;
            \Log::info('-------campaign loop ------');
            if(count($allCampaign) > 0) {
                $is_account_inactive = false;
                $reason = 'Email disconnect';

                foreach ($allCampaign as $key => $campaign) {
                   // date_default_timezone_set($campaign->timezone);

                   // self::mailClient();
                    $this->client = new \Google_Client();
                    $this->client->setApplicationName('Reachomation');
                    $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
                    $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
                    $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
                    // $this->client->setScopes(["https://www.googleapis.com/auth/gmail.compose"]);
                   // $this->client->setScopes(array('https://www.googleapis.com/auth/drive','https://www.googleapis.com/auth/drive.file','https://www.googleapis.com/auth/spreadsheets'));
       
                    $this->client->setAuthConfig(storage_path('credential/'.env('CREDENTIAL_FILE_NAME','credential-prod').'.json'));
                    
                    $this->client->setAccessType('offline');   
                  //  $this->client->addScope('email');
                   // $this->client->addScope('https://mail.google.com');              
                    $this->client->setApprovalPrompt('force');       
                    $this->client->setPrompt('select_account consent');   

                     /*----Outlook-------------------*/
                    $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
                    $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

                    $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
                    $this->outlookclient = Onedrive::client($this->clientId);

                    Log::info('Loop Start campaign id--'.$campaign->camp_id.'-----------');
                    Log::info('Sender Info');
                    Log::info('from email---'.$campaign->fromUser->email);

                //   dd($campaign);
                    // $checkToken = null;
                      /*-----------Check mail account active or not -----------*/
                    if($campaign->fromUser->status == 0){
                        $is_account_inactive = true;
                        $reason = 'Email disconnect';
                        Log::info($campaign->camp_id.'-----campaign stop due to email disconnect');
                        $this->campaignStopMail($campaign->camp_id,$reason);
                    }
                    $isBlackList = $this->blackListCheck($campaign);
                
                    if($campaign->account_type == '1'){
                        $accessToken = $campaign->fromUser->accesstoken;
                        $this->client->setAccessToken($accessToken);
                        $checkToken = $this->checkToken($campaign->fromUser);
                        if(@$checkToken['accessToken']== NULL ){
                            $is_account_inactive = true;
                            $reason = @$checkToken['reason'];
                            Log::info($campaign->camp_id.'-----campign stop due to '.$reason);
                            $this->campaignStopMail($campaign->camp_id,$reason.'-Null access token');
                        }
                        $accessToken = @$checkToken['accessToken'];
                        $service = new \Google_Service_Gmail($this->client);
                        $this->client->setAccessToken($accessToken);
                        $oauth2 = new  \Google_Service_Oauth2($this->client);
                        $userInfo = $oauth2->userinfo_v2_me->get();
                        if($campaign->from_email != @$userInfo->email ){
                            $this->campaignStopMail($campaign->camp_id,'Email disconnect due to server error (token not refresh and oauth email not same as from email)');
                            \Log::info($campaign->camp_id.'-------token not refresh and oauth email not same as from email');
                        }
                    }
                    if($campaign->account_type == '2'){
                       $checkToken =  $this->getToken($campaign->fromUser);
                        if(@$checkToken['accessToken']== NULL ){
                            $is_account_inactive = true;
                            $reason = @$checkToken['reason'];
                            Log::info($campaign->camp_id.'-----campign stop due to '.$reason);
                            $this->campaignStopMail($campaign->camp_id,$reason.'--Null acess token');
                        }
                        $accessToken = @$checkToken['accessToken'];
                        $userData = self::__curl('GET','https://graph.microsoft.com/v1.0/me',$accessToken);
                        if($campaign->from_email != @$userData['userPrincipalName'] ){
                            Log::info($campaign->camp_id.'--------Email disconnect due to server error outlook end');
                            $this->campaignStopMail($campaign->camp_id,'Email disconnect due to server error');
                            \Log::info($campaign->camp_id.'--------token not refresh and aouth email not same as from email');
                        }
                    }
                    // dd($campaign);


                    
                    if($campaign->fromUser->daily_limit < 1){
                        $is_account_inactive = true;
                        $reason = 'Daily Limit exceed! Campaign has been paused.';
                        Log::info($campaign->camp_id.'---------campign stop due to '.$reason);
                        $this->campaignStopMail($campaign->camp_id,$reason.'---DailLimitExceed');
                        
                    }

                    /*--------------check Too many bounces-----------------------------*/
                    if($campaign->bounceMailCount >= $campaign->bounce_count){
                        $is_account_inactive = true;
                        $reason = 'Too many bounces';
                        Log::info($campaign->camp_id.'----------campign stop due to '.$reason);
                        $this->campaignStopMail($campaign->camp_id,$reason.'--Toomany Bounces');
                       
                    } 
                     
                    /*-------------------CheckBlackList-------------*/
                    /*if($campaign->isBlackList =='true'){
                        $DataToUpdateInSystem['send_mail_response'][]=[
                            'id'=>$campaign->send_id,
                            'user_id'=>$campaign->user_id,
                            'status'=>'2',
                            'threadId'=> '',
                            'msg_id'=>'',
                            'mail_send_date'=> now(),
                        ];
                    }*/
                    if($campaign->start_date!='') {
                     
                        $currentDate =  Carbon::now()->setTimezone($campaign->timezone)->format('Y-m-d');
                        $start_date = timeZoneDate($campaign->start_date,$campaign->timezone,'Y-m-d')->format("Y-m-d");
                        
                        if($start_date <= $currentDate){
                            $nowDatetime  =  Carbon::now()->setTimezone($campaign->timezone)->format("Y-m-d H:i:s");
                            $now  =  strtotime($nowDatetime); 
                           
                            $startd = strtotime($start_date.' '.$campaign->starttime);
                            $endd = strtotime($start_date.' '.$campaign->endtime);
                            $currentDatePlus = strtotime($currentDate.' '.$campaign->starttime);
                            if($startd >= $endd){
                                $end_str = strtotime(date("Y-m-d", strtotime('1 days', $currentDatePlus)));
                                $end_date = date("Y-m-d", $end_str);
                            } else{
                                $end_date =  Carbon::now()->setTimezone($campaign->timezone)->format("Y-m-d");
                            }
                           
                            $starttime = $currentDate.' '.$campaign->starttime;
                            $endtime =   $end_date.' '.$campaign->endtime;
                            
                            $open_time = timeZoneDate($starttime,$campaign->timezone,'Y-m-d H:i:s')->format("H:i:s"); 
                            $close_time = timeZoneDate($endtime,$campaign->timezone,'Y-m-d H:i:s')->format("H:i:s") ;
                            $start = strtotime($currentDate.' '.$open_time);
                            $end = strtotime($end_date.' '.$close_time);
                            Log::info($campaign->camp_id.'---Timing');
                            Log::info($campaign->camp_id.'------'.timeZoneDate($starttime,$campaign->timezone,'Y-m-d H:i:s')->format("Y-m-d H:i:s"));
                            Log::info($campaign->camp_id.'----'.timeZoneDate($endtime,$campaign->timezone,'Y-m-d H:i:s')->format("Y-m-d H:i:s"));

                            Log::info($campaign->camp_id.'------start '.$start);
                            Log::info($campaign->camp_id.'-----end ' .$end);
                            Log::info($campaign->camp_id.'-----now '.$now);
    
                
                            $is_run = 'N';
                            if($campaign->non_stop == '1'){
                                $is_run = 'Y';
                            }
                            elseif($start <= $now && $end >=  $now){
                                $is_run = 'Y';
                            } else{
                                $is_run = 'N';
                            }
                            Log::info($campaign->camp_id.'-------is_run---'.$is_run);
                           
                            if($is_run == 'Y'){
        
                                Log::info($campaign->camp_id.'-----inside mail send is_run ');
                                /*------Start Mail Processing--------------*/

                                $updateSendStatus = SendCampaignMail::find($campaign->send_id);
                                $updateSendStatus->status = '5';
                                $updateSendStatus->save();

                                $subject = $campaign->subject;
                                $message = $campaign->message;
                                $id = base64url_encode($campaign->send_id);
                                $websiteEncode = base64url_encode($campaign->website);
                                $hasmac = Hasmac($campaign->send_id);
                                $clicktoken = $id.'.'.$websiteEncode.'.'.$hasmac;
                                $websiteLink = '<a href="'.route('click-mail',$clicktoken).'">'.$campaign->website.'</a>';

                                /*-------add Unsubscribe link--------------------*/
                                
                                $send_id = base64url_encode($campaign->send_id);
                                $fromEmail = base64url_encode($campaign->from_email);
                                $toEmailuser = base64url_encode($campaign->to_email).'.'.$send_id.'.'.$fromEmail;
                                $hasmac = Hasmac($campaign->to_email);
                                $token = $toEmailuser.'.'.$hasmac;
                                
                                $unSubTag ="<br>  If you don't want to receive such emails in the future, <a href='".route('unsubscribe-mail',$token)."'>unsubscribe</a> here";

                                $message = $message.$unSubTag;
                                
                                $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
                                   
                                $message = str_replace($campaign->website, $websiteLink, $message);

                                if($campaign->account_type == '1' && $campaign->send_status == '0' && $is_account_inactive == false ){
                                    
                                    $DataToUpdateInSystem['send_mail_response'][]= $this->sendMailByGmail($campaign->from_email,$campaign->to_email,$subject,$message,$campaign->user_id,$campaign->camp_id,$campaign->send_id,$key,$campaign->fromUser,$isBlackList,$nowDatetime);
                                 \Log::info($campaign->camp_id.'--------data sent for process to gmail function');

                                   
                                }
                                 /*------Outlook----------------*/
                                if($campaign->account_type == '2' && $campaign->send_status == '0' && $is_account_inactive == false ){
                                   
                                    $DataToUpdateInSystem['send_mail_response'][] = $this->sendMailByOutlook($campaign->from_email,$campaign->to_email,$subject,$message,$campaign->user_id,$campaign->camp_id,$campaign->send_id,$key,$campaign->fromUser,$isBlackList,$nowDatetime);
                                    //  \Log::info($$campaign->camp_id.'------'.json_encode($DataToUpdateInSystem));
                                     
                                }

                                $DataToUpdateInSystem['update_daily_limit'][]=[
                                    "id" => @$campaign->fromUser->id,
                                    "daily_limit" => @$campaign->fromUser->daily_limit-1,
                                    
                                ];
                                $DataToUpdateInSystem['campaign_send_mail'][]=[
                                    "id" => @$campaign->send_id,
                                    "from_email" => @$campaign->from_email,
                                    "to_email" => @$campaign->to_email,
                                    
                                ];

                               
                                /*---------------The code will run when one email is sent to Compiign --- */
                                if($campaign->camp_status=='0'){
                                   $this->campaignStartMail($campaign);
                                    
                                }

                                /*---------------This code will run when compaign is completed --- */
                                if($campaign->CompleteCount >= ($campaign->import_contact-1)){
                                    /*$DataToUpdateInSystem['update_camp_status'][]=[
                                        "id" => $campaign->camp_id,
                                        "status" => '4',
                                        "is_active" => '0',
                                        "is_completed" => '1',
                                        "campaign_send_date" => $currentDate,
                                        "total_delivered" => $campaign->deliveredCount+1,
                                        "total_failed" => $campaign->bounceMailCount,

                                    ];*/
                                    $DataToUpdateInSystem['camp_partial_complete'][]= $campaign;
            
                                }
                                    

                            }

                        }
                    }
                    Log::info('Loop end campaign id--'.$campaign->camp_id.'-----------');

                    
                }

                Log::info('-----------All loop data send Start-------');
                Log::info($DataToUpdateInSystem);
                if(isset($DataToUpdateInSystem['send_mail_response']) && !empty($DataToUpdateInSystem['send_mail_response'])){
                    SendCampaignMail::massUpdate(values:@$DataToUpdateInSystem['send_mail_response']);
                    if(isset($DataToUpdateInSystem['update_daily_limit']) && !empty($DataToUpdateInSystem['update_daily_limit'])){
                        EmailCollection::massUpdate(values:@$DataToUpdateInSystem['update_daily_limit']);
                      
                    }


                }
               /* if(@$DataToUpdateInSystem['update_camp_status'] && !empty(@$DataToUpdateInSystem['update_camp_status'])){
                   Campaign::massUpdate(values:@$DataToUpdateInSystem['update_camp_status']);
                 
                    
                }*/
                  /*------------Check campiagn complete---------------*/
                if(isset($DataToUpdateInSystem['camp_partial_complete']) && !empty(@$DataToUpdateInSystem['camp_partial_complete'])){
                    $this->campaignPartialComplete($DataToUpdateInSystem['camp_partial_complete']);
                }
                
                
        
               
            }
            Log::info('-----------All loop data send End-------');
            echo Carbon::now()->format('Y-m-d H:i:s') . '------campiagn  end------'.PHP_EOL;
            
        }catch(Exception $exception) {
            Log::info('camp start exception');
            \Log::error($exception);
            return false;
            
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
        $this->client->setPrompt('select_account consent');   

         /*----Outlook-------------------*/
        $this->clientId =  env('OUTLOOK_CLIENT_ID','df45ec20-0dee-462b-a29d-d0b0b5797e67');
        $this->redirectUrl = env('OUTLOOK_REDIRECT','https://reachomation.com/callback_outlook');

        $this->clientsecret =  env('OUTLOOK_SECRET','H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
        $this->outlookclient = Onedrive::client($this->clientId);


    }
    /*------------- Check Black List data -------------*/
    public function blackListCheck($campaign){
        $is_blacklist = false;
        if(count(@$campaign->blackList) > 0 ){
            foreach (@$campaign->blackList as $key => $list) {
                if($campaign->from_email ==  $list->from && $campaign->to_email ==  $list->to){
                    $is_blacklist = true;

                }
               
            }
        }
        return $is_blacklist;

    }
    /*-------------------start campaign Mail--------------------*/
    public  function campaignStartMail($campaign) { 
        if($campaign->camp_status== 0 || $campaign->camp_status== '2'){
            $updateCamp = Campaign::find($campaign->camp_id);
            $updateCamp->status = '3';
            $updateCamp->save();
            $fname =  ($updateCamp->is_parent==NULL)  ? 'outreach'  :'followup' ; 
            $content = [
                'name' => ($updateCamp->user) ? $updateCamp->user->name :'',
                'camp_name' => $updateCamp->name,
                'total_email' => $updateCamp->import_contact,
                'start_time' => $updateCamp->starttime,
                'stage' => $fname,
                'attemp' => $updateCamp->attemp,
               
            ];
            if (env('IS_MAIL_ENABLE', false) == true) {
                Mail::to($updateCamp->user->email)->send(new CampaignStartMail($content));
            }

            if (env('IS_NOTIFY_ENABLE', false) == true) {
                Notification::send($updateCamp->user, new CampiagnNotification($updateCamp));
            }
        }
        
    }
    /*-------------------Stop campaign Mail--------------------*/
    public  function campaignStopMail($camp_id, $reason = 'Unknown reason' ) { 

        $updateCamp = Campaign::find($camp_id);
        $updateCamp->status = '5';
        $updateCamp->reason = $reason.'.SM';
        $updateCamp->bounce_count = ($reason == 'Too many bounces') ? $updateCamp->bounce_count +5 : 5 ;
        $updateCamp->save();

        $updateAccount = $updateCamp->fromUser;
        $updateAccount->status ='0';
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
    /*-------------------compaign complete /child campaign  remianing --------------------*/
    public function campaignPartialComplete($campaigns){ 
       
        foreach ($campaigns as $key => $campaign) {
            $currentDate = Carbon::now()->timezone($campaign->timezone)->format("Y-m-d H:i:s");    
            if($campaign->is_parent == NULL && $campaign->attemptCount < 1 && $campaign->followpCount <1){
                $updateCampPartial = Campaign::find($campaign->camp_id);
                $updateCampPartial->is_active = '0';
                $updateCampPartial->status = '8';
                $updateCampPartial->is_completed = '1';
                $updateCampPartial->campaign_send_date = $currentDate;
                $updateCampPartial->total_delivered = $campaign->deliveredCount+1;
                $updateCampPartial->total_failed = $campaign->bounceMailCount;
                $updateCampPartial->last_mail_send_date = $currentDate;
                $updateCampPartial->save();
                $fname =  ($updateCampPartial->is_parent==NULL)  ? 'outreach'  :'followup' ; 
                $content = [
                    'user_id' => $updateCampPartial->user_id,
                    'user_name' => $updateCampPartial->user->name,
                    'name' => $updateCampPartial->name,
                    'stage' => $fname,
                    'attemp' => $updateCampPartial->attemp,
                    'type' => '1',
                    'start_date_time' => date('M d Y',strtotime($updateCampPartial->start_date)).' '.date('H:i A',strtotime($updateCampPartial->starttime)),
                    'campaign_send_date' => date('M d Y H:i A',strtotime($updateCampPartial->campaign_send_date)),
                    'import_contact' => $updateCampPartial->import_contact,
                    'total_delivered' => $updateCampPartial->total_delivered,
                    'total_failed' => $updateCampPartial->total_failed,
                    'csvfile' => $this->downloadReport($updateCampPartial,'1'),
                   
                ];
                if (env('IS_MAIL_ENABLE', false) == true) {
                    Mail::to($updateCampPartial->user->email)->send(new CampaignCompleteMail($content));
                }
                if (env('IS_NOTIFY_ENABLE', false) == true) {
                    Notification::send($campaign->user, new CampiagnNotification($updateCampPartial));
                }


            } else{
                $updateCampPartial = Campaign::find($campaign->camp_id);
                $updateCampPartial->is_active = '0';
                $updateCampPartial->status = '4';
                $updateCampPartial->is_completed = '1';
                $updateCampPartial->campaign_send_date = $currentDate;
                $updateCampPartial->last_mail_send_date = $currentDate;
                $updateCampPartial->total_delivered = $campaign->deliveredCount+1;
                $updateCampPartial->total_failed = $campaign->bounceMailCount;
                $updateCampPartial->save();

                $fname =  ($updateCampPartial->is_parent==NULL)  ? 'outreach'  :'followup' ; 
                $content = [
                    'user_id' => $updateCampPartial->user_id,
                    'user_name' => $updateCampPartial->user->name,
                    'name' => $updateCampPartial->name,
                    'stage' => $fname,
                    'attemp' => $updateCampPartial->attemp,
                    'type' => '1',
                    'start_date_time' => date('M d Y',strtotime($updateCampPartial->start_date)).' '.date('H:i A',strtotime($updateCampPartial->starttime)),
                    'campaign_send_date' => date('M d Y H:i A',strtotime($updateCampPartial->campaign_send_date)),
                    'import_contact' => $updateCampPartial->import_contact,
                    'total_delivered' => $updateCampPartial->total_delivered,
                    'total_failed' => $updateCampPartial->bounceMailCount,
                    'csvfile' => $this->downloadReport($updateCampPartial,'1'),
                   
                ];
                if (env('IS_MAIL_ENABLE', false) == true) {
                    Mail::to($campaign->user->email)->send(new CampaignCompleteMail($content));
                }
                if (env('IS_NOTIFY_ENABLE', false) == true) {
                    Notification::send($updateCampPartial->user, new CampiagnNotification($updateCampPartial));
                }

                /*---------Followups check/Update folllowup start data ---------------------*/
                $follow_camp = Campaign::where('is_parent',$campaign->follow_is_parent)->where('is_followup','1')->where('status','1')->first();

                if(!empty($follow_camp)){
                    $conIds = SendCampaignMail::where('camp_id',$campaign->follow_is_parent);

                    if($updateCampPartial->followup_condition =='1'){
                        $conIds = $conIds->where('is_open','0')->pluck('id');
                        
                    }
                    if($updateCampPartial->followup_condition =='2'){
                        $conIds = $conIds->where('is_reply','0')->pluck('id');

                    }
                    if($updateCampPartial->followup_condition =='3'){
                        $conIds = $conIds->pluck('id');
                    }
                    $ids = $conIds->toArray();
                   
                    $this->addDataToSendCampiagnMail($ids,$follow_camp,$campaign->follow_is_parent,$campaign);

                }
                $stage = $campaign->stage+1;
                $attemp = $campaign->attemp-1;
               
                if($campaign->is_attempt == '1'){
                    if($campaign->attempt_type =='1'){
                        $check_follow_attmt = Campaign::where('is_parent',$campaign->attempt_is_parent)->where('is_followup','1')->where('stage',$stage)->where('status','4')->first();
                        if(empty($check_follow_attmt)){
                            $check_follow_attmt = Campaign::where('id',$campaign->attempt_is_parent)->where('stage','1')->where('status','4')->first();
                        }
                    } else{
                        $is_parent_id = $campaign->parent->is_parent;

                        $check_follow_attmt = Campaign::where('is_parent',$is_parent_id)->where('is_followup','1')->where('stage',$stage)->where('status','4')->first();

                        if(empty($check_follow_attmt)){
                            $check_follow_attmt = Campaign::where('id',$is_parent_id)->where('stage','1')->where('status','4')->first();
                        }


                    }
                    if(!empty($check_follow_attmt)){
                        $follAtt = Campaign::where('is_parent',$check_follow_attmt->id)->where('is_attempt','1')->where('stage',$check_follow_attmt->stage)->where('status','1')->first();
                        if(!empty($follAtt)){
                            $camp_start_date = date("Y-m-d", strtotime('+'.$campaign->cooling_period.' hours', strtotime($currentDate)));
                           
                           $timezone_start_date = Carbon::parse($camp_start_date)->timezone('America/New_York')->format('Y-m-d');
                            $updateAttemptCamp = Campaign::find($follAtt->id);
                            $updateAttemptCamp->start_date = $camp_start_date;
                            $updateAttemptCamp->timezone_start_date = $timezone_start_date;
                            $updateAttemptCamp->status = '0';
                            $updateAttemptCamp->is_active = '1';
                            $updateAttemptCamp->save();

                        }
                    } 

                } else{
                    if($campaign->attemptCount >0 && $campaign->followpCount <=0){
                        $attempt_camp = Campaign::where('is_parent',$campaign->attempt_is_parent)->where('is_attempt','1')->where('status','1')->first();
                        if(!empty($attempt_camp)){
                            $camp_start_date = date("Y-m-d", strtotime('+'.$campaign->cooling_period.' hours', strtotime($currentDate)));
                           $timezone_start_date = Carbon::parse($camp_start_date)->timezone('America/New_York')->format('Y-m-d');
                            $updateAttemptCamp = Campaign::find($attempt_camp->id);
                            $updateAttemptCamp->start_date = $camp_start_date;
                            $updateAttemptCamp->timezone_start_date = $timezone_start_date;
                            $updateAttemptCamp->status = '0';
                            $updateAttemptCamp->is_active = '1';
                            $updateAttemptCamp->save();
                            
                        }

                    }
                }

                /*------whole atttemplts/Folloup Campiagn Completed mail --------*/
                if($campaign->is_followup=='1'){
                    $gParent = $campaign->is_parent;
                } elseif($campaign->is_attempt=='1'){
                    if($campaign->attempt_type =='1'){
                        $gParent = $campaign->is_parent;
                    } else{
                        $gParent = $campaign->parent->is_parent;
                    }
                    
                } else{
                    $gParent = $campaign->camp_id;
                }

                $allChilds = getChildren($gParent);
                $allChild = array_merge($allChilds,[$gParent]);
                $checkAllcompete = Campaign::whereIn('id',$allChild)->where('status','4')->count();
                
                if(count($allChild) == $checkAllcompete){
                    $updateComplte = Campaign::find($gParent);
                    $updateComplte->status = '8';
                    $updateComplte->save();
                    $stag =  ($updateComplte->is_parent==NULL)  ? 'outreach'  :'followup' ; 
                    $content = [
                        'user_id' => $updateComplte->user_id,
                        'user_name' => $updateComplte->user->name,
                        'name' => $updateComplte->name,
                        'stage' => $stag,
                        'attemp' => $updateComplte->attemp,
                        'start_date_time' => date('M d Y',strtotime($updateComplte->start_date)).' '.date('H:i A',strtotime($updateComplte->starttime)),
                        'campaign_send_date' => date('M d Y H:i A',strtotime($updateComplte->campaign_send_date)),
                        'import_contact' => $updateComplte->import_contact,
                        'total_delivered' => $updateComplte->total_delivered,
                        'total_failed' => $updateComplte->total_failed,
                        'type' => '2',
                        'csvfile' => $this->downloadReport($updateComplte,'2'),
                       
                    ];
                    if (env('IS_NOTIFY_ENABLE', false) == true) {
                        Notification::send($updateComplte->user, new CampiagnNotification($updateComplte));
                    }
                    if (env('IS_MAIL_ENABLE', false) == true) {
                        Mail::to($updateComplte->user->email)->send(new CampaignCompleteMail($content));
                    }

                }

            }

            
        }
    
    }

    

    public  function downloadReport($campaign,$type) { 
        if($campaign){
            $camp_id = $campaign->id;
            $uuid = $campaign->uuid;
            $level = NULL;
            $fileName = Str::slug($campaign->name).'-'.$uuid.'.csv';
            Excel::store(new ReportExport($camp_id,$level,$type),$fileName, 's3');
            $filePath = Storage::disk('s3')->url($fileName);
            return $fileName;

        } 
      
    }


    public function addDataToSendCampiagnMail($ids,$follow_camp,$is_parent,$campaign){
        try{
            //Log::info($campaign);
            $currentDate =  Carbon::now()->timezone($campaign->timezone)->format("Y-m-d H:i:s");    
            $copyCampiagnData = SendCampaignMail::whereIn('id',$ids)->pluck('to_email')->toArray();
            $followupDataInsert = [];
            $attemptDataInsert = [];
            if(count($copyCampiagnData) >0 ) {
                $implode = implode(',',$ids);
                if(!empty($campaign)){
                    $followup_start_date = date("Y-m-d", strtotime('+'.$campaign->cooling_period.' hours', strtotime($currentDate)));
                    $timezone_start_date = Carbon::parse($followup_start_date)->timezone('America/New_York')->format('Y-m-d');
                    $updateFollowUpStartTime = Campaign::find($follow_camp->id);
                    $updateFollowUpStartTime->start_date = $followup_start_date;
                    $updateFollowUpStartTime->timezone_start_date = $timezone_start_date;
                    $updateFollowUpStartTime->import_contact = count($copyCampiagnData);
                    $updateFollowUpStartTime->status = '0';
                    $updateFollowUpStartTime->is_active = '1';
                    $updateFollowUpStartTime->save();
                }
                
                $file = $follow_camp->final_upload_csv;
                $subject = $follow_camp->subject;
                $message = $follow_camp->message;
                $excelDatCsv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($excelDatCsv);
                $default_fallback = explode(',','Name|there,Website|site');
                $fallback_text = (!empty($follow_camp->fallback_text)) ? explode(',',$follow_camp->fallback_text) : $default_fallback;
                /*----------Follow ups*/
                foreach ($excelDatCsv as $key1 => $csv) {
                    $rowNum =0;
                    $level =1;
                    $email = '';
                    $name = '';
                    $website = '';
                    $site = '';
                    $changeArray    = [];
                    $newKeyword     = [];
                    foreach ($heading as $head => $word) {
                        $rowNum = $head;
                        if(ucfirst($word) =='Email' ){
                            $email =  @$csv[$rowNum];
                        }
                        if(ucfirst($word) =='Name'){
                            $name =  @$csv[$rowNum];
                        }
                        if(ucfirst($word) =='Website'){
                            $website =  @$csv[$rowNum];
                        }
                        if($word =='Level'){
                            $level =  @$csv[$rowNum];
                            
                        }
                        
                    }
                    if(is_array($fallback_text) && count($fallback_text) >  0 ){
                        foreach ($fallback_text as $nk => $text) {
                            $fval = explode('|',$text);
                            $akey = @$fval[0];
                            $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                        }
                    }
                    foreach ($heading as $head => $word) {
                        $rowNum = $head;
                        $newKeyword[$word] = @$csv[$rowNum];
                    }
                    $sub = $this->strReplaceAssoc($newKeyword,$subject);
                    $msg = $this->strReplaceAssoc($newKeyword,$message);
                    $sub = preg_replace('/<[^>]*>/', '', $sub);
                    $followupDataInsert =[];
                    $uniqueArrayFollowUps =[];

                    $is_valid_email = false;
                    $inValidEmail =0;
                    $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix';
                    if(preg_match($pattern,trim(@$email)) == 1){
                        $is_valid_email = true;
                        $inValidEmail++;
                    }


                    if(!empty($follow_camp->list_id)){
                        $is_valid_email = true;
                    }

                    if(in_array($email,$copyCampiagnData)) {
                        $checkEmailAlready = SendCampaignMail::where('camp_id',$follow_camp->id)->where('to_email',$email)->first();
                        if(empty($checkEmailAlready) && $is_valid_email == true){
                            $addFollowups = new SendCampaignMail;
                            $addFollowups->user_id =$follow_camp->user_id;
                            $addFollowups->camp_id =$follow_camp->id;
                            $addFollowups->from_email =$follow_camp->from_email;
                            $addFollowups->website =$website;
                            $addFollowups->to_email =$email;
                            $addFollowups->name =$name;
                            $addFollowups->subject =$sub;
                            $addFollowups->message =$msg;
                            $addFollowups->level =$level;
                            $addFollowups->is_open = '0';
                            $addFollowups->is_reply = '0';
                            $addFollowups->is_click = '0';
                            $addFollowups->status = '0';
                            $addFollowups->stage =$follow_camp->stage;
                            $addFollowups->type =$follow_camp->account_type;
                            $addFollowups->save();
                            
                        }
                        

                        
                    }

                }
              
                $attemptDataInsert =[];
                $checkParentAttempt = Campaign::where('is_parent',$is_parent)->where('is_attempt','1')->get();
                if(count($checkParentAttempt) >0){
                    foreach ($checkParentAttempt as $key => $data) {
                        $attemptSave = new Campaign;
                        $attemptSave->user_id = $data->user_id;
                        $attemptSave->name = $data->name; 
                        $attemptSave->target_type = $data->target_type;
                        $attemptSave->list_id = $data->list_id;
                        $attemptSave->campid = $data->campid;
                        $attemptSave->is_parent = $follow_camp->id;
                        $attemptSave->top_most_parent = $follow_camp->is_parent;
                        $attemptSave->from_email = $data->from_email;
                        $attemptSave->mail_account_id = $data->mail_account_id;
                        $attemptSave->account_type = $data->account_type;
                        $attemptSave->file_csv = $data->file_csv;
                        $attemptSave->list_to_file_csv = $data->list_to_file_csv;
                        $attemptSave->subject = $data->subject;
                        $attemptSave->message = $data->message;
                        $attemptSave->temp_id = $data->temp_id;
                        $attemptSave->stage = $follow_camp->stage;
                        $attemptSave->file_mail_clm_name = 'Email';
                        $attemptSave->total_domain = $data->total_domain;
                        $attemptSave->final_upload_csv = $data->final_upload_csv;
                        $attemptSave->final_upload_csv_count = $data->final_upload_csv_count;
                        $attemptSave->starttime = date('H:i:s',strtotime($data->starttime));
                        $attemptSave->endtime = date('H:i:s',strtotime($data->endtime)); 
                        $attemptSave->timezone = $data->timezone;
                        $attemptSave->cooling_period = $data->cooling_period;
                        $attemptSave->type = $data->type;
                        $attemptSave->credit_deduct = $data->credit_deduct;
                        $attemptSave->total_contact = $data->textTotalContact;
                        $attemptSave->import_contact = $data->import_contact;
                        $attemptSave->followup_condition = $data->followup_condition;
                        $attemptSave->fallback_text = $data->fallback_text;
                        $attemptSave->features = $data->features;
                        $attemptSave->is_feature = ($data->is_feature == 1) ? 1:0;
                        $attemptSave->non_stop = ($data->non_stop == 1) ? 1:0;
                        $attemptSave->interval = '60';
                        $attemptSave->attemp = $data->attemp;
                        $attemptSave->attempt_type = '2';
                        $attemptSave->is_attempt = '1';
                        $attemptSave->status = '1';
                        $attemptSave->save();
                         /*-------------Attempt----------------------*/
                        $attempDatas = SendCampaignMail::where('camp_id',$data->id)->get();
                        if(count($attempDatas) >0) {
                            foreach ($attempDatas as $key => $attempt) {
                                $attemptDataInsert[] = [
                                    'user_id'=> $follow_camp->user_id,
                                    'camp_id'=> $attemptSave->id,
                                    'from_email'=> $attempt->from_email,
                                    'website'=> $attempt->website,
                                    'to_email'=> $attempt->to_email,
                                    'subject'=> $attempt->subject,
                                    'message'=> $attempt->message,
                                    'status'=> 0,
                                    'is_open'=> 0,
                                    'is_reply'=> 0,
                                    'is_click'=> 0,
                                    'level'=> $attempt->level,
                                    'stage'=> $follow_camp->stage,
                                    'type'=> $attempt->account_type,
                                   
                                ];
                                
                            }
                             
                            
                        }
                        
                    }
                    SendCampaignMail::insertOrIgnore($attemptDataInsert);
                }
            }
        } catch(Exception $exception) {
           
            \Log::info('insert');
            \Log::error($exception);
            //return $resultArr;
            
        }
            
    }
     
    
    public  function updateSendMailStatus($user_id,$camp_id,$send_id,$threadId,$msg_id,$status) { 

        $GmailDetails = SendCampaignMail::find($send_id);
        $GmailDetails->msg_id = $msg_id;
        $GmailDetails->threadId = $threadId;
        $GmailDetails->status = $status;
        $GmailDetails->mail_send_date = date('Y-m-d H:i:s');
        $GmailDetails->save();
        
        $updateDailyLimit = EmailCollection::where('user_id',$user_id)->where('email',$GmailDetails->from_email)->first();
        $updateDailyLimit->daily_limit = $updateDailyLimit->daily_limit-1;
        $updateDailyLimit->save();
    }
    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }

    public function sendMailByGmail($from,$to,$subject,$message,$user_id,$campaign_id,$send_id,$key,$gmailAccount,$isBlackList,$nowDatetime){
        //self::mailClient();
        $bouncedMail = array();
        $msg_id ='';
        $threadId ='';
        $resultArr =[];
        $nowDate = now();
        
        try {
            if($isBlackList == true){
                $resultArr =[
                    'id'=>$send_id,
                    'user_id'=>$user_id,
                    'status'=> '2',
                    'threadId'=> '',
                    'msg_id'=> '',
                    'mail_send_date'=> $nowDatetime,
                    'default_timezone_date'=> $nowDate,
                ];
    
                return $resultArr;
            
            }
            $sendData=[
                "id" => $send_id,
                "from_email" => $from,
                "to_email" => $to,
                "campaign_id" => $campaign_id,
                "gmailAccount" => $gmailAccount->id,
                    
            ];
            Log::info($sendData);
           
            $status = '0';
            //date_default_timezone_set("Asia/Calcutta"); 
            $this->client->setAccessToken($gmailAccount->accesstoken);

            $checkToken = $this->checkToken($gmailAccount);
            $accessToken = $checkToken['accessToken']; //$gmailAccount->accesstoken ;
            $service = new \Google_Service_Gmail($this->client);
            //Log::info($accessToken);
            $this->client->setAccessToken($accessToken);
            $filepatharr = array();
            $filenamearr = array();

            $subject = html_entity_decode($subject);

            $subject = preg_replace("/\s/",' ',$subject);
            $subject = str_replace(chr(194),"",$subject);
            $subject = str_replace("&nbsp;",'', $subject);
            //$subject = str_replace("<spanstyle",'', $subject);
            $subject = substr(strip_tags($subject),0,110);
            


            $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
            $message = str_replace(chr(194),"",$message);
            

            $message = str_replace("&nbsp;",' ', $message);
            $message = str_replace("’",'’',$message);


            /*----------Mail token--------------------*/
            $sendId = base64url_encode($send_id);
            $camp_id = base64url_encode($campaign_id);
            $hasmac = Hasmac($campaign_id);
            $token = $sendId.'.'.$camp_id.'.'.$hasmac;
            $baseUrl = '<img src="'.route('track-mail',$token).'" width="1px" height="1px">';
            
            if(!empty($message)){
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($message);
                libxml_use_internal_errors(false);
                $imageTags = $doc->getElementsByTagName('img');
                if(!empty($imageTags)){
                    foreach($imageTags as $tag) {
                        $imagepaths = '';
                        $imagepaths = $tag->getAttribute('src');
                        $newsrc = 'cid'.pathinfo($imagepaths, PATHINFO_FILENAME);
                        $tag->setAttribute('src',$newsrc); 
                    }
                    $htmlString = $doc->saveHTML();
                }
            }

            
            $message =  $message.$baseUrl;
            $from_name = (!empty($gmailAccount->name)) ? $gmailAccount->name:'Reachomation';
            
            $strRawMessage = "";
            $boundary = uniqid(rand(), true);
            $strRawMessage = "From: ".$from_name."  <".$from."> \r\n";
            $strRawMessage .= "To: ".$to."\r\n";
            
            $strRawMessage .= 'Subject: ' . $subject . "\r\n";
            $strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
            $strRawMessage .= 'Content-type: Multipart/Mixed; boundary="' . $boundary . '"' . "\r\n";
            $strRawMessage .= "\r\n--{$boundary}\r\n";
            $strRawMessage .= 'Content-Type: text/html; charset=utf-8\r\n';
            
            $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
            $strRawMessage .= $message . "\r\n";
            $strRawMessage .= "\r\n--{$boundary}\r\n";
            $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
            $msg = new \Google_Service_Gmail_Message();
            $msg->setRaw($mime);
            $todayDate = gmdate("M d Y H:i:s");
            $timestamp = strtotime($todayDate);
            //Log::info('Raw Message');
            //Log::info($strRawMessage);
           
            $objSentMsg = $service->users_messages->send('me', $msg);
            $msgObj = self::getMessage($service, 'me', $objSentMsg->id);
          
            if(!empty($msgObj)){
                $msg_id = $objSentMsg->id;
                $threadId = $msgObj->threadId;
                $status ='1';
                /*------------check Bounce---------------------*/
                /*$bouncedMail = array();
                $headerArr = self::getHeaderArr($msgObj->getPayload()->getHeaders());
                sleep(3);
                $todayDate = gmdate("M d Y H:i:s");
                $timestamp = strtotime($todayDate);
                $bounce_messages = self::listMessages($service, 'me', [
                    'q' => "from:mailer-daemon after after:".$timestamp
                ]);

                if(!empty($bounce_messages)){
                    foreach($bounce_messages as $bounce){
                        array_push($bouncedMail,$bounce->threadId);
                    }
                }

                if(in_array($objSentMsg->id,$bouncedMail)){
                    print_r($objSentMsg->id);
                
                    $status ='3';

                }*/
                
                
            } else{
                $status ='4';
                Log::info('Status 4');
                Log::info($send_id);
            }
            
            $resultArr =[
                'id'=>$send_id,
                'user_id'=>$user_id,
                'status'=>$status,
                'threadId'=>$threadId,
                'msg_id'=>$msg_id,
                'mail_send_date'=> $nowDatetime,
                'default_timezone_date'=> $nowDate,
            ];
            
            return $resultArr;
        } catch(Exception $exception) {
            Log::info('exception in gmail send');
            \Log::error($exception);
            // $this->campaignStopMail($campaign_id,'Server error');
            Log::info('excep in mail send');                     
        }
    }
    public function sendMailByOutlook($from,$to,$subject,$message,$user_id,$campaign_id,$send_id,$key,$outlookAccount,$isBlackList,$nowDatetime){
        //self::mailClient();
        $resultArr =[];
        $nowDate = now();
        try{
            if($isBlackList == true){
                $resultArr =[
                    'id'=>$send_id,
                    'user_id'=>$user_id,
                    'status'=> '2',
                    'threadId'=> '',
                    'msg_id'=> '',
                    'mail_send_date'=> $nowDatetime,
                    'default_timezone_date'=> $nowDate,
                ];
    
                return $resultArr;
            
            }

            $bouncedMail = array();
            $status = '0';
            $msg_id ='';
            $threadId ='';
        
            $accessToken = $outlookAccount->accesstoken;
            $checkToken = $this->getToken($outlookAccount);
            $accessToken = @$checkToken['accessToken'];

            $userId = $user_id;
            $sendemail = $from;
            $auth_id = $from;

            $thisTo = array();
            if($to !='') {
                $thisTo[] = array(
                    "EmailAddress" => array(
                        "Address" => trim($to)
                    )
                );
            }
             
            $attachments = array();
            $filenamearr = array();
            //$subject = html_entity_decode($subject);

            $subject = preg_replace("/\s/",' ',$subject);
            $subject = str_replace(chr(194),"",$subject);
            $subject = str_replace("&nbsp;",'', $subject);
            $subject = substr(strip_tags($subject),0,110);

            $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
            $message = str_replace("\u{c2a0}", "", $message);
            //$message = str_replace(chr(194)," ",$message);
            $message = str_replace("&nbsp;",' ', $message);

            $message = str_replace("’",'’',$message);

            
             /*----------Mail token--------------------*/
            $sendId = base64url_encode($send_id);
            $camp_id = base64url_encode($campaign_id);
            $hasmac = Hasmac($campaign_id);
            $token = $sendId.'.'.$camp_id.'.'.$hasmac;
            $baseUrl = '<img src="'.route('track-mail',$token).'" width="1px" height="1px">';

            $doc = new \DOMDocument();
            if(!empty($message)){
                libxml_use_internal_errors(true);
                $doc->loadHTML($message);
                libxml_use_internal_errors(false);
                $imageTags = $doc->getElementsByTagName('img');
                if(!empty($imageTags)){
                    foreach($imageTags as $tag) {
                        $imagepaths = '';
                        $imagepaths = $tag->getAttribute('src');
            
                        $newsrc = 'cid:'.pathinfo($imagepaths, PATHINFO_FILENAME);
                        $tag->setAttribute('src',$newsrc); 
                        $content = base64_encode(file_get_contents($imagepaths));
                        
                        $htmlString = $doc->saveHTML();

                    }
                }
            }
            
            $message = $message.$baseUrl;
            
            $mail_request = array(
                "message" => array(
                    "subject" =>$subject,
                    "importance"=>"High",
                    "body" => array(
                        "ContentType" => "HTML",
                        "Content" => $message
                    ),
                    "attachments" => $attachments,
                    "toRecipients" => $thisTo,

                )
            );
           
            $mail_request = json_encode($mail_request);
         
            $headers = array(
                "User-Agent: php-tutorial/1.0",
                "Authorization: Bearer ".$accessToken,
                "Accept: application/json",
                "Content-Type: application/json",
                "Content-Length: ". strlen($mail_request)
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://graph.microsoft.com/v1.0/me/sendmail');
            if($mail_request != null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $mail_request);
            }
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if($headers != null) {
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            $response = curl_exec($ch);
            //echo "<PRE>";print_R($response);die;
            $data = json_decode($response);
        
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if($http_code == '202'){
                $status = '1';
                date_default_timezone_set("Asia/Calcutta"); 
                $formateDate = date('Y-m-d');
                $formateDate = $formateDate.'T'.date('h:m:s').'Z';
                
        
                $bounceData = self::__curl('GET','https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages?$search="from:postmaster"&?$filter=receivedDateTime ge '.$formateDate,$accessToken);
                //echo "<PRE>";print_R($bounceData);die;
                $bounceItem = array();
                $bounceItem = array();
                if(!empty($bounceData)){
                    if(isset($bounceData['value'])) {
                        foreach($bounceData['value'] as $bounceG){
                            if(trim(str_replace('Undeliverable:','',$bounceG['subject'])) == trim($subject)){
                                foreach($bounceG['toRecipients'] as $emailvalarr){
                                    if($emailvalarr['emailAddress']['address'] == $to){
                                        
                                       $status = '3';
                                       $msg_id = @$bounceG->id;
                                    } else{
                                       $status = '1';
                                       $msg_id = @$bounceG->id;
                                    }
                                   
                                }
                            }
                        }
                    }
        
                }

            } else{
                $statua ='4';
            } 

             
            $resultArr =[
                'id'=>$send_id,
                'user_id'=>$user_id,
                'status'=>$status,
                'threadId'=>$threadId,
                'msg_id'=>$msg_id,
                'mail_send_date'=> $nowDatetime,
                'default_timezone_date'=> $nowDate,
            ];
            return $resultArr;
        }catch(Exception $exception) {
            Log::info('outlook exception');
            \Log::error($exception);
            // $this->campaignStopMail($campaign_id,'Server error');

           
        }
        
    }

    public function getToken($outlookuser){
        $tokenResponse =[];
        try {
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
                        CURLOPT_FAILONERROR => true,
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
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    
                    if (isset($error_msg)) {
                        $tokenResponse =[
                            'accessToken' => NULL,
                            'reason' => 'Email disconnect',

                        ];
                        return $tokenResponse;
                       
                    } else {
                        $tokenData = json_decode($response);

                        $accessToken = $tokenData->access_token;
                        $resfreshToken = $tokenData->refresh_token;
                        $endtime = strtotime("+60 minutes");

                        $outlookuser->accesstoken = $accessToken;
                        $outlookuser->refreshtoken = $resfreshToken;
                        $outlookuser->expired_in = $endtime;
                        $outlookuser->save();
                        $tokenResponse =[
                            'accessToken' => $accessToken,
                            'reason' => '',

                        ];
                        return $tokenResponse;


                    }
                }else{
                    $tokenResponse =[
                        'accessToken' => $accessToken,
                        'reason' => '',

                    ];
                    return $tokenResponse;
                }
            }
        }catch(Exception $exception) {
            \Log::error($exception->getMessage());
             Log::info('outlook token generated exp');
            $tokenResponse =[
                'accessToken' => NULL,
                'reason' => 'Email disconnect',

            ];
            return $tokenResponse;
            
        }
    }
    public function checkToken($gmailauthData){
        $tokenResponse =[];
        $reason = 'Inside Check token';
        try {
            if(!empty($gmailauthData)){

                    if ($this->client->isAccessTokenExpired()) {
                    if ($this->client->getRefreshToken()) {
                        $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                        if(@$accessToken['error']){
                            if(@$accessToken['error'] =='invalid_grant'){
                                $reason = 'Error in connecting your Email account';
                                Log::info('campign stop due to '.$reason);
            
                            }elseif(@$accessToken['error'] =='unauthorized_client '){ 
                                $reason = 'Email disconnect unauthorized client';
                                Log::info('campign stop due to '.$reason);
                                
                            } else{
                                $reason = 'Multiple email block';
                                Log::info('campign stop due to '.$reason);
                            }
                            $tokenResponse =[
                                'accessToken' =>NULL,
                                'reason' =>$reason,

                                ];
                                Log::info('campign stop due to '.$accessToken['error']);
                                \Log::error(@$accessToken['error']);
                                return $tokenResponse;
                            } else{
                                $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                                $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                                $gmailauthData->expired_in =  $accessToken['expires_in'];
                                $gmailauthData->save();
                                 \Log::info('New acessToken in  sendemail');
                                 \Log::info($accessToken['expires_in']);
                                $tokenResponse =[
                                    'accessToken' => $accessToken,
                                    'reason' =>'New acessToken in  sendemail',

                            ];

                            return $tokenResponse;
                        }
                        
                    }else {
                        $tokenResponse =[
                            'accessToken' => NULL,
                            'reason' => 'Email disconnect',

                        ];
                        return $tokenResponse;
                    }
                }else{
                    $accessToken = $gmailauthData->accesstoken;
                    $tokenResponse =[
                        'accessToken' => $accessToken,
                        'reason' =>'Old acessToken in  sendemail',

                    ];
                    \Log::info('Old acessToken in  sendemail');
                    return $tokenResponse;
                }
               
            } else{
                $tokenResponse =[
                    'accessToken' => NULL,
                    'reason' => 'Email disconnect, No token',

                ];
                return $tokenResponse;
            }
        return $tokenResponse;   
        }catch(Exception $exception) {
            Log::info('gmail token generated exp');
            \Log::error($exception->getMessage());
           $tokenResponse =[
                'accessToken' => NULL,
                'reason' => $exception->getMessage(),

            ];
            return $tokenResponse;
            
        }
        
    }
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
    public function getMessage($service, $userId, $messageId) {
      try {
        $message = $service->users_messages->get($userId, $messageId);
        return $message;
      } catch (Exception $e) {
        \Log::error($e->getMessage());
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
