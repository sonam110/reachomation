<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Helper;
use Str;
use Auth;
use App\Models\SendCampaignMail;
use App\Models\Campaign;
use App\Models\Inbox;
use App\Models\Feed;
use Yajra\Datatables\Facades\Datatables;
use App\Models\EmailCollection;
class InboxController extends Controller
{
     public function feeds()
    {
        return View('feeds');
    }
    public function feedsList(Request $request)
    {
        $whereRaw = $this->getWhereRawFromRequest($request);
        
        if ($whereRaw != '')
        {
            $query = Feed::where('user_id', auth()->user()->id)->whereRaw($whereRaw)->with('campiagn')->limit('50')->orderBy('id','DESC');

        }
        else
        {
            $query = Feed::where('user_id', auth()->user()->id)->with('campiagn')->limit('50')->orderBy('id','DESC');
        }
        return datatables($query)->addColumn('message', function ($query)
        {
            $campign = ($query->campiagn !='') ? $query->campiagn->name :'';
            
            $send = SendCampaignMail::select('id','stage')->where('id',$query->internal_id)->first();
            $stage =  (@$send->stage) ? $send->stage :'1';
            $status = '';
            $icon = '';
            if($query->status == '1'){
             $status ='opened';
             $icon ='<i class="bi bi-envelope-open" aria-hidden="true"></i> ';
             
            }
            if($query->status == '2'){
             $status ='replied';
             $icon ='<i class="bi bi-reply-fill" aria-hidden="true"></i>';
            }
            if($query->status == '3'){
             $status ='clicked';
             $icon ='<i class="bi bi-messenger" aria-hidden="true"></i>';
            }
            if($query->status == '4'){
              $status ='Unsubscribed';
              $icon ='<i class="bi bi-backspace-reverse" aria-hidden="true"></i>';
            }
            
            $msg = '<div class="mailbox-subject">'.$icon.' '.$query->to_email.'  '.$status.' attempt '.$stage.' email of campaign <strong>'.$campign.'</strong></div>';
            return $msg;
                
        })
        ->addColumn('count', function ($query)
        {
            $count = '';
            $button = '';
            if($query->status == '1'){
             $count = ($query->campiagn) ? $query->campiagn->total_open :'0';
             $button = '<button class="btn countBtn">'.$count.' times</button>';
            }
            if($query->status == '2'){
              $count = ($query->campiagn) ? $query->campiagn->total_reply :'0';
            }
            if($query->status == '3'){
              $count = ($query->campiagn) ? $query->campiagn->total_click :'0';
              $button = '<button class="btn countBtn">'.$count.' times</button>';
            }
            if($count >1){
                
                return $button;
            } 
            
        })
        ->editColumn('c_date', function ($query)
        {
           
           $getTime = ($query->created_at)->diffForHumans();
           $data = '<div class="mailbox-date">'.$getTime.'</div>';
            return $data;
        })
        ->escapeColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    function get_time_ago( $time )
    {
        $time_difference = time() - $time;

        if( $time_difference < 1 ) { return 'less than 1 second ago'; }
        $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second'
        );

        foreach( $condition as $secs => $str )
        {
            $d = $time_difference / $secs;

            if( $d >= 1 )
            {
                $t = round( $d );
                return  $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
            }
        }
    }

    public function sendbox()
    {
        $allCampaign = Campaign::where('user_id',auth()->user()->id)->where('status','4')->get();
        $EmailCollection = EmailCollection::where('user_id',auth()->user()->id)->get();
        return View('sendbox',compact('allCampaign','EmailCollection'));
    }
    public function sendBoxList(Request $request)
    {
        $whereRaw = $this->getWhereRawFromRequest($request);
        
        if ($whereRaw != '')
        {
            $query = Inbox::where('user_id', auth()->user()->id)->whereRaw($whereRaw)->with('campiagn')->orderBy('id','DESC');

        }
        else
        {
            $query = Inbox::where('user_id', auth()->user()->id)->with('campiagn')->orderBy('id','DESC');
        }
        return datatables($query)->addColumn('message', function ($query)
        {
            $campign = ($query->campiagn !='') ? $query->campiagn->name :'';
            
            $send = SendCampaignMail::select('id','stage')->where('id',$query->internal_id)->first();
            $stage =  (@$send->stage) ? $send->stage :'1';
            $status = '';
            $icon = '';
            if($query->status == '1'){
             $status ='Send';
             $icon ='<i class="bi bi-send-fill"></i> ';
             
            }
            if($query->status == '2'){
             $status ='Bounce';
             $icon ='<i class="bi bi-textarea" aria-hidden="true"></i> ';
             
            }
            if($query->status == '3'){
             $status ='Failed';
             $icon ='<i class="bi bi-send-exclamatio" aria-hidden="true"></i>';
            }
            if($query->status == '4'){
             $status ='Replied';
             $icon ='<i class="bi bi-reply-fill" aria-hidden="true"></i>';
            }
            
            $msg = '<div class="mailbox-subject">'.$icon.' '.$query->to_email.'  '.$status.' attempt '.$stage.' email of campaign <strong>'.$campign.'</strong></div>';
            return $msg;
                
        })
        ->addColumn('action', function ($query)
        {
            //$message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$query->message); 
            //$message = str_replace(chr(194)," ",$message);
           // $message = str_replace("&nbsp;",' ', $message);
            return '<a href="javascript:;" data-msg="'.htmlspecialchars($query->message).'" id="preview-msg-'.$query->id.'" onclick="openSentMail('.$query->id.')"><i class="bi bi-envelope-fill" role="button" tabindex="0" ></i></a>';
            
        })
        ->editColumn('c_date', function ($query)
        {
           $time = strtotime($query->c_date);
           $getTime = ($query->created_at)->diffForHumans();
           $data = '<div class="mailbox-date">'.$getTime.'</div>';
            return $data;
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
            if ($w != '')
            {
                $w = $w . " AND ";
            }
            $w = $w . "(" . "to_email like '%" .trim(strtolower($request->input('keyword'))) . "%')";
        }
        
       
        return ($w);

    }
}
