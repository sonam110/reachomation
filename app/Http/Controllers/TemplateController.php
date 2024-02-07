<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Template;
use App\Models\EmailCollection;
use Mail;
use App\Mail\TestMail;
use App\Services\SendSmsService;
class TemplateController extends Controller
{
    public function index(Request $request){
        $ids= [auth()->user()->id,'1'];
        if(isset($request->q)){
            $keyword = $request->q;
           
            $templates = Template::whereIn('user_id',$ids)
                        ->where('status' ,'1')
                        ->where(function ($templates) use ($keyword) {
                            $templates->where('name', 'LIKE', '%'.$keyword.'%');
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(10);
            $templates->withPath(url()->full());
        }else{
            $templates = Template::whereIn('user_id',$ids)
                    ->where('status','1')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }

        $groups = DB::table('templates')
                ->select(DB::raw('count(id) as total, name'))
                ->whereIn('user_id',$ids)
                ->where('status' ,'1')
                ->groupBy('name')
                ->orderByDesc('total')
                ->get();
        // dd($groups);
       $emailCollection = EmailCollection::where('user_id',auth()->user()->id)->where('status','1')->orderBy('id','ASC')->get();
        return view('templates', ['templates'=>$templates, 'groups'=>$groups,'emailCollection'=>$emailCollection]);
    }

    public function createTemplate(Request $request){
        $template = new Template;
        $template->type = 2;
        $template->user_id = $request->user()->id;
        $template->name = $request->group;
        $template->subject = $request->subject;
        $template->body = $request->body;
        $template->fallback_text = $request->fallback_text;
        $template->status = 1;
        $saved = $template->save();
        if($saved){
            return '1';
        }else{
            return '2';
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
        $unSubTag = "If you don't want to receive such emails in the future, <a href='#'>unsubscribe</a> here";
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

    public function templateMailPreview(Request $request){
        $con = DB::connection('blogsearch');
        $details = $con->table('blog_search_data')
                ->join('blog_author', 'blog_author.domain_id', '=', 'blog_search_data.domain_id')
                ->select('blog_author.author','blog_search_data.title','blog_search_data.website')
                ->first();
        $default_fallback = explode(',','Name|there,Website|site');
        $fallback_text = (!empty($request->fallback_text)) ? explode(',',$request->fallback_text) : $default_fallback;
        $heading = ['Name','Website'];
        $author = 'Name';
        $website = 'Website';
        $csv = [''.$author.'',''.$website.''];
        if(@$request->id !=''){
            $templ = Template::where('id',$request->id)->first();
          
            $subject = $templ->subject;
            $message = $templ->body;
        } else{
            $subject = $request->subject;
            $message = $request->message;
        }
        
        $unSubTag = "If you don't want to receive such emails in the future, <a href='#'>Unsubscribe</a> here";
        
        $send_id ='0';
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
                        $newKeyword[$word] = $csv[$rowNum];
                        
                    }
                    
                   
                    $subject = $this->strReplaceAssoc($newKeyword,$subject);
                    $message = $this->strReplaceAssoc($newKeyword,$message);
                    $message = $message.$unSubTag;
                    
                    $subject = preg_replace('/<[^>]*>/', ' ', $subject);
                    $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 

                }
              
        }

        return view('preview-message',compact('message','subject','send_id'));
        
    }
    public  function strReplaceAssoc(array $replace, $subject) { 
       return str_replace(array_keys($replace), array_values($replace), $subject);
    }

    public function editTemplate(Request $request){
        $template = DB::table('templates')
                    ->where('id', '=', $request->id)
                    ->where('user_id', $request->user()->id)
                    ->first();
        // dd($template);
        return response()->json(['template'=>$template]);
    }

    public function editedTemplate(Request $request){
        $template = Template::find($request->id);
        $template->name = $request->group;
        $template->subject = $request->subject;
        $template->body = $request->body;
        $template->fallback_text = $request->fallback_text;
        $template->save();
                  

        if($template){
            return '1';
        }else{
            return '2';
        }
    }

    public function copyTemplate(Request $request){
        $ids= [auth()->user()->id,'1'];
        $temp = DB::table('templates')
                    ->where('id', '=', $request->id)
                    ->whereIn('user_id',$ids)
                    ->first();

        if(!empty($temp)){
            $subject = $temp->subject;
            $template = new Template;
            $template->type = 2;
            $template->user_id = auth()->user()->id;
            $template->name = $temp->name;
            $template->subject = $subject.'-copy';
            $template->body = $temp->body;
            $template->status = 1;
            $template->save();
            $data = [
                'type'      => 'success',
                'template'      => $template,
            ];
            return response()->json($data, 200);  
        } else{
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);       
        }
           
    }

    public function deleteTemplate(Request $request){
        $template = DB::table('templates')
                    ->where('id', '=', $request->id)
                    ->where('user_id', $request->user()->id)
                    ->update(['status' => 2]);
        
        if($template){
            return '1';
        }else{
            return '2';
        }            
    }

    public function templateGroups(Request $request){
        $ids= [auth()->user()->id,'1'];
        $templates = DB::table('templates')
                    ->whereIn('user_id', $ids)
                    ->where('status', '=' ,1)
                    ->where('name', '=' ,$request->name)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $tid = $request->user()->default_tid;
        return response()->json(['templates'=>$templates, 'tid'=>$tid]);        
    }

    public function setDefault(Request $request){
        $user = DB::table('users')
                ->where('id', $request->user()->id)
                ->update(['default_tid' => $request->tid]);
        if($user){
            return '1';
        }else{
            return '2';
        }            
    }

}
