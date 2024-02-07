<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EmailCollection;
class TestingController extends Controller
{
    //
    public function checkCampTimeLoop(){
        $nowDatetime  =  Carbon::now()->setTimezone($timezone)->format("Y-m-d H:i:s");
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

        Log::info('Timing');
        Log::info(timeZoneDate($starttime,$campaign->timezone,'Y-m-d H:i:s')->format("Y-m-d H:i:s"));
        Log::info(timeZoneDate($endtime,$campaign->timezone,'Y-m-d H:i:s')->format("Y-m-d H:i:s"));

        Log::info('start '.$start);
        Log::info('end ' .$end);
        Log::info('now '.$now);


        $is_run = false;
        if($campaign->non_stop =='1'){
            $is_run = true;
        }
        elseif($start <= $now && $end >=  $now){
            $is_run = true;
        } else{
            $is_run = false;
        }
        Log::info('is_run'.$is_run);
        
        
    }

}
