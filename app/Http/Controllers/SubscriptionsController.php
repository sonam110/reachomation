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
class SubscriptionsController extends Controller
{

    public function subscription($slug)
    {
        try
        {
            $user = auth()->user();
            $countries = DB::table('user_country')->get();
            $intent = $user->createSetupIntent(['payment_method_types' => ['card'], ]);
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $plan = SubscriptionPlan::where('slug', $slug)->first();
            $cards = PaymentCard::where('user_id',auth()->user()->id)->limit(5)->get();
            $country_id = DB::table('user_country')->where('shortcode',auth()->user()->country)->first();
            $now = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));
            $countryId = (!empty($country_id)) ? $country_id->id : null;
            $statsList = DB::table('states')->where('country_id',$countryId)->get();
            if ($plan)
            {
                $checkSubsc = Subscription::where('user_id',$user->id)->where('plan_id',$plan->id)->where('is_canceled','0')->first();
                if(!empty($checkSubsc) ){
                    return redirect()->back()->with('error','You have already subscribed  this plan.');
                }
                if ($plan->plan_type == '3')
                {
                    $user = User::find(Auth::id());
                    $user->plan = $plan->id;
                    $user->duration = $plan->plan_type;
                    $user->status = '1';
                    $user->credits = $plan->credits;
                    $user->save();

                    $addSubsc = new Subscription;
                    $addSubsc->user_id = Auth::id();
                    $addSubsc->name = $plan->name;
                    $addSubsc->stripe_id = $plan->stripe_plan_id;
                    $addSubsc->plan_id = $plan->id;
                    $addSubsc->stripe_status = 'Active';
                    $addSubsc->stripe_price = $plan->stripe_plan_id;
                    $addSubsc->quantity = '1';
                    $addSubsc->status = '1';
                    $addSubsc->full_name = $user->name;
                    $addSubsc->email = $user->email;
                    $addSubsc->country = $user->country;
                    $addSubsc->start_at = $now;
                    $addSubsc->state = $user->state;
                    $addSubsc->city = $user->city;
                    $addSubsc->postal_code = $user->postal_code;
                    $addSubsc->address = $user->address;
                    $addSubsc->save();

                    if (env('IS_NOTIFY_ENABLE', false) == true) {
                        $userdata = User::where('id',$user->id)->first();
                        Notification::send($userdata, new PayementNotification($addSubsc));
                    }
                    return redirect('dashboard');

                }
                else
                {
                    $stripe_key = env('STRIPE_KEY');
                    return View('front-pages.plan-detail', compact('plan', 'intent','countries','cards','statsList'));
                }

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
            return back()->with('error', $e->getMessage());
        }

    }
    public function subscriptionCallback(Request $request){
        dd($request->all());

    }
    public function subscriptionCreate(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'plan' => 'required',
            'country' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'card_token' => 'required',
        ],         
        [
            'card_token.required' => 'Please select atleast one card',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }*/
        try
        {
            $plan = SubscriptionPlan::where('stripe_plan_id',$request->plan)
                ->first();
            if(empty($plan)){
                return redirect()->back()->with('error', 'Plan not found');
            }
            $user = Auth::user();
            $token = $request->stripeToken;
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            
            
           if (empty($request->cardId)) {
                return back()->with('error', 'Please select atleast one card');
            }
            $userCard = $user->payment_cards()->where('id',$request->card_token)->where('user_id',Auth::user()->id)->first();
            if (empty($userCard)) {
                return redirect()->back()->with('error', 'Card not found.');
            } 

            $cust_id = $user->stripe_id;
            /*----------Create Customer---------------------*/
            if (empty($cust_id))
            {
                $customer = $stripe->customers->create(['description' => 'Customer ' . $user->email . ' Create', 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'payment_method' => 'pm_card_visa', 'address' => ['line1' =>  $request->address, 'postal_code' =>  $request->postal_code, 'city' => $request->city, 'state' =>  $request->state, 'country' =>  $request->country ], ]);

                $cust_id = $customer->id;
            }

            if(empty($user->country)){
                $customer = $stripe->customers->update($cust_id,['description' => 'Customer ' . $user->email . ' Create', 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phon, 'address' => ['line1' =>  $request->address, 'postal_code' =>  $request->postal_code, 'city' => $request->city, 'state' =>  $request->state, 'country' =>  $request->country ], ]);
            }
            $user->stripe_id = $cust_id;
            $user->stripe_customer_id = $cust_id;
            $user->country = (empty($user->country))? $request->country :$user->country;
            $user->state = (empty($user->state))? $request->state :$user->state;
            $user->city = (empty($user->city))? $request->city :$user->city;
            $user->postal_code = (empty($user->postal_code))? $request->postal_code :$user->postal_code;
            $user->line1 = (empty($user->line1))? $request->address :$user->line1;
            $user->save();
            
                $paymentMethod =$stripe->paymentMethods->retrieve(
                  $userCard->token,
                  []
                );

                $payment_method = $user->updateDefaultPaymentMethod($paymentMethod);

                 $checkSubscribe = Subscription::where('user_id', Auth::id())
                    ->where('is_canceled', '0')
                    ->where('status', '1')
                    ->where('subscription_id','!=','')
                    ->first();

                /*----------Create Subscription---------*/
                if (empty($checkSubscribe))
                {
                    $subscription = $stripe->subscriptions->create(['customer' => $cust_id, 'items' => [['price' => $request->plan], ], 'expand' => ['latest_invoice.payment_intent'], 'off_session' => true, ]);

                } else{

                    $subsc  = $stripe->subscriptions->retrieve(
                      $checkSubscribe->subscription_id,
                      []
                    );
                    
                    $subscription =  $stripe->subscriptions->update($checkSubscribe->subscription_id, [
                      'cancel_at_period_end' => false,
                      'off_session' => true,
                      'proration_behavior' => 'always_invoice',
                      'items' => [
                        [
                          'id' => @$subsc->items->data[0]->id,
                          'price' => $request->plan,
                        ],
                      ],
                    ]);
                }
                //dd($subscription);
               
                /*if($subscription->latest_invoice->payment_intent==null)
                {
                    return redirect()->back()->with('success','client secret not found');
                    
                }*/

                $subs = Subscription::where('user_id', Auth::id())
                    ->where('is_canceled', '0')
                    ->first();
               
                if ($subs)
                {
                    $subs->is_canceled = '1';
                    $subs->status = '4';
                    $subs->stripe_status = 'Cancelled';
                    $subs->canceled_date = date('Y-m-d');
                    $subs->save();
                    /*if(is_null($subs->subscription_id)==false) {
                        $cancelSubscription = $stripe
                            ->subscriptions
                            ->cancel($subs->subscription_id, []);
                    }*/
                }
                $user->plan = $plan->id;
                $user->duration = $plan->plan_type;
                $user->plan_started_at = date('Y-m-d', $subscription->current_period_start);
                $user->plan_expired_at = date('Y-m-d', $subscription->current_period_end);
                $user->credits = $user->credits + $plan->credits;
                $user->status = '1';
                $user->save();

                $addSubsc = new Subscription;
                $addSubsc->user_id = Auth::id();
                $addSubsc->name = $plan->name;
                $addSubsc->plan_id = $plan->id;
                $addSubsc->stripe_id = $cust_id;
                $addSubsc->stripe_status = $subscription->status;
                $addSubsc->stripe_price = $plan->stripe_plan_id;
                $addSubsc->quantity = '1';
                $addSubsc->subscription_id = $subscription->id;
               /* $addSubsc->hosted_invoice_url = $subscription
                    ->latest_invoice->hosted_invoice_url;*/
                $addSubsc->invoice_id = (empty($checkSubscribe)) ? $subscription
                    ->latest_invoice->id : $subscription
                    ->latest_invoice;
                $addSubsc->status = ($subscription->status=='active') ? '1':'2';
                $addSubsc->start_at = date('Y-m-d', $subscription->current_period_start);
                $addSubsc->ends_at = date('Y-m-d', $subscription->current_period_end);
                $addSubsc->full_name = $request->full_name;
                $addSubsc->email = $request->email;
                $addSubsc->country = $request->country;
                $addSubsc->state = $request->state;
                $addSubsc->city = $request->city;
                $addSubsc->postal_code = $request->postal_code;
                $addSubsc->address = $request->address;
                $addSubsc->save();

               
                $invoice  = $stripe->invoices->retrieve(
                      $addSubsc->invoice_id,
                      []
                );
               
                if(!empty($invoice)){
                    $invoice_id = 'Invoice-'.$invoice->number;
                    $invoicedetail = @$invoice->lines->data;
                    $invoice_detail = collect($invoicedetail)->sortBy('count')->reverse()->toArray();
                    $fileName= ''.$invoice_id.'.pdf';
                    
                    $content = [
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'invoice_id' => $invoice_id,
                        'invoice_detail' => $invoice_detail,
                        'email' => $user->email,
                        'name' => $user->name,
                        'plan_name' => $plan->name,
                        'fileName' => $fileName
                    ];
                    
                    
                    if (env('IS_MAIL_ENABLE', false) == true) {
                       
                        Mail::to($user->email)->send(new SubscriptionMail($content));
                    }
                    
                }
                
                Helper::transactionHistory($user->id,'2','2',$plan->price,$plan->credits,'You'.$plan->name.' has been successfully activate ','1');
                Helper::transactionHistory($user->id,'2','2',$plan->price,$plan->credits,'credits '.$plan->credits.' has been credited to your account ','1');

                return redirect()->route('dashboard')->with('success', 'Welcome to Reachomation! Your Plan has been successfully activate');
        }
        catch(\Exception $e)
        {
            return back()->withErrors(['message' => 'Error creating subscription. ' . $e->getMessage() ]);
        }

    }

     public function purchaseCredit(Request $request)
    {

        $user = Auth::user();
        $token = $request->stripeToken;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $cust_id = $user->stripe_id;
            try
            {

                if (isset($request->card_token) && !empty($request->card_token)) {
                    $userCard = $user->payment_cards()->where('id', $request->card_token)->where('user_id',Auth::user()->id)->first();
                    if(empty($userCard)) {
                        return redirect()->back()->with('error', 'Card not found.');
                    } 
                    $paymentMethod =$stripe->paymentMethods->retrieve(
                      $userCard->token,
                      []
                    );
                    $paymentIntent = $stripe->paymentIntents->create(['amount' => $request->price * 100, 'description' => "Payment for user " . $user->email . " purchase credit", 'shipping' => ['name' => $user->name, 'address' => ['line1' => $request->line1, 'postal_code' => $request->postal_code, 'city' => $request->city, 'state' => $request->state, 'country' => $request->country, ], ], 'currency' => 'USD', 'payment_method_types' => ['card'], 'off_session' => true, 'confirm' => true, 'customer' => $cust_id, 'payment_method' => $paymentMethod, 'confirm' => true, ]);

                    $user->credits = $user->credits + $request->credits;
                    $user->save();
                    $content = [
                        'name' => $user->name,
                        'credits' => $request->credits,
                        'total' => $user->credits,

                    ];
                    if (env('IS_MAIL_ENABLE', false) == true) {
                        Mail::to($user->email)->send(new CreditRechargeMail($content));
                    }

                    Helper::transactionHistory($user->id,'1','1',$request->price,$request->credits,''.$request->credits.' Credit has been added..','1');
                }else{
                    return back()
                    ->with('error', 'Please select atleast one card');
                }
            }
            catch(\Exception $e)
            {
                return back()->withErrors(['message' => 'Error creating . ' . $e->getMessage() ]);
            }

            return back()
                    ->with('success', 'Credit Added Successfully.');
        

    }
    public function purchaseCreditModel(Request $request)
    {

        try{
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $data = $request->all();
        $user = auth()->user();
        /*--Request Credit-*/
        $price = $request->credits/100;
        $session = $stripe->checkout->sessions->create([
            'line_items' => [[
               'name' => 'Buy More Credit',
                'description' => ' '.$request->credits.' Credit Purchase in  $'.$price.'',
                'amount' => $price*100,
                'currency' => 'usd',
                'quantity' => 1,
            ]],
            'metadata' => ['credits' => $request->credits,'price'=>$price,'type'=>'credit'],
            'mode' => 'payment',
            'customer' => $user->stripe_id,
            'success_url' => ''.route('dashboard').'',
            'cancel_url' => ''.route('dashboard').'',
          ]);
            return redirect($session->url);
          
        }
        catch(Exception $e)
        {
            \Log::info($e->getMessage());
            return back()->with('error', $e->getMessage());
        }

    }

     public function cancelSubscription($subscription_id)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $subs = Subscription::where('user_id', Auth::id())->whereNotNull('subscription_id')
                    ->where('subscription_id',$subscription_id)
                    ->first();
        $user = Auth::user();
        if ($subs)
        {
            $subs->is_canceled = '1';
            $subs->status = '4';
            $subs->stripe_status = 'Cancelled';
            $subs->canceled_date = date('Y-m-d');
            $subs->save();
            $cancelSubscription = $stripe
                ->subscriptions
                ->cancel($subs->subscription_id, []);

            $plan = SubscriptionPlan::where('id','1')->first();

            $user->plan = $plan->id;
            $user->duration = $plan->plan_type;
            $user->status = '1';
            $user->save();
            $now = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));

            $addSubsc = new Subscription;
            $addSubsc->user_id = Auth::id();
            $addSubsc->name = $plan->name;
            $addSubsc->stripe_id = $plan->stripe_plan_id;
            $addSubsc->plan_id = $plan->id;
            $addSubsc->stripe_status = 'Active';
            $addSubsc->stripe_price = $plan->stripe_plan_id;
            $addSubsc->quantity = '1';
            $addSubsc->status = '1';
            $addSubsc->full_name = $user->name;
            $addSubsc->email = $user->email;
            $addSubsc->country = $user->country;
            $addSubsc->start_at = $now;
            $addSubsc->state = $user->state;
            $addSubsc->city = $user->city;
            $addSubsc->postal_code = $user->postal_code;
            $addSubsc->address = $user->address;
            $addSubsc->save();

            if (env('IS_NOTIFY_ENABLE', false) == true) {
                $userdata = User::where('id',$user->id)->first();
                Notification::send($userdata, new PayementNotification($subs));
            }
             return redirect('billing')
                    ->with('success', 'Subscription Cancelled Successfully.');
        } else {
             return back()->with('error', 'Subscription not found');
        }

    }
   

}

