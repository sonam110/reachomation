<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Excel;
use Session;
use Illuminate\Support\Facades\File;
use App\Models\Campaign;
use Validator;
use Helper;
use Str;
use App\Services\BlogJudgementService;
use App\Services\EmailExtractionService;
use App\Services\PersonalizeDataService;
use Illuminate\Support\Carbon;
class ListFinalCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:list';

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
        $allCampaign = Campaign::whereIn('status',['0','1'])->whereDate('start_date','>=',date('Y-m-d'))->get();
        $counter = 0;
        foreach ($allCampaign as $key => $campaign) {
            if($campaign->type=='1'){
                $createCsv = $this->createCsvFile($campaign->subject,$campaign->message,$campaign->id,$campaign->list_to_file_csv,'1');
                Campaign::where('id',$campaign->id)->update(['status'=>'1','final_upload_csv'=>$createCsv['fileName'],'final_upload_csv_count'=>$createCsv['textTotalContact']]);
                
            }
            if($campaign->type=='2'){
                if($campaign->is_feature == '0'){
                    $createCsv1 = $this->createCsvFile($campaign->subject,$campaign->message,$campaign->id,$campaign->file_csv,'2');
                    Campaign::where('id',$campaign->id)->update(['status'=>'1','final_upload_csv'=>$createCsv1['fileName'],'final_upload_csv_count'=>$createCsv1['textTotalContact']]);
                   
                } 
            }
           
            $counter++;
        }
       echo '---------------------------------'.$counter.'------------------------------------.' . PHP_EOL;
       echo Carbon::now() . '------update End------' . PHP_EOL;
    }

    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }
    /*-----------Create custom csv---------------------------------*/
    public  function createCsvFile($subject,$message,$fileName,$file,$type)
    {
        $subject_actual = $subject;
        $message_actual = $message;
        $fileName               = $fileName;
        $textTotalContact       = 0;
        $data_for_insert        = null;

        $header_email     = 'Email';
        $header_subject    = 'Subject';
        $header_message    = 'Message';
        $header_level    = 'Level';
  
       
            
        $excelDatCsv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
        $heading = array_shift($excelDatCsv);
        $head_data = implode(',',$heading);
        if($type == '2'){
            $csvData = $head_data.",".$header_level."\n";
        } else {
            $csvData = $head_data."\n";
        }
       
        
        $msg ='';
        $sub ='';
        $email ='';
        $level ='1';
        
        if(is_array($excelDatCsv) && count($excelDatCsv)>0) {
            foreach ($excelDatCsv as $key => $csv) {
                $changeArray    = [];
                $newKeyword     = [];
                if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                {   
                    $rowNum =0;
                    foreach ($heading as $head => $word) {
                        $rowNum = $head;
                        $newKeyword[$word] = $csv[$rowNum];
                        if($word == 'Email'){
                            $email =  $csv[$rowNum];
                        }
                        if($word == 'Level'){
                            $level =  $csv[$rowNum];
                        }
                    }
                   
                    
                }
                
                $csv_data = implode(',',$csv);
                if($type == '2'){
                   $csvData .= $csv_data.",".$level."\n";
                } else{
                  $csvData .= $csv_data."\n";
                }
               

            }
            //die;
           
        }

        
        $destinationPath    = base_path('csv_file/');
        $uniqueName         = \Str::random(6);
        if($fileName!=null) {
            $fileName           = $uniqueName.'-'.$fileName.".csv";
        } else {
            $fileName           = "final-csv-".$uniqueName.".csv";
        }
        $fileDestination    = fopen ($destinationPath.$fileName, "w");
        fputs($fileDestination, $csvData);
        fclose($fileDestination);
        $textTotalContact   = count($excelDatCsv);

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
