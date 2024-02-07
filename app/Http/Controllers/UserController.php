<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Favourite;
use App\Models\Hidden;
// use App\Models\BlogEmail;
// use App\Models\SemrushTrafficUS;
use App\Models\Revealed;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\EmailCollection;
// use App\Models\BlogAuthor;
// use App\Models\BlogSearchData;
use Cookie;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CreditNotification;
use App\Mail\TestMail;
use App\Services\SendSmsService;
class UserController extends Controller
{
    public function updateNiches(Request $request){
        $user_id = $request->user()->id;
        $user = DB::table('users')
            ->where('id', $user_id)
            ->update(['niches' => $request->niches]);
        if($user){
            Cookie::expire('welcome');
        }else{
            return '2';
        }
    }
    public function updateUserInterest(Request $request){
        $user_id = $request->user()->id;
        $user = DB::table('users')
            ->where('id', $user_id)
            ->update(['way_of_support'=>$request->way_of_support,'niches' => $request->niches,'skype'=>$request->skype]);
            
        if($user){
           return '1';
        }else{
            return '2';
        }
    }

    public function addtoFavourites(Request $request){
        $user_id = $request->user()->id;
        $favourites = DB::table('favourites')->where('domain_id', $request->domain_id)->where('user_id', $user_id)->first();
        if(!empty($favourites)){
            return '2';
        }else{
            $favourite = new Favourite;
            $favourite->user_id = $user_id;
            $favourite->domain_id = $request->domain_id;
            $favourite->domain = $request->website;
            $saved = $favourite->save();

            /*------------Save to Favorite list--*/
            $list = DB::table('collections')
                    ->where('user_id', auth()->user()->id)
                    ->where('name','Favourites')
                    ->first();
            if(!empty($list)){
                DB::table('domain_collections')->insert([
                    'user_id' => auth()->user()->id,
                    'collection_id' => $list->id,
                    'domain_id' => $request->domain_id,
                    'domain' => $request->website,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $size = DB::table("domain_collections")->where('user_id',auth()->user()->id)->where('collection_id', '=' ,$list->id)->count();
                $collections = DB::table('collections')->where('id',$list->id)->update(['website_count' => $size]);

            }
            if($saved){
                return '1';
            }else{
                return '2';
            }
        }
    }

    public function removetoFavourites(Request $request){
        $user_id = $request->user()->id;
        $favourites = DB::table('favourites')->where('domain_id', $request->domain_id)->where('user_id', $user_id)->delete();
        /*------------remove  to Favorite list--*/

        if($favourites){
            $list = DB::table('collections')
                ->where('user_id', auth()->user()->id)
                ->where('name','Favourites')
                ->first();
            if(!empty($list)){
                DB::table('domain_collections')->where('domain_id',$request->domain_id)->where('user_id',$user_id)->where('collection_id',$list->id)->delete();
               
                $size = DB::table("domain_collections")->where('user_id',auth()->user()->id)->where('collection_id', '=' ,$list->id)->count();
                $collections = DB::table('collections')->where('id',$list->id)->update(['website_count' => $size-1]);

            }
            return '1';
        }else{
            return '2';
        }
    }

    public function getEmail(Request $request){
        $email = DB::table('website_data')
                ->select('emails')
                ->where('domain_id', '=', $request->domain_id)
                ->where(function($q){
                    $q->whereNotNull('emails')->orWhere('emails', '!=', '');
                })->first();
        if($email)
        {
            $arr = json_decode($email->emails, true);
            $eml = '';
            foreach($arr as $key => $val){
                if(is_array($val)){
                    foreach ($val as $key => $value) {
                        $eml .= $value.',';
                    }
                }else{
                    $eml .= $val.',';
                }
            }
            $string = trim($eml, ',');
            $emails = explode(",", $string);
            return response()->json(['emails'=>$emails]);

        }
    }

    public function hide(Request $request){
        $domain = DB::table('hiddens')->where('domain_id', $request->domain_id)->where('user_id', $request->user()->id)->first();
        if(!empty($domain)){
            return '2';
        }else{
            $hidden = new Hidden;
            $hidden->user_id = $request->user()->id;
            $hidden->domain_id = $request->domain_id;
            $hidden->domain = $request->website;
            $saved = $hidden->save();
            if($saved){
                return '1';
            }else{
                return '2';
            }
        }
    }

    public function unhide(Request $request){
        $domain = DB::table('hiddens')
                ->where('domain_id', $request->domain_id)->where('user_id', $request->user()->id)->delete();
        if($domain){
            return '1';
        }else{
            return '2';
        }
    }

    public function openTraffic(Request $request){
        $traffic = DB::table('semrush_history_us')
                ->select('sot', 'checkedon')
                ->where('domain_id', $request->domain_id)
                ->where('checkedon', '>=', '2021-05-30')
                // ->groupBy('checkedon')
                ->orderBy('checkedon', 'asc')
                ->get();
        return response()->json(['traffic'=>$traffic]);
    }

    public function insertTemplate(Request $request){
        $template = DB::table('templates')
                    ->where('id', $request->id)
                    ->first();
        return response()->json(['template'=>$template]);
    }
    public function setDefaultTemplate(Request $request){
       
            $checkAlreadySet = User::where('id',auth()->user()->id)->where('default_tid',$request->id)->count();
            if($checkAlreadySet <1){
                $senTemplte = User::where('id',auth()->user()->id)->update(['default_tid'=>$request->id]);
                if($senTemplte){
                    $data = [
                        'type'      => 'success',
                    ];
                    return response()->json($data, 200);
                } else{
                     $data = [
                        'type'      => 'error',
                    ];
                    return response()->json($data, 200);
                }

            } else{
                $removeTemplte = User::where('id',auth()->user()->id)->update(['default_tid'=>'']);
                $data = [
                    'type'      => 'unset',
                ];
                return response()->json($data, 200);
            }
    
    }

    public function revealedDomain(Request $request){
        $old = $request->user()->credits;
        $domain = DB::table('revealeds')->where('domain_id', $request->domain_id)->where('user_id', $request->user()->id)->first();

        if(!empty($domain)){
            return '2';
        }else{
            $reveal = new Revealed;
            $reveal->user_id = $request->user()->id;
            $reveal->domain_id = $request->domain_id;
            $saved = $reveal->save();
            
            if($saved){

                /*------------Save to Reveales list--*/
                $list = DB::table('collections')
                        ->where('user_id', auth()->user()->id)
                        ->where('name','Revealed')
                        ->first();
                
                if(!empty($list)){

                    $site = DB::table('website_data')->select('domain_id','website')->where('domain_id',$request->domain_id)->first();
                    
                    DB::table('domain_collections')->insert([
                        'user_id' => auth()->user()->id,
                        'collection_id' => $list->id,
                        'domain_id' => $request->domain_id,
                        'domain' => $site->website,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);


                    $size = DB::table("domain_collections")->where('user_id',auth()->user()->id)->where('collection_id', '=' ,$list->id)->count();
                    $collections = DB::table('collections')->where('id',$list->id)->update(['website_count' => $size]);

                }

                $credits = $request->user()->credits-$request->credit;
                if($request->dontshow==1){
                    $user = DB::table('users')
                            ->where('id', $request->user()->id)
                            ->update(['credits' => $credits, 'dont_show' => $request->dontshow]);
                }else{
                    $user = DB::table('users')
                            ->where('id', $request->user()->id)
                            ->update(['credits' => $credits]);
                }
                
                if($credits){
                    $website = \DB::table('website_data')->where('domain_id',$request->domain_id)->first();
                    $transaction = new TransactionHistory;
                    $transaction->user_id = $request->user()->id;
                    $transaction->bal_type = 2;
                    $transaction->old_credits = $old-$request->credit;
                    $transaction->credits = $request->credit;
                    $transaction->comment = 'Revealed '.$website->website.'';
                    $transaction->status = 1;
                    $transaction->save();
                    if (env('IS_NOTIFY_ENABLE', false) == true) {
                        $user = User::where('id',$request->user()->id)->first();
                        Notification::send($user, new CreditNotification($transaction));
                    }

                    if($transaction){
                        return '1';
                    }else{
                        return '2';
                    }
                }else{
                    return '2';
                }
            }else{
                return '2';
            }
        }
    }

    public function testMail(Request $request){
        
       
        $email = EmailCollection::where('user_id',auth()->user()->id)->where('email',$request->from_email)->where('status','1')->first();
        $user_id = auth()->user()->id;
        if(empty($email)){
            $data = [
                'type'      => 'not_found',
                'message'      => 'Please Connnect with your email in settings section',
            ];
            return response()->json($data, 200);
        }
        $con = DB::connection('blogsearch');
        $details = $con->table('blog_search_data')
            ->join('blog_author', 'blog_author.domain_id', '=', 'blog_search_data.domain_id')
            ->select('blog_author.author','blog_search_data.title','blog_search_data.website')
            ->where('blog_search_data.domain_id', $request->domain_id)
            ->first();
        $fallback_text = (!empty($request->fallback_text)) ? explode(',',$request->fallback_text) : null  ;
        $to = $request->email;
        $heading = ['Name','Website','Title'];
        $author = (!empty($details->author)) ? $details->author :'';
        $website = (!empty($details->website)) ? $details->website :'';
        $title = (!empty($details->title)) ? $details->title :'';
        $csv = [''.$author.'',''.$website.'',''.$title.''];

        $sub = $request->subject;
        $msg = $request->body;
        $unSubTag = "<br> If you don't want to receive such emails in the future, <a href='#'>unsubscribe</a> here";
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
                    
                    $sub = $this->strReplaceAssoc($newKeyword,$sub);
                    $msg = $this->strReplaceAssoc($newKeyword,$msg);
                    $msg = $msg.$unSubTag;
                    $sub = preg_replace('/<[^>]*>/', ' ', $sub);

                }
              
        }
  
        $sendSms = new SendSmsService;
        if($email->account_type == '1'){
            
            $is_send = $sendSms->sendMailByGmail($email->email,$to,$sub,$msg,$user_id);
        }
        if($email->account_type == '2'){
            $is_send = $sendSms->sendMailByOutlook($email->email,$to,$sub,$msg,$user_id);
            
        }
        if($is_send == true){
            $email->daily_limit = $email->daily_limit-1;
            $email->save();

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

    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }

    public function emailVerification($id, $hash) {
        $user = DB::table('users')->where('id', $id)->where('email_verified_token', $hash)->first();
        if ($user) {
            $update = DB::table('users')
                    ->where('id', $id)
                    ->update(['email_verified_at' => Now(),'status'=>'1']);
            return view('auth.verify-email-success');
        } else {
           return redirect()->route('login')
                ->with('error', 'Invalid Link');
        }
    }

}