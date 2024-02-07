<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Excel;
use Session;
use App\Imports\CsvDataImport;
use Illuminate\Support\Facades\File;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\CampaignFollowup;
use App\Models\User;
use App\Models\CampaignCsvWebsite;
use App\Models\SubscriptionPlan;
use App\Models\BlackListEmail;
use App\Models\SendCampaignMail;
use App\Models\Template;
use App\Models\Collection;
use Validator;
use Helper;
use Exception;
use DateTime;
use DateTimeZone;
use Str;
use App\Services\SendSmsService;
use App\Services\EmailExtractionService;
use App\Services\BlogJudgementService;
use App\Services\MessageListService;
use App\Services\PersonalizeDataService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CampiagnNotification;
use Illuminate\Support\Carbon;
class ScheduleCampaignController extends Controller
{
    


    public function index(Request $request,$id=Null){
        
        $user = auth()->user();
        $collections = DB::table('collections')
                        ->select()
                        ->where('user_id', $request->user()->id)
                        ->where('status', '=' ,1)
                        ->where('website_count', '>' ,0)
                        ->orderBy('created_at', 'desc')
                        ->get();

        Session::forget('heading');
        Session::forget('excelData');
        Session::forget('lis_id');
        $default_email =  EmailCollection::where('user_id',auth()->user()->id)->where('is_default','1')->first();
        $default_template = Template::where('id', auth()->user()->default_tid)
                    ->where('status', '=' ,1)
                    ->first();
        $allOldCampign = Campaign::select('id','name','import_contact','final_upload_csv_count')->whereNull('is_parent')->where('user_id',$request->user()->id)->get();
       $list_id = $id;
        if($id !=''){
            $checkList = Collection::where('id',$id)->where('user_id',auth()->user()->id)->first();
            if(empty($checkList)){
                return redirect()->route('campaigns')->with('error', 'You are not authorize to access this list');
            }
        }
       $plan = SubscriptionPlan::where('id',$user->plan)->first();
        if(empty($plan)){
            return redirect()->back()->with('error','You have no active plan.');
           
        }
       $size_limit = (!empty($plan))? $plan->size_limit :0;
        $duplicateNameC = Campaign::whereNull('is_parent')
        ->where('user_id',auth()->user()->id)
        ->whereDate('created_at',date('Y-m-d'))
        ->count();
       if($duplicateNameC >0){
            $camp_name = date('l d M Y').' #'.$duplicateNameC;
       } else{
        $camp_name = date('l d M Y');
       }
       
        return view('schedulecampaign', [ 'collections'=>$collections, 'default_email'=>$default_email,'default_template'=>$default_template,'allOldCampign'=>$allOldCampign,'list_id'=>$list_id,'size_limit'=>$size_limit,'camp_name'=> $camp_name]);
      } 
    

    /*public function loadList(Request $request){
        $collections = DB::table('website_data')
                    ->join('domain_collections', 'website_data.domain_id', '=', 'domain_collections.domain_id')
                    ->join('collections', 'domain_collections.collection_id', '=', 'collections.id')
                    ->select('website_data.website','website_data.emails','website_data.tags','collections.name')
                    ->where('collections.user_id', $request->user()->id)
                    ->where('collections.status', '=' ,1)
                    ->where('domain_collections.collection_id', '=', $request->collectionId)
                    ->get();
                
        $collections_count = DB::table('domain_collections')
                    ->join('collections', 'collections.id', '=', 'domain_collections.collection_id')
                    ->select('domain_collections.*', 'collections.name')
                    ->where('collections.user_id', $request->user()->id)
                    ->where('collections.status', '=' ,1)
                    ->where('domain_collections.collection_id', '=', $request->collectionId)
                    ->count();
        return response()->json(['collections'=>$collections, 'collections_count'=>$collections_count]);
    }*/

    public function listDomainCollection(Request $request){
        Session::forget('lis_id');
        Session::forget('heading');
        Session::forget('excelData');
        $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
        if(empty($plan)){
            return response()->json(['type'=> 'no_plan','message'=>'You can not upload a file! you have no purchase plan']);
        }

        $collections = DB::table('website_data')
                    ->join('domain_collections', 'website_data.domain_id', '=', 'domain_collections.domain_id')
                    ->join('collections', 'domain_collections.collection_id', '=', 'collections.id')
                    ->select('website_data.website','website_data.domain_id','website_data.id','website_data.emails','website_data.tag_category','website_data.author','website_data.credit_cost','website_data.title','website_data.domain_id','collections.name')
                    ->where('collections.user_id', $request->user()->id)
                    ->where('collections.status', '=' ,1)
                    ->where('domain_collections.collection_id', '=', $request->collectionId)
                    ->get();
        if(count($collections) > $plan->size_limit){
            $message = ' With a '.$plan->name.' plan, you can create a list of upto '.$plan->size_limit.' entries. To add more than '.$plan->size_limit.' sites in a list';
            $data = [
                'type'      => 'limit',
                'size_limit'      => $plan->size_limit,
                 'message'      => $message,

            ];
            return response()->json($data, 200);
        }

        Session::put('list_id', $request->collectionId);   
        $list = DB::table('collections')->where('id', '=', $request->collectionId)->first();
        return View('list-domain-collection', compact('collections','list'));
    }
    public function listCampaignCollection(Request $request){
        Session::forget('lis_id');
        Session::forget('heading');
        Session::forget('excelData');
        $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
        if(empty($plan)){
            return response()->json(['type'=> 'no_plan','message'=>'You can not upload a file! you have no purchase plan']);
        }

        $campaign = Campaign::where('id',$request->camp_id)->first();
        if(!empty($campaign)){
            if($campaign->type=='1'){
                $collections = DB::table('website_data')
                    ->join('domain_collections', 'website_data.domain_id', '=', 'domain_collections.domain_id')
                    ->join('collections', 'domain_collections.collection_id', '=', 'collections.id')
                    ->select('website_data.website','website_data.domain_id','website_data.id','website_data.emails','website_data.tag_category','website_data.author','website_data.credit_cost','website_data.title','website_data.domain_id','collections.name')
                    ->where('collections.user_id', $request->user()->id)
                    ->where('collections.status', '=' ,1)
                    ->where('domain_collections.collection_id', '=', $campaign->list_id)
                    ->get();
                Session::put('list_id', $campaign->list_id);   
                if(count($collections) > $plan->size_limit){
                    $message = ' With a '.$plan->name.' plan, you can create a list of upto '.$plan->size_limit.' entries. To add more than '.$plan->size_limit.' sites in a list';
                    $data = [
                        'type'      => 'limit',
                        'size_limit'      => $plan->size_limit,
                        'message'      => $message,
                    ];
                    return response()->json($data, 200);
                }

                $list = DB::table('collections')->where('id', '=', $campaign->list_id)->first();
                return View('list-domain-collection', compact('collections','list'));
            } else{
                $file = $campaign->final_upload_csv;
                $realfilename = \Str::slug($campaign->name).'csv';
                $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($excelData);
                Session::put('heading', $heading);
                Session::put('excelData', $excelData);
                if(count($excelData) > $plan->size_limit){
                    $data = [
                        'type'      => 'limit',
                        'size_limit'      => $plan->size_limit,
                    ];
                    return response()->json($data, 200);
                }

                return View('domainlist', compact('excelData','heading', 'realfilename','campaign'));
                
            }
        } else{
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
        
    }
   

    public function downloadfile()
    {
        return response()->download(storage_path('reachomation-campaign-sample-csv-file.csv'));
    }

    public function domainUpload(Request $request) 
    {
        Session::forget('lis_id');
        Session::forget('heading');
        Session::forget('excelData');
        try{
        $validator = Validator::make($request->all(), [
            'file'          => 'required|mimes:csv,txt|max:10240', 
        ]);
        if ($validator->fails()) {
             $data = [
                'type'      => 'error',
                'message'      => $validator->errors()->first(),
            ];
            return response()->json($data, 200);
        }
        $path = $request->file('file')->getRealPath();

        $file = $request->file('file');
        $realfilename = $request->file('file')->getClientOriginalName();
        //$excelData  =  Excel::toArray(new CsvDataImport(), $file); 
        $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES ));
        $row = ''; 
        $heading = array_shift($excelData);
        $validContact = 0;
        $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix';
        foreach($heading as $key => $head){
            if(ucfirst($head) == 'Email'){
               $row = $key;
            }
        }
        foreach($excelData as $key=>  $excel){
            if(!empty($excel[$row]) ){
                if(preg_match($pattern,$excel[$row]) == 1){
                    $validContact++;
                }
            }
        }

        $is_heading = false;
        $plan = SubscriptionPlan::where('id',auth()->user()->plan)->first();
        if(empty($plan)){
            return response()->json(['type'=> 'no_plan','message'=>'You can not upload a file! you have no purchase plan']);
        }
        if(count($excelData) <=0){
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
        if( $plan->import_sites =='0'){
            $data = [
                'type'      => 'not_allow',
            ];
            return response()->json($data, 200);
        }

        if(in_array('Email',$heading) || in_array('email',$heading)){
           $is_heading = true;
        }
        /*if(in_array('Website',$heading)){
           $is_heading = true;
        } */
        if($is_heading == false){
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
        if(count($excelData) > $plan->size_limit){
            $message = ' With a '.$plan->name.' plan, you can create a list of upto '.$plan->size_limit.' entries. To add more than '.$plan->size_limit.' sites in a list';
            $data = [
                'type'      => 'limit',
                'size_limit'      => $plan->size_limit,
                'message'      => $message,
            ];
            return response()->json($data, 200);
        }
       
        if($validContact < 1){
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }


        Session::put('heading', $heading);
        Session::put('excelData', $excelData);
        return View('domainlist', compact('excelData','heading', 'realfilename'));
    
        } catch (\Exception $e) {
           
            Log::error($e);
             return response()->json(['type'=> 'no_plan','message'=>'Opps! Something went wrong']);
        }

    }
     public function creditModel(Request $request) 
    {
        
        /*$this->validate($request, [
            'file'          => 'required|mimes:csv,txt', 
        ]);
        $path = $request->file('file')->getRealPath();

        $file = $request->file('file');
        $realfilename = $request->file('file')->getClientOriginalName();
        //$excelData  =  Excel::toArray(new CsvDataImport(), $file); 
        $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
        $heading = array_shift($excelData);
        $is_only_email = false;
        $is_only_domain = false;
        $is_email_domain = false;
        if(in_array('Email',$heading) && !in_array('Website',$heading) ){
           $is_only_email = true;
        }
        if(in_array('Website',$heading) && !in_array('Email',$heading) ){
           $is_only_domain = true;
          // $is_email_domain = true;
        }
        if(in_array('Website',$heading) && in_array('Email',$heading) ){
           $is_email_domain = true;
        }*/

        if($request->edit_id!=''){
            $camp = Campaign::where('id',$request->edit_id)->first();
            $feature_string = explode(',',@$request->features);
            $domain_authority = $camp->domain_authority;
            $semrus_traffic = $camp->semrus_traffic;
            $credit_deduct = $camp->credit_deduct;
        } else{
            $feature_string = explode(',',@$request->features);
            $domain_authority = NULL;
            $semrus_traffic = NULL;
            $credit_deduct = 0;
        }

        $totalCount = $request->valid_contact;

        return View('credit-model', compact('totalCount','feature_string','domain_authority','semrus_traffic','credit_deduct'));
        
    }
    public function editCreditModel(Request $request) 
    {
        $data = Campaign::where('id',$request->id)->where('user_id',auth()->user()->id)->first();
        if($data){
        $file= $data->file_csv;
        $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
        $heading = array_shift($excelData);
        $is_only_email = false;
        $is_only_domain = false;
        $is_email_domain = false;
        if(in_array('Email',$heading) && !in_array('Website',$heading) ){
           $is_only_email = true;
        }
        if(in_array('Website',$heading) && !in_array('Email',$heading) ){
           $is_only_domain = true;
           //$is_email_domain = true;
        }
        if(in_array('Website',$heading) && in_array('Email',$heading) ){
           $is_email_domain = true;
        }
        $realfilename = 'file-csv'.'-'.$data->id;
        $feature_string = explode(',',$request->features);
        $credit = $request->credit;

        return View('edit-credit-model', compact('data','feature_string','credit','excelData','heading', 'realfilename','is_only_email','is_only_domain','is_email_domain'));
        

        } else {
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
        
  
    }
    public function getCustomData(Request $request){
        if($request->type== '1'){
            $stageCount = $request->stageCount;
            $data = [
                'type'      => 'success',
                'custom-type'   => $request->type,
                'stageCount'   => $stageCount,
            ];
            return response()->json($data, 200);
        }
        if($request->type== '2' || $request->type== '3' ){
            $stageCount = $request->stageCount;
            $heading = Session::get('heading');

            $data = [
                'type'      => 'success',
                'custom-type'   => $request->type,
                'stageCount'   => $stageCount,
                'heading'   => $heading,
            ];
            return response()->json($data, 200);
        }
    }

    public function PreviewCampaign(Request $request){
        $data = $request->all();
        /*---------------------List email id collection-----------------*/
        $domain_id = explode(',', $request->domain_ids);
        $nofAttempts = 1;
        $excelData =[];
        $heading =[];
        if(@$request->id !=''){
            $camp = Campaign::where('id',$request->id)->first();
            $file = $camp->final_upload_csv;
            $realfilename = \Str::slug($camp->name);
            $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
            if(!empty($excelData)){
                $heading = array_shift($excelData);
            }

            $uniqueName         = \Str::random(6).$realfilename; 
            $fileNamee = $uniqueName;
            $file_name = $fileNamee;
            $storagePath = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
            $fileNew = Storage::disk('s3')->url($file_name);
 
            $textTotalContact = count($excelData);

        } else{
            if($request->customtype == '1'){
                $collections = DB::table('website_data')->select('id','domain_id','emails','author','website','title')->whereIn('domain_id',$domain_id)->get();
                $file = $this->createListToCsv($collections)['fileFullPath'];
                $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                if(!empty($excelData)){

                    $heading = array_shift($excelData);
                    $nofAttempts = max(array_column($excelData, 4));
                }
                

                  
                
            }

           /*---------------------Csv email id collection-----------------*/
          
           
            if($request->customtype == '2'){
                
                $excelData = Session::get('excelData');
                if(!empty($excelData)){

                    $heading = array_shift($excelData);
                    $textTotalContact = count($excelData);
                }
                
            }
            if($request->customtype == '3'){
                $camp = Campaign::where('id',$request->camp_id)->first();

                $file = $camp->final_upload_csv;

                $realfilename = \Str::slug($camp->name);
                $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                if(!empty($excelData)){

                    $heading = array_shift($excelData);
                    $uniqueName         = \Str::random(6).$realfilename; 
                    $fileNamee = $uniqueName;
                    $file_name = $fileNamee;
                    $storagePath = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
                    $fileNew = Storage::disk('s3')->url($file_name);
         
                    $textTotalContact = count($excelData);
                }


               
               

            }
           
        }
       
         if($request->import_contact < 1){
                $data = [
                    'type'      => 'error',
                ];
                return response()->json($data, 200);
            }
        if(in_array('Level',$heading) ){
            $nofAttempts = max(array_column($excelData, 4));
        } else{
            $nofAttempts = 1;
        }
        
        
        
       
        $collections = DB::table('website_data')->select('id','domain_id','emails')->whereIn('domain_id',$domain_id)->get();
         
        return View('preview-campaign', compact('data','collections','nofAttempts','excelData'));
    }
    public function createCampaign(Request $request){
        try{

           $this->validate($request, [ 
                'campaign_name'       => 'required',
                'target'              => 'required',
                'from_email'          => 'required',
                'subject'             => 'required',    
                "subject.*"           => "required", 
                'message'             => 'required',
                'message.*'           => 'required',
            ]);
           if(isset($request->is_feature) && $request->is_feature !='1'){
                $this->validate($request, [ 
                    'start_date'          => 'required',
                    //'starttime'           => 'required',
                   // 'endtime'             => 'required',
                    'cooling_period'      => 'required|numeric',
                ]);

           }
            
            if(isset($request->list_id) && !empty($request->list_id)){
                $this->validate($request, [
                    'list_id'             => 'required|exists:collections,id',
      
                ]);
                
            }
            if(isset($request->csv_file) && !empty($request->csv_file)){
               $this->validate($request, [
                    'csv_file'             => 'required|mimes:csv,txt', 
      
                ]);
                

            }
            if(isset($request->camp_id) && !empty($request->camp_id)){
                $this->validate($request, [
                    'camp_id'             => 'required|exists:campaigns,id',
      
                ]);
                
            }
            
            /*$heading = Session::get('heading');
            if($heading !='' && in_array('Website',$heading) && !in_array('Email',$heading) ){
               return redirect()->back()
                        ->with('error', 'Please select email extraction tools for extract email form website');
            }*/
            $status = '6';
            if(isset($request->btn_type) && $request->btn_type =='lunch'){
                $status = '0';
            }
           
            if(isset($request->is_feature) && $request->is_feature =='1'){
                $status = '7';
            }
             if(isset($request->btn_type) && $request->btn_type =='draft'){
                $status = '6';
            }

            $user = User::where('id',auth()->user()->id)->first();

            /*---------------------List email id collection-----------------*/
            if(isset($request->customtype) && $request->customtype == '1'){
                $domain_id = explode(',', $request->domain_ids);
                $collections = DB::table('website_data')->select('id','domain_id','emails','author','website','title')->whereIn('domain_id',$domain_id)->get();
                $file = $this->createListToCsv($collections)['fileFullPath'];
                $file_name = $this->createListToCsv($collections)['fileName'];
                $fileNew = $this->createListToCsv($collections)['fileFullPath'];
                $textTotalContact = $this->createListToCsv($collections)['textTotalContact'];

                  
                
            }

           /*---------------------Csv email id collection-----------------*/
            $excelData =[];
            $excelDatCsv =[];
            if(isset($request->customtype) && $request->customtype == '2'){
                $file = $request->file('csv_file');
                $uniqueName         = \Str::random(6); 
                $fileNamee = time().'-'.$uniqueName.'-'.$request->file('csv_file')->getClientOriginalName();
                $file_name = $fileNamee;
                $storagePath = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
                $fileNew = Storage::disk('s3')->url($file_name);
                $getcsvData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($getcsvData);
                $textTotalContact = count($getcsvData);
               

            }
            $list_id = $request->list_id;
            if(isset($request->customtype) && $request->customtype == '3'){
                $camp = Campaign::where('id',$request->camp_id)->first();

                $file = $camp->final_upload_csv;

                $realfilename = \Str::slug($camp->name);
                $getcsvData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($getcsvData);

                $uniqueName         = \Str::random(6).$realfilename; 
                $fileNamee = time().'-'.$uniqueName;
                $file_name = $fileNamee;
                $storagePath = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
                $fileNew = Storage::disk('s3')->url($file_name);
     
                $textTotalContact = count($getcsvData);
                $list_id = $camp->list_id;
               

            }
            if(isset($request->is_feature) && $request->is_feature == 1){
                $starttime =  date('H:i:s');
                $endtime =  date('H:i:s');
            } else{
                if(isset($request->non_stop) && $request->non_stop == 1){
                    $starttime =  date('H:i:s');
                    $endtime =   date('H:i:s', strtotime('+ 24 hours'));
                } else{
                    $starttime =  date('H:i:s',strtotime($request->starttime));
                    $endtime =   date('H:i:s',strtotime($request->endtime));
                }

            }
            $start_date =  date('Y-m-d',strtotime($request->start_date));
            $mail_account = EmailCollection::where('user_id',auth()->user()->id)->where('email',$request->from_email)->first();
           
            $cooling_period = $request->cooling_period;
            $timezone_start_date = Carbon::parse($request->start_date)->timezone('America/New_York')->format('Y-m-d');

            if(is_array(@$request->subject) && count(@$request->subject) >0 ){
                $j = 0;
                for ($i = 0;$i <= count($request->subject);$i++) {
                    $j++;
                    if (!empty($request->subject[$i])) {
                       
                        if($request->is_followup[$i] == '0') {
                            $campaign = new Campaign;
                            $campaign->user_id = auth()->user()->id;
                            $campaign->name = $request->campaign_name; 
                            $campaign->target_type = $request->target;
                            $campaign->list_id = $list_id;
                            $campaign->campid = $request->camp_id;
                            $campaign->from_email = $request->from_email;
                            $campaign->mail_account_id = $mail_account->id;
                            $campaign->account_type = $request->account_type;
                            $campaign->file_csv = $fileNew;
                            $campaign->list_to_file_csv = $fileNew;
                            $campaign->final_upload_csv = $fileNew;
                            $campaign->final_upload_csv_count = $textTotalContact;
                            $campaign->subject = $request->subject[$i];
                            $campaign->message = $request->message[$i];
                            $campaign->temp_id = $request->temp_id[$i];
                            $campaign->followup_condition = $request->followup_cond[$i];
                            $campaign->file_mail_clm_name = 'Email';
                            $campaign->total_domain = $request->total_count;
                            $campaign->start_date = $start_date;
                            $campaign->stage = $j;
                            $campaign->starttime = $starttime;
                            $campaign->endtime = $endtime; 
                            $campaign->timezone_start_date = $timezone_start_date; 
                            $campaign->timezone = $request->timezone;
                            $campaign->cooling_period = $request->cooling_period;
                            $campaign->type = $request->customtype;
                            $campaign->credit_deduct = $request->credit_deduct;
                            $campaign->fallback_text = (isset($request->fallback_text) &&  !empty($request->fallback_text)) ? $request->fallback_text :'Name|there,Website|site';
                            $campaign->features = (isset($request->features) && !empty($request->features)) ? $request->features :'' ;
                            $campaign->domain_authority = $request->domain_authority;
                            $campaign->semrus_traffic = $request->semrus_traffic;
                            $campaign->total_contact = $request->textTotalContact;
                            $campaign->import_contact = ($request->customtype=='1') ? $textTotalContact  :$request->import_contact;
                            $campaign->invalid_contact = $request->invalid_contact;
                            $campaign->duplicate_contact = $request->duplicate_contact;
                            $campaign->is_feature = (isset($request->is_feature) && $request->is_feature == 1) ? 1:0;
                            $campaign->including_non_blog = (isset($request->including_non_blog) && $request->including_non_blog == 1) ? 1:0;
                            $campaign->is_same_thread = (isset($request->is_same_thread) && $request->is_same_thread == 1) ? 1:0;
                            $campaign->non_stop = (isset($request->non_stop) && $request->non_stop == 1) ? 1:0;
                            $campaign->is_credit_deduct = (isset($request->is_feature) && $request->is_feature == 1) ? 'Y': 'N';
                            $campaign->interval = '60';
                            $campaign->status = $status;
                            $campaign->is_active = '1';
                            $campaign->save();


                            if($campaign->status=='0'){
                                if (env('IS_NOTIFY_ENABLE', false) == true) {
                                    //Notification::send($user, new CampiagnNotification($campaign));
                                }
                               
                            }
                          
                            if(isset($request->is_feature) && $request->is_feature == 1){
                                $user  = User::where('id',auth()->user()->id)->first();
                                $user->credits = $user->credits-$request->credit_deduct; 
                                $user->save(); 

                                \Helper::transactionHistory(auth()->user()->id,'2','1','0',$request->credit_deduct,'Credit deduct for campaign tools','1');
                                foreach ($getcsvData as $key => $csv) {
                                    $rowNum =0;
                                    $email = '';
                                    $website = '';
                                    foreach ($heading as $head => $word) {
                                        $rowNum = $head;
                                        if($word == 'Email'){
                                            $email =  $csv[$rowNum];
                                        }
                                        if($word == 'Website'){
                                            $website =  $csv[$rowNum];
                                        }
                                    
                                        
                                    }
                                    $features = explode(',', $request->features);
                                    $checkEmail = CampaignCsvWebsite::where('camp_id',$campaign->id)->where('email',$email)->first();
                                    if(!empty($email) && empty($checkEmail) ) {
                                        $websitedata = new CampaignCsvWebsite;
                                        $websitedata->user_id = auth()->user()->id;
                                        $websitedata->camp_id = $campaign->id;
                                        $websitedata->website = $website;
                                        $websitedata->email = $email;
                                        $websitedata->is_blog_judgement = (in_array('1',$features)) ? 1:0;
                                        $websitedata->is_email_extract = (in_array('2',$features)) ? 1:0;
                                        $websitedata->is_email_validate = (in_array('3',$features)) ? 1:0;
                                        $websitedata->is_personalize_data = (in_array('4',$features)) ? 1:0;
                                        $websitedata->is_compute_semrus_da = (in_array('5',$features)) ? 1:0;
                                        $websitedata->save();
                                    }
                                }
                            } else{
                                $this->addDataToSendCampiagnMail(auth()->user()->id,$campaign->id,$request->from_email,$fileNew,$request->message[$i],$request->subject[$i],$request->account_type,$j,$campaign->fallback_text);


                            }

                            
                        }
                        if($campaign){
                            if($request->is_followup[$i] == '1') {
                                $followup_condition = (!empty(@$request->followup_cond[$i])) ? $request->followup_cond[$i] :'3';
                                $followUp = new Campaign;
                                $followUp->user_id = auth()->user()->id;
                                $followUp->is_parent = $campaign->id;
                                $followUp->top_most_parent = $campaign->id;
                                $followUp->target_type = $request->target;
                                $followUp->name = $request->campaign_name;
                                $followUp->list_id = $list_id;
                                $followUp->campid = $request->camp_id;
                                $followUp->from_email = $request->from_email;
                                $followUp->mail_account_id = $mail_account->id;
                                $followUp->account_type = $request->account_type;
                                $followUp->file_csv = $fileNew;
                                $followUp->list_to_file_csv = $fileNew;
                                $followUp->final_upload_csv = $fileNew;
                                $followUp->final_upload_csv_count = $textTotalContact;
                                $followUp->subject = $request->subject[$i];
                                $followUp->message = $request->message[$i];
                                $followUp->temp_id = $request->temp_id[$i];
                                $followUp->followup_condition = $followup_condition;
                                $followUp->file_mail_clm_name = 'Email';
                                $followUp->total_domain = $request->total_count;
                                $followUp->stage =$j; 
                                $followUp->starttime = $starttime;
                                $followUp->endtime = $endtime; 
                                $followUp->timezone = $request->timezone;
                                $followUp->cooling_period = $request->cooling_period;
                                $followUp->type = $request->customtype;
                                $followUp->credit_deduct = $request->credit_deduct;
                                $followUp->fallback_text = (!empty($request->fallback_text)) ? $request->fallback_text :'Name|there,Website|site';
                                $followUp->features = (isset($request->features) && !empty($request->features)) ? $request->features :'' ;
                                $followUp->domain_authority = $request->domain_authority;
                                $followUp->semrus_traffic = $request->semrus_traffic;
                                $followUp->total_contact = $request->textTotalContact;
                                $followUp->import_contact = $campaign->import_contact;
                                $followUp->invalid_contact = $request->invalid_contact;
                                $followUp->duplicate_contact = $request->duplicate_contact;
                                $followUp->is_feature = ($request->is_feature == 1) ? 1:0;
                                $followUp->including_non_blog = ($request->including_non_blog == 1) ? 1:0;
                                $followUp->is_same_thread = ($request->is_same_thread == 1) ? 1:0;
                                $followUp->non_stop = ($request->non_stop == 1) ? 1:0;
                                $campaign->is_credit_deduct = ($request->is_feature == 1) ? 'Y': 'N';
                                $followUp->interval = '60';
                                $followUp->status = '1';
                                $followUp->is_followup = '1';
                                $followUp->save();
                              //  $this->addDataToSendCampiagnMail(auth()->user()->id,$followUp->id,$request->from_email,$fileNew,$request->message[$i],$request->subject[$i],$request->account_type,$j,$request->fallback_text);
                               
                                $cooling_period += $request->cooling_period;
                            }

                        }

                       
                    }
                }
                
                return redirect('campaigns')
                        ->with('success', 'Campaign Created Successfully.');
                
            } else {
                return redirect('campaigns')
                        ->with('success', 'Oops!!!, something went wrong, please try again.');
                
            }
        } catch (Exception $exception) {
            \Log::info($exception->getMessage());
            return redirect('campaigns')->with('error', $exception->getMessage());
        }
        
    }
    /*------------Edit Campiagn------------------------*/
    public function editCampaign(Request $request){
       try{
        $this->validate($request, [ 
            'id'                  => 'exists:campaigns,id',
            'campaign_name'       => 'required',
            'target'              => 'required',
            'from_email'          => 'required',
            'subject'             => 'required',    
            "subject.*"           => "required", 
            'message'             => 'required',
            'message.*'           => 'required',
        ]);

        if($request->is_feature !='1'){
            $this->validate($request, [ 
                'start_date'          => 'required',
                //'starttime'           => 'required',
                //'endtime'             => 'required',
                'cooling_period'      => 'required|numeric',
            ]);

        }
        
        $checkUserCamp = Campaign::where('id',$request->id)->where('user_id',auth()->user()->id)->first();
        $checkMailsend = SendCampaignMail::where('camp_id',$request->id)->whereIn('status',['1','2','3','4','5'])->count();
        if(!is_object($checkUserCamp)){
          return redirect()->route('campaigns')->with('error','Campaign Not found');
        }
        $is_edit = false;
        if($checkUserCamp->status  == '0'){
          $is_edit = true;
        
        }
        if($checkUserCamp->status  == '1'){
          $is_edit = true;
        
        }
        if($checkUserCamp->status  == '6'){
          $is_edit = true;
        
        }
        if($checkUserCamp->status  == '5'){
          $is_edit = true;
        
        }
        if($checkMailsend >0){
            $is_edit = false;
            
        }
        if($is_edit == false ){
           return redirect()->route('campaigns')->with('error','You can not edit this campiagn');
        
        }

        if(isset($request->list_id) && !empty($request->list_id)){
            $this->validate($request, [
                'list_id'             => 'required|exists:collections,id',
  
            ]);
            
        }
        if(isset($request->csv_file) && $request->hasFile('csv_file')){
           $this->validate($request, [
                'csv_file'             => 'required|mimes:csv,txt', 
  
            ]);
            

        }
        if(isset($request->camp_id) && !empty($request->camp_id)){
            $this->validate($request, [
                'camp_id'             => 'required|exists:campaigns,id',
  
            ]);
            
        }
       
        $status = '6';
        if(isset($request->btn_type) && $request->btn_type =='lunch'){
            $status = '0';
        }
       
        if(isset($request->is_feature) && $request->is_feature =='1'){
            $status = '7';
        }
         if(isset($request->btn_type) && $request->btn_type =='draft'){
            $status = '6';
        }
        

        $user = User::where('id',auth()->user()->id)->first();

        /*---------------------List email id collection-----------------*/
        if(isset($request->customtype) && $request->customtype == '1'){
            $domain_id = explode(',', $request->domain_ids);
            $collections = DB::table('website_data')->select('id','domain_id','emails','author','website','title')->whereIn('domain_id',$domain_id)->get();
            $file = $this->createListToCsv($collections)['fileFullPath'];
            $file_name = $this->createListToCsv($collections)['fileName'];
            $fileNew = $this->createListToCsv($collections)['fileFullPath'];
            $textTotalContact = $this->createListToCsv($collections)['textTotalContact'];

              
            
        }
       /*---------------------Csv email id collection-----------------*/
        $excelData =[];
        $excelDatCsv =[];
        if(isset($request->customtype) && $request->customtype == '2'){
            if(!empty($request->hasFile('csv_file'))){
                $file = $request->file('csv_file');
                $fileNamee = $request->file('csv_file')->getClientOriginalName();
                $file_name = time().'-'.$fileNamee;
                $storagePath = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
                $fileNew = Storage::disk('s3')->url($file_name);
            } else {
                $fileNew = $request->old_csv;
                $file = $request->old_csv;
                $file_name = time().'-'.Str::random(6);
            }

            $getcsvData = array_map("str_getcsv", file($fileNew,FILE_SKIP_EMPTY_LINES));
            $heading = array_shift($getcsvData);
            $textTotalContact = count($getcsvData);
           

        }
        $list_id = $request->list_id;
        if(isset($request->customtype) && $request->customtype == '3'){
            $camp = Campaign::where('id',$request->camp_id)->first();
            $file = $checkUserCamp->final_upload_csv;
            $realfilename = \Str::slug($checkUserCamp->name);
            $getcsvData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
            $heading = array_shift($getcsvData);

            $uniqueName         = \Str::random(6).$realfilename; 
            $fileNamee = $uniqueName;
            $file_name = time().'-'.$fileNamee;
            $storagePath = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
            $fileNew = Storage::disk('s3')->url($file_name);
 
            $textTotalContact = count($getcsvData);
            $list_id = $camp->list_id;
           

        }
        $cooling_period = $request->cooling_period;

        if(isset($request->is_feature) && $request->is_feature == 1){
            $starttime =  date('H:i:s');
            $endtime =  date('H:i:s');
        } else{
            if(isset($request->non_stop) && $request->non_stop == 1){
                $starttime =  date('H:i:s');
                $endtime =   date('H:i:s', strtotime('+ 24 hours'));
            } else{
                $starttime =  date('H:i:s',strtotime($request->starttime));
                $endtime =   date('H:i:s',strtotime($request->endtime));
            }

        }

        $start_date =  date('Y-m-d',strtotime($request->start_date));

        $mail_account = EmailCollection::where('user_id',auth()->user()->id)->where('email',$request->from_email)->first();
       
        $timezone_start_date = Carbon::parse($request->start_date)->timezone('America/New_York')->format('Y-m-d');
        if(is_array(@$request->subject) && count(@$request->subject) >0 ){
            $j=0;
            for ($i = 0;$i <= count($request->subject);$i++) {
                $j++;
                if (!empty($request->subject[$i])) {
                    if($request->is_followup[$i] == '0') {

                        if($request->is_feature =='1'){
                            $ol_credit = ($checkUserCamp->credit_deduct!='') ? $checkUserCamp->credit_deduct :'0';
                            $backOldCampCredit = User::where('id',auth()->user()->id)->update(['credits'=>auth()->user()->credits+$checkUserCamp->credit_deduct]);
                            if($ol_credit > 0){
                                \Helper::transactionHistory(auth()->user()->id,'1','1','0',$ol_credit,'Old credit back during update campaign tools','1');
                        
                            }
                            \Helper::transactionHistory(auth()->user()->id,'2','1','0',$request->credit_deduct,'Credit deduct for campaign tools','1');
                            
                        }

                        $deleteFilemail = SendCampaignMail::where('camp_id',$request->id)->delete();
                        $deleteOld = Campaign::where('id',$request->id)->delete();
                        $campaign = new Campaign;
                        $campaign->user_id = auth()->user()->id;
                        $campaign->name = $request->campaign_name; 
                        $campaign->target_type = $request->target;
                        $campaign->list_id = $list_id;
                        $campaign->campid = $request->camp_id;
                        $campaign->from_email = $request->from_email;
                        $campaign->mail_account_id = $mail_account->id;
                        $campaign->account_type = $request->account_type;
                        $campaign->file_csv = $fileNew;
                        $campaign->list_to_file_csv = $fileNew;
                        $campaign->final_upload_csv = $fileNew;
                        $campaign->final_upload_csv_count = $textTotalContact;
                        $campaign->subject = $request->subject[$i];
                        $campaign->message = $request->message[$i];
                        $campaign->temp_id = $request->temp_id[$i];
                        $campaign->followup_condition = $request->followup_cond[$i];
                        $campaign->file_mail_clm_name = 'Email';
                        $campaign->total_domain = $request->total_count;
                        $campaign->start_date = $start_date;
                        $campaign->timezone_start_date = $timezone_start_date;
                        $campaign->stage = $j;
                        $campaign->starttime = $starttime;
                        $campaign->endtime = $endtime; 
                        $campaign->timezone = $request->timezone;
                        $campaign->cooling_period = $request->cooling_period;
                        $campaign->type = $request->customtype;
                        $campaign->credit_deduct = $request->credit_deduct;
                        $campaign->fallback_text = (isset($request->fallback_text) && !empty($request->fallback_text)) ? $request->fallback_text :'Name|there,Website|site';
                        $campaign->features = (isset($request->features) && !empty($request->features)) ? $request->features :'' ;
                        $campaign->domain_authority = $request->domain_authority;
                        $campaign->semrus_traffic = $request->semrus_traffic;
                        $campaign->total_contact = $request->textTotalContact;
                        $campaign->import_contact = (isset($request->customtype) && $request->customtype=='1') ? $textTotalContact  :$request->import_contact;
                        $campaign->invalid_contact = $request->invalid_contact;
                        $campaign->duplicate_contact = $request->duplicate_contact;
                        $campaign->is_feature = (isset($request->is_feature) && $request->is_feature == 1) ? 1:0;
                        $campaign->including_non_blog = (isset($request->including_non_blog) && $request->including_non_blog == 1) ? 1:0;
                        $campaign->is_same_thread = (isset($request->is_same_thread) && $request->is_same_thread == 1) ? 1:0;
                        $campaign->non_stop = (isset($request->non_stop) && $request->non_stop == 1) ? 1:0;
                        $campaign->is_credit_deduct = (isset($request->is_feature) && $request->is_feature == 1) ? 'Y': 'N';
                        $campaign->interval = '60';
                        $campaign->status = $status;
                        $campaign->is_active = '1';
                        $campaign->save();

                        if($campaign->status =='0'){
                            if (env('IS_NOTIFY_ENABLE', false) == true) {
                                //Notification::send($user, new CampiagnNotification($campaign));
                            }
                           
                        }
                        if(isset($request->is_feature) && $request->is_feature == 1){
                           
                            $user  = User::where('id',auth()->user()->id)->first();
                            $user->credits = $user->credits-$request->credit_deduct; 
                            $user->save(); 

                            \Helper::transactionHistory($user->id,'2','4','0',$request->credit_deduct,'Credit deduct for campaign tools','1');
                           
                            foreach ($getcsvData as $key => $csv) {
                                $rowNum =0;
                                $email = '';
                                $website = '';
                                foreach ($heading as $head => $word) {
                                    $rowNum = $head;
                                    if($word == 'Email'){
                                        $email =  $csv[$rowNum];
                                    }
                                    if($word == 'Website'){
                                        $website =  $csv[$rowNum];
                                    }
                                
                                    
                                }
                                $features = explode(',', $request->features);
                                $checkEmail = CampaignCsvWebsite::where('camp_id',$campaign->id)->where('email',$email)->first();
                                if(!empty($email) && empty($checkEmail) ) {
                                    $websitedata = new CampaignCsvWebsite;
                                    $websitedata->user_id = auth()->user()->id;
                                    $websitedata->camp_id = $campaign->id;
                                    $websitedata->website = $website;
                                    $websitedata->email = $email;
                                    $websitedata->is_blog_judgement = (in_array('1',$features)) ? 1:0;
                                    $websitedata->is_email_extract = (in_array('2',$features)) ? 1:0;
                                    $websitedata->is_email_validate = (in_array('3',$features)) ? 1:0;
                                    $websitedata->is_personalize_data = (in_array('4',$features)) ? 1:0;
                                    $websitedata->is_compute_semrus_da = (in_array('5',$features)) ? 1:0;
                                    $websitedata->save();
                                }
                            }
                        
                        } else{
                             
                            $this->addDataToSendCampiagnMail(auth()->user()->id,$campaign->id,$request->from_email,$fileNew,$request->message[$i],$request->subject[$i],$request->account_type,$j,$campaign->fallback_text);
                        }
                        
                    }
                    if($campaign){
                        if($request->is_followup[$i] == '1') {
                            $deleteFilemail = SendCampaignMail::where('camp_id',$request->id)->delete();
                            $deleteOldFollowup = Campaign::where('is_parent',$request->id)->delete();
                            $followUp = new Campaign;
                            $followUp->user_id = auth()->user()->id;
                            $followUp->is_parent = $campaign->id;
                            $followUp->top_most_parent = $campaign->id;
                            $followUp->target_type = $request->target;
                            $followUp->name = $request->campaign_name;
                            $followUp->list_id = $list_id;
                            $followUp->campid = $request->camp_id;
                            $followUp->from_email = $request->from_email;
                            $followUp->mail_account_id = $mail_account->id;
                            $followUp->account_type = $request->account_type;
                            $followUp->file_csv = $fileNew;
                            $followUp->list_to_file_csv = $fileNew;
                            $followUp->final_upload_csv = $fileNew;
                            $followUp->final_upload_csv_count = $textTotalContact;
                            $followUp->subject = $request->subject[$i];
                            $followUp->message = $request->message[$i];
                            $followUp->temp_id = $request->temp_id[$i];
                            $followUp->followup_condition = $request->followup_cond[$i];
                            $followUp->file_mail_clm_name = 'Email';
                            $followUp->total_domain = $request->total_count;
                            $followUp->stage = $j; 
                            $followUp->starttime = $starttime;
                            $followUp->endtime = $endtime; 
                            $followUp->timezone = $request->timezone;
                            $followUp->cooling_period = $request->cooling_period;
                            $followUp->type = $request->customtype;
                            $followUp->credit_deduct = $request->credit_deduct;
                            $followUp->fallback_text = (!empty($request->fallback_text)) ? $request->fallback_text :'Name|there,Website|site';
                            $followUp->features = (isset($request->features) && !empty($request->features)) ? $request->features :'' ;
                            $followUp->domain_authority = $request->domain_authority;
                            $followUp->semrus_traffic = $request->semrus_traffic;
                            $followUp->total_contact = $request->textTotalContact;
                            $followUp->import_contact = $campaign->import_contact;
                            $followUp->invalid_contact = $request->invalid_contact;
                            $followUp->duplicate_contact = $request->duplicate_contact;
                            $followUp->is_feature = (isset($request->is_feature) && $request->is_feature == 1) ? 1:0;
                            $followUp->including_non_blog = (isset($request->including_non_blog) && $request->including_non_blog == 1) ? 1:0;
                            $followUp->is_same_thread = (isset($request->is_same_thread) && $request->is_same_thread == 1) ? 1:0;
                            $followUp->non_stop = (isset($request->non_stop) && $request->non_stop == 1) ? 1:0;
                            $campaign->is_credit_deduct = (isset($request->is_feature) && $request->is_feature == 1) ? 'Y': 'N';
                            $followUp->interval = '60';
                            $followUp->status = '1';
                            $followUp->is_followup = '1';
                            $followUp->save();

                            //$this->addDataToSendCampiagnMail(auth()->user()->id,$followUp->id,$request->from_email,$fileNew,$request->message[$i],$request->subject[$i],$request->account_type,$j,$request->fallback_text);

                            $cooling_period += $request->cooling_period;
                            
                        }

                    }
                   
                }
               
            }

            
            if($campaign->is_feature == '1'){
                return redirect('campaigns')
                    ->with('success', 'Campaign Updated Successfully.');
            } else{

                    return redirect('campaigns')
                    ->with('success', 'Campaign Updated Successfully.');
            }
            
        } else {
            return  redirect('campaigns')->with('error', 'Oops!!!, something went wrong, please try again.');
            
        }
        } catch (Exception $exception) {
            return redirect('campaigns')->with('error', $exception->getMessage());
        }
        
    }

    public function addDataToSendCampiagnMail($user_id,$campaign_id,$from_email,$file,$message,$subject,$account_type,$stage,$fallbacktext){
        if(!empty($file)){
           
            $excelDatCsv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
            $heading = array_shift($excelDatCsv);
            $emailColumn = array_column($excelDatCsv, 4);
            $emailCount = array_count_values($emailColumn);
            $default_fallback = explode(',','Name|there,Website|site');
            $fallback_text = (!empty($fallbacktext)) ? explode(',',$fallbacktext) : $default_fallback;

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
                $data = Campaign::where('id',$campaign_id)->whereIn('type',['1','3'])->first();

                $is_valid_email = false;
                $inValidEmail =0;
                $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix';
                if(preg_match($pattern,trim($email)) == 1){
                    $is_valid_email = true;
                    $inValidEmail++;

                    
                } 
                if(!empty($data->list_id)){
                    $is_valid_email = true;
                }


                $checkEmail = SendCampaignMail::where('camp_id',$campaign_id)->where('to_email',$email)->first();
                if(!empty($data)){
                    $import_contact =  (!empty(@$emailCount[1])) ? @$emailCount[1]  : $data->import_contact;
                    $data->import_contact = $import_contact;
                    $data->invalid_contact = $inValidEmail;
                    $data->save();
                }
                $attempt ='1';
                /*------Attempt--------------*/
                // \Log::info($email.'---- '.$checkEmail);
                if(!empty($email)  && empty($checkEmail) ) {
                     
                    if($level >1){
                      
                        $campAlreadyExist = Campaign::where('is_parent',$campaign_id)->where('attemp',$level)->first();
                        if(empty($campAlreadyExist) && !empty($data)) {
                           
                            $attemptSave = new Campaign;
                            $attemptSave->user_id = auth()->user()->id;
                            $attemptSave->name =  $data->name; 
                            $attemptSave->target_type = $data->target_type;
                            $attemptSave->list_id = $data->list_id;
                            $attemptSave->campid = $data->campid;
                            $attemptSave->is_parent = $campaign_id;
                            $attemptSave->top_most_parent = $campaign_id;
                            $attemptSave->from_email = $data->from_email;
                            $attemptSave->mail_account_id = $data->mail_account_id;
                            $attemptSave->account_type = $data->account_type;
                            $attemptSave->file_csv = $data->file_csv;
                            $attemptSave->list_to_file_csv = $data->list_to_file_csv;
                            $attemptSave->subject = $data->subject;
                            $attemptSave->message = $data->message;
                            $attemptSave->temp_id = $data->temp_id;
                            $attemptSave->stage = $data->stage;
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
                            $attemptSave->import_contact = @$emailCount[$level];
                            $attemptSave->followup_condition = $data->followup_condition;
                            $attemptSave->fallback_text = $data->fallback_text;
                            $attemptSave->features = $data->features;
                            $attemptSave->domain_authority = $data->domain_authority;
                            $attemptSave->semrus_traffic = $data->semrus_traffic;
                            $attemptSave->is_feature = ($data->is_feature == 1) ? 1:0;
                            $attemptSave->non_stop = ($data->non_stop == 1) ? 1:0;
                            $attemptSave->is_same_thread = ($data->is_same_thread == 1) ? 1:0;
                            $attemptSave->interval = '60';
                            $attemptSave->attemp = (!empty($level)) ? $level :'1';
                            $attemptSave->attempt_type = '1';
                            $attemptSave->is_attempt = '1';
                            $attemptSave->status = '1';
                            $attemptSave->save();


                            $send_camp_id = $attemptSave->id;
                            $attempt = $attemptSave->attemp;
                        } else{
                            $send_camp_id = $campaign_id;
                            $attempt = '1';
                        }
                    } else{

                        $send_camp_id = $campaign_id;

                    }
                   
                    if($level== $attempt && $is_valid_email == true) {
                       
                        $sendMailData = new SendCampaignMail;
                        $sendMailData->user_id = $user_id;
                        $sendMailData->camp_id = $send_camp_id;
                        $sendMailData->from_email = $from_email;
                        $sendMailData->website = $website;
                        $sendMailData->name = $name;
                        $sendMailData->to_email = $email;
                        $sendMailData->subject = $sub;
                        $sendMailData->message = $msg;
                        $sendMailData->status = 0;
                        $sendMailData->level = (!empty($level)) ? $level :'1';
                        $sendMailData->stage = $stage;
                        $sendMailData->type = $account_type;
                        $sendMailData->save();

                        //return true;
                        //print_r($sendMailData);
                        //die;
                    }   
                }

                   
            }
             
            

        }
        //die;

    }
    /*------------Copy Campiagn------------------------*/
    public function copyCampaign(Request $request){
        $data = Campaign::where('id',$request->camp_id)->where('user_id',auth()->user()->id)->first();
        if(!empty($data)){ 
            $copyText = '';
            for ($i=0; $i <= $data->copy_count; $i++) { 
                $copyText .= 'Copy Of'.' ';
            }

            $sdate = new DateTime("now", new DateTimeZone($data->timezone));
            $sdate->format('Y-m-d');

            $timezone_start_date = new DateTime("now", new DateTimeZone('America/New_York'));

            $copyCount = ($data->copy_count =='1') ? 1 :$data->copy_count;
            $campaign = new Campaign;
            $campaign->user_id = auth()->user()->id;
            $campaign->name = ''.$data->name.'('.$copyCount.')'; 
            $campaign->target_type = $data->target_type;
            $campaign->list_id = $data->list_id;
            $campaign->campid = $data->campid;
            $campaign->from_email = $data->from_email;
            $campaign->mail_account_id = $data->mail_account_id;
            $campaign->account_type = $data->account_type;
            $campaign->file_csv = $data->file_csv;
            $campaign->list_to_file_csv = $data->list_to_file_csv;
            $campaign->subject = $data->subject;
            $campaign->message = $data->message;
            $campaign->temp_id = $data->temp_id;
            $campaign->stage = $data->stage;
            $campaign->file_mail_clm_name = 'Email';
            $campaign->total_domain = $data->total_domain;
            $campaign->final_upload_csv = $data->final_upload_csv;
            $campaign->final_upload_csv_count = $data->final_upload_csv_count;
            $campaign->start_date = $sdate;
            $campaign->timezone_start_date = $timezone_start_date->format('Y-m-d');
            $campaign->starttime = date('H:i:s',strtotime($data->starttime));
            $campaign->endtime = date('H:i:s',strtotime($data->endtime)); 
            $campaign->timezone = $data->timezone;
            $campaign->cooling_period = $data->cooling_period;
            $campaign->type = $data->type;
            $campaign->credit_deduct = $data->credit_deduct;
            $campaign->total_contact = $data->textTotalContact;
            $campaign->import_contact = $data->import_contact;
            $campaign->invalid_contact = $data->invalid_contact;
            $campaign->duplicate_contact = $data->duplicate_contact;
            $campaign->followup_condition = $data->followup_condition;
            $campaign->non_stop = $data->non_stop;
            $campaign->is_same_thread = $data->is_same_thread;
            $campaign->fallback_text = $data->fallback_text;
            $campaign->features = $data->features;
            $campaign->domain_authority = $data->domain_authority;
            $campaign->semrus_traffic = $data->semrus_traffic;
            $campaign->is_feature = ($data->is_feature == 1) ? 1:0;
            $campaign->interval = '60';
            $campaign->status = '6';
            $campaign->save();
            $updateCopyCount = Campaign::where('id',$request->camp_id)->update(['copy_count'=>$copyCount+1]);
            $followUps = Campaign::where('is_parent',$data->id)->where('is_followup','1')->get();
            if(count($followUps) > 0){
                foreach ($followUps as $key => $follow) {
                    $fdate = new DateTime("now", new DateTimeZone($follow->timezone));
                    $fdate->format('Y-m-d');

                    $followUp = new Campaign;
                    $followUp->user_id = auth()->user()->id;
                    $followUp->is_parent = $campaign->id;
                    $followUp->top_most_parent = $campaign->id;
                    $followUp->target_type = $data->target_type;
                    $followUp->name = $campaign->name;
                    $followUp->list_id = $follow->list_id;
                    $followUp->campid = $follow->campid;
                    $followUp->from_email = $follow->from_email;
                    $followUp->mail_account_id = $follow->mail_account_id;
                    $followUp->account_type = $follow->account_type;
                    $followUp->file_csv = $follow->file_csv;
                    $followUp->list_to_file_csv = $follow->list_to_file_csv;
                    $followUp->subject = $follow->subject;
                    $followUp->message = $follow->message;
                    $followUp->temp_id = $follow->temp_id;
                    $followUp->stage = $follow->stage;
                    $followUp->file_mail_clm_name = 'Email';
                    $followUp->total_domain = $follow->total_domain;
                    $followUp->fallback_text = $follow->fallback_text;
                    $followUp->final_upload_csv = $follow->final_upload_csv;
                    $followUp->final_upload_csv_count = $follow->final_upload_csv_count;
                    $followUp->start_date = $fdate;
                    $followUp->starttime = date('H:i:s',strtotime($follow->starttime));
                    $followUp->endtime = date('H:i:s',strtotime($follow->endtime)); 
                    $followUp->timezone = $follow->timezone;
                    $followUp->cooling_period = $follow->cooling_period;
                    $followUp->type = $follow->type;
                    $followUp->credit_deduct = $follow->credit_deduct;
                    $followUp->total_contact = $follow->total_contact;
                    $followUp->import_contact = $follow->import_contact;
                    $followUp->invalid_contact = $follow->invalid_contact;
                    $followUp->duplicate_contact = $follow->duplicate_contact;
                    $followUp->followup_condition = $follow->followup_condition;
                    $followUp->non_stop = $follow->non_stop;
                    $followUp->is_same_thread = $follow->is_same_thread;
                    $followUp->features = $follow->features;
                    $followUp->domain_authority = $follow->domain_authority;
                    $followUp->semrus_traffic = $follow->semrus_traffic;
                    $followUp->is_feature = ($follow->is_feature == 1) ? 1:0;
                    $followUp->interval = '60';
                    $followUp->status = '6';
                    $followUp->is_followup = '1';
                    $followUp->save();
                }
                   
            } 

            return redirect('edit-campaign/'.$campaign->uuid.'')
                        ->with('success', 'Campaign Copy Successfully.');

        } else {
            return redirect('campaigns')->with('error','Campaign Not found');
        }
      
    
    }
    /*------------Copy Campiagn------------------------*/
    public function scheduleCampaign(Request $request){
        $this->validate($request, [ 
            'start_date'          => 'required',
            'cooling_period'      => 'required|numeric',
        ]);
        if($request->non_stop != '1'){
            $this->validate($request, [ 
                'starttime'           => 'required',
                'endtime'             => 'required',
            ]);
        }
        $data = Campaign::where('id',$request->camp_id)->where('user_id',auth()->user()->id)->first();
        if($data){ 
        $cooling_period = $request->cooling_period;


        if($request->non_stop == 1){
            $starttime =  date('H:i:s');
            $endtime =   date('H:i:s', strtotime('+ 24 hours'));
        } else{
            $starttime =  date('H:i:s',strtotime($request->starttime));
            $endtime =   date('H:i:s',strtotime($request->endtime));
        }
        $timezone_start_date = Carbon::parse($request->start_date)->timezone('America/New_York')->format('Y-m-d');

        $campaign = Campaign::find($data->id);
        $campaign->start_date = $request->start_date;
        $campaign->timezone_start_date = $timezone_start_date;
        $campaign->starttime = $starttime;
        $campaign->endtime = $endtime; 
        $campaign->timezone = $request->timezone;
        $campaign->cooling_period = $request->cooling_period;
        $campaign->non_stop = ($request->non_stop==1) ? 1:0;
        $campaign->status = '2';
        $campaign->save();
        $followUps = Campaign::where('is_parent',$data->id)->get();
        if(count($followUps) > 0){
            foreach ($followUps as $key => $follow) {
                
                $followUp = Campaign::find($follow->id);
                $followUp->start_date =$request->start_date; 
                $followUp->starttime = date('H:i:s',strtotime($request->starttime));
                $followUp->endtime = date('H:i:s',strtotime($request->endtime)); 
                $followUp->timezone = $request->timezone;
                $followUp->cooling_period = $request->cooling_period; 
                $followUp->non_stop = ($request->non_stop==1) ? 1:0;
                $followUp->status = '2'; 
                $followUp->save();

            }
               
               
        }   
        return redirect('campaigns')
                    ->with('success', 'Campaign Schedule Successfully.');

        } else {
            return redirect()->back()->with('error','Campaign Not found');
        }
      
    
    }
    public function deleteCampaign(Request $request){
        $data = Campaign::where('id',$request->campid)->where('user_id',auth()->user()->id)->first();
        if($data){ 
        if($data->status == '0' || $data->status =='6') {
            $dFollowUps = Campaign::where('is_parent',$request->campid)->get();
            foreach ($dFollowUps as $key => $follow) {
               $deleteFollowMail= SendCampaignMail::where('camp_id',$follow->campid)->delete();
               $deleteFollow = Campaign::where('id',$follow->campid)->delete();
            }
            $deleteSendCampData = SendCampaignMail::where('camp_id',$request->campid)->delete();
            $deleteCamp = Campaign::where('id',$request->campid)->delete();
            return redirect('campaigns')
                    ->with('success', 'Campaign delete Successfully.');
        } else {
             return redirect('campaigns')
                    ->with('error', 'You can not delete this campaign.');
        }
        
        } else {
            return redirect()->back()->with('error','Campaign Not found');
        }
      
    
    }
    

    public  function createListToCsv($collections) { 
       
        $header_email     = 'Email';
        $header_author     = 'Name';
        $header_url    = 'Website';
        $header_title    = 'Title';
        $header_level    = 'Level';
        $uniqueName         = \Str::random(6);
        $fileName           = time().'-list-csv-'.$uniqueName.".csv";

        $csvData = $header_email.','.$header_author.','.$header_url.','.$header_title.','.$header_level."\n";
        
        $emailArr =[];
        $textTotalContact = 0;

        if(count($collections)>0) {
           
            foreach ($collections as $key => $site) {
         
                $arr = json_decode($site->emails, true);
                $allEmails = Helper::getAllEmail($arr);
                $textTotalContact = count($allEmails);
                foreach ($allEmails as $nkey => $val) {
                    $level  = $nkey+1;
                    $csvData .= $val.','.@$site->author.','.@$site->website.','.@$site->title.','.@$level."\n";
                            
                }
                

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
        'fileName' =>   $fileName,
        'textTotalContact' =>   $textTotalContact,

        ];

        
        return $resultData;
    }

    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }

    public  function getEmailAccount() { 
       return  view('getEmailAccount');
    }



    /*---------Send Test Mail---------------------------*/
    public function sendMail(Request $request) 
    {   
        
        $subject_actual = $request->subject;
        $message_actual = $request->message;
        $sub = '';
        $msg = '';
        $user_id = auth()->user()->id;
        $from = $request->from_email;
        $to = $request->to_email;
        $default_fallback = explode(',','Name|there,Website|site');
        $fallback_text = (!empty($request->fallback_text)) ? explode(',',$request->fallback_text) : $default_fallback  ;

        $userId = base64url_encode(auth()->user()->id);
        $fromEmail = base64url_encode($from);
        $toEmailuser = base64url_encode($to).'.'.$userId.'.'.$fromEmail;
        $hasmac = Hasmac($to);
        $token = $toEmailuser.'.'.$hasmac;
        $unSubTag ="<br> If you don't want to receive such emails in the future, <a href='".route('unsubscribe-mail',$token)."'>unsubscribe</a> here";
        if(@$request->edit_id !=''){
            $camp = Campaign::where('id',$request->edit_id)->first();
            $heading = Session::get('heading');
            $excelData = Session::get('excelData');
            $list_id = Session::get('list_id');
           
            if($request->type ==='2' && !empty($excelData)){
               
                if(is_array($excelData) && count($excelData)>0) {
                    $csv = $excelData[0];
                    $changeArray    = [];
                    $newKeyword     = [];
                    if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                    {   
                        $rowNum =0;
                        $rid = Str::uuid();
                        if(is_array($fallback_text) && count($fallback_text) >  0 ){
                            foreach ($fallback_text as $nk => $text) {
                                $fval = explode('|',$text);
                                $akey = @$fval[0];
                                $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                            }
                        }
                        foreach ($heading as $head => $word) {
                            $rowNum = $head;
                            $newKeyword[$word] = $csv[$rowNum]  ;
                            if(!empty($csv[$rowNum])){
                                $newKeyword[$word] =  $csv[$rowNum]  ;
                            }

                        }
                        $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                        $msg = $this->strReplaceAssoc($newKeyword,$message_actual);

                        $msg = $msg.$unSubTag;
                        $sub = preg_replace('/<[^>]*>/', '', $sub); 
                        //$msg = preg_replace('/<[^>]*>/', ' ', $msg); 
                        //print_r($msg);
                        //die;

                    }
                }
            }elseif($request->type =='3' && !empty($request->camp_id)) {
                $campaign = Campaign::where('id',$request->camp_id)->first();
                $excelData = array_map("str_getcsv", file($campaign->final_upload_csv,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($excelData);
                if(is_array($excelData) && count($excelData)>0) {
                    $csv = $excelData[0];
                    $changeArray    = [];
                    $newKeyword     = [];
                    if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                    {   
                        $rowNum =0;
                        $rid = Str::uuid();
                        if(is_array($fallback_text) && count($fallback_text) >  0 ){
                            foreach ($fallback_text as $nk => $text) {
                                $fval = explode('|',$text);
                                $akey = @$fval[0];
                                $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                            }
                        }
                        foreach ($heading as $head => $word) {
                            $rowNum = $head;
                            $newKeyword[$word] = $csv[$rowNum]  ;
                            if(!empty($csv[$rowNum])){
                                $newKeyword[$word] =  $csv[$rowNum]  ;
                            }
                        }
                        $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                        $msg = $this->strReplaceAssoc($newKeyword,$message_actual);
                        $msg = $msg.$unSubTag;
                        $sub = preg_replace('/<[^>]*>/', '', $sub); 
                        //$msg = preg_replace('/<[^>]*>/', ' ', $msg); 
                       

                    }
                }

            } elseif($request->type ==='1' && !empty($list_id) ) {

                $col_data = DB::table('website_data')
                ->join('domain_collections', 'website_data.domain_id', '=', 'domain_collections.domain_id')
                ->join('collections', 'domain_collections.collection_id', '=', 'collections.id')
                ->select('website_data.website','website_data.domain_id','website_data.id','website_data.emails','website_data.tag_category','website_data.author','website_data.credit_cost','website_data.title','website_data.domain_id','collections.name')
                ->where('collections.user_id', $request->user()->id)
                ->where('collections.status', '=' ,1)
                ->where('domain_collections.collection_id', '=', $list_id)
                ->first();
                $heading = ['Name','Website','Title'];
                $author = (!empty($col_data->author)) ? $col_data->author :'';
                $website = (!empty($col_data->website)) ? $col_data->website :'';
                $title = (!empty($col_data->title)) ? $col_data->title :'';
                $csv = [''.$author.'',''.$website.'',''.$title.''];
                $rowNum =0;
                if(is_array($csv) && count($csv)>0) {
                        $changeArray    = [];
                        $newKeyword     = [];
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
                                $newKeyword[$word] = $csv[$rowNum]  ;
                                if(!empty($csv[$rowNum])){
                                    $newKeyword[$word] =  $csv[$rowNum]  ;
                                }
                            }
                            
                            $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                            $msg = $this->strReplaceAssoc($newKeyword,$message_actual);
                            $msg = $msg.$unSubTag;
                            $sub = preg_replace('/<[^>]*>/', '', $sub);

                            /*$a = str_replace("Author", $author, $subject_actual);
                            $u = str_replace("Website", $website, $a);
                            $t = str_replace("Title", $title, $u);

                            $m = str_replace("Author", $author, $message_actual);
                            $mu = str_replace("Website", $website, $m);
                            $mt = str_replace("Title", $title, $mu);
                            $st = str_replace("Unsubscribe", $unSubTag, $mt);
                            $sub = $t;
                            $msg = $st;
                            
                            $sub = preg_replace('/<[^>]*>/', '', $sub); */
                            
                           

                        }
                       //print_r($sub);
                    
                   //die; 
                }

            } else{
                $file = $camp->final_upload_csv;
                $realfilename = \Str::slug($camp->name);
                $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($excelData);
                

                if(is_array($excelData) && count($excelData)>0) {
                    $csv = $excelData[0];
                    $changeArray    = [];
                    $newKeyword     = [];
                    if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                    {   
                        $rowNum =0;
                        $rid = Str::uuid();
                        if(is_array($fallback_text) && count($fallback_text) >  0 ){
                            foreach ($fallback_text as $nk => $text) {
                                $fval = explode('|',$text);
                                $akey = @$fval[0];
                                $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                            }
                        }
                        foreach ($heading as $head => $word) {
                            $rowNum = $head;
                            $newKeyword[$word] = $csv[$rowNum]  ;
                            if(!empty($csv[$rowNum])){
                                $newKeyword[$word] =  $csv[$rowNum]  ;
                            }

                        }
                        $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                        $msg = $this->strReplaceAssoc($newKeyword,$message_actual);

                        $msg = $msg.$unSubTag;
                        $sub = preg_replace('/<[^>]*>/', '', $sub); 
                        //$msg = preg_replace('/<[^>]*>/', ' ', $msg); 
                        //print_r($msg);
                        //die;

                    }
                }
                    $sub = $request->subject;
                    $msg = $request->message;
                    $msg = $msg.$unSubTag;
                }

            
        } else{
            if($request->type ==='2'){
                $heading = Session::get('heading');
                $excelData = Session::get('excelData');
                if(is_array($excelData) && count($excelData)>0) {
                    $csv = $excelData[0];
                    $changeArray    = [];
                    $newKeyword     = [];
                    if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                    {   
                        $rowNum =0;
                        $rid = Str::uuid();
                        if(is_array($fallback_text) && count($fallback_text) >  0 ){
                            foreach ($fallback_text as $nk => $text) {
                                $fval = explode('|',$text);
                                $akey = @$fval[0];
                                $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                            }
                        }
                        foreach ($heading as $head => $word) {
                            $rowNum = $head;
                            $newKeyword[$word] = $csv[$rowNum]  ;
                            if(!empty($csv[$rowNum])){
                                $newKeyword[$word] =  $csv[$rowNum]  ;
                            }

                        }
                        $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                        $msg = $this->strReplaceAssoc($newKeyword,$message_actual);

                        $msg = $msg.$unSubTag;
                        $sub = preg_replace('/<[^>]*>/', '', $sub); 
                        //$msg = preg_replace('/<[^>]*>/', ' ', $msg); 
                        //print_r($msg);
                        //die;

                    }
                }
            }elseif($request->type =='3') {
                $campaign = Campaign::where('id',$request->camp_id)->first();
                $excelData = array_map("str_getcsv", file($campaign->final_upload_csv,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($excelData);
                if(is_array($excelData) && count($excelData)>0) {
                    $csv = $excelData[0];
                    $changeArray    = [];
                    $newKeyword     = [];
                    if(isset($changeArray) && is_array($changeArray) && count($changeArray) < 1)
                    {   
                        $rowNum =0;
                        $rid = Str::uuid();
                        if(is_array($fallback_text) && count($fallback_text) >  0 ){
                            foreach ($fallback_text as $nk => $text) {
                                $fval = explode('|',$text);
                                $akey = @$fval[0];
                                $newKeyword[$text] = (!empty($csv[$nk])) ? $csv[$nk] : @$fval[1];
                            }
                        }
                        foreach ($heading as $head => $word) {
                            $rowNum = $head;
                            $newKeyword[$word] = $csv[$rowNum]  ;
                            if(!empty($csv[$rowNum])){
                                $newKeyword[$word] =  $csv[$rowNum]  ;
                            }
                        }
                        $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                        $msg = $this->strReplaceAssoc($newKeyword,$message_actual);
                        $msg = $msg.$unSubTag;
                        $sub = preg_replace('/<[^>]*>/', '', $sub); 
                        //$msg = preg_replace('/<[^>]*>/', ' ', $msg); 
                       

                    }
                }

            } elseif($request->type ==='1') {
                $list_id = Session::get('list_id');
                $col_data = DB::table('website_data')
                ->join('domain_collections', 'website_data.domain_id', '=', 'domain_collections.domain_id')
                ->join('collections', 'domain_collections.collection_id', '=', 'collections.id')
                ->select('website_data.website','website_data.domain_id','website_data.id','website_data.emails','website_data.tag_category','website_data.author','website_data.credit_cost','website_data.title','website_data.domain_id','collections.name')
                ->where('collections.user_id', $request->user()->id)
                ->where('collections.status', '=' ,1)
                ->where('domain_collections.collection_id', '=', $list_id)
                ->first();
                $heading = ['Name','Website','Title'];
                $author = (!empty($col_data->author)) ? $col_data->author :'';
                $website = (!empty($col_data->website)) ? $col_data->website :'';
                $title = (!empty($col_data->title)) ? $col_data->title :'';
                $csv = [''.$author.'',''.$website.'',''.$title.''];
                $rowNum =0;
                if(is_array($csv) && count($csv)>0) {
                        $changeArray    = [];
                        $newKeyword     = [];
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
                                $newKeyword[$word] = $csv[$rowNum]  ;
                                if(!empty($csv[$rowNum])){
                                    $newKeyword[$word] =  $csv[$rowNum]  ;
                                }
                            }
                            
                            $sub = $this->strReplaceAssoc($newKeyword,$subject_actual);
                            $msg = $this->strReplaceAssoc($newKeyword,$message_actual);
                            $msg = $msg.$unSubTag;
                            $sub = preg_replace('/<[^>]*>/', '', $sub);

                            /*$a = str_replace("Author", $author, $subject_actual);
                            $u = str_replace("Website", $website, $a);
                            $t = str_replace("Title", $title, $u);

                            $m = str_replace("Author", $author, $message_actual);
                            $mu = str_replace("Website", $website, $m);
                            $mt = str_replace("Title", $title, $mu);
                            $st = str_replace("Unsubscribe", $unSubTag, $mt);
                            $sub = $t;
                            $msg = $st;
                            
                            $sub = preg_replace('/<[^>]*>/', '', $sub); */
                            
                           

                        }
                       //print_r($sub);
                    
                   //die; 
                }

            } else{
                $sub = $request->subject;
                $msg = $request->message;
                $msg = $msg.$unSubTag;
            }
        }
        $mailAccount = EmailCollection::where('user_id',auth()->user()->id)->where('email',$from)->first();
        $sendSms = new SendSmsService;
        
        if(@$mailAccount->account_type == '1'){
            
            $is_send = $sendSms->sendMailByGmail($from,$to,$sub,$msg,$user_id);
        }
        if(@$mailAccount->account_type == '2'){
            $is_send = $sendSms->sendMailByOutlook($from,$to,$sub,$msg,$user_id);
            
        }
        if($is_send == true){
            $data = [
                'type'      => 'success',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }


    }
    public function sendPreviewMail(Request $request) 
    {   
        
        if($request->id ==0){
            $email = EmailCollection::where('user_id',auth()->user()->id)->where('status','1')->first();
            
            if(empty($email)){
                $data = [
                    'type'      => 'no_account',
                    'message'      => 'Please Connnect with your email in settings section',
                ];
                return response()->json($data, 200);
            }
            $from = $email->email;
            $to = auth()->user()->email;
            $user_id = auth()->user()->id;
            $sub = $request->subject;
            $msg = $request->message;
            $sendSms = new SendSmsService;
            if($email->account_type == '1'){
                
                $is_send = $sendSms->sendMailByGmail($from,$to,$sub,$msg,$user_id);
            }
            if($email->account_type == '2'){
                $is_send = $sendSms->sendMailByOutlook($from,$to,$sub,$msg,$user_id);
                
            }
            if($is_send == true){
                $data = [
                    'type'      => 'success',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'type'      => 'error',
                    'message'      => 'Opps! Something went wrong!',
                ];
                return response()->json($data, 200);
            }
            

        } else{
        $id = base64url_decode($request->id);
            $send_mail = SendCampaignMail::where('id',$id)
                        ->where('user_id',auth()->user()->id)
                        ->first();
           
            if(!empty($send_mail)){

                $sub = ($send_mail->subject!='') ? $send_mail->subject:'';
                $sub = preg_replace('/<[^>]*>/', '', $sub);

                $message = ($send_mail->message!='') ? $send_mail->message:'';
                
                $send_id = base64url_encode($send_mail->id);
                $fromEmail = base64url_encode($send_mail->from_email);
                $toEmailuser = base64url_encode($send_mail->to_email).'.'.$send_id.'.'.$fromEmail;
                $hasmac = Hasmac($send_mail->to_email);
                $token = $toEmailuser.'.'.$hasmac;
            
                $unSubTag ="<br> If you don't want to receive such emails in the future, <a href='".route('unsubscribe-mail',$token)."'>Unsubscribe</a> here";

                $msg = $message.$unSubTag;
                $msg = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$msg); 
                $from = $send_mail->from_email;
                $to = $send_mail->to_email;
                $user_id = $send_mail->user_id;
                $sendSms = new SendSmsService;
                if($send_mail->type == '1'){
                    
                    $is_send = $sendSms->sendMailByGmail($from,$to,$sub,$msg,$user_id);
                }
                if($request->type == '2'){
                    $is_send = $sendSms->sendMailByOutlook($from,$to,$sub,$msg,$user_id);
                    
                }
                if($is_send == true){
                    $data = [
                        'type'      => 'success',
                    ];
                    return response()->json($data, 200);
                } else {
                    $data = [
                        'type'      => 'error',
                        'message'      => 'Opps! Something went wrong!',
                    ];
                    return response()->json($data, 200);
                }
            } else{
                $data = [
                        'type'      => 'error',
                        'message'      => 'Opps! Something went wrong!',
                    ];
                    return response()->json($data, 200);
            }
        }


    }


    public function unsubscribeMail($token) 
    {
        
        if (isset($token)) 
        {
          $tok = explode('.', $token);
    
            // check token parts
            $is_valid = true;
            if (count($tok) !== 4) {
                // not valid token
                $is_valid = false;
                
            }
            // check email segment
            if (!isset($tok[0]) || !filter_var(base64url_decode($tok[0]), FILTER_VALIDATE_EMAIL)) {
                // not valid email
                $is_valid = false;
                
            }
            $to = base64url_decode(@$tok[0]);
            $send_id = base64url_decode(@$tok[1]);
            $from = base64url_decode(@$tok[2]);
            if (@$tok[3] != Hasmac($to)) {
                // failed verification
                $is_valid = false;
               
            }
            $checkSendMail = SendCampaignMail::where('id',$send_id)->first();
            if(empty($checkSendMail)){
                $is_valid = false ;
            }
            if($is_valid== true){
                $checkAlready = BlackListEmail::where('from',$from)->where('to',$to)->first();
                if(!is_object($checkAlready)){
                    $unsubscribe = new BlackListEmail;
                } else{
                    $unsubscribe = BlackListEmail::find($checkAlready->id);
                }
                $unsubscribe->user_id = $checkSendMail->user_id ;
                $unsubscribe->from = $from ;
                $unsubscribe->to = $to ;
                $unsubscribe->save();
                if($unsubscribe){
                    $updateCamign = SendCampaignMail::where('id',$send_id)->update(['is_unsubscribe'=>'1']);
                   
                    Feed($checkSendMail->user_id,$checkSendMail->camp_id,$send_id,$from,$to,date('Y-m-d H:i:s'),'4',$checkSendMail->msg_id);
                    $msg='Unsubscribe successful';
                    $body='Your email has been successfully unsubscribed from this list!';
                }
                else{
                    $msg='Exceptions';
                    $body='Opps!something went wrong';
                }
                return View('unsubscribe', compact('msg','body'));

            } else{
                $msg='Invalid unsubscribe request';
                $body='You have clicked on an invalid link to unsubscribe.  Please make sure that you have typed the link correctly.  If are copying this link from a mail reader please ensure that you have copied all the lines in the link.';
                return View('unsubscribe', compact('msg','body'));

            }

        }
        


    }
}