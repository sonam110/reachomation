<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampaignCsvWebsite;
use App\Models\Campaign;
use App\Models\SendCampaignMail;
use App\Models\User;
use Excel;
use Storage;
use Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CampiagnNotification;
use Mail;
use App\Mail\DataProcessingMail;
class AddToolsCsvDataToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campiagn:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        /*
        is_blog_judgement: 4 is completed
        is_email_extract: 2 is completed
        is_email_validate: 2 is completed
        is_personalize_data: 2 is completed
        is_compute_semrus_da: 4 is completed

        */
        $allProcessingCampign = Campaign::where('status','7')->where('id','101')->where('is_file_read','N')->get();
       
          echo Carbon::now() . '------Sync  Start------'  . PHP_EOL;
        foreach ($allProcessingCampign as $key => $campaign) {
            $features = explode(',', $campaign->features);
            $blog_jug =  CampaignCsvWebsite::where('camp_id',$campaign->id)->whereIn('is_blog_judgement',['4','5'])->count();
            $email_extarct =  CampaignCsvWebsite::where('camp_id',$campaign->id)->where('is_email_extract','2')->count();
            $email_valid =  CampaignCsvWebsite::where('camp_id',$campaign->id)->where('is_email_validate','2')->count();
            $personalize_data =  CampaignCsvWebsite::where('camp_id',$campaign->id)->whereIn('is_personalize_data',['2','3'])->count();
            $semrus_da =  CampaignCsvWebsite::where('camp_id',$campaign->id)->where('is_compute_semrus_da','4')->count();
            /*------Blog Judge*/
            \Log::info($blog_jug);
            \Log::info($email_valid);
            \Log::info($personalize_data);
            \Log::info($semrus_da);
            
            if(in_array('1',$features)){
                if($campaign->import_contact == $blog_jug){
                    $is_blog_judgement = true;
                } else{
                    $is_blog_judgement = false;
                }
            } else{
                $is_blog_judgement = true;
            }
            
            /*--------Email extraction*/
           /* if(in_array('2',$features)){
                if($campaign->final_upload_csv_count == $email_extarct){
                    $is_email_extract = true;
                } else{
                    $is_email_extract = false;
                }
            }*/
             /*--------Email validation*/
            if(in_array('3',$features)){
                if($campaign->import_contact == $email_valid){
                    $is_email_validate = true;
                } else{
                    $is_email_validate = false;
                }
            } else{
                $is_email_validate = true;
            }

             /*--------Personalized datat*/
            if(in_array('4',$features)){
                if($campaign->import_contact == $personalize_data){
                    $is_personalize_data = true;
                } else{
                    $is_personalize_data = false;
                }
            } else{
                $is_personalize_data = true;
            }
             /*--------semrus/da*/
            if(in_array('5',$features)){
               
                if($campaign->import_contact == $semrus_da){
                    $is_compute_semrus_da = true;
                } else{
                    $is_compute_semrus_da = false;
                }
            } else{

                $is_compute_semrus_da = true;
            }
           
           
            if($is_blog_judgement== true && $is_email_validate == true && $is_personalize_data== true &&  $is_compute_semrus_da == true){
                
                $file = $this->createWebsiteDataToCsv(@$campaign->name,$campaign->id)['fileFullPath'];
                if($file !=''){
                    $user = User::where('id',$campaign->user_id)->first();
                    $campaignUpdate = Campaign::find($campaign->id);
                    $campaignUpdate->final_upload_csv = $file;
                    $campaignUpdate->status = '7';
                    $campaignUpdate->is_file_read = 'Y';
                    $campaignUpdate->save();

                    $folloUps = Campaign::where('is_parent',$campaign->id)->update(['final_upload_csv'=>$file,'status'=>'7','is_file_read'=>'Y']);
                    if (env('IS_NOTIFY_ENABLE', false) == true) {
                        Notification::send($user, new CampiagnNotification($campaignUpdate));
                    }

                    

                    if (env('IS_MAIL_ENABLE', false) == true) {
                        $content = [
                            'name' => ($campaignUpdate->user) ? $campaignUpdate->user->name :'',
                            'camp_name' => $campaignUpdate->name,
                            'total_email' => $campaignUpdate->import_contact,
                            'csvfile' => $campaignUpdate->final_upload_csv,
                           
                         ];
                        Mail::to($campaign->from_email)->send(new DataProcessingMail($content));
                    }

                    $this->addDataToSendCampiagnMail($campaign->user_id,$campaign->id,$campaign->from_email,$file,$campaign->message,$campaign->subject,$campaign->account_type,$campaign->stage,$campaign->fallback_text);
                }
            }
            
           
        }
          echo Carbon::now() . '------Sync  end------'  . PHP_EOL;
    }
    public  function createWebsiteDataToCsv($camp_name,$campaign_id) { 
       
        $header_email     = 'Email';
        $header_author     = 'Name';
        $header_url    = 'Website';
        $header_title    = 'Title';
        $header_email_valid    = 'Email validation';
        $header_is_blog    = 'Blog status';
        $header_sot    = 'Semrush traffic (US)';
        $header_sok    = 'Semrush keywords (US)';
        $header_da    = 'Da';
        $campName    = \Str::slug($camp_name);
        $uniqueName         = \Str::random(6).$campName;
        $fileName           = 'camp-csv-'.$uniqueName.".csv";
        $csvData = $header_url.','.$header_email.','.$header_title.','.$header_author.','.$header_email_valid.','.$header_is_blog.','.$header_sot.','.$header_sok.','.$header_da."\n";
        
        $allWebsiteData = CampaignCsvWebsite::where('camp_id',$campaign_id)->get();
        if(count($allWebsiteData)>0) {
           
            foreach ($allWebsiteData as $key => $data) {
                $level  ='1';
                $is_valid_email  = ($data->is_email_valid == '1') ? 'true' : '';
                $is_blog  = ($data->is_blog == '1') ? 'true' :'';
                $csvData .= @$data->website.','.@$data->email.','.@$data->title.','.@$data->author.','.@$is_valid_email.','.@$is_blog.','.@$data->sot.','.@$data->sok.','.@$data->da."\n";
                            
                
            }
            $destinationPath    = storage_path('csv_file/');
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $fileDestination    = fopen ($destinationPath.$fileName, "w");
            fputs($fileDestination, $csvData);
            fclose($fileDestination);
            

            $file = storage_path('csv_file/'.$fileName.'');
        
            $FilePath = $fileName;
            $storagePath = Storage::disk('s3')->put($FilePath, file_get_contents($file), 'public');
            $fileFullPath = Storage::disk('s3')->url($FilePath);
            $getcsvData = array_map("str_getcsv", file($fileFullPath,FILE_SKIP_EMPTY_LINES));
            $heading = array_shift($getcsvData);
            $textTotalContact = count($getcsvData);
            if($fileFullPath !=''){
                if(file_exists($destinationPath.$fileName)){ 
                    unlink($destinationPath.$fileName);
                }
            }
        

        }
        $resultData = [
        'fileFullPath' => $fileFullPath,
        ];

        
        return $resultData;
    }
    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }
     public function addDataToSendCampiagnMail($user_id,$campaign_id,$from_email,$file,$message,$subject,$account_type,$stage,$fallbacktext){
        if(!empty($file)){
            $excelDatCsv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
            $heading = array_shift($excelDatCsv);
            $fallback_text = (!empty($fallbacktext)) ? explode(',',$fallbacktext) : null  ;

            foreach ($excelDatCsv as $key1 => $csv) {
                $rowNum =0;
                $level =1;
                $email = '';
                $website = '';
                $site = '';
                $changeArray    = [];
                $newKeyword     = [];
                foreach ($heading as $head => $word) {
                    $rowNum = $head;
                    if($word =='Email'){
                        $email =  $csv[$rowNum];
                    }
                    if($word =='Website'){
                        $website =  $csv[$rowNum];
                    }
                    if($word =='level'){
                        $level =  $csv[$rowNum];
                    }
                }
                if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                {
                    if(is_array($fallback_text) && count($fallback_text) >  0 ){
                        foreach ($fallback_text as $nk => $text) {
                            $fval = explode('|',$text);
                            $akey = @$fval[0];
                            $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                        }
                    }
                    foreach ($heading as $head => $word) {
                        $rowNum = $head;
                        $newKeyword[$word] = $csv[$rowNum];
                        
                    }
                   
                }
                $sub = $this->strReplaceAssoc($newKeyword,$subject);
                $msg = $this->strReplaceAssoc($newKeyword,$message);
                $sub = preg_replace('/<[^>]*>/', ' ', $sub);

            
                $sendMailData = new SendCampaignMail;
                $sendMailData->user_id = $user_id;
                $sendMailData->camp_id = $campaign_id;
                $sendMailData->from_email = $from_email;
                $sendMailData->website = $website;
                $sendMailData->to_email = $email;
                $sendMailData->subject = $sub;
                $sendMailData->message = $msg;
                $sendMailData->status = 0;
                $sendMailData->level = $level;
                $sendMailData->stage = $stage;
                $sendMailData->type = $account_type;
                $sendMailData->save();

                   
            }
            //die;

        }

    }
}
