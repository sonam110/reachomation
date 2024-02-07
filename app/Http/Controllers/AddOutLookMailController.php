<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
set_time_limit(0);
ini_set('memory_limit',-1);
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

class AddOutLookMailController extends Controller
{
    private $clientId;
    private $redirectUrl;
    private $clientsecret;
    private $client;
    public function __construct()
    {
        $this->clientId = '92ad5e30-702a-4f36-8377-07b18418d608';
        $this->redirectUrl = ''.env('APP_URL').'/callback_outlook';

        $this->clientsecret = 'qIW7Q~i4CNJzPWUj5p7BrAwPVA3wlVLgfE-wA';
        $this->client = Onedrive::client($this->clientId);
          
    }

    public function index(Request $request){
       
        $data = array();
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
        
        return view('outlooklogin',['data'=>$data]);
    }
}
