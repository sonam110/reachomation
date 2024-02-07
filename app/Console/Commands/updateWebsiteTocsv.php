<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Excel;
use Session;
use Illuminate\Support\Facades\File;
use App\Models\Campaign;
use App\Models\CampaignCsvWebsite;
use Validator;
use Helper;
use Str;
use App\Services\BlogJudgementService;
use App\Services\EmailExtractionService;
use App\Services\PersonalizeDataService;
use Illuminate\Support\Carbon;
class updateWebsiteTocsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tool-csv';

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
        echo Carbon::now() . '------update start------' . '1' . PHP_EOL;
        $allCampaign = Campaign::where('status','7')->where('is_file_read','N')->where('id','23')->get();
        $counter = 0;
        foreach ($allCampaign as $key => $campaign) {
            $file = $campaign->file_csv;
            $createCsv3 =  $this->createCsvFileWithTools($campaign->id,$file,$campaign->features);
            Campaign::where('id',$campaign->id)->update(['status'=>'7','is_file_read'=>'Y','final_upload_csv'=>$createCsv3['fileName'],'final_upload_csv_count'=>$createCsv3['textTotalContact']]);
            \Log::info($createCsv3);
            $counter++;
        }
           
       echo '---------------------------------'.$counter.'------------------------------------.' . PHP_EOL;
       echo Carbon::now() . '------update End------' . PHP_EOL;
    }

     public  function createCsvFileWithTools($id,$file,$features)
    {
        
        $excelDatCsv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
        $fileName = $id;
        $heading1 = array_shift($excelDatCsv);

        $allWesiteData = CampaignCsvWebsite::where('camp_id',$id)->get();
        
        $features = explode(',', $features);
        $email ='';
        $website ='';
        
        $email ='';
        $website ='';
        $emailid ='NA';
        $da ='NA';
        $author ='NA';
        $title ='NA';
        $sot ='NA';
        $sok ='NA';
        $is_blog ='NA';
        $is_valid ='NA';
        $csvData1 =[];
        $csvData2 = '';
        $csvData2 = '';
        $csv_data = '';
        $csvArr = [];
        if(in_array('Email',$heading1) && !in_array('Website',$heading1) ){
           $heading  = ["Website","Email","IsValid"];

        }
        if(in_array('Website',$heading1) && !in_array('Email',$heading1) ){
           $heading = ["Website","Email","Da","Author","Title","Sot","Sok","IsBlog"];
        }
        if(in_array('Website',$heading1) && in_array('Email',$heading1) ){
           $heading = ["Website","Email","IsValid","Da","Author","Title","Sot","Sok","IsBlog"];
        }
        //$heading = array_merge($heading1,$heading);
        $dataCsv = implode(', ',$heading)."\n";
        if(count($allWesiteData)>0) {
            foreach ($allWesiteData as $key => $cdata) {
                $email =  $cdata->email;
                $website =  $cdata->website;
                    if($website !='' && $email ==''){
                       // print_r($website);
                        /* Evaluate domains for 'Blog'*/
                        if(in_array('1',$features)){
                            $da = self::domainDa($website);
                            if(in_array('Da',$heading)){
                                $da =  $da;
                            }
                        }
                        /*-------Email Extract*/
                        if(in_array('2',$features)){
                            $emailid = self::emailExtraction($website);
                            if(in_array(' Email',$heading)){
                                $emailid =  $emailid;
                            }
                           
                        }
                         /*Extract personalization data like recent blog article, author name*/
                        if(in_array('4',$features)){
                           $data = self::getDomainAuthorTitle($website);
                           $author = $data['author'];
                           $title = $data['title'];
                            if(in_array('Author',$heading)){
                                $author =  $author;
                            }
                            if(in_array('Title',$heading)){
                                $title =  $title;
                            }
                        }
                        /*Compute SEMrush Traffic and Domain Authority for domain*/
                        if(in_array('5',$features)){
                            
                            $semrush = self::domainSemrush($website);
                            $sot = $semrush['sot'];
                            $sok = $semrush['sok'];
                            if(in_array('Sot',$heading)){
                                $sot =  $sot;
                            }
                            if(in_array('Sok',$heading)){
                                $sok =  $sok;
                            }
                        }
                        /*------------Blog judgemnet--------*/
                        if(in_array('6',$features)){
                            $is_blog = self::BlogJudgement($website);
                            if(in_array('IsBlog',$heading)){
                                $is_blog =  $is_blog;
                            }
                        }
                       
                        $dataCsv .= $website.",".$emailid.",".$da.",".$author.",".$title.",".$sot.",".$sok.",".$is_blog."\n";
                        \Log::info($dataCsv);

                    }
                    if($website =='' && $email !=''){
                        /*Validate emails*/
                        if(in_array('3',$features))   {   
                            $is_valid = self::emailValidation($email);
                            if(in_array('IsValid',$heading)){
                                $is_valid =  $is_valid;
                            }
                        }
                        $dataCsv .= $website.",".$email.",".$is_valid."\n";
                         \Log::info($dataCsv);
                                
                    }
                        
                    if($website !='' && $email !=''){
                        /* Evaluate domains for 'Blog'*/
                        if(in_array('1',$features)){
                            $da = self::domainDa($website);
                            if(in_array('Da',$heading)){
                                $da =  $da;
                            }
                        }
                        //*Validate emails*/
                        if(in_array('3',$features))   {   
                            $is_valid = self::emailValidation($email);
                            if(in_array('IsValid',$heading)){
                                $is_valid =  $is_valid;
                            }
                                
                        }
                        /*Extract personalization data like recent blog article, author name*/
                        if(in_array('4',$features)){
                           $data = self::getDomainAuthorTitle($website);
                           $author = $data['author'];
                           $title = $data['title'];
                           if(in_array('Author',$heading)){
                                $author =  $author;
                            }
                            if(in_array('Title',$heading)){
                                $title =  $title;
                            }
                        }
                        /*Compute SEMrush Traffic and Domain Authority for domain*/
                        if(in_array('5',$features)){
                            
                            $semrush = self::domainSemrush($website);
                            $sot = $semrush['sot'];
                            $sok = $semrush['sok'];
                            if(in_array('Sot',$heading)){
                                $sot =  $sot;
                            }
                            if(in_array('Sok',$heading)){
                                $sok =  $sok;
                            }
                        }
                         /*------------Blog judgemnet--------*/
                        if(in_array('6',$features)){
                            $is_blog = self::BlogJudgement($website);
                            if(in_array('IsBlog',$heading)){
                                $is_blog =  $is_blog;
                            }
                        }
                        $dataCsv .= $website.",".$email.",".$is_valid.",".$da.",".$author.",".$title.",".$sot.",".$sok.",".$is_blog."\n";
                         \Log::info($dataCsv);

                    }
                   
                
            }
             \Log::info($dataCsv);
           
            $destinationPath    = base_path('csv_file/');
            $uniqueName         = \Str::random(6);
            $fileName = $fileName;
            if($fileName!=null) {
                $fileName           = $uniqueName.'-'.$fileName.".csv";
            } else {
                $fileName           = $uniqueName.".csv";
            }
            $fileDestination    = fopen ($destinationPath.$fileName, "w");
            fputs($fileDestination, $dataCsv);
            fclose($fileDestination);
            $textTotalContact   = count($allWesiteData);

            $filenew= base_path('csv_file/'.$fileName.'');
            
            $FilePath = $fileName;
            $storagePath = Storage::disk('s3')->put($FilePath, file_get_contents($filenew), 'public');
            $fileFullPath = Storage::disk('s3')->url($FilePath);
            
            if($fileFullPath !=''){
                if(file_exists($destinationPath.$fileName)){ 
                    unlink($destinationPath.$fileName);
                }
            }

           
            $returnData = [
            'fileName'              => $fileFullPath,
            'textTotalContact'      => $textTotalContact,
            ];
            return $returnData;
        }
                

    }
    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }


    public function emailValidation($email){
        $isEmailValid = validateEmail($email);
        return $isEmailValid;
    }
    public function emailExtraction($website){
        $email ='';
        if($website!=''){
             $collection = DB::table('website_data')->where('website',$website)->first();
            if(is_object($collection)){
                $emails = $collection->emails;
                $emls = json_decode($emails,true);
                foreach($emls as $k => $eml) {
                  if(is_array($eml)){
                     $email  = @$emls[$k][0];
                  } else {
                      $email  = @$eml;
                      
                  }
                  
                  
                }
            } else {
                /*--------use email extraction tools*/
                $getEmail=  new EmailExtractionService;
                $email =  $getEmail->extractEmail($website);

            }
        }
        return $email;
    }
    public function getDomainAuthorTitle($website){
        $author  = '';
        $title  = '';
        if($website!=''){
            $collection = DB::table('website_data')->where('website',$website)->first();
            if(is_object($collection)){
                $author = $collection->author;
                $title = $collection->title;
            } else {
                /*--------use email extraction tools*/
                $blog = new PersonalizeDataService;
                $data = $blog->personalizeData($website);
                $author= (!empty($data)) ? $data['author'] :'';
                $title= (!empty($data)) ? $data['title'] :'';
            }
        }
        return $resultArr =[
            'author'=>$author,
            'title'=>$title,

        ];
    }
    public function domainDa($website){
        $da ='';
        if($website!=''){
            $collection = DB::table('website_data')->where('website',$website)->first();
            if(is_object($collection)){
                $da = $collection->da;
            } else {
                /*--------use semrush tools*/
                $getda = getda($website);
                $da = $getda;
            }
        }
        return $da;
    }
     public function domainSemrush($website){
        $sot ='';
        $sok ='';
        if($website!=''){
            $collection = DB::table('website_data')->where('website',$website)->first();
            if(is_object($collection)){
                $sot = $collection->sot;
                $sok = $collection->sok;
            } else {
                /*--------use semrush tools*/
                $semrush = getSemrush($website);
                $sot = $semrush['sot'];
                $sok = $semrush['sok'];
            }
        }
        return $resultArr =[
            "sot"=>$sot,
            "sok"=>$sok,
        ];
    }
    function BlogJudgement($website){
        $is_blog = false;
        if($website!=''){
            $collection = DB::table('website_data')->where('website',$website)->first();
            if(is_object($collection)){
                $da = $collection->blog_status;
            } else {
                /*--------use blog judgement tools*/
                $blog = new BlogJudgementService;
                $is_blog = $blog->BlogJudgement($website);
            }
        }
        return $is_blog;
    }
}
