<?php
use App\Models\Inbox;
use App\Models\Feed;
use App\Models\Campaign;
use App\Models\Collection;
use App\Models\SendCampaignMail;
use App\Models\EmailCollection;
use App\Models\Revealed;
use App\Models\Favourite;
use App\Models\Template;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayementNotification;

function Hasmac($email){

	$server_secret = env('SECRET_STRING');
	$token = hash_hmac('sha256',$email,$server_secret);
	return $token;
}

function base64url_encode($str) {
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}

function base64url_decode($str) {
    return base64_decode(strtr($str, '-_', '+/'));
}

/*------------------Canpaign Feed------------------*/
function Feed($user_id,$camp_id,$internal_id,$from_email,$to_email,$c_date,$status,$msg_id){
	$feed = new Feed;
	$feed->user_id = $user_id;
	$feed->camp_id = $camp_id;
	$feed->internal_id = $internal_id;
	$feed->msg_id = $msg_id;
	$feed->from_email = $from_email;
	$feed->to_email = $to_email;
	$feed->c_date = $c_date;
	$feed->status = $status;
	$feed->ip_address = \Request::ip();
	$feed->save();
}

/*------------------Canpaign Inbox------------------*/
function Inbox($user_id,$camp_id,$internal_id,$from_email,$to_email,$subject,$message,$c_date,$status,$msg_id){
	$index = new Inbox;
	$index->user_id = $user_id;
	$index->camp_id = $camp_id;
	$index->internal_id = $internal_id;
	$index->msg_id = $msg_id;
	$index->from_email = $from_email;
	$index->to_email = $to_email;
	$index->subject = $subject;
	$index->message = $message;
	$index->c_date = $c_date;
	$index->status = $status;
	$index->ip_address = \Request::ip();
	$index->save();
}

function userCampiagn($user_id){
	$totalCampaign = Campaign::where('user_id',$user_id)->whereNull('is_parent')->count();
	return $totalCampaign;
}
function userList($user_id){
	$totalCollection = Collection::where('user_id',$user_id)->where('status','!=','2')->count();
	return $totalCollection;
}

function totalSendMail($user_id){
	$totalSendMail = SendCampaignMail::where('user_id',$user_id)->where('status','1')->count();
	return $totalSendMail;
}
function totalReplyMail($user_id){
	$totalReplyMail = SendCampaignMail::where('user_id',$user_id)->where('is_reply','>','0')->count();
	return $totalReplyMail;
}
function RevealedCount($user_id){
    $RevealedCount = Revealed::where('user_id',$user_id)->count();
    return $RevealedCount;
}
function TemplateCount($user_id){
    $TemplateCount = Template::whereIn('user_id',[$user_id,'1'])->where('status','1')->count();
    return $TemplateCount;
}
function FavouriteCount($user_id){
    $FavouriteCount = Favourite::where('user_id',$user_id)->count();
    return $FavouriteCount;
}

function getLast30Days()
    {
        $today     = new \DateTime();
        $begin     = $today->sub(new \DateInterval('P10D'));
        $end       = new \DateTime();
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);
        foreach ($daterange as $date) {
            $dateList[] = '"'.$date->format("Y-m-d").'"';
        }
        $allDates = implode(', ', $dateList);
        //dd($allDates);
        return $allDates;
    }
function getLast30DaysSentMails($user_id)
    {
        $today     = new \DateTime();
        $begin     = $today->sub(new \DateInterval('P10D'));
        $end       = new \DateTime();
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);
        $sent =[];
        foreach ($daterange as $date) {
        	
           $sendmail = SendCampaignMail::where('user_id',$user_id)->whereDate('mail_send_date',$date->format('Y-m-d'))->count(); 
            
            $sent[] = $sendmail;
        }
      
    
        $data = implode(', ', $sent);
        return $data;
    }
    function getLast30DaysFailedMails($user_id)
    {
        $today     = new \DateTime();
        $begin     = $today->sub(new \DateInterval('P10D'));
        $end       = new \DateTime();
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);
        $failed =[];
        foreach ($daterange as $date) {
            
           $failedMail = SendCampaignMail::whereIn('status',['2','3','4'])->where('user_id',$user_id)->whereDate('mail_send_date',$date->format('Y-m-d'))->count(); 
            
            $failed[] = $failedMail;
        }
      
    
        $data = implode(', ', $failed);
        return $data;
    }
    function getLast30DaysDeliveredMails($user_id)
    {
        $today     = new \DateTime();
        $begin     = $today->sub(new \DateInterval('P10D'));
        $end       = new \DateTime();
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);
        $send =[];
        foreach ($daterange as $date) {
            
           $sendMail = SendCampaignMail::where('status','1')->where('user_id',$user_id)->whereDate('mail_send_date',$date->format('Y-m-d'))->count(); 
            
            $send[] = $sendMail;
        }
      
    
        $data = implode(', ', $send);
        return $data;
    }
    function getLast30DaysReplyMails($user_id)
    {
        $today     = new \DateTime();
        $begin     = $today->sub(new \DateInterval('P10D'));
        $end       = new \DateTime();
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);
        $send =[];
        foreach ($daterange as $date) {
            
           $sendMail = SendCampaignMail::where('is_reply','>','0')->where('user_id',$user_id)->whereDate('mail_send_date',$date->format('Y-m-d'))->count(); 
            
            $send[] = $sendMail;
        }
      
    
        $data = implode(', ', $send);
        return $data;
    }
    function getLast30DaysList()
    {
        $today     = new \DateTime();
        $begin     = $today->sub(new \DateInterval('P10D'));
        $end       = new \DateTime();
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);
        foreach ($daterange as $date) {
            $dateList[] = '"'.$date->format("Y-m-d").'"';
        }
        
        return $dateList;
    }
    function account_email()
    {
       $account_email = EmailCollection::where('user_id',auth()->user()->id)->where('status','1')->first();
       return ($account_email!='') ? $account_email->email:null;
    }

    function isRevealed($domain_id){
        if (\Auth::check()) {
    	   $revealedDomains = Revealed::where('user_id',auth()->user()->id)->where('domain_id',$domain_id)->first();
    	   return (!empty($revealedDomains)) ? true:false;
        } else{
            return false;
        }
    }

   

  function generate_timezone_list()
 {
    static $regions = array(
         \DateTimeZone::AMERICA,
             \DateTimeZone::AFRICA,
             \DateTimeZone::ANTARCTICA,
             \DateTimeZone::ASIA,
             \DateTimeZone::ATLANTIC,
             \DateTimeZone::AUSTRALIA,
             \DateTimeZone::EUROPE,
             \DateTimeZone::INDIAN,
             \DateTimeZone::PACIFIC
    );

    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }

    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC{$offset_prefix}{$offset_formatted}";

        $timezone_list[$timezone] = "$timezone ({$pretty_offset})";
    }

    return $timezone_list;
}

 function timeZoneDate($datatime,$timezone,$format){
 	$timestamp = $datatime;
	$date = Carbon::createFromFormat($format, $timestamp,$timezone);
	$date->setTimezone($timezone);
  	return $date;
}

 function getCreatedAtAttribute($value,$timezone,$format)
{
    return Carbon::createFromTimestamp(strtotime($value))
        ->timezone($timezone)
        ; 
}



function getChildren($id)
{
    $tree = Array();
    if (!empty($id))
    {
        $tree = Campaign::where('is_parent', $id)->pluck('id')
            ->toArray();
        foreach ($tree as $key => $val)
        {
            $ids = getChildren($val);
            if (!empty($ids))
            {
                if (count($ids) > 0) $tree = array_merge($tree, $ids);
            }
        }
    }
    return $tree;
}

function group(){
	if (\Auth::check()) {
		$ids= [auth()->user()->id,'1'];
	    $groups = \DB::table('templates')
	    ->select(\DB::raw('count(id) as total, name'))
	    ->whereIn('user_id', $ids)
	    ->where('status', '=' ,1)
	    ->groupBy('name')
	    ->orderByDesc('total')
	    ->get();
	    return $groups;
   }
}
function template(){
	$templates =[];
	if (\Auth::check()) {
		$ids= [auth()->user()->id,'1'];
	    $templates = \DB::table('templates')
	        ->whereIn('user_id', $ids)
	        ->where('status', '=' ,1)
	        ->orderBy('created_at', 'desc')
	        ->get();
	    
	}
	return $templates;
}
function emailCollections(){
	$emailCollection=[];
	if (\Auth::check()) {
		$emailCollection = EmailCollection::where('user_id',auth()->user()->id)->where('status','1')->orderBy('id','ASC')->get(); 
	     
	}
	return $emailCollection;

}

function defaultTemplate(){
	$default_template ='';
	if (\Auth::check()) {
		$defaulttemplate = Template::where('user_id', auth()->user()->id)
	        ->where('id', auth()->user()->default_tid)
	        ->where('status', '=' ,1)
	        ->first();
	    $default_template = (!empty($defaulttemplate)) ? $defaulttemplate :'';
    }
    return $default_template;
}


function createFreePackage($user){
	$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    $cust_id = (!empty($user)) ? $user->stripe_id : NULL;
    if (empty($cust_id)) {
        // Create a Stripe customer
	    $getCust = $stripe->customers->create([
	        'name'              => $user->name,
	        'email'             => $user->email,
	        'description'       => 'New Customer added',
	    ]);
	    // Set the Stripe id for our user
	    $cust_id = $getCust->id;
   	}
    $free_plan = SubscriptionPlan::where('id','1')->first();
    if(!empty($free_plan)){
        $addSubsc = new Subscription;
        $addSubsc->user_id = $user->id;
        $addSubsc->name = $free_plan->name;
        $addSubsc->stripe_id = $free_plan->stripe_plan_id;
        $addSubsc->plan_id = $free_plan->id;
        $addSubsc->stripe_status = 'Active';
        $addSubsc->stripe_price = $free_plan->stripe_plan_id;
        $addSubsc->quantity = '1';
        $addSubsc->status = '1';
        $addSubsc->full_name = $user->name;
        $addSubsc->email = $user->email;
        $addSubsc->country = $user->country;
        $addSubsc->status = '1';
        $addSubsc->start_at = date('Y-m-d');
        $addSubsc->state = $user->state;
        $addSubsc->city = $user->city;
        $addSubsc->postal_code = $user->postal_code;
        $addSubsc->address = $user->address;
        $addSubsc->save();

        \Helper::transactionHistory($user->id,'1','1',$free_plan->price,$free_plan->credits,'Monthly credits','1');

       	$userinfo = User::find($user->id);
        $userinfo->stripe_id = $cust_id;           
        $userinfo->stripe_customer_id = $cust_id;           
        $userinfo->plan = $free_plan->id;
        $userinfo->duration = $free_plan->plan_type;
        $userinfo->plan_started_at = date('Y-m-d');
        $userinfo->credits = $userinfo->credits + $free_plan->credits;
        $userinfo->save();

       

        if (env('IS_NOTIFY_ENABLE', false) == true) {
           
            Notification::send($userinfo, new PayementNotification($addSubsc));
        }


	}


	
}

function bulkInsertOrUpdate(array $rows,$table){
    $first = reset($rows);

    $columns = implode( ',',
        array_map( function( $value ) { return "$value"; } , array_keys($first) )
    );

    $values = implode( ',', array_map( function( $row ) {
            return '('.implode( ',',
                array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
            ).')';
        } , $rows )
    );

    $updates = implode( ',',
        array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
    );

    $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
  

    return \DB::statement($sql);
}





