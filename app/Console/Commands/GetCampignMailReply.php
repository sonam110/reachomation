<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google_Client;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\SendCampaignMail;
use App\Models\BlackListEmail;
use Krizalys\Onedrive\Onedrive;
use Exception;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Carbon;
use App\Models\Inbox;
use App\Models\Feed;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CampiagnNotification;
use Mail;
use App\Mail\CampaignStopMail;

class GetCampignMailReply extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:reply  {type=regular} {page=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $client;
    private $clientId;
    private $redirectUrl;
    private $clientsecret;
    private $outlookclient;

    public function __construct() {
        parent::__construct();
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
        $this->client->setApprovalPrompt('force');
        $this->client->setPrompt('select_account consent');

        /* ----Outlook------------------- */
        $this->clientId = env('OUTLOOK_CLIENT_ID', 'df45ec20-0dee-462b-a29d-d0b0b5797e67');
        $this->redirectUrl = env('OUTLOOK_REDIRECT', 'https://reachomation.com/callback_outlook');

        $this->clientsecret = env('OUTLOOK_SECRET', 'H5H8Q~VfimqQUspNVx8F.~WI174fRX1WxIrHHaok');
        $this->outlookclient = Onedrive::client($this->clientId);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $type = $this->argument('type');
        $page = $this->argument('page');
        try {
//            \DB::enableQueryLog();
            $sendMailData = SendCampaignMail::select(['send_campaign_mails.*', 'email_collections.*', 'send_campaign_mails.id as scmid', 'email_collections.id as ecid']);
            $sendMailData->where('send_campaign_mails.msg_id', '!=', '');
            $sendMailData->where('send_campaign_mails.status', '1');
            $sendMailData->leftjoin('email_collections', 'send_campaign_mails.from_email', '=', 'email_collections.email');
            $sendMailData->leftjoin('campaigns', 'send_campaign_mails.camp_id', '=', 'campaigns.id');
            if ($type == 'regular') {
                $sendMailData->where('send_campaign_mails.is_open', '>', '0');
                $sendMailData->whereBetween('send_campaign_mails.is_open_time', [now()->subMinutes(20), now()]);
            } elseif ($type == '20hourAfterSend') {
//                $sendMailData->leftjoin('campaigns', 'send_campaign_mails.camp_id', '=', 'campaigns.id');
                $sendMailData->whereBetween('campaigns.last_mail_send_date', [now()->subMinutes(1400), now()->subMinutes(1200)]);
            } elseif ($type == '2hourAfterSend') {
//                $sendMailData->leftjoin('campaigns', 'send_campaign_mails.camp_id', '=', 'campaigns.id');
                $sendMailData->whereBetween('campaigns.last_mail_send_date', [now()->subMinutes(180), now()->subMinutes(120)]);
            } elseif ($type == 'debug') {
//                $sendMailData->where('send_campaign_mails.is_open', '>', '0');
                $sendMailData->skip(($page - 1) * 1000)->limit(1000);
            }
           $sendMailData->whereIn('campaigns.status', ['0', '2', '3']);
            $sendMailDataResult = $sendMailData->orderBy('send_campaign_mails.id', 'desc')->get();
//            dd(\DB::getQueryLog());
            echo Carbon::now() . '------Sync  Start------Total : ' . count($sendMailDataResult) . PHP_EOL;
            $DataToUpdateInSystem = [];
            $campIds = [];
//   dd($sendMailDataResult);
            $numbering = 0;
            if (count($sendMailDataResult) > 0) {
                foreach ($sendMailDataResult as $key1 => $data) {
//                echo Carbon::now() . '---' . $key1 . '--Start Email---' . $data->from_email . PHP_EOL;
                    if ($data->type == '1') {
                        $DataToUpdateInSystem[] = $this->getGmailReply($data->from_email, $data->to_email, $data->user_id, $data->camp_id, $data->scmid, $data->subject, $data->msg_id, $data->threadId, $data->ecid, $data->accesstoken, $data->refreshtoken, $data->expired_in);
                    }
                    if ($data->type == '2') {
                        $DataToUpdateInSystem[] = $this->getOutlookReply($data->from_email, $data->to_email, $data->user_id, $data->camp_id, $data->scmid, $data->subject, $data->msg_id, $data->threadId, $data->ecid, $data->accesstoken, $data->refreshtoken, $data->expired_in);
                    }
                    $campIds[] = $data->camp_id;
                    $numbering++;
                    echo 'MSG_ID--' . $data->msg_id . '--' . $data->type . '-->' . Carbon::now() . ' ' . $numbering . ' From Email---' . $data->from_email . ':::::::To Email---' . $data->to_email . PHP_EOL;
                }
            }
            echo Carbon::now() . '------update End------' . PHP_EOL;
            $DataToUpdateInSystem = array_filter($DataToUpdateInSystem);
            if (!empty($DataToUpdateInSystem)) {
                $feed = [];
                $sendCampMailReply = [];
                $totalReply = [];
                foreach ($DataToUpdateInSystem as $DTIS) {
                    if (!empty($DTIS['feed_insert_ignore'])) {
                        foreach ($DTIS['feed_insert_ignore'] as $ditsFeed) {
                            $feed[] = $ditsFeed;
                        }
                    }
                    $sendCampMailReply[] = ['id' => $DTIS['send_camp_mail_update']['send_id'], 'is_reply' => $DTIS['send_camp_mail_update']['is_reply']];
                    $totalReply[] = ['id' => $DTIS['send_camp_mail_update']['camp_id'], 'total_reply' => SendCampaignMail::where('camp_id', $DTIS['send_camp_mail_update']['camp_id'])->sum('is_reply')];
                }
//            dd($feed);
                Feed::insertOrIgnore($feed);
                SendCampaignMail::massUpdate(values: $sendCampMailReply);
                Campaign::massUpdate(values: $totalReply);
                echo Carbon::now() . '------System Insert/update End------' . PHP_EOL;
            }
        } catch (Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function getGmailReply($from, $to, $user_id, $camp_id, $send_id, $subject, $msg_id, $threadId, $ecid, $accessToken, $refreshtoken = null, $expired_in) {
        try {
//            dd($expired_in);
//            self::checkToken($user_id, $ecid, $accessToken, $refreshtoken, $expired_in);
            $this->client->setAccessToken($accessToken);
            $checkToken = self::checkToken($user_id, $ecid, $accessToken, $refreshtoken, $expired_in);
            if (@$checkToken['accessToken'] == NULL) {
                $is_account_inactive = true;
                $reason = @$checkToken['reason'].'FROM-GOOGLE';
                $this->campaignPauseMail($camp_id, $reason);
            }
            $accessToken = @$checkToken['accessToken'];
            $service = new \Google_Service_Gmail($this->client);
            $this->client->setAccessToken($accessToken);
            $oauth2 = new \Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo_v2_me->get();
            if ($from != @$userInfo->email) {
                $this->campaignPauseMail($camp_id, 'Email disconnect due to server error');
                \Log::info('token not refresh and aouth email not same as from email');
            }
            $service = new \Google_Service_Gmail($this->client);
//            dd($accessToken);
            $user = 'me';
            $thread = $service->users_threads->get($user, $threadId);
//            dd($thread);
            if ($thread) {
                $dataToUpdate = [];
                $opt_param['threadId'] = $threadId;
                $thread_messages = $thread->getMessages($opt_param);
                $i = 0;
                foreach (@$thread_messages as $key => $msg) {
                    $mid = $msg->getId();
                    if ($msg_id != $mid) {
//                        $i++;
                        $messageDetails = $this->getMessage($service, $user, $mid);
                        if (!empty($messageDetails)) {
//                            echo 'hhhh' . PHP_EOL;
                            $header = $this->getHeaderArr($messageDetails->getPayload()->getHeaders());
                            $body = $this->getBody($messageDetails->getPayload()->getParts());
                            $message = (empty(@$body[0])) ? '' : $body[0];
                            $subject = (empty(@$header['Subject'])) ? '' : $header['Subject'];
//                            $checkFeed = Feed::where('msg_id', $mid)->first();
//                            $checkInbox = Inbox::where('msg_id', $mid)->first();
//                            if (empty($checkFeed)) {
//                                Feed($user_id, $camp_id, $send_id, $from, $to, date('Y-m-d H:i:s'), '2', $mid);
                            echo $camp_id . '--' . $subject . PHP_EOL;
                            if (!str_contains($subject, 'Delivery Status Notification (Failure)') || !str_contains($subject, '(Failure)') || !str_contains($subject, 'Notification (Failure)')) {
                                echo 'Inside---' . $camp_id . '---' . $subject . PHP_EOL;
                                $dataToUpdate['feed_insert_ignore'][] = [
                                    'user_id' => $user_id,
                                    'camp_id' => $camp_id,
                                    'internal_id' => $send_id,
                                    'msg_id' => $mid,
                                    'from_email' => $from,
                                    'to_email' => $to,
                                    'c_date' => date('Y-m-d H:i:s'),
                                    'status' => 2,
                                    'ip_address' => \Request::ip()];
//                            }
                                /* if (empty($checkInbox)) {
                                  Inbox($user_id, $camp_id, $send_id, $from, $to, $subject, $message, date('Y-m-d H:i:s'), '4', $mid);
                                  } */
                                $i++;
                            }
                        }
                    }
                }
                if ($i >= '1') {
                    $dataToUpdate['send_camp_mail_update'] = ['is_reply' => $i, 'send_id' => $send_id, 'camp_id' => $camp_id, 'last_reply_time' => now()];
                }
                \Log::info('-------Gmail Reply');
                \Log::info($dataToUpdate);
                return $dataToUpdate;
            }
            return null;
        } catch (Exception $exception) {
//            dd($exception);
            \Log::error($exception->getMessage());
        }
    }

    /* -------------------Stop campaign Mail-------------------- */

    public function campaignPauseMail($camp_id, $reason) {

        $updateCamp = Campaign::find($camp_id);
        $updateCamp->status = '5';
        $updateCamp->reason = $reason;
        $updateCamp->bounce_count = ($reason == 'Too many bounces') ? $updateCamp->bounce_count + 5 : 5;
        $updateCamp->save();

        $updateAccount = $updateCamp->fromUser;
        $updateAccount->status = '0';
        $updateAccount->save();

        $fname = ($updateCamp->is_parent == NULL) ? 'outreach' : 'followup';
        $content = [
            'name' => ($updateCamp->user) ? $updateCamp->user->name : 'User',
            'camp_name' => $updateCamp->name,
            'reason' => $reason,
            'stage' => $fname,
            'attemp' => $updateCamp->attemp,
        ];
        if (env('IS_NOTIFY_ENABLE', false) == true) {
            Notification::send($updateCamp->user, new CampiagnNotification($updateCamp));
        }
        if (env('IS_MAIL_ENABLE', false) == true) {
            Mail::to($updateCamp->user->email)->send(new CampaignStopMail($content));
        }
    }

    public function getOutLookReply($from, $to, $user_id, $camp_id, $send_id, $subject, $msg_id, $threadId, $ecid, $accessToken, $refreshtoken, $expired_in) {
        try {
            $accessToken = self::getToken($user_id, $ecid, $accessToken, $refreshtoken, $expired_in);
            $conversionId = $threadId;
            $msgData = self::__curl('GET', 'https://graph.microsoft.com/v1.0/me/mailfolders/INBOX/messages?$search= subject eq ' . trim($subject) . '&orderby=ReceivedDateTime desc', $accessToken);
            $i = 0;
            if (isset($msgData['value'])) {
                $dataToUpdate = [];
                foreach (@$msgData['value'] as $key => $msg) {
                    if ($conversionId == $msg['conversationId']) {
                        $i++;
                        $mid = $msg['id'];
                        $message = $msg['bodyPreview'];
                        $message = $msg['bodyPreview'];
//                        $checkFeed = Feed::where('msg_id', $mid)->first();
//                        $checkInbox = Inbox::where('msg_id', $mid)->first();
//                        if (empty($checkFeed)) {
//                            Feed($user_id, $camp_id, $send_id, $from, $to, date('Y-m-d H:i:s'), '2', $mid);
                        $dataToUpdate['feed_insert_ignore'][] = [
                            'user_id' => $user_id,
                            'camp_id' => $camp_id,
                            'internal_id' => $send_id,
                            'msg_id' => $mid,
                            'from_email' => $from,
                            'to_email' => $to,
                            'c_date' => date('Y-m-d H:i:s'),
                            'status' => 2,
                            'ip_address' => \Request::ip()];
//                            $updateCamp = Campaign::find($camp_id);
//                            $updateCamp->total_reply = $updateCamp->total_reply + 1;
//                            $updateCamp->save();
//                        }
                        /* if (empty($checkInbox)) {
                          Inbox($user_id, $camp_id, $send_id, $from, $to, $subject, $message, date('Y-m-d H:i:s'), '4', $mid);
                          } */
                    }
                }
                if ($i >= '1') {
//                    \Log::info($send_id);
//                    $trackReplyMail->is_reply = $i;
//                    $trackReplyMail->save();
                    $dataToUpdate['send_camp_mail_update'] = ['is_reply' => $i, 'send_id' => $send_id, 'camp_id' => $camp_id, 'last_reply_time' => now()];
//                    $totalReply = SendCampaignMail::where('camp_id', $camp_id)->sum('is_reply');
//                    $updateCamp = Campaign::find($camp_id);
//                    $updateCamp->total_reply = $totalReply;
//                    $updateCamp->save();
                }
                \Log::info('-------Outlook Reply');
                \Log::info($dataToUpdate);
                return $dataToUpdate;
            }
            return null;
        } catch (Exception $exception) {
            \Log::error($exception->getMessage());
        }
    }

    public function getToken($userid, $id, $accessToken, $refreshtoken, $expired_time) {
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
            $tokenData = json_decode($response);
            if (!empty($tokenData)) {
                $accessToken = $tokenData->access_token;
                $resfreshToken = $tokenData->refresh_token;
                $endtime = strtotime("+60 minutes");
                $outlookuser = EmailCollection::where('user_id', $userid)->where('id', $id)->first();
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

    public function checkToken($user_id, $id, $accessToken, $refreshtoken) {

        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $accessToken = $this->client->fetchAccessTokenWithRefreshToken($refreshtoken);
                if (@$accessToken['error']) {
                    if (@$accessToken['error'] == 'invalid_grant') {
                        $reason = 'Error in connecting your Email account';
                    } elseif (@$accessToken['error'] == 'unauthorized_client ') {
                        $reason = 'Email disconnect unauthorized client';
                    } else {
                        $reason = 'Multiple email block';
                    }
                    $tokenResponse = [
                        'accessToken' => NULL,
                        'reason' => $reason,
                    ];
                    \Log::error(@$accessToken['error']);
                    return $tokenResponse;
                } else {
                    $gmailauthData = EmailCollection::where('user_id', $user_id)->where('id', $id)->first();

                    $gmailauthData->accesstoken = json_encode($this->client->getAccessToken());
                    $gmailauthData->refreshtoken = $this->client->getRefreshToken();
                    $gmailauthData->expired_in = @$accessToken['expires_in'];
                    $gmailauthData->save();
                    \Log::info('New acessToken in  sendemail');
                    \Log::info(@$accessToken['expires_in']);
                    $tokenResponse = [
                        'accessToken' => $accessToken,
                        'reason' => '',
                    ];
                    $this->client->setAccessToken($gmailauthData->accesstoken);
                    return $tokenResponse;
                }
            } else {
                $tokenResponse = [
                    'accessToken' => NULL,
                    'reason' => 'Email disconnect',
                ];
                return $tokenResponse;
            }
        } else {
            $this->client->setAccessToken($accessToken);
            $tokenResponse = [
                'accessToken' => $accessToken,
                'reason' => '',
            ];
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
                \Log::error($e->getMessage());
            }
        } while ($pageToken);

        return $messages;
    }

    public function getBody($dataArr) {
        $outArr = [];
        foreach ($dataArr as $key => $val) {
            $outArr[] = base64url_decode($val->getBody()->getData());
            break; // we are only interested in $dataArr[0]. Because $dataArr[1] is in HTML.
        }
        return $outArr;
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
            \Log::error($e->getMessage());
        }
    }

    public function __curl($method, $url, $accessToken) {
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
        $msgData = json_decode($response_mess, true);
        return $msgData;
    }

}
