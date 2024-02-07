<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentCard;
use Cookie;
use Mail;
use Stripe;
use Session;
use Auth;
use Exception;
use Carbon\Carbon;
use Validator;
use Helper;
//use Barryvdh\DomPDF\Facade\Pdf;
use PDF;
use Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayementNotification;
use App\Notifications\CreditNotification;
use App\Mail\SubscriptionMail;
use App\Mail\CreditRechargeMail;
use Illuminate\Support\Facades\File;
class CheckoutController extends Controller
{
     public function userCheckout($slug)
    {  
        try
        {
            $user = auth()->user();
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $plan = SubscriptionPlan::where('slug', $slug)->first();
           
            if (!empty($plan))
            {
                $checkSubsc = Subscription::where('user_id',$user->id)->where('plan_id',$plan->id)->where('is_canceled','0')->first();

                if(!empty($checkSubsc) ){
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
                
                $session= $stripe->checkout->sessions->create([
                  'customer' => $user->stripe_id,
                  'success_url' => ''.route('dashboard').'',
                  'cancel_url' => ''.route('dashboard').'',
                  'line_items' => [
                    [
                      'price' => $plan->stripe_plan_id,
                      'quantity' => 1,
                    ],
                  ],
                  'mode' => 'subscription',
                ]);

                return redirect($session->url);

            }
            else
            {
                return redirect()
                    ->back()
                    ->with('error', 'Plan Not found.');
            }
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }

    }
    public function upgradeUserPlan()
    {
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
