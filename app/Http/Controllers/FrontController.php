<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Auth;
use App\Models\Subscription;
use App\Models\Contact;
use App\Models\User;
use App\Models\Userlog;
use Stripe;
use Mail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Hash;
class FrontController extends Controller
{
    public function index()
    {
       return View('front-pages.index');
    }
     public function pricing()
    {

        return View('front-pages.pricing');
        /*if (\Auth::check()) {
            if(auth()->user()->plan =='1'){
                return View('front-pages.pricing');
            } else{
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                $stripe->billingPortal->configurations->create(
                  [
                    'business_profile' => [
                      'headline' => 'Cactus Practice partners with Stripe for simplified billing.',
                    ],
                    'features' => ['invoice_history' => ['enabled' => true]],
                  ]
                );

                
                $user = auth()->user();
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                // Authenticate your user.
                $session = \Stripe\BillingPortal\Session::create([
                  'customer' => $user->stripe_id,
                  'return_url' => ''.route('dashboard').'',
                  
                ]);

                return redirect($session->url);
            }
        }
        else{
            return View('front-pages.pricing');
        }*/
      
      
    }
     public function paymentBilling()
    {
        if (\Auth::check()) {
            if(auth()->user()->plan =='1'){
                return View('front-pages.pricing');
            } else{
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                $stripe->billingPortal->configurations->create(
                  [
                    'business_profile' => [
                      'headline' => 'Cactus Practice partners with Stripe for simplified billing.',
                    ],
                    'features' => ['invoice_history' => ['enabled' => true]],
                  ]
                );

                
                $user = auth()->user();
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                // Authenticate your user.
                $session = \Stripe\BillingPortal\Session::create([
                  'customer' => $user->stripe_id,
                  'return_url' => ''.route('dashboard').'',
                  
                ]);

                return redirect($session->url);
            }
        }
        else{
            return View('front-pages.pricing');
        }
      
      
    }
     public function fatchPlan(Request $request)
    {

        $plans = SubscriptionPlan::where('plan_type',$request->plan)->orWhere('plan_type','3')->orderBy('id','ASC')->get();
        return View('front-pages.pricing-table',compact('plans'));
       
    }

     public function upgradePlan()
    {
      
      $user = auth()->user();
      if(auth()->user()->plan !='' && auth()->user()->plan !='1' && date('Y-m-d',strtotime(auth()->user()->plan_expired_at)) < date('Y-m-d')){
            return View('front-pages.upgrade-plan');
      } else{
         return redirect(route('dashboard'));
      }
      
    }


   public function fatchPlans(Request $request)
    {
       $plans = SubscriptionPlan::where('plan_type',$request->plan)->orWhere('plan_type','3')->orderBy('id','ASC')->get();
       $checkFree = Subscription::where('user_id',auth()->user()->id)->where('name','Free Forever')->first();
       return View('front-pages.upgrade-pricing-table',compact('plans','checkFree'));
    }

    public function toManyAccount($email)
    {
        $email = base64url_decode($email);
        $checkUser = User::where('email',$email)->where('status','1')->first();
        if(empty($checkUser)){
            return redirect()->back()->with('error', 'User not found');
        }
        $id = base64url_encode($checkUser->id);
        return View('to-many-account',compact('checkUser','id'));
       
    }
    public function pauseSession(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {

            Auth::logoutOtherDevices($request->password,);
            $userLog = Userlog::where('userId',auth()->user()->id)->update(['status'=>'0']);
            Userlog::create([
               'userId'  => $user->id,
                'ip'      => $request->ip()
            ]);
            return redirect('/dashboard');
        } else{
            return redirect()->back()->with('error', 'Wrong Password');
        }
        
        
    }
    public function saveContact(Request $request)
    {
        $this->validate($request, [
            'name'         => 'required|alpha_num:ascii|max:25',
            'email'       => 'required|email',
            'skypewhatsapp'     => 'required|alpha_num',
            'message'   => 'required|alpha_num:ascii',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $saveContact = new Contact;
        $saveContact->name   = $request->name;
        $saveContact->email = $request->email;
        $saveContact->skype_whatsapp = $request->skypewhatsapp;
        $saveContact->message = $request->message;
        $saveContact->save();
        if($saveContact){

            $content = [
                'name' => $request->name,
                'email' => $request->email,
                'skype_whatsapp' => $request->skype_whatsapp,
                'message' => $request->message,
            ];
                    
            if (env('IS_MAIL_ENABLE', false) == true) {
               
                Mail::to('support@reachomation.com')->send(new ContactMail($content));
            }
            return redirect()->back()->with('success', 'Thanks for contacting us team will get back soon to you');
        } else{
            return redirect()->back()->with('error', 'Opps! Something went wrong');
        }
       
    }
    
}
