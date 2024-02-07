<?php

namespace App\Services;

set_time_limit(0);
ini_set('memory_limit', -1);

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google_Client;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\SendCampaignMail;
use App\Models\BounceMail;
use Krizalys\Onedrive\Onedrive;
use Exception;
use Illuminate\Support\Carbon;

class SendSmsService {

    protected $client;
    protected $clientId;
    protected $redirectUrl;
    protected $clientsecret;
    protected $outlookclient;

    public function __construct() {

        /* ----------Gmail---------------------- */
        $destinationPath = storage_path('credential/');
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Reachomation');
        $this->client->addScope(\Google_Service_Gmail::GMAIL_MODIFY);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_PROFILE);
        $this->client->addScope(\Google_Service_Oauth2::USERINFO_EMAIL);
        $this->client->setAuthConfig(storage_path('credential/' . env('CREDENTIAL_FILE_NAME', 'credential-prod') . '.json'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        /* ----Outlook------------------- */
        $this->clientId = env('OUTLOOK_CLIENT_ID', 'df45ec20-0dee-462b-a29d-d0b0b5797e67');
        $this->redirectUrl = env('OUTLOOK_REDIRECT', 'https://reachomation.com/callback_outlook');

        $this->clientsecret = env('OUTLOOK_SECRET', 'H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
        $this->outlookclient = Onedrive::client($this->clientId);

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function sendMailByGmail($from, $to, $subject, $message, $user_id) {

        date_default_timezone_set("Asia/Calcutta");
        $gmailauthData = EmailCollection::where(['user_id' => $user_id, 'email' => $from])->where('status', '1')->first();
        if (!is_object($gmailauthData)) {
            return false;
        }

        try {


            $accessToken = $gmailauthData->accesstoken;
            $this->client->setAccessToken($accessToken);
            $accessToken = self::checkToken($user_id, $gmailauthData->id);
            if (!empty($accessToken)) {

                $service = new \Google_Service_Gmail($this->client);
                $this->client->setAccessToken($accessToken);

                $filepatharr = array();
                $filenamearr = array();

                $subject = html_entity_decode($subject);
                $subject = preg_replace("/\s/",' ',$subject);
                $subject = str_replace(chr(194),'',$subject);
                $subject = str_replace("&nbsp;",'', $subject);
                $subject = substr(strip_tags($subject),0,110);

                $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
                $message = str_replace(chr(194),'',$message);
                $message = str_replace("&nbsp;",' ', $message);
                $message = str_replace("’",'’',$message);

                if (!empty($message)) {
                    $doc = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($message);
                    libxml_use_internal_errors(false);
                    $imageTags = $doc->getElementsByTagName('img');
                    if (!empty($imageTags)) {
                        foreach ($imageTags as $tag) {
                            $imagepaths = '';
                            $imagepaths = $tag->getAttribute('src');
                            $newsrc = 'cid' . pathinfo($imagepaths, PATHINFO_FILENAME);
                            $tag->setAttribute('src', $newsrc);
                        }
                        $htmlString = $doc->saveHTML();
                    }
                }
                $from_name = (!empty(@$gmailauthData->name)) ? $gmailauthData->name : 'Reachomation';

                $strRawMessage = "";
                $boundary = uniqid(rand(), true);
                $strRawMessage = "From: " . $from_name . " <" . $from . "> \r\n";
                $strRawMessage .= "To: " . $to . "\r\n";

                $strRawMessage .= 'Subject: ' . $subject . "\r\n";
                $strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
                $strRawMessage .= 'Content-type: Multipart/Mixed; boundary="' . $boundary . '"' . "\r\n";
                $strRawMessage .= "\r\n--{$boundary}\r\n";
                $strRawMessage .= 'Content-Type: text/html; charset=utf-8\r\n';

                $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
                $strRawMessage .= $message . "\r\n";
                $strRawMessage .= "\r\n--{$boundary}\r\n";
                $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
                $msg = new \Google_Service_Gmail_Message();
                $msg->setRaw($mime);
                $todayDate = gmdate("M d Y H:i:s");
                $timestamp = strtotime($todayDate);
                $objSentMsg = $service->users_messages->send("me", $msg);
                $msgObj = self::getMessage($service, 'me', $objSentMsg->id);

                if (!empty($msgObj)) {
                    /* --------------Check Bounce Mail------------ */

                    $bouncedMail = array();
                    $headerArr = self::getHeaderArr($msgObj->getPayload()->getHeaders());
                    sleep(3);
                    $todayDate = gmdate("M d Y H:i:s");
                    $timestamp = strtotime($todayDate);
                    $bounce_messages = self::listMessages($service, 'me', [
                                'q' => "from:mailer-daemon@googlemail.com after:" . $timestamp
                    ]);

                    if (!empty($bounce_messages)) {
                        foreach ($bounce_messages as $bounce) {
                            array_push($bouncedMail, $bounce->threadId);
                        }
                    }

                    if (in_array($objSentMsg->id, $bouncedMail)) {

                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $exception) {
            \Log::error($exception);
            return false;
        }
    }

    public function sendMailByOutlook($from, $to, $subject, $message, $user_id) {
        $outlookuser = EmailCollection::where(['user_id' => $user_id, 'email' => $from])->first();

        try {
            $accessToken = self::getToken($user_id, $outlookuser->id);
            if (!empty($accessToken)) {
                $userId = $user_id;
                $sendemail = $from;
                $auth_id = $from;

                $thisTo = array();
                if ($to != '') {
                    $thisTo[] = array(
                        "EmailAddress" => array(
                            "Address" => trim($to)
                        )
                    );
                }

                $attachments = array();
                $filenamearr = array();

                $from_name = (!empty($outlookuser->name)) ? $outlookuser->name : 'Reachomation';

                //$subject = html_entity_decode($subject);
                $subject = preg_replace("/\s/",' ',$subject);
                $subject = str_replace(chr(194),"",$subject);
                $subject = str_replace("&nbsp;",'', $subject);
                $subject = substr(strip_tags($subject),0,110);

                $message = preg_replace('/<span [^<]*?class="([^<]*?custom-data.*?)">(.*?)<\/span>/','$2',$message); 
                $message = str_replace(chr(194),"",$message);
                $message = str_replace("&nbsp;",' ', $message);
                $message = str_replace("’",'’',$message);

                $doc = new \DOMDocument();
                if (!empty($message)) {
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($message);
                    libxml_use_internal_errors(false);
                    $imageTags = $doc->getElementsByTagName('img');
                    if (!empty($imageTags)) {
                        foreach ($imageTags as $tag) {
                            $imagepaths = '';
                            $imagepaths = $tag->getAttribute('src');

                            $newsrc = 'cid:' . pathinfo($imagepaths, PATHINFO_FILENAME);
                            $tag->setAttribute('src', $newsrc);
                            $content = base64_encode(file_get_contents($imagepaths));

                            $htmlString = $doc->saveHTML();
                        }
                    }
                }

                $request = array(
                    "message" => array(
                        "subject" => $subject,
                        "importance" => "High",
                        "body" => array(
                            "ContentType" => "HTML",
                            "Content" => $message
                        ),
                        "attachments" => $attachments,
                        "toRecipients" => $thisTo
                    )
                );

                $request = json_encode($request);
                $headers = array(
                    "User-Agent: php-tutorial/1.0",
                    "Authorization: Bearer " . $accessToken,
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Content-Length: " . strlen($request)
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://graph.microsoft.com/v1.0/me/sendmail');
                if ($request != null) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                }
                //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                if ($headers != null) {
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
                $response = curl_exec($ch);
                //echo "<PRE>";print_R($response);die;
                $data = json_decode($response);

                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                if ($http_code == '202') {
                    date_default_timezone_set("Asia/Calcutta");
                    $formateDate = date('Y-m-d');
                    $formateDate = $formateDate . 'T' . date('h:m:s') . 'Z';

                    sleep(8);
                    $bounceData = self::__curl('GET', 'https://graph.microsoft.com/v1.0/me/MailFolders/INBOX/messages?$search="from:postmaster"&?$filter=receivedDateTime ge ' . $formateDate, $accessToken);
                    //echo "<PRE>";print_R($bounceData);die;
                    $bounceItem = array();
                    $bounceItem = array();

                    if (!empty($bounceData)) {
                        if (isset($bounceData['value'])) {
                            foreach ($bounceData['value'] as $bounceG) {
                                if (trim(str_replace('Undeliverable:', '', $bounceG['subject'])) == trim($subject)) {
                                    foreach ($bounceG['toRecipients'] as $emailvalarr) {
                                        if ($emailvalarr['emailAddress']['address'] == $to) {


                                            return false;
                                        } else {
                                            return true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $exception) {
            \Log::error($exception);
            return false;
        }
    }

    public function getToken($userid, $id) {
        try {
            $outlookuser = EmailCollection::where('user_id', $userid)->where('id', $id)->first();

            if ($outlookuser) {
                $accessToken = $outlookuser->accesstoken;
                $refreshtoken = $outlookuser->refreshtoken;
                $expired_time = $outlookuser->expired_in;
                $time = time();
                if ($time >= $expired_time) {
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
                        CURLOPT_POSTFIELDS => 'client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUrl . '&client_secret=' . $this->clientsecret . '&refresh_token=' . $refreshtoken . '&grant_type=refresh_token',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/x-www-form-urlencoded'
                        ),
                    ));
                    $response = curl_exec($curl);
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    if (isset($error_msg)) {
                        return NULL;
                    } else {
                        $tokenData = json_decode($response);

                        $accessToken = $tokenData->access_token;
                        $resfreshToken = $tokenData->refresh_token;
                        $endtime = strtotime("+60 minutes");

                        $outlookuser->accesstoken = $accessToken;
                        $outlookuser->refreshtoken = $resfreshToken;
                        $outlookuser->expired_in = $endtime;
                        $outlookuser->save();

                        return $accessToken;
                    }
                } else {
                    return $accessToken;
                }
            }
        } catch (Exception $exception) {
            \Log::error($exception);
            return false;
        }
    }

    public function checkToken($user_id, $id) {
        try {
            //echo "<PRE>";print_R($this->client);die;
            if ($this->client->getRefreshToken()) {
                $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                //print_r($accessToken);
                //die;
                if (@$accessToken['error']) {
                    \Log::error(@$accessToken['error']);
                    return NULL;
                } else {
                    $gmailauthData = EmailCollection::where('user_id', $user_id)->where('id', $id)->first();

                    $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                    $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                    $gmailauthData->expired_in = $accessToken['expires_in'];
                    $gmailauthData->save();

                    return $accessToken;
                }
            }
        } catch (Exception $exception) {
            \Log::error($exception);
            return false;
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
                $messagesResponse = $service->users_messages->listUsersMessages($userId, $optArr);
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

    public function getMessage($service, $userId, $messageId) {
        try {
            $message = $service->users_messages->get($userId, $messageId);
            return $message;
        } catch (Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }
    }

    public function __curl($method, $url, $accessToken) {
        //echo "<br/>".str_replace(' ','%20',$url);die;
        $curlmess = curl_init();
        curl_setopt_array($curlmess, array(
            CURLOPT_URL => str_replace(' ', '%20', $url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ),
        ));
        $response_mess = curl_exec($curlmess);
        //echo "<PRE>";print_R($response_mess);die;
        $msgData = json_decode($response_mess, true);
        return $msgData;
    }

}
