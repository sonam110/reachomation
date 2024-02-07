<?php

namespace App\Http\Controllers;
// session_start();
use Illuminate\Support\Facades\Storage;
// set_time_limit(0);
// ini_set('memory_limit',-1);
use Cookie;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\OutlookAuthUser;
use App\Models\OutlookInboxMail;
use App\Models\OutlookInboxAttachment;
use App\Models\OutlookDetails;
use App\Models\OutlookAttachmentUpload;
use App\Models\OnedriveUploadList;
use Krizalys\Onedrive\Onedrive;
use App\Models\OutlookBounceMails;

class OutlookMailController extends Controller
{
    private $clientId;
    private $redirectUrl;
    private $clientsecret;
    private $client;
    public function __construct()
    {
        $this->clientId = '92ad5e30-702a-4f36-8377-07b18418d608';
        //$this->redirectUrl = 'https://stage.reachomation.com/outlook_callback';
        $this->redirectUrl = 'http://localhost/reachomation/outlook_callback';
        $this->clientsecret = 'qIW7Q~i4CNJzPWUj5p7BrAwPVA3wlVLgfE-wA';
        $this->client = Onedrive::client($this->clientId);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
          
    }

    public function index(Request $request){
        $outlookauthData = OutlookAuthUser::where('user_id',$request->user()->id)->first();
        $data = array();
        if($outlookauthData){
            return redirect()->to('outlook_list');
        }else{
            $outlook_login_url = $this->client->getLogInUrl([
                          'files.read',
                          'files.read.all',
                          'files.readwrite',
                          'files.readwrite.all',
                          'Mail.Read',
                          'Mail.Read.Shared',
                          'Mail.ReadBasic',
                          'Mail.ReadWrite',
                          'Mail.ReadWrite.Shared',
                          'Mail.Send',
                          'Mail.Send.Shared',
                          'User.Read',
                          'profile',
                          'openid',
                          'offline_access',
                      ], $this->redirectUrl);
            $data['authurl'] = $outlook_login_url;
        }
        return view('outlooklogin',['data'=>$data]);
    }

    public function outlook_callback(Request $request){
        $curl = curl_init();
        if(isset($_GET['code'])){
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id='.$this->clientId.'&redirect_uri='.$this->redirectUrl.'&client_secret='.$this->clientsecret.'&code='.$_GET['code'].'&grant_type=authorization_code',
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/x-www-form-urlencoded'
            ),
          ));

          $response = curl_exec($curl);

          $tokenData = json_decode($response);

          $accessToken = $tokenData->access_token;
          $resfreshToken = $tokenData->refresh_token;
          $endtime = strtotime("+60 minutes");

          //OutlookAuthUser::where('user_id',$request->user()->id)->delete();

          $OutlookAuthUser = new OutlookAuthUser();

          $OutlookAuthUser->user_id = $request->user()->id;
          $OutlookAuthUser->accesstoken = $accessToken;
          $OutlookAuthUser->refreshtoken = $resfreshToken;
          $OutlookAuthUser->expired_in = $endtime;
          $OutlookAuthUser->save();
          $last_id = $OutlookAuthUser->id;
          $userData = self::__curl('GET','https://graph.microsoft.com/v1.0/me',$accessToken);
          $surname = $userData['surname'];
          $givenName = $userData['givenName'];
          $userPrincipalName = $userData['userPrincipalName'];

          $OutlookAuthUser = OutlookAuthUser::where(['user_id'=>$request->user()->id,'id'=>$last_id])->first();
          $OutlookAuthUser->email = $userPrincipalName;
          $OutlookAuthUser->save();
          unset($_SESSION['authuesrid']);
          return redirect()->route('outlook_list')
            ->with('success','We are Fetching Your Mail!');

        }
    }

    public function outlook_listData(Request $request){
        $outlookauthData = OutlookAuthUser::where('user_id',$request->user()->id)->first();
        if($outlookauthData){
            $data = OutlookInboxMail::where('user_id',$request->user()->id)
            ->orderBy('date_timestamp','DESC')
            ->get();
            //$data = OutlookInboxMail::all();
            $alloutlookUser = OutlookAuthUser::where('user_id',$request->user()->id)
                ->orderBy('id','DESC')
                ->get();
            $outlook_login_url = $this->client->getLogInUrl([
                          'files.read',
                          'files.read.all',
                          'files.readwrite',
                          'files.readwrite.all',
                          'Mail.Read',
                          'Mail.Read.Shared',
                          'Mail.ReadBasic',
                          'Mail.ReadWrite',
                          'Mail.ReadWrite.Shared',
                          'Mail.Send',
                          'Mail.Send.Shared',
                          'User.Read',
                          'profile',
                          'openid',
                          'offline_access',
                      ], $this->redirectUrl);
            //$data['authurl'] = $outlook_login_url;
            //echo "<PRE>";print_R($alloutlookUser);die;
            return view('outlook_list',['maildata'=>$data,'allOutlookUser'=>$alloutlookUser,'authurl'=>$outlook_login_url]);
        }else{
            return redirect()->route('outlook_mail');
        }
    }

    public function listing_data(Request $request){
        //echo "<PRE>";print_R($_POST);die;
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue = $_POST['search']['value']; // Search value
        $authid = $_POST['user'];
        //\Cookie::forever('authuesrid', $authid);
        $_SESSION['authuesrid'] = $authid;
        //Cookie::forever('name', 'value');
        $searchQuery = "";
        if($searchValue !== ''){
            $searchQuery .= " and (mail_from like '%".$searchValue."%' or 
                            subject like '%".$searchValue."%' or 
                            Date like'%".$searchValue."%' ) ";
        }
        $records = OutlookInboxMail::where(['user_id'=>$request->user()->id,'auth_id'=>$authid])
            ->get();
        //echo "<PRE>";print_R($records);die;
        $totalRecords = $records->count();
        $filterrecord = DB::select('select count(*) as allcount from outlook_inbox_mails WHERE auth_id = '.$authid.' '.$searchQuery);
        //echo "<PRE>";print_R($filterrecord);die;
        $totalRecordwithFilter = $filterrecord[0]->allcount;

        $getData = DB::select("select * from outlook_inbox_mails WHERE  auth_id = ".$authid." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);
        $data = array();
        $k = 1;
        foreach($getData as $d){
            $removestr = str_replace('Email','',$d->mail_from);
            $removestr1 = str_replace('<','',$removestr);
            $removestr2 = str_replace('>','',$removestr1);
            $data[] = array(
             "id" => $k,
             "mail_from"=>$removestr2,
             "subject"=>$d->subject,
             "Date"=>date('Y-m-d',strtotime($d->Date)),
             "action"=>"<a href='javascript:void(0);' onclick=DeleteMail('".$d->messageid."')>Delete</a> | <a href='".url('view_outlook_mail/'.$d->id)."'> View </a>"
           );
           $k++;
        }
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);

    }

    public function mailGetList($type,$authid='',Request $request){
        if(!empty($authid)){
            $outlookuser = OutlookAuthUser::where(['user_id'=>$request->user()->id,'id'=>$authid])->get()->toArray();
        }else{
            $outlookuser = OutlookAuthUser::get()->toArray();
        }
        //echo "<PRE>";print_R($outlookuser);die;
        if(!empty($outlookuser)){
            foreach($outlookuser as $v=>$user){
                $accessToken = self::getToken($user['user_id'],$user['id']);
                $oulookInboxMail = OutlookInboxMail::selectRaw('max(mailcreate_date) , DATE')
                    ->where(['user_id'=>$user['user_id'],'auth_id'=>$user['id']])
                    ->first();
                //echo "<PRE>";print_R($oulookInboxMail);die;
                if(trim($oulookInboxMail->DATE) !== ''){
                    $date = $oulookInboxMail->DATE;
                    //$timestamp = strtotime($date);
                    $url = 'https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages/?$filter=receivedDateTime ge '.$date;
                }else{
                    $url = 'https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages/';
                }

                //echo $url;die;

                $finalData = array();
          
                $msgData = self::__curl('GET',$url,$accessToken);
                //echo "<PRE>";print_R($msgData);die;
                $finalData[] = $msgData;
                  
                $flag = false;
                if(isset($msgData['@odata.nextLink'])){
                    while(!$flag){
                      $accessToken = self::getToken($user['user_id'],$user['id']);
                      //echo $accessToken;die;
                      $nextData = self::__curl('GET',$msgData['@odata.nextLink'],$accessToken);
                      //echo "<PRE>";print_R($nextData);
                      if(!empty($nextData)){ 
                        $finalData[] = $nextData;
                      }
                      if(!isset($nextData["@odata.nextLink"])){
                        //echo "Fdfsdfd";die;
                        $flag = true;
                      }
                    }
                }
                //echo "<PRE>";print_R($finalData);die;
                if(!empty($finalData[0])){
                    foreach($finalData as $value1){
                        foreach($value1['value'] as $msg){
                              //echo "<PRE>";print_R($msg);die;
                              $mesId = $msg['id'];
                              $subject = $msg['subject'];
                              $body = $msg['body']['content'];
                              $from = $msg['from']['emailAddress']['address'];
                              $date = $msg['createdDateTime'];
                              $createdat = date('Y-m-d',strtotime($date));
                              $hasAttachments = $msg['hasAttachments'] ? $msg['hasAttachments'] : 0;
                              //$hasAttachments1 = $msg['hasAttachments'] ? $msg['hasAttachments'] : 0;
                              /*if($hasAttachments == 0){
                                if (strpos($body, '<img src="cid') !== false) { 
                                  $hasAttachments1 = 1;
                                }else{
                                  $hasAttachments1 = 0;
                                }
                              }*/

                              $OutlookInboxMail = OutlookInboxMail::where(['messageid'=> $mesId,'user_id'=>$user['user_id'],'auth_id'=>$user['id']])->first();
                              if(!$OutlookInboxMail){
                                //$user['user_id'],$user['id']
                                $OutlookInboxMail = new OutlookInboxMail();
                                $OutlookInboxMail->user_id = $user['user_id'];
                                $OutlookInboxMail->auth_id = $user['id'];
                                $OutlookInboxMail->messageid = $mesId;
                                $OutlookInboxMail->Date = $date;
                                $OutlookInboxMail->mailcreate_date = $createdat;
                                $OutlookInboxMail->date_timestamp = date('Y-m-d H:m:s',strtotime($date));
                                $OutlookInboxMail->mail_from = $from;
                                $OutlookInboxMail->subject = $subject;
                                $OutlookInboxMail->body = htmlentities($body);
                                $OutlookInboxMail->has_attchment = $hasAttachments;
                                $OutlookInboxMail->save();

                                $last_id = $OutlookInboxMail->id;
                                //if($hasAttachments1 !== 0 ){
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                    CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/messages/".$mesId."/attachments",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_CUSTOMREQUEST => "GET",
                                    CURLOPT_HTTPHEADER => array(
                                      "Prefer: IdType=\"ImmutableId\"",
                                      "Authorization: ".$accessToken
                                    ),
                                  ));

                                  $response = json_decode(curl_exec($curl),true);
                                  
                                  curl_close($curl);

                                  if(!empty($response['value'])){
                                    foreach($response['value'] as $attch){
                                        $hasAttachments++;
                                        $attachmentId = $attch['id'];
                                        $fileName = $attch['name']; 
                                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                        $onlyname =  basename($fileName, ".".$extension); 
                                        $newFilename = str_replace(' ','_',$onlyname).'-'.time().'.'.$extension;
                                        $messagepath = 'outlookattachment';
                                        $filename = $messagepath.'/'.$newFilename;
                                        $contents = base64_decode($attch['contentBytes']);
                                        $path = Storage::disk('s3')->put($filename, $contents,'public-read');
                                        $path = Storage::disk('s3')->url($filename);
                                        /*if(! File::isDirectory(public_path('outlookattachment'))){
                                            File::makeDirectory(public_path('outlookattachment'), 0777, true, true);

                                        }
                                        $messagepath = public_path('outlookattachment/'.$newFilename);
                                        $contents = base64_decode($attch['contentBytes']);
                                        $myfile = fopen($messagepath,"w");
                                        fwrite($myfile,$contents);
                                        fclose($myfile);*/


                                        $OutlookInboxAttachment = new OutlookInboxAttachment();

                                        $OutlookInboxAttachment->mail_id = $last_id;
                                        $OutlookInboxAttachment->attchment_id = $attachmentId;
                                        $OutlookInboxAttachment->filename = $newFilename;
                                        $OutlookInboxAttachment->save();
                                    }
                                  }
                                //}

                            }
                            
                        }
                    }
                    if($type == 1){
                        return redirect()->route('outlook_list')
                            ->with('success',"Mail Fetch Successfully");
                    }

                }else{
                    if($type == 1){
                        return redirect()->route('outlook_list')
                            ->with('error',"No new mail found");
                    }
                }
            }
        }else{
            if($type == 1){
                return redirect()->route('outlook_mail');
            }
        }
        
    }

    public function forceDownloadOulook($id){
        $fileData = OutlookInboxAttachment::where('id',$id)->first();
        $file_name = $fileData->filename;
        $key = 'outlookattachment/'.$file_name;
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = env('AWS_BUCKET');

        $command = $client->getCommand('GetObject', [
             'Bucket' => $bucket,
             'Key' => $key
        ]);
        $request = $client->createPresignedRequest($command, '+20 minutes');
        $presignedUrl = (string)$request->getUri();
        //$file_url = public_path('outlookattachment/'.$filename);
        ob_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header("Content-disposition: attachment; filename=\"".$file_name."\""); 
        ob_clean();
        flush();
        readfile($presignedUrl);
        exit;
    }

    public function sendMail(Request $request){
        $alloutlookUser = OutlookAuthUser::where('user_id',$request->user()->id)
        ->orderBy('id','DESC')
        ->get();
        //echo "<PRE>";print_R($alloutlookUser);die;
        return view('send_outlook_mail',['allOutlookUser'=>$alloutlookUser]);
    }

    public function mailProcess(Request $request){
        
        $accessToken = self::getToken($request->user()->id,$request->from_mail);
        /*date_default_timezone_set("Asia/Calcutta"); 
            $formateDate = date('Y-m-d');
            $formateDate = $formateDate.'T'.date('h:m:s').'Z';

        $bounceData = self::__curl('GET','https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages?$search="from:postmaster"&?$filter=receivedDateTime ge '.$formateDate,$accessToken);
        //echo "<PRE>";print_R($bounceData);die;
        $bounceItem = array();
        if(!empty($bounceData)){
            
            foreach($bounceData['value'] as $bounceI){
                array_push($bounceItem,$bounceI['id']);
            }
               
        }

        echo "<PRE>";print_R($bounceItem);die;*/


        $outlookuser = OutlookAuthUser::where(['user_id'=>$request->user()->id,'id'=>$request->from_mail])->first();
        $userId = $request->user()->id;
        $sendemail = $outlookuser->email;
        $auth_id = $request->from_mail;
        //echo $sendemail;die;
        $toemailArr = $request->email;
        $ccemail = $request->ccemail;
        $bccemail = $request->bccemail;
        $toFromForm = explode(",", $request->email);
        $thisTo = array();
        foreach ($toFromForm as $eachTo) {
            $email = filter_var($eachTo, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->route('send_outlook_mail')
                        ->with('error',$eachTo." is not a valid email address");
            }
            if(strlen(trim($eachTo)) > 0) {
                $thisTo[] = array(
                    "EmailAddress" => array(
                        "Address" => trim($eachTo)
                    )
                );
            }
        }
        $thisCc = array();
        if(trim($request->ccemail) !== ''){
            $toFromCC = explode(",", trim($request->ccemail));
            //echo "<PRE>";print_R($toFromCC);die;
            if(isset($toFromCC)){
                foreach ($toFromCC as $eachToC) {
                    $email1 = filter_var($eachToC, FILTER_SANITIZE_EMAIL);
                    if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
                        return redirect()->route('send_outlook_mail')
                                ->with('error',$email1." is not a valid email address");
                    }
                }
            }
            if(strlen(trim($eachToC)) > 0) {
                $thisCc[] = array(
                    "EmailAddress" => array(
                        "Address" => trim($eachToC)
                    )
                );
            }
        }
        
        $thisBcc = array();

        if(trim($request->bccemail) !== ''){
            $toFromBcc = explode(",", $request->bccemail);

            if(count($toFromBcc) !== 0){
                foreach ($toFromBcc as $eachToB) {
                    $email2 = filter_var($eachToB, FILTER_SANITIZE_EMAIL);
                    if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
                        return redirect()->route('send_outlook_mail')
                                ->with('error',$email2." is not a valid email address");
                    }
                }
            }
            if(strlen(trim($eachToB)) > 0) {
                $thisBcc[] = array(
                    "EmailAddress" => array(
                        "Address" => trim($eachToB)
                    )
                );
            }
        }

        $attachments = array();
        $filenamearr = array();
        if($request->hasfile('formFile')){
            foreach($request->file('formFile') as $file){
                //echo $file->getClientOriginalExtension();die;
                $removearr = explode(',',$_POST['remove_val']);
                if(!in_array($file->getClientOriginalName(), $removearr)){
                    $fullpath = $file->getClientOriginalName();
                    $filename = pathinfo($fullpath, PATHINFO_FILENAME);
                    $name = $filename.time().rand(1,100).'.'.$file->getClientOriginalExtension();
                    $path = Storage::disk('s3')->put('outlookupload', $file);
                    $key = $path;
                    $pathData = explode("/",$path);
                    $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
                    $bucket = env('AWS_BUCKET');

                    $command = $client->getCommand('GetObject', [
                         'Bucket' => $bucket,
                         'Key' => $key
                    ]);
                    $request12 = $client->createPresignedRequest($command, '+20 minutes');
                    $presignedUrl = (string)$request12->getUri();
                    //$file->move(public_path('outlookupload'), $name);
                    //$filepath = public_path('outlookupload/'.$name);

                    /*$file->storeAs('/public/outlookupload', $name);
                    $filepath = 'public/storage/outlookupload/'.$name;*/
                    $content = base64_encode(file_get_contents($presignedUrl));
                    $attachment = array(
                        "@odata.type" => "#microsoft.graph.fileAttachment",
                        "name" => $pathData[1],
                        "contentType" => "text/plain",
                        "contentBytes" => $content
                    );
                    array_push($attachments, $attachment);
                    array_push($filenamearr,$pathData[1]);
                }
            }
        }

        $doc = new \DOMDocument();
        if(!empty($_POST['body'])){
            $doc->loadHTML($_POST['body']);
            $imageTags = $doc->getElementsByTagName('img');
            if(!empty($imageTags)){
                foreach($imageTags as $tag) {
                    $imagepaths = '';
                    $imagepaths = $tag->getAttribute('src');
                    /*$img = 'public/storage/outlookupload/'.basename($imagepaths);
                    file_put_contents($img, file_get_contents($imagepaths));*/
                    $newsrc = 'cid:'.pathinfo($imagepaths, PATHINFO_FILENAME);
                    $tag->setAttribute('src',$newsrc); 
                    $content = base64_encode(file_get_contents($imagepaths));
                    /*$attachment = array(
                        "@odata.type" => "#microsoft.graph.fileAttachment",
                        "name" => basename($imagepaths),
                        "contentType" => "text/plain",
                        "contentBytes" => $content,
                        "contentId" => pathinfo($imagepaths, PATHINFO_FILENAME),
                        "isInline" => true  
                    );
                    array_push($attachments, $attachment);
                    array_push($filenamearr, basename($imagepaths));*/
                    
                    $htmlString = $doc->saveHTML();

                }
            }
        }
        

        $request = array(
            "message" => array(
                "subject" =>$_POST['subject'],
                "body" => array(
                    "ContentType" => "HTML",
                    "Content" => utf8_encode($_POST['body'])
                ),
                "attachments" => $attachments,
                "toRecipients" => $thisTo,
                "ccRecipients" => $thisCc,
                "bccRecipients" => $thisBcc

            )
        );

        $request = json_encode($request);

        $headers = array(
            "User-Agent: php-tutorial/1.0",
            "Authorization: Bearer ".$accessToken,
            "Accept: application/json",
            "Content-Type: application/json",
            "Content-Length: ". strlen($request)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.microsoft.com/v1.0/me/sendmail');
        if($request != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($headers != null) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        //echo "<PRE>";print_R($response);die;
        $data = json_decode($response);
    
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($http_code == '202'){
            date_default_timezone_set("Asia/Calcutta"); 
            $formateDate = date('Y-m-d');
            $formateDate = $formateDate.'T'.date('h:m:s').'Z';
            
            sleep(10);
            
            $bounceData = self::__curl('GET','https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages?$search="from:postmaster"&?$filter=receivedDateTime ge '.$formateDate,$accessToken);
            //echo "<PRE>";print_R($bounceData);die;
            $bounceItem = array();
            $bounceItem = array();
            if(!empty($bounceData)){
                
                foreach($bounceData['value'] as $bounceI){
                    array_push($bounceItem,$bounceI['id']);
                }
                   
            }
            
            //echo "<PRE>";print_R($bounceItem);
            $getFolderItem = self::__curl('GET','https://graph.microsoft.com/v1.0/me/mailfolders/SentItems/messages?filter=receivedDateTime ge '.$formateDate,$accessToken);
            //echo "<PRE>";print_R($getFolderItem);die;
            $sendItem = array();
            

            $OutlookDetails = new OutlookDetails();
            $OutlookDetails->user_id = $userId;
            $OutlookDetails->auth_id = $auth_id;
            $OutlookDetails->from_mail = $sendemail;
            $OutlookDetails->to_mail = $_POST["email"];
            $OutlookDetails->cc_mail = $_POST["ccemail"];
            $OutlookDetails->bcc_mail = $_POST["bccemail"];
            $OutlookDetails->subject = $_POST['subject'];
            $OutlookDetails->body = htmlentities($_POST['body']);
            $OutlookDetails->has_attachment = count($attachments);
            $OutlookDetails->save();
            $last_id = $OutlookDetails->id;
            //echo "<PRE>";print_R($getFolderItem);die;
            if(!empty($bounceData)){
                foreach ($toFromForm as $eachTo) {
                    foreach($bounceData['value'] as $bounceG){
                        if(trim(str_replace('Undeliverable:','',$bounceG['subject'])) == trim($_POST['subject'])){
                            foreach($bounceG['toRecipients'] as $emailvalarr){
                                if($emailvalarr['emailAddress']['address'] == $eachTo){
                                    $OutlookBounceMails = new OutlookBounceMails();
                                    $OutlookBounceMails->user_id = $userId;
                                    $OutlookBounceMails->auth_id = $auth_id;
                                    $OutlookBounceMails->mail_id = $last_id;
                                    $OutlookBounceMails->messageId = $bounceG['id'];
                                    $OutlookBounceMails->from_mail = $sendemail;
                                    $OutlookBounceMails->subject = $_POST['subject'];
                                    $OutlookBounceMails->body = htmlentities($_POST['body']);
                                    $OutlookBounceMails->save();
                                }
                            }
                        }
                    }
                    
                }
            }
            foreach ($toFromForm as $eachTo) {
                //foreach($getFolderItem as $senditem){
                
                    /*if(!empty($getFolderItem)){
                        foreach($getFolderItem['value'] as $sendI){
                            echo "SENDDATA======><PRE>";print_R($sendI);die;
                            if($sendI['subject'] == $_POST['subject']){
                                echo "fdgfdgfg";
                                    foreach($sendI['toRecipients'] as $emailvalarr){
                                        echo "<PRE>";print_R($emailvalarr);
                                        if($emailvalarr['emailAddress']['address'] == $eachTo){
                                             echo $sendI['id'];
                                             echo "<PRE>";print_R($bounceItem);die;
                                             if(in_array($sendI['id'],$bounceItem)){
                                                $OutlookBounceMails = new OutlookBounceMails();
                                                $OutlookBounceMails->user_id = $userId;
                                                $OutlookBounceMails->auth_id = $auth_id;
                                                $OutlookBounceMails->mail_id = $last_id;
                                                $OutlookBounceMails->messageId = $sendI['id'];
                                                $OutlookBounceMails->from_mail = $sendemail;
                                                $OutlookBounceMails->subject = $_POST['subject'];
                                                $OutlookBounceMails->body = htmlentities($_POST['body']);
                                                $OutlookBounceMails->save();
                                            }
                                        }
                                    }
                                   
                                }
                        }
                    }*/
                    
                //}

            }


            if(!empty($filenamearr)){
                foreach($filenamearr as $fl){
                    $OutlookAttachmentUpload = new OutlookAttachmentUpload();
                    $OutlookAttachmentUpload->mail_id = $last_id;
                    $OutlookAttachmentUpload->filename = $fl;
                    $OutlookAttachmentUpload->save();
                }
                
            }
            return redirect()->route('send_outlook_mail')
            ->with('success','Mail Send Successfully!');
        }else{
            return redirect()->route('send_outlook_mail')
            ->with('error','Mail not send');
        }
    }

    public function onedriveUpload(Request $request){
        $accessToken = self::getToken($request->user()->id,$request->from_mail);
        $filepatharr = array();
        $filenamearr = array();
        if($request->hasfile('formFile')){
            foreach($request->file('formFile') as $file){
                $fullpath = $file->getClientOriginalName();
                $filename = pathinfo($fullpath, PATHINFO_FILENAME);
                $name = $filename.time().rand(1,100).'.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->put('outlookupload', $file);
                $key = $path;
                $pathData = explode("/",$path);
                $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
                $bucket = env('AWS_BUCKET');

                $command = $client->getCommand('GetObject', [
                     'Bucket' => $bucket,
                     'Key' => $key
                ]);
                $request12 = $client->createPresignedRequest($command, '+20 minutes');
                $presignedUrl = (string)$request12->getUri();

                array_push($filepatharr,$presignedUrl);
                array_push($filenamearr,$pathData[1]);
            }
        }
        $weblinkArr = array();
        $insertIDArr = array();
        $resultArr = array();
        $maxuploadsize = 1024 * 1024 * 4;
        if(!empty($filepatharr)){
            for($p=0;$p<count($filepatharr);$p++){
                $graph = new \Microsoft\Graph\Graph();
                $graph->setAccessToken($accessToken);
                /*if (filesize($filepatharr[$p]) < $maxuploadsize) {
                    $resp = $graph->createRequest("PUT", "/me/drive/root:/Attachments/" . $filenamearr[$p] . ":/content")->upload($filepatharr[$p]);
                }
                else {*/
                    $uploadSession = $graph->createRequest("POST", "/me/drive/root:/Attachments/"  . $filenamearr[$p] . ":/createUploadSession")
                    ->addHeaders(["Content-Type" => "application/json"])
                    ->attachBody([
                        "item" => [
                            "@microsoft.graph.conflictBehavior" => "replace"
                        ]
                    ])
                    ->setReturnType(\Microsoft\Graph\Model\UploadSession::class)
                    ->execute();

                    $file = $filepatharr[$p];
                    //echo $file;die;
                    $curlSession = curl_init();
                    curl_setopt($curlSession, CURLOPT_URL, $file);
                    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

                    $buffer = curl_exec($curlSession);
                    
                    $fileSize = curl_getinfo($curlSession, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                    curl_close($curlSession);
                    $handle = fopen($file, 'rb');
                    //$fileSize = fileSize($file);
                    $chunkSize = 1024*1024*2;
                    $prevBytesRead = 0;
                    while (!feof($handle)) {
                        $bytes = fread($handle, $chunkSize);
                        $bytesRead = ftell($handle);

                        $resp = $graph->createRequest("PUT", $uploadSession->getUploadUrl())
                            ->addHeaders([
                                'Connection' => "keep-alive",
                                'Content-Length' => ($bytesRead-$prevBytesRead),
                                'Content-Range' => "bytes " . $prevBytesRead . "-" . ($bytesRead-1) . "/" . $fileSize,
                            ])
                            ->setReturnType(\Microsoft\Graph\Model\UploadSession::class)
                            ->attachBody($bytes)
                            ->execute();
                         //echo "<PRE>";print_R($resp);die;   
                        $prevBytesRead = $bytesRead;
                        //echo "<PRE>";print_r($resp);exit;
                    }
                //}
                $data = json_encode($resp);
                $data = json_decode($data);
                if(!empty($data)){
                    $fileId = $data->id;
                    $curlmess = curl_init();
                    curl_setopt_array($curlmess, array(
                    CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/items/'.$fileId.'/createLink',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{"type":"view","scope":"anonymous"}',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$accessToken,
                        'Content-Type: application/json'
                    ),
                  ));
                   $response_mess = curl_exec($curlmess);
                   $msgData = json_decode($response_mess, true);
                   $sharelink = $msgData['link']['webUrl'];

                   $OnedriveUploadList = new OnedriveUploadList();
                   $OnedriveUploadList->insertedfileId = $fileId;
                   $OnedriveUploadList->filename = $filenamearr[$p];
                   $OnedriveUploadList->web_link_view = $sharelink;
                   $OnedriveUploadList->save();
                   array_push($weblinkArr,$sharelink);
                   array_push($insertIDArr,$fileId);
                }else{
                    $resultArr['status'] = 0;
                    echo json_encode($resultArr);exit();
                }
            }
            $resultArr['status'] = 1;
            $resultArr['weblinkArr'] = $weblinkArr;
            $resultArr['insertIDArr'] = $insertIDArr;
            $resultArr['filenamearr'] = $filenamearr;
            $resultArr['filepatharr'] = $filepatharr;
            echo json_encode($resultArr);exit();
        }else{
            $resultArr['status'] = 0;
            echo json_encode($resultArr);exit();
        }

    }

    public function viewOutlookDetail($mailid,Request $request){
        $data = OutlookInboxMail::where('id',$mailid)->first();
        return view('outlook_detail_view',['maildata'=>$data]);
    }

    public function deleteOutlookMail(Request $request){
        $accessToken = self::getToken($request->user()->id,$_POST['authuser']);
        $curlmess = curl_init();
        curl_setopt_array($curlmess, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/messages/'.$_POST['mailid'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$accessToken,
            'Content-Type: application/json'
        ),
      ));
      //$response_mess = curl_exec($curlmess);
      //$OutlookInboxMail = OutlookInboxMail::where('messageid',$_POST['mailid'])->delete();
      
      echo "1";exit();
    }

    public function __curl($method,$url,$accessToken){
        //echo "<br/>".str_replace(' ','%20',$url);die;
        $curlmess = curl_init();
        curl_setopt_array($curlmess, array(
        CURLOPT_URL => str_replace(' ','%20',$url),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$accessToken,
            'Content-Type: application/json'
        ),
      ));
      $response_mess = curl_exec($curlmess);
      //echo "<PRE>";print_R($response_mess);die;
      $msgData = json_decode($response_mess, true);
      return $msgData;
    }

    public function getToken($userid,$authid){
        $outlookuser = OutlookAuthUser::where(['user_id'=>$userid,'id'=>$authid])->first();
        if($outlookuser){
            $accessToken = $outlookuser->accesstoken;
            $refreshtoken = $outlookuser->refreshtoken;
            $expired_time = $outlookuser->expired_in;
            $time = time();
            if($time >= $expired_time){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'client_id='.$this->clientId.'&redirect_uri='.$this->redirectUrl.'&client_secret='.$this->clientsecret.'&refresh_token='.$refreshtoken.'&grant_type=refresh_token',
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
                ),
              ));
            $response = curl_exec($curl);
            $tokenData = json_decode($response);

            $accessToken = $tokenData->access_token;
            $resfreshToken = $tokenData->refresh_token;
            $endtime = strtotime("+60 minutes");

            $outlookuser->accesstoken = $accessToken;
            $outlookuser->refreshtoken = $resfreshToken;
            $outlookuser->expired_in = $endtime;
            $outlookuser->save();

            return $accessToken;

            }else{
                return $accessToken;
            }
        }
    }
}
