<?php

namespace App\Http\Controllers;
// use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
// session_start();
use DOMDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\google_auth_user;
use App\Models\GmailDetails;
use App\Models\GmailAttachmentUpload;
use App\Models\GdriveUploadList;
use App\Models\GmailInboxMail;
use App\Models\GmailInboxAttachment;
use App\Models\GmailBounceMails;
use Google_Client; 

class MailController extends Controller
{
    private $client;
    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Gmail Api');
        $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
        $this->client->setAuthConfig(__DIR__ . '/../../../credentials.json');
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');   
          
    }


    public function index(Request $request){
        $gmailauthData = google_auth_user::where('user_id',$request->user()->id)->first();
        $data = array();
        if($gmailauthData){
            return redirect()->to('gmail_list');
        }else{
            $google_login_url = self::getauthurl();
            $data['authurl'] = $google_login_url;
        }
        return view('googlelogin',['data'=>$data]);
    }

    public function getauthurl(){
        $authUrl = $this->client->createAuthUrl();
        return $authUrl; 
    }

    public function gmail_callback(Request $request){
        if(!empty($_GET['code'])){
            if ($this->client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($this->client->getRefreshToken()) {
                    $accessToken = $this->client-> fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());

                    //google_auth_user::where('user_id',$request->user()->id)->delete();
                    $gmailauthData = new google_auth_user();
                    $gmailauthData->user_id = $request->user()->id;
                    $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                    $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                    $gmailauthData->expired_in = $accessToken['expires_in'];
                    $gmailauthData->save();
                    $last_id = $gmailauthData->id;

                } else {
                    $accessToken = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $this->client->setAccessToken($accessToken);
                    //echo "<PRE>";print_R($accessToken);die;
                    $gmailauthData = new google_auth_user();
                    $gmailauthData->user_id = $request->user()->id;
                    $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                    $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                    $gmailauthData->expired_in = $accessToken['expires_in'];
                    $gmailauthData->save();
                    $last_id = $gmailauthData->id;

                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                }

                $user = 'me';

                $service = new \Google_Service_Gmail($this->client);
                $oauth2 = new  \Google_Service_Oauth2($this->client);

                $userInfo = $oauth2->userinfo_v2_me->get();
                $array = json_decode(json_encode($userInfo), true);
                $picture = $userInfo->picture;
                $email = $userInfo->email;
                $gmailauthData = google_auth_user::where(['user_id'=>$request->user()->id,'id'=>$last_id])->first();
                $gmailauthData->email = $email;
                $gmailauthData->picture = $picture;
                $gmailauthData->save();

            }
            unset($_SESSION['gmailauthuesrid']);
            return redirect()->route('gmail_list')
            ->with('success','We are Fetching Your Mail!');
            //return redirect()->to('gmail_list');
        }
    }

    public function gmail_listData(Request $request){

        $gmailauthData = google_auth_user::where('user_id',$request->user()->id)->first();
        if($gmailauthData){
            $allGmailUser = google_auth_user::where('user_id',$request->user()->id)
            ->orderBy('id','DESC')
            ->get();
            $data = GmailInboxMail::where('user_id',$request->user()->id)
            ->orderBy('date_timestamp','DESC')
            ->get();
            $google_login_url = self::getauthurl();
            //$data['authurl'] = $google_login_url;
            return view('gmail_list',['maildata'=>$data,'allGmailUser'=>$allGmailUser,'authurl'=>$google_login_url]);
        }else{
            return redirect()->route('mail');
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
        //$_COOKIE['authuesrid'] = $authid;
        //$cookie = \Cookie::forever('authuesrid', $authid);
        $_SESSION['gmailauthuesrid'] = $authid;
        //Cookie::forever('name', 'value');
        $searchQuery = "";
        if($searchValue !== ''){
            $searchQuery .= " and (mail_From like '%".$searchValue."%' or 
                            subject like '%".$searchValue."%' or 
                            Date like'%".$searchValue."%' ) ";
        }
        $records = GmailInboxMail::where(['user_id'=>$request->user()->id,'auth_id'=>$authid])
            ->get();
        //echo "<PRE>";print_R($records);die;
        $totalRecords = $records->count();
        $filterrecord = DB::select('select count(*) as allcount from gmail_inbox_mails WHERE auth_id = '.$authid.' '.$searchQuery);
        //echo "<PRE>";print_R($filterrecord);die;
        $totalRecordwithFilter = $filterrecord[0]->allcount;

        $getData = DB::select("select * from gmail_inbox_mails WHERE  auth_id = ".$authid." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);
        $data = array();
        $k = 1;
        foreach($getData as $d){
            $removestr = str_replace('Email','',$d->mail_From);
            $removestr1 = str_replace('<','',$removestr);
            $removestr2 = str_replace('>','',$removestr1);
            $data[] = array(
             "id" => $k,
             "mail_from"=>$removestr2,
             "subject"=>$d->subject,
             "Date"=>date('Y-m-d',strtotime($d->Date)),
             "action"=>"<a href='javascript:void(0);' onclick=DeleteMail('".$d->messageid."')>Delete</a> | <a href='".url('view_gmail/'.$d->id)."'> View </a>"
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
            $gmailauthData = google_auth_user::where(['user_id'=>$request->user()->id,'id'=>$authid])->get()->toArray();
        }else{
            $gmailauthData = google_auth_user::get()->toArray();
        }
        //echo "<PRE>";print_R($gmailauthData);die;
        if(!empty($gmailauthData)){
            foreach($gmailauthData as $v => $user1){
                $accessToken = $user1['accesstoken'];
                //echo $accessToken;die;
                $fromMail = $user1['email'];
                $this->client->setAccessToken($accessToken);
                $accessToken = self::checkToekn($user1['user_id'],$user1['id']);
                $service = new \Google_Service_Gmail($this->client);
                $user = 'me';
                $GmailInboxMail = GmailInboxMail::selectRaw('max(mailcreate_date) , DATE')
                        ->where(['user_id'=>$user1['user_id'],'auth_id'=>$user1['id']])
                        ->first();
                if(trim($GmailInboxMail->DATE) !== ''){
                    $date = $GmailInboxMail->DATE;
                    $timestamp = strtotime($date);
                    $messages = self::listMessages($service, $user, [
                        'q' => "category:primary after:".$timestamp
                    ]);
                }else{
                    $messages = self::listMessages($service, $user, [
                        'q' => "category:primary"
                    ]);
                }
                //echo "<PRE>";print_R($messages);die;
                $k = 0;
                if(isset($messages)){
                    foreach ($messages as $message) {
                        if($k == 100){
                            $accessToken = self::checkToekn($user1['user_id'],$user1['id']);
                            $service = new \Google_Service_Gmail($this->client);
                        }
                        $messageId = $message->getId();
                        
                        $mailData = GmailInboxMail::where(['messageid'=>$messageId,'user_id'=>$user1['user_id'],'auth_id'=>$user1['id']])->first();
                        
                        if(!$mailData){
                            $msgObj = self::getMessage($service, $user, $messageId);

                            $headerArr = self::getHeaderArr($msgObj->getPayload()->getHeaders());
                            //echo "<PRE>";print_r($headerArr);die;
                            if(isset($headerArr['Delivered-To'])){
                                $deliverdto = $headerArr['Delivered-To'];
                            }else{
                                $deliverdto = $headerArr['To'];
                            }
                            
                            $Date = $headerArr['Date'];
                            $createdat = date('Y-m-d',strtotime($Date));
                            $Subject = $headerArr['Subject'];
                            //echo $Subject;die;
                            $mail_from = $headerArr['From'];
                            //echo "<PRE>";print_R($msgObj->getPayload());die;
                            //$bodyArr = $msgObj->getPayload()->getParts();
                            $bodyArr = $msgObj->getPayload();
                            //echo "<PRE>";print_r($bodyArr);die;
                            $body = '';
                            $filenameArr = array();
                            $attachmentId = array();
                            if(!empty($bodyArr)){
                                

                                if(isset($bodyArr->parts)){
                                    //echo "<PRE>";print_R($bodyArr->parts);die;
                                    foreach($bodyArr->parts as $part){
                                        
                                        if(isset($part->parts)){
                                            foreach($part->parts as $p1){
                                                if($p1['mimeType'] == 'text/html'){
                                                    $body = base64_decode(strtr($p1->body->data, '-_', '+/'));
                                                }
                                            }
                                        } //else{
                                            if(trim($part->body->attachmentId) !== ''){
                                                array_push($filenameArr,$part->filename);
                                                array_push($attachmentId,$part->body->attachmentId);
                                            }
                                            if(trim($part->body->data) !== ''){
                                                $body = base64_decode(strtr($part->body->data, '-_', '+/'));
                                            }

                                       // }
                                    }
                                }else{
                                    $body = base64_decode(strtr($bodyArr->body->data, '-_', '+/'));
                                }

                                //echo "<PRE>";print_R($attachmentId);die;
                                

                                $final_body1 = htmlentities($body, ENT_NOQUOTES, 'UTF-8');
                                

                                $final_subject = htmlentities($Subject, ENT_NOQUOTES, 'UTF-8');
                                

                                $final_date = htmlentities($Date, ENT_NOQUOTES, 'UTF-8');
                                

                                $mailfrom = str_replace('"',"",$mail_from);
                                $mailfrom1 = str_replace("'","",$mailfrom);
                                
                                $mailData = new GmailInboxMail();
                                $mailData->user_id = $user1['user_id'];
                                $mailData->auth_id = $user1['id'];
                                $mailData->messageid = $messageId;
                                $mailData->deliver_to = $deliverdto;
                                $mailData->Date = $final_date;
                                $mailData->mailcreate_date = $createdat;
                                $mailData->date_timestamp = date('Y-m-d H:m:s',strtotime($Date));
                                $mailData->mail_From = $mailfrom1;
                                $mailData->subject = $final_subject;
                                $mailData->body = $final_body1;
                                $mailData->save();
                                
                                $last_id = $mailData->id;
                                
                                if(count($attachmentId) !== 0){
                                    for($k=0;$k<count($attachmentId);$k++){
                                        $data = $service->users_messages_attachments->get($user, $messageId, $attachmentId[$k]);
                                        $data = $data->data;
                                        $data = strtr($data, array('-' => '+', '_' => '/'));
                                        $messagepath = 'gmailattchment/'.$messageId;

                                        
                                        $updatefilename = str_replace(' ','_',$filenameArr[$k]);
                                        $filename = $messagepath.'/'.$updatefilename;

                                        $path = Storage::disk('s3')->put($filename, base64_decode($data),'public-read');
                                        $path = Storage::disk('s3')->url($filename);

                                        $GmailInboxAttachment = new GmailInboxAttachment();
                                        $GmailInboxAttachment->mail_id = $last_id;
                                        $GmailInboxAttachment->attchment_id = $attachmentId[$k];
                                        $GmailInboxAttachment->filename = $updatefilename;
                                        $GmailInboxAttachment->save();
                                        
                                    }
                                }
                            }
                        }

                        $k++;
                    }
                    if($type == 1){
                        return redirect()->route('gmail_list')
                                        ->with('success',"Mail Fetch Successfully");
                    }
                }else{
                    if($type == 1){
                        return redirect()->route('gmail_list')
                                        ->with('error',"No new mail found");
                    }
                }
            }
            
        }else{
            if($type == 1){
                return redirect()->route('mail');
            }
            
        }
    }

    public function viewGmailDetail($mailid,Request $request){
        $data = GmailInboxMail::where('id',$mailid)->first();
        return view('gmail_detail_view',['maildata'=>$data]);
    }
    
    public function sendMail(Request $request){
        $allGmailUser = google_auth_user::where('user_id',$request->user()->id)
        ->orderBy('id','DESC')
        ->get();
        return view('send_gmail',['allGmailUser'=>$allGmailUser]);
    }

    public function mailProcess(Request $request){
        date_default_timezone_set("Asia/Calcutta"); 
        /*$todayDate = date('Y-m-d H:i:s', time());
            $timestamp = strtotime($todayDate);
            echo $timestamp;die;*/
        //echo $request->form_mail;die;
        $gmailauthData = google_auth_user::where(['user_id'=>$request->user()->id,'id'=>$request->from_mail])->first();
        //echo "<PRE>";print_R($gmailauthData);die;
        $accessToken = $gmailauthData->accesstoken;
        $fromMail = $gmailauthData->email;
        $this->client->setAccessToken($accessToken);
        $accessToken = self::checkToekn($request->user()->id,$request->from_mail);
        $service = new \Google_Service_Gmail($this->client);
        //echo "<PRE>";print_R($service);die;
        $toemailArr = $request->email;
        $ccemail = $request->ccemail;
        $bccemail = $request->bccemail;
        $toFromForm = explode(",", $request->email);
        $gmailauthData = google_auth_user::where(['user_id'=>$request->user()->id,'id'=>$request->form_mail])->first();
        $this->client->setAccessToken($accessToken);
        
        
        foreach ($toFromForm as $eachTo) {
            $email = filter_var($eachTo, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->route('send_gmail')
                        ->with('error',$eachTo." is not a valid email address");
            }
        }

        if(trim($request->ccemail) !== ''){
            $toFromCC = explode(",", trim($request->ccemail));
            //echo "<PRE>";print_R($toFromCC);die;
            if(isset($toFromCC)){
                foreach ($toFromCC as $eachToC) {
                    $email1 = filter_var($eachToC, FILTER_SANITIZE_EMAIL);
                    if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
                        return redirect()->route('send_gmail')
                                ->with('error',$email1." is not a valid email address");
                    }
                }
            }
        }
        
        
        if(trim($request->bccemail) !== ''){
            $toFromBcc = explode(",", $request->bccemail);

            if(count($toFromBcc) !== 0){
                foreach ($toFromBcc as $eachToB) {
                    $email2 = filter_var($eachToB, FILTER_SANITIZE_EMAIL);
                    if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
                        return redirect()->route('send_gmail')
                                ->with('error',$email2." is not a valid email address");
                    }
                }
            }
        }
        
        /*$bounce_messages = self::listMessages($service, 'me', [
            'q' => "from:mailer-daemon"
        ]);

        echo "<PRE>";print_R($bounce_messages);die;*/

        /*$msgObj = self::getMessage($service, 'me', '180f568b78f2f68d');

        $headerArr = self::getHeaderArr($msgObj->getPayload()->getHeaders());
        echo "<PRE>";print_R($headerArr);die;*/

        $filepatharr = array();
        $filenamearr = array();
        $notallowExt = array('ade', 'adp', 'apk', 'appx', 'appxbundle', 'bat', 'cab', 'chm', 'cmd', 'com', 'cpl', 'dll', 'dmg', 'ex', 'ex_', 'exe', 'hta', 'ins', 'isp', 'iso', 'jar', 'js', 'jse', 'lib', 'lnk', 'mde', 'msc', 'msi', 'msix', 'msixbundle', 'msp', 'mst', 'nsh', 'pif', 'ps1', 'scr', 'sct', 'shb', 'sys', 'vb', 'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh');
        if($request->hasfile('formFile')){
            foreach($request->file('formFile') as $file){
                //echo $file->getClientOriginalExtension();die;
                $removearr = explode(',',$_POST['remove_val']);
                if(!in_array($file->getClientOriginalName(), $removearr)){
                    if(in_array($file->getClientOriginalExtension(),$notallowExt)){
                        return redirect()->route('send_gmail')
                        ->with('error','For security reasons, Gmail does not allow you to use this type of file as it violates Google policy for executables and archives.');
                    }
                    $fullpath = $file->getClientOriginalName();
                    $filename = pathinfo($fullpath, PATHINFO_FILENAME);
                    $name = $filename.time().rand(1,100).'.'.$file->getClientOriginalExtension();
                    $path = Storage::disk('s3')->put('gmailupload', $file);
                    //echo $path;die;
                    //$path = Storage::disk('s3')->url($path);
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
        }
        if(!empty($_POST['body'])){
            $doc = new \DOMDocument();
            $doc->loadHTML($_POST['body']);
            $imageTags = $doc->getElementsByTagName('img');
            if(!empty($imageTags)){
                foreach($imageTags as $tag) {
                    $imagepaths = '';
                    $imagepaths = $tag->getAttribute('src');
                    $newsrc = 'cid'.pathinfo($imagepaths, PATHINFO_FILENAME);
                    $tag->setAttribute('src',$newsrc); 
                    //array_push($filepatharr,$img); 
                    //array_push($filenamearr, basename($imagepaths));
                }
                $htmlString = $doc->saveHTML();
            }
        }
        

        $strRawMessage = "";
        $boundary = uniqid(rand(), true);
        $strRawMessage = "From: Email <".$fromMail."> \r\n";
        $strRawMessage .= "To: ".$toemailArr."\r\n";
        if(trim($ccemail) !== ''){
            $strRawMessage .= "Cc:" .  $ccemail  . "\r\n";
        }
        if(trim($bccemail) !== ''){
            $strRawMessage .= "Bcc:" .  $bccemail  . "\r\n";
        }
        $strRawMessage .= 'Subject: ' . $request->subject . "\r\n";
        $strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
        $strRawMessage .= 'Content-type: Multipart/Mixed; boundary="' . $boundary . '"' . "\r\n";
        $strRawMessage .= "\r\n--{$boundary}\r\n";
        $strRawMessage .= 'Content-Type: text/html; charset=utf-8\r\n';
        
        $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
        $strRawMessage .= $request->body . "\r\n";
        $strRawMessage .= "\r\n--{$boundary}\r\n";
        if(!empty($filepatharr)){
            for($f=0;$f<count($filepatharr);$f++){
                $filePath = $filepatharr[$f];
                //echo $filePath;die;
                //$finfo = finfo_open(FILEINFO_MIME_TYPE); 
                //$mimeType = finfo_file($finfo, $filePath);
                $curlSession = curl_init();
                curl_setopt($curlSession, CURLOPT_URL, $filePath);
                curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

                $buffer = curl_exec($curlSession);
                
                $fileSize = curl_getinfo($curlSession, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                curl_close($curlSession);
                //$buffer = file_get_contents($filePath);
                $finfo = new \finfo(FILEINFO_MIME_TYPE);

                $mimeType = $finfo->buffer($buffer);
                $myFile = pathinfo($filePath);

                //$fileName = $filenamearr[$f];
                $fileName = $myFile['basename'];
                //$fileData = base64_encode(file_get_contents($filePath));

                $strRawMessage .= "--{$boundary}\r\n";
                $strRawMessage .= 'Content-Type: '. $mimeType .'; name="'. $fileName .'";' . "\r\n";            
                $strRawMessage .= 'Content-ID: <' . $fromMail . '>' . "\r\n";            
                $strRawMessage .= 'Content-Description: ' . $fileName . ';' . "\r\n";
                $strRawMessage .= 'Content-Disposition: attachment; filename="' . $fileName . '"; size=' . $fileSize. ';' . "\r\n";
                $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
                $strRawMessage .= chunk_split(base64_encode($buffer), 76, "\n") . "\r\n";
                $strRawMessage .= '--' . $boundary . "\r\n";
                
            }
        }

        

        try {
            $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
            $msg = new \Google_Service_Gmail_Message();
            $msg->setRaw($mime);
            $todayDate = gmdate("M d Y H:i:s");
            $timestamp = strtotime($todayDate);
            $objSentMsg = $service->users_messages->send("me", $msg);
            //echo "<PRE>";print_R($objSentMsg);
            $msgObj = self::getMessage($service, 'me', $objSentMsg->id);

            $headerArr = self::getHeaderArr($msgObj->getPayload()->getHeaders());
            
            sleep(3);

            $timestamp = strtotime($headerArr['Date']);
            $bounce_messages = self::listMessages($service, 'me', [
                'q' => "from:mailer-daemon after:".$timestamp
            ]);
            
            $bouncedMail = array();

            //echo "<PRE>";print_R($bounce_messages);
            if(!empty($bounce_messages)){
                foreach($bounce_messages as $bounce){
                    array_push($bouncedMail,$bounce->threadId);
                }
            }

            //echo "<PRE>";print_R($bouncedMail);die;

            $GmailDetails = new GmailDetails();
            $GmailDetails->user_id = $request->user()->id;
            $GmailDetails->auth_id = $request->from_mail;
            $GmailDetails->from_mail = $fromMail;
            $GmailDetails->to_mail = $request->email;
            $GmailDetails->cc_mail = $request->ccemail;
            $GmailDetails->bcc_mail = $request->bccemail;
            $GmailDetails->subject = $request->subject;
            $GmailDetails->body = htmlentities($request->body);
            $GmailDetails->save();
            $GmailId = $GmailDetails->id;

            if(in_array($objSentMsg->id,$bouncedMail)){

                $GmailBounceMails = new GmailBounceMails();
                $GmailBounceMails->user_id = $request->user()->id;
                $GmailBounceMails->auth_id = $request->from_mail;
                $GmailBounceMails->mail_id = $GmailId;
                $GmailBounceMails->messageId = $objSentMsg->id;
                $GmailBounceMails->from_mail = $fromMail;
                $GmailBounceMails->subject = $request->subject;
                $GmailBounceMails->body = htmlentities($request->body);
                $GmailBounceMails->save();

            }


            if(!empty($filepatharr)){
                foreach($filepatharr as $kl=>$fl){
                    $GmailAttachmentUpload = new GmailAttachmentUpload();
                    $GmailAttachmentUpload->mail_id = $GmailId;
                    $GmailAttachmentUpload->filename = $filenamearr[$kl];
                    $GmailAttachmentUpload->save();
                }
                
            }

            return redirect()->route('send_gmail')
            ->with('success','Mail Send Successfully!');

        } catch (Exception $e) {
            return redirect()->route('send_gmail')
            ->with('error',$e->getMessage());
        }
    }

    public function gdriveUpload(Request $request){
        $gmailauthData = google_auth_user::where(['user_id'=>$request->user()->id,'id'=>$request->form_mail])->first();
        $accessToken = $gmailauthData->accesstoken;
        $this->client->setAccessToken($accessToken);
        $accessToken = self::checkToekn($request->user()->id,$request->form_mail);
        $this->client->setAccessToken($accessToken);
        $service = new \Google_Service_Gmail($this->client);
        $filepatharr = array();
        $filenamearr = array();
        

        if($request->hasfile('formFile')){
            foreach($request->file('formFile') as $file){
                $fullpath = $file->getClientOriginalName();
                $filename = pathinfo($fullpath, PATHINFO_FILENAME);
                $name = $filename.time().rand(1,100).'.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->put('gmailupload', $file);
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
                /*$file->move(public_path('gmailupload'), $name);
                $filepath = public_path('gmailupload/'.$name);
                array_push($filepatharr,$filepath);
                array_push($filenamearr,$name);*/
            }
        }

        $weblinkArr = array();
        $insertIDArr = array();
        $resultArr = array();
        

        if(!empty($filepatharr)){
            for($p=0;$p<count($filepatharr);$p++){
                $filePath = $filepatharr[$p];
                $file = new \Google_Service_Drive_DriveFile();
                $file->setName($filenamearr[$p]);
                $service = new \Google_Service_Drive($this->client);
                $insertedFile = $service->files->create($file, array('data' => file_get_contents($filePath), 'mimeType' => 'application/octet-stream'));
                //echo "<PRE>";print_R($insertedFile);die;
                if($insertedFile){
                    $file = $service->files->get($insertedFile->id, array('fields' => 'webViewLink'));
                    if($file){
                        $web_link_view = $file->getWebViewLink();
                        $GdriveUploadList = new GdriveUploadList();
                        $GdriveUploadList->insertedfileId = $insertedFile->id;
                        $GdriveUploadList->filename = $filenamearr[$p];
                        $GdriveUploadList->web_link_view = $web_link_view;
                        $GdriveUploadList->save();
                        array_push($weblinkArr,$web_link_view);
                        array_push($insertIDArr,$insertedFile->id);
                    }else{
                        $resultArr['status'] = 0;
                        echo json_encode($resultArr);exit();
                    }
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
        }

    }

    public function deleteGmail(Request $request){
        $gmailauthData = google_auth_user::where(['user_id'=>$request->user()->id,'id'=>$request->authuser])->first();
        $accessToken = $gmailauthData->accesstoken;
        $fromMail = $gmailauthData->email;
        $this->client->setAccessToken($accessToken);
        $accessToken = self::checkToekn($request->user()->id,$request->authuser);
        $service = new \Google_Service_Gmail($this->client);
        $service->users_messages->trash('me', $_POST['mailid']);
        $gmailData = GmailInboxMail::where('messageid',$_POST['mailid'])->first();
        $attachData = GmailInboxAttachment::where('mail_id',$gmailData->id)->get();
        GmailInboxMail::where('messageid',$_POST['mailid'])->delete();
        //$gmailData->delete();
        if($attachData){
            //unlink();
            $ids = array();
            foreach($attachData as $at){
                array_push($ids,$at->id);
            }

            DB::table("gmail_inbox_attachments")->whereIn('id',$ids)->delete();
            //unlink(public_path('gmailattchment/'.$_POST['mailid']));
        }
        
        echo "1";exit();
    }

    public function forceDownload($fileid,$messageid){
        $fileData = GmailInboxAttachment::where('id',$fileid)->first();
        //echo "<PRE>";print_R($fileData);die;
        $file_name = $fileData->filename;
        $messageId = $messageid;
        $key = 'gmailattchment/'.$messageId.'/'.$file_name;
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = env('AWS_BUCKET');

        $command = $client->getCommand('GetObject', [
             'Bucket' => $bucket,
             'Key' => $key
        ]);
        $request = $client->createPresignedRequest($command, '+20 minutes');
        $presignedUrl = (string)$request->getUri();
        
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

    public function checkToekn($id,$authid){
        if ($this->client->isAccessTokenExpired()) {
            //echo "<PRE>";print_R($this->client);die;
            if ($this->client->getRefreshToken()) {
                $accessToken = $this->client-> fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                //echo "<PRE>";print_R($accessToken);die;
                $gmailauthData = google_auth_user::where(['user_id'=>$id,'id'=>$authid])->first();
                //$gmailauthData = new google_auth_user();
                $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                $gmailauthData->expired_in = $accessToken['expires_in'];
                $gmailauthData->save();

                return $accessToken;
            }
        }else{
            $gmailauthData = google_auth_user::where(['user_id'=>$id,'id'=>$authid])->first();
            $accessToken = $gmailauthData->accesstoken;
            return $accessToken;
        }
        
    }

    public function listMessages($service, $userId, $optArr = []) {
      $pageToken = NULL;
      $messages = array();
      do {
        try {
          if ($pageToken) {
            $optArr['pageToken'] = $pageToken;
          }
          $messagesResponse = $service->users_messages->listUsersMessages($userId, ['maxResults' => 1000]);
          if ($messagesResponse->getMessages()) {
            $messages = array_merge($messages, $messagesResponse->getMessages());
            $pageToken = $messagesResponse->getNextPageToken();
          }
        } catch (Exception $e) {
          print 'An error occurred: ' . $e->getMessage();
        }
      } while ($pageToken);

      return $messages;
    }

    public function getHeaderArr($dataArr) {
        $outArr = [];
        foreach ($dataArr as $key => $val) {
            $outArr[$val->name] = $val->value;
        }
        return $outArr;
    }

    public function getBody($dataArr) {
        $outArr = [];
        foreach ($dataArr as $key => $val) {
            $outArr[] = self::base64url_decode($val->getBody()->getData());
            break; // we are only interested in $dataArr[0]. Because $dataArr[1] is in HTML.
        }
        return $outArr;
        //echo "<PRE>";print_R($dataArr);die;
        
    }

    public function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function getMessage($service, $userId, $messageId) {
      try {
        $message = $service->users_messages->get($userId, $messageId);
        return $message;
      } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
      }
    }
}
