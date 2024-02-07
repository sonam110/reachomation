<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use App\Models\EmailCollection;
use App\Models\SendCampaignMail;
use App\Models\SubscriptionPlan;
use App\Models\Inbox;
use App\Exports\ReportExport;
use Excel;
use Str;
use Yajra\Datatables\Facades\Datatables;
class CampaignController extends Controller
{
    public function index(Request $request){
        $campaigns = Campaign::whereNull('is_parent')
                    ->where('user_id',auth()->user()->id)
                    ->orderBy('campaigns.start_date', 'ASC')
                    ->orderBy('campaigns.id', 'desc')
                    ->paginate(10);
        $allUsers = EmailCollection::where('user_id',auth()->user()->id)->get();

        return view('campaigns',compact('campaigns','allUsers'));
    }
    public function campaignList(Request $request)
    {
       
        $query = Campaign::select('id','uuid','is_parent', 'name', 'from_email', 'start_date', 'starttime','total_open','total_reply','total_click','final_upload_csv','is_file_read','final_upload_csv_count','import_contact','account_type','timezone','status','reason')->whereNull('is_parent')->where('user_id',auth()->user()->id)->with('fromUser')
            //->orderByRaw("FIELD(status , '0', '3', '6','5', '4','7','8') ASC")
          //->orderByRaw("IF(status = '4', id, id) DESC");
            ->orderBy('id','DESC');
        if (!empty($request->mystatus))
        {
            $query->where('status',$request->mystatus);
        }
        
        if (!empty($request->from_email))
        {
            $query->where('from_email',$request->from_email);
        }
        if (!empty($request->keyword))
        {
            $query->where('name', 'LIKE', "%{$request->keyword}%");
        }
        
        return datatables($query)->addColumn('action', function ($query)
        {
           $checkMailsend = SendCampaignMail::where('camp_id',$query->id)->whereIn('status',['1','2','3','4','5'])->count();
           $action ='';
           $action .='<div class="d-flex">';
                        if($query->status != '7') {
                            $action .='<a href="'.route('campaign-details',$query->uuid).'" type="button" class="btn btn-outline-success btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View"><i class="bi bi-eye-fill"></i></a>';
                         }
                         if($query->status == '7' && $query->is_file_read == 'Y') {
                            $action .='<a href="'.$query->final_upload_csv.'" class="btn btn-outline-danger btn-sm fw-500"  data-bs-original-title="Download" download  target="_blank"><i class="bi bi-download"></i></a>';
                         }
                        
                        if($query->status=='0' || $query->status=='1' || $query->status=='6' || ($query->status=='5' &&  $checkMailsend == '0')) {
                        // $action .='<a href="'.route('edit-campaign',$query->uuid).'" type="button" class="btn btn-outline-primary btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit"><i class="bi bi-pencil-fill" ></i></a>';
                        }
                        $action .='<a href="javascript:;"  id="show-copy-model" data-id="'.$query->id.'" type="button" class="btn btn-outline-success btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Copy"><i class="bi bi-clipboard"></i></a>';
                        if(($query->status=='7' || $query->status=='2') &&  $query->is_file_read=='Y') {
                        $action .='<a href="javascript:;"  id="show-schedule-model" data-id="'.$query->id.'" type="button" class="btn btn-outline-success btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Schedule Campagn"><i class="bi bi-clock"></i></a>';
                       }
                       if($query->status=='0' || $query->status=='1' || $query->status=='6') {
                        //$action .='<button type="button" id="show-delete-model" data-id="'.$query->id.'" class="btn btn-outline-danger btn-sm fw-500" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" ><i class="bi bi-trash-fill"></i></button>';
                       }
                        if($query->status=='8' ) {
                        // $action .='<a href="'.route('download-report',[$query->uuid,NULL]).'"  class="btn btn-outline-danger btn-sm fw-500" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download" ><i class="bi bi-download"></i></a>';
                        }
                     $action .='</div>';
            return $action;
                
        })
        ->editColumn('name', function ($query)
        {
            $allChilds = getChildren($query->id);
            $allChild = array_merge($allChilds,[$query->id]);
            $totalSendCount = Campaign::whereIn('id',$allChild)->sum('import_contact');
            $name  =  \Illuminate\Support\Str::limit($query->name, 80, $end='...') ;

            $totalCount = SendCampaignMail::where('camp_id',$query->id)->whereIn('status',['1','2','3','4','5'])->count();
            $nameduv ='<a href="'.route('campaign-details',$query->uuid).'" class="text-decoration-none text-black"  role="button"   tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top"  title="View report for this campiagn" data-bs-original-title="View report for this campiagn" ><p class="mb-2">
                        '.$name.'
                     </p>';
            $nameduv .= '<small>
             '.date('M d Y',strtotime($query->start_date)).' '.date('H:i A',strtotime($query->starttime)) .'
           
             </small>
             <p class="mb-0 fw-500">
                <small>'.$query->timezone.' </small>
             </p></a>';
            
            /*$nameduv .= '<div class="d-flex">
                        <span class="badge bg-white text-dark border border-dark me-2">'.$query->import_contact.'</span>
                        <span class="badge bg-white text-dark border border-dark">'.$totalCount.'</span>
                     </div>';*/

            return $nameduv;
        })
        ->editColumn('status', function ($query)
        {

            $status = 'To begin';
            if($query->status == '2'){
                $status = 'To begin';
            }
            if($query->status == '3'){
                $status = 'In Progress';
            }
            if($query->status == '5'){
                $status = 'Paused';
            }
            if($query->status == '4'){
                $status = 'In Process';
               
            }
            if($query->status == '8'){
                $status = 'Finished';
            }
            if($query->status == '6'){
                $status = 'Draft';
            }
            if($query->status == '7' && $query->is_file_read == 'N'){
                $status = 'Data processing';
            }
            if($query->status == '7' && $query->is_file_read == 'Y'){
                $status = 'Scheduled Pending';
            }
            if($query->status == '5'){
                $statuDiv = '<p class="mb-0 fw-500">
                        <small class="text-success">'.$status.'</small>
                     </p>
                     
                     <p class="mb-0 fw-500">
                        <small style="color:red">'.$query->reason.' </small>
                     </p>';
            } else {
            $statuDiv = '<p class="mb-0 fw-500">
                        <small class="text-success">'.$status.'</small>
                     </p>
                     ';

            }
            
            return $statuDiv;
        })
        ->editColumn('import_contact', function ($query)
        {
            
            return $query->import_contact;
        })
        ->editColumn('total_open', function ($query)
        {

            $allChild = Campaign::where('is_parent',$query->id)->where('is_attempt','1')->orWhere('id',$query->id)->pluck('id')->toArray();
            $totalSendMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('status','1')->count();

            $totalOpenMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('is_open','>','0')->where('status','1')->count();

            $total_open  = 0;
            if($totalSendMail >0){

                $mailOpen = ($totalOpenMail / $totalSendMail) * 100;
                $total_open = ($mailOpen > 0 ) ? round($mailOpen,2).'%' :'';
            }
     
            return $total_open;
        })
         ->editColumn('total_reply', function ($query)
        {
            $allChild = Campaign::where('is_parent',$query->id)->where('is_attempt','1')->orWhere('id',$query->id)->pluck('id')->toArray();
            $totalSendMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('status','1')->count();

           
            $totalReplyMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('is_reply','>','0')->where('status','1')->count();
            $total_reply = 0;
            if($totalSendMail >0){
                $mailReply = ($totalReplyMail / $totalSendMail) * 100;
                $total_reply = ($mailReply > 0 ) ? round($mailReply,2).'%' :'';
            }
     
            return $total_reply;
        })
          ->editColumn('total_click', function ($query)
        {
            $allChild = Campaign::where('is_parent',$query->id)->where('is_attempt','1')->orWhere('id',$query->id)->pluck('id')->toArray();

            $totalSendMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('status','1')->count();
           
            $totalClickMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('is_click','>','0')->where('status','1')->count();
            $total_click = 0;
            if($totalSendMail >0){
                $mailClick = ($totalClickMail / $totalSendMail) * 100;
                $total_click = ($mailClick > 0 ) ? round($mailClick,2).' %' :'';
            }
        
            return $total_click;
        })
           ->editColumn('from_email', function ($query)
        {
            $emailData = EmailCollection::where('user_id',auth()->user()->id)->where('email',$query->from_email)->first();
            $name = (!empty($emailData)) ? $emailData->name :'';
            $createDiv= '';
            $createDiv .='<div class="hstack mb-2">
                        <div class="me-3">';
                        if(@$emailData->account_type=='2') {
                          $createDiv .='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00A4EF" class="bi bi-microsoft" viewBox="0 0 16 16">
                              <path d="M7.462 0H0v7.19h7.462V0zM16 0H8.538v7.19H16V0zM7.462 8.211H0V16h7.462V8.211zm8.538 0H8.538V16H16V8.211z"/>
                            </svg>';


                        } else {
                        $createDiv .='
                        
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="1rem" class="LgbsSe-Bz112c">
                           <g>
                              <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                              </path>
                              <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                              </path>
                              <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                              </path>
                              <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                              </path>
                              <path fill="none" d="M0 0h48v48H0z"></path>
                           </g>
                        </svg>';

                        }
                     $createDiv .='</div>';
                        $createDiv .='
                        <div class="">
                           <p class="mb-0 text-muted fw-500"><small>'.$name.'</small>
                        </p>
                           <h6 class="mb-0 fw-bold">
                              <small>'.$query->from_email.'</small>';
                            if($emailData->status== '0') {
                            $createDiv .='<a href="javascript:;" onClick="connectEmailAccount();" title="Inactive Account"> <i
                                                class="bi bi-exclamation-circle-fill" style="color:red"></i> </a>';
                            }
                           $createDiv .='</h6>
                        </div>
                     </div>';
            return $createDiv;
        })
        ->escapeColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

        // for filter
    private function getWhereRawFromRequest(Request $request)
    {
        $w = '';
        
        $mystatus = $request->input('mystatus');
        
        if (is_null($request->input('mystatus')) == false)
        {
            if ($w != '')
            {
                $w = $w . " AND ";
            }
            $w = $w . "(" . "status = " . "'" . $request->input('mystatus') . "'" . ")";
        }
        if (is_null($request->input('camp_id')) == false)
        {
            if ($w != '')
            {
                $w = $w . " AND ";
            }
            $w = $w . "(" . "camp_id = " . "'" . $request->input('camp_id') . "'" . ")";
        }
        if (is_null($request->input('from_email')) == false)
        {
            if ($w != '')
            {
                $w = $w . " AND ";
            }
            $w = $w . "(" . "from_email = " . "'" . $request->input('from_email') . "'" . ")";
        }
        if (is_null($request->input('keyword')) == false)
        {
            if ($w != '') {$w = $w . " AND ";}
            $w = $w ."(";
            $w = $w . "(" . "from_email like '%" .trim(strtolower($request->input('keyword'))) . "%')";
            
            if ($w != '') {$w = $w . " OR ";}
               $w = $w . "(" . "name like '%" .trim(strtolower($request->input('keyword'))) . "%')";
             $w = $w .")";
        }
        
       
        return ($w);

    }
    public  function editCampaign($uuid) { 
        
        $user = auth()->user();
        
        $data = Campaign::where('uuid',$uuid)->where('user_id',auth()->user()->id)->first();
       
        if($data){
            $checkMailsend = SendCampaignMail::where('camp_id',$data->id)->whereIn('status',['1','2','3','4','5'])->count();
           
            $is_edit = false;
            if($data->status  == '0'){
              $is_edit = true;
            
            }
            if($data->status  == '1'){
              $is_edit = true;
            
            }
            if($data->status  == '5'){
              $is_edit = true;
            
            }
            if($data->status  == '6'){
              $is_edit = true;
            
            }
            if($checkMailsend >0){
              $is_edit = false;
            
            }
            if($is_edit == false ){
               return redirect()->route('campaigns')->with('error','You can not edit this campiagn');
            
            }
            $followUps = Campaign::where('is_parent',$data->id)->where('is_followup','1')->get()->toArray();
           
            $collections = DB::table('collections')
                        ->select()
                        ->where('user_id', auth()->user()->id)
                        ->where('status', '=' ,1)
                        ->where('website_count', '>' ,0)
                        ->orderBy('created_at', 'desc')
                        ->get();

            $excelData ='';
            $heading ='';
            $realfilename ='';
            $totalCount = count($followUps)+1;
            if($data->type=='2' || $data->type=='3'  ){
                $file = $data->file_csv;
                $excelData = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
                $heading = array_shift($excelData);
                \Session::put('heading', $heading);
                
            }
            $plan = SubscriptionPlan::where('id',$user->plan)->first();
            $size_limit = (!empty($plan))? $plan->size_limit :0;

            $fallback_text = (!empty($data->fallback_text)) ? explode(',',$data->fallbacktext) : null  ;
            $features = ($data->features!='') ? explode(',',$data->features) : NULL;
            $allOldCampign = Campaign::select('id','name','import_contact','final_upload_csv_count')->whereNull('is_parent')->where('user_id',auth()->user()->id)->where('id','!=',$data->id)->get();
       
            return view('edit-campaign',compact('data','totalCount','collections','excelData','heading','realfilename','followUps','allOldCampign','fallback_text','size_limit','features'));
        } else {
            abort('404');
        }
      
    }
    public  function campaignDetails($uuid) { 
        
      
        $data = Campaign::where('uuid',$uuid)->where('user_id',auth()->user()->id)->first();
        if($data){
            $OutreachfollowUps = Campaign::where('is_parent',$data->id)->where('attemp','1')->orWhere('id',$data->id)->orderBy('id','ASC')->get()->toArray();
            $attemptsData = Campaign::where('is_parent',$data->id)->orWhere('id',$data->id)->orderBy('id','ASC')->get();
            //dd($attemptsData);

            $noOfAttempt= Campaign::where('is_parent',$data->id)->where('is_followup','0')->orWhere('id',$data->id)->get();
            
            if($data->is_parent ==  ''){
                $attemptsCamp =Campaign::where('is_parent',$data->id)->where('is_followup','0')->orWhere('id',$data->id)->get();
            }else{
              $attemptsCamp =Campaign::where('is_parent',$data->id)->orWhere('id',$data->id)->get();
           }
  
            
            $checkMailsend = SendCampaignMail::where('camp_id',$data->id)->whereIn('status',['1','2','3','4','5'])->count();
            
            return view('campaign-details',compact('data','noOfAttempt','attemptsData','attemptsCamp','OutreachfollowUps','checkMailsend'));
        } else {
            abort('404');
        }
      
    }
     public function getSendMail(Request $request)
    {
       
        $query = SendCampaignMail::where('camp_id',$request->camp_id)->where('user_id',auth()->user()->id)->orderby('id','ASC');
        
        return datatables($query)->addColumn('action', function ($query)
        {
           $action ='';
           $action .='<div class="d-flex">
                        <a href="javascript:;"   onclick="openSentMail('.$query->id.')"><i class="bi bi-envelope-fill" role="button" tabindex="0" ></i></a>
                       </div>';
            return $action;
                
        })
        
        ->editColumn('status', function ($query)
        {

            $status = 'Pending';
            if($query->status == '1'){
               $status ='Sent';
            }
            if($query->status == '2'){
               $status ='Unsubscribed';
            }
            if($query->status == '3'){
               $status ='Bounced';
            }
            if($query->status == '4'){
               $status ='Bounced';
            }

           
            $statuDiv = '<p class="mb-0 fw-500">
                        <small class="text-success">'.$status.'</small>
                     </p>
                     ';
            return $statuDiv;
        })
        ->editColumn('is_open', function ($query)
        {
            $is_open = ($query->is_open =='0') ? '-' : 'Y';
            return '<div class="d-flex">
                        <span class="badge bg-white text-dark ">'.$is_open.'</span></div>';
        })
        ->editColumn('is_click', function ($query)
        {
            $is_click = ($query->is_click =='0') ? '-' : 'Y';
            return '<div class="d-flex">
                        <span class="badge bg-white text-dark ">'.$is_click.'</span></div>';
        })
         ->editColumn('is_reply', function ($query)
        {
            $is_reply = ($query->is_reply =='0') ? '-' : 'Y';
            return '<div class="d-flex">
                        <span class="badge bg-white text-dark ">'.$is_reply.'</span></div>';
        })
        
        ->escapeColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }
    
   
    public  function showStatics(Request $request) { 
        
        $data = Campaign::where('id',$request->id)->where('user_id',auth()->user()->id)->first();
        $campign = Campaign::where('uuid',$request->uuid)->where('user_id',auth()->user()->id)->first();
        $uuid = $request->uuid;
        if($data){

            $allChild = Campaign::where('is_parent',$data->id)->where('is_attempt','1')->orWhere('id',$data->id)->pluck('id')->toArray();
            $totalSendCount = Campaign::whereIn('id',$allChild)->sum('import_contact');
            $totalEmails = $totalSendCount;


            $totalSendMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('status','1')->count();
            $totalFailedMail = SendCampaignMail::whereIn('camp_id',$allChild)->whereIn('status',['3','2','4','5'])->count();

            $totalReplyMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('is_reply','>','0')->where('status','1')->count();
            $totalOpenMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('is_open','>','0')->where('status','1')->count();
            $totalClickMail = SendCampaignMail::whereIn('camp_id',$allChild)->where('is_click','>','0')->where('status','1')->count();
            $mailSend = ($totalSendMail /$totalEmails) * 100;
            $mailFailed = ($totalFailedMail / $totalEmails) * 100;
            $mailReply = 0;
            $mailOpen = 0;
            $mailClick = 0;
            if($totalSendMail >0){
                $mailReply = ($totalReplyMail / $totalSendMail) * 100;
                $mailOpen = ($totalOpenMail / $totalSendMail) * 100;
                $mailClick = ($totalClickMail / $totalSendMail) * 100;

            }
            $OutreachfollowUps = Campaign::select('id')->where('is_parent',$campign->id)->where('attemp','1')->orWhere('id',$campign->id)->orderBy('id','ASC')->get()->toArray();
            $attemptsData = Campaign::where('is_parent',$campign->id)->orWhere('id',$campign->id)->orderBy('id','ASC')->get()->toArray();
           

            $checkActiveCamp = Campaign::where('top_most_parent',$campign->id)->where('is_active','1')->first();
            $activeCamp = (!empty($checkActiveCamp)) ? $checkActiveCamp : $campign;
            $status = 'to begin';
            $ostatus = '0';

            if($activeCamp->status == '2'){
                $status = 'to begin';
                $ostatus = '0';
            }
            if($activeCamp->status == '3'){
                $status = 'in Progress';
                $ostatus = '3';
            }
            if($activeCamp->status == '5'){
                $status = 'paused';
                $ostatus = '5';
            }
            if($activeCamp->status == '4'){
                $status = 'in progress';
                $ostatus = '4';
            }
            if($activeCamp->status == '8'){
                $status = 'finished';
                $ostatus = '8';
            }
            if($activeCamp->status == '6'){
                $status = 'draft';
                $ostatus = '6';
            }
            if($activeCamp->status == '7' && $activeCamp->is_file_read == 'N'){
                $status = 'activeCamp processing';
                $ostatus = '7';
            }
            if($activeCamp->status == '7' && $activeCamp->is_file_read == 'Y'){
                $status = 'scheduled Campaign';
                $ostatus = '7';
            }
            
            return view('view-campiagn-statics',compact('totalEmails','totalSendMail','mailSend','mailFailed','mailReply','mailOpen','mailClick','status','OutreachfollowUps','data','uuid','attemptsData','activeCamp','totalReplyMail','totalOpenMail','totalClickMail','totalSendMail','totalFailedMail','ostatus'));
        } 
      
    }
    public  function downloadReport($uuid,$level=NULL) { 
        
        $data = \DB::table('campaigns')->select('uuid','id','name','user_id')->where('uuid',$uuid)->where('user_id',auth()->user()->id)->first();
        if($data){
            $camp_id = $data->id;
            $level = $level;
            $type = '2';
            $fileName = Str::slug($data->name).'-'.$uuid.'.csv';
            return  Excel::download(new ReportExport($camp_id,$level,$type),$fileName);
        } else {
            return redirect()->back()->with('error','No campaign Found');
        }
      
    }
    public  function playPauseCampaign(Request $request) { 
        
        $data = Campaign::where('id',$request->id)->where('user_id',auth()->user()->id)->first();
        if($data){
           
            if($request->cond =='play'){
                $status = '0';
                $statusW ='to begin';
            } else{
                $status = '5';
                $statusW ='Pause';
            }
            $updateCampiagn = Campaign::where('id',$request->id)->update(['status'=>$status]);
            $fname =  ($data->is_parent==NULL)  ? 'outreach'  :'followup' ; 
            $j =  $data->attemp;
            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
            if (($j %100) >= 11 && ($j%100) <= 13)
            $term =  'th';
            else
            $term =  $ends[$j % 10];

            $statusN = ''.$j.''.$term.'  attempt  '.$fname.' '.$statusW.'';
            $data = [
                'type'      => 'success',
                'status'      => $statusN
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
      
    }

    public function previewMessage(Request $request){
        $campaigns = SendCampaignMail::where('id',$request->id)
                    ->where('user_id',auth()->user()->id)
                    ->first();
        $subject = ($campaigns->subject!='') ? $campaigns->subject:'';
        $message = ($campaigns->message!='') ? $campaigns->message:'';
        //$message = preg_replace("/\s/",' ',$message);
        //$message = str_replace(chr(194)," ",$message);
        
        $send_id = base64url_encode($campaigns->id);
        $fromEmail = base64url_encode($campaigns->from_email);
        $toEmailuser = base64url_encode($campaigns->to_email).'.'.$send_id.'.'.$fromEmail;
        $hasmac = Hasmac($campaigns->to_email);
        $token = $toEmailuser.'.'.$hasmac;
        
        $unSubTag ="If you don't want to receive such emails in the future, <a href='".route('unsubscribe-mail',$token)."'>unsubscribe</a> here";

        $message = $message.$unSubTag;

        $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
       
        $from = $campaigns->from_email;
        $to = $campaigns->to_email;
        
        return view('preview-message',compact('message','subject','from','to','send_id'));
    }
    public function previewMessageInbox(Request $request){
        $campaigns = Inbox::where('id',$request->id)
                    ->where('user_id',auth()->user()->id)
                    ->first();
        $unSubTag = 'If you did  prefer not to receive email from me
                            <a href="#">unsubscribe </a> here .';
        $message = ($campaigns->message!='') ? $campaigns->message:'';
        $subject = ($campaigns->subject!='') ? $campaigns->subject:'';
        $message = str_replace("Unsubscribe", $unSubTag, $message);
        //$message = preg_replace("/\s/",' ',$message);
        //$message = str_replace(chr(194)," ",$message);
        
        $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
       
      
        
        return view('preview-message',compact('message','subject'));
    }
}
