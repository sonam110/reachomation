<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Helper;
use Str;
use App\Models\SendCampaignMail;
use App\Models\Campaign;
use App\Models\Feed;
use Illuminate\Support\Facades\Redirect;
use DateTime;
class MailTrackController extends Controller
{

    public function mailTrack($token){
        header("Content-Type: image/jpeg");
        $file_name ='track.jpg';
        readfile($file_name);
        //readfile(dirname(__FILE__).'/'.$file_name);
       
        if (isset($token)) 
        {
            $tok = explode('.', $token);
            $is_valid = true;
            if (count($tok) !== 3) {
                // not valid token
                $is_valid = false;
                
            }
            $send_id = base64url_decode(@$tok[0]);
            $camp_id = base64url_decode(@$tok[1]);
            if (@$tok[2] !== Hasmac($camp_id)) {
                // failed verification
                $is_valid = false;
               
            }
            $checkSendMail = SendCampaignMail::where('camp_id',$camp_id)->where('id',$send_id)->first();
            if(empty($checkSendMail)){
                $is_valid = false ;
            }
            $mail_send_date = (!empty($checkSendMail)) ? $checkSendMail->mail_send_date:NULL;
            $d1 = new DateTime(@$checkSendMail->mail_send_date);
            $d2 = now();
            $interval = $d1->diff($d2);
            $diffInSeconds = $interval->s;
            if($diffInSeconds <=7 )
            {
                $is_valid = false ;
            }
           
            if($is_valid== true){

                $trackOpenMail = SendCampaignMail::find($send_id);
                $trackOpenMail->is_open = $checkSendMail->is_open+1 ;
                $trackOpenMail->is_open_time = now() ;
                $trackOpenMail->save();

                $updateCamp = Campaign::find($camp_id);
                $updateCamp->total_open = $updateCamp->total_open+1;
                $updateCamp->save();

                /*---Open mail-------------*/
                Feed($checkSendMail->user_id,$camp_id,$checkSendMail->id,$checkSendMail->from_email,$checkSendMail->to_email,date('Y-m-d H:i:s'),'1',$checkSendMail->msg_id);
                \Log::info($send_id);
               
                if($trackOpenMail){
                    return true;
                }
                else{
                    return false;
                }
                

            } else{
                
                return false;

            }

        }

    }
     public function clickMail($token){  
        if (isset($token)) 
        {
            $tok = explode('.', $token);
            $is_valid = '1';
            if (count($tok) !== 3) {
                // not valid token
                $is_valid = '0';
                
            }
            $id = base64url_decode(@$tok[0]);
            $website = base64url_decode(@$tok[1]);
            $url = 'http://'.$website;
            if ($tok[2] !== Hasmac($id)) {
                // failed verification
                $is_valid = '0';
               
            }
            $checkSendMail = SendCampaignMail::where('id',$id)->first();
            if(empty($checkSendMail)){
                $is_valid = '0';
            }
            //\Log::info($checkSendMail);die;
            if($is_valid== true){
                $trackClickMail = SendCampaignMail::find($checkSendMail->id);
                $trackClickMail->is_click = $checkSendMail->is_click+1 ;
                $trackClickMail->last_click_time = now();
                $trackClickMail->save();

                $campaign  = Campaign::where('id',$checkSendMail->camp_id)->first();
                $updateCamp = Campaign::find($checkSendMail->camp_id);
                $updateCamp->total_click = $campaign->total_click+1;
                $updateCamp->save();

                /*---Click mail-------------*/

                Feed($checkSendMail->user_id,$checkSendMail->camp_id,$checkSendMail->id,$checkSendMail->from_email,$checkSendMail->to_email,date('Y-m-d H:i:s'),'3',$checkSendMail->msg_id);
                if($trackClickMail){
                    return  Redirect::to($url);
                }
                else{
                   return   Redirect::to($url);

                }
                

            } else{
                
                return  Redirect::to($url);

            }

        }


      

    }
    
    
}
