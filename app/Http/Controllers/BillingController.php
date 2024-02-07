<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Auth;
use App\Models\Subscription;
use App\Models\PaymentCard;
use App\Models\User;
use Stripe;
use Carbon\Carbon;
use PDF;
use Storage;
use Exception;
use Illuminate\Support\Facades\File;
class BillingController extends Controller {

       
    public function index(Request $request) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $user = auth()->user();
        $userplan = SubscriptionPlan::where('id', Auth::user()->plan)->where('status', '1')->first();
        $credits = ($userplan) ? $userplan->credits : 0;
        $type = 'Free';
        if (isset($userplan) && $userplan->plan_type == '1') {
            $type = " Monthly";
        }
        if (isset($userplan) && $userplan->plan_type == '2') {
            $type = " Yearly";
        }
        $subscriptions = Subscription::where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(10);

        if (is_null($userplan) == false && $userplan->plan_type != '3') {
            $data = $this->getNextBillDetail();
            $next_bill_date = $data['next_payment_attempt_date'];
        } else {
            $next_bill_date = 'Free Forever';
        }
       

       $customer = $stripe->customers->retrieve(
          $user->stripe_id,
          []
        );
        $default_payment_method = @$customer->invoice_settings->default_payment_method;
        $cards = $stripe->customers->allPaymentMethods(
          $user->stripe_id,
          ['type' => 'card']
        );


      
        $intent = $user->createSetupIntent(['payment_method_types' => ['card'], ]);
        
        
        return view('billing', compact('userplan', 'type', 'credits', 'subscriptions', 'next_bill_date','cards','intent','default_payment_method'));
    }

    public function downloadInvoice($id) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $userid = Auth::user();
        $addSubsc = Subscription::where('user_id', $userid->id)->where('invoice_id', $id)->orderBy('id', 'DESC')->first();
        if(empty($addSubsc)){
            return redirect()->back()->with('error', 'No Subscription found');
        }
        try{
                $invoice  = $stripe->invoices->retrieve(
                      $addSubsc->invoice_id,
                      []
                );

                if(!empty($invoice)){
                    //dd($invoice);

                   /* $invoice_id = 'Invoice-'.$invoice->number;
                    $invoicedetail = @$invoice->lines->data;
                    $invoice_detail = collect($invoicedetail)->sortBy('count')->reverse()->toArray();
                    $plan =SubscriptionPlan::where('id',$addSubsc->plan_id)->first();
                    $userInfo =User::where('id',$userid->id)->first();
                    $data = [
                        'plan' => $plan,
                        'userInfo' => $userInfo,
                        'invoice_id' => $invoice_id,
                        'invoice_detail' => $invoice_detail,
                        
                    ];
                    
                    $fileName= ''.$invoice_id.'.pdf';
                    //return view('invoice',$data);
                    $pdf = PDF::loadView('invoice', $data);
                    if(Storage::disk('s3')->exists($fileName)){
                        return Storage::disk('s3')->download($fileName);
                    } else{
                        $storagePath = Storage::disk('s3')->put($fileName,$pdf->output(), 'public');
                        return Storage::disk('s3')->download($fileName);
                    }*/
                    return redirect($invoice->hosted_invoice_url);

                } else{
                    return redirect()->back()->with('error', 'Opps! Something went wrong.');
                }
                
            
        }catch(Exception $exception) {
           return redirect()->back()->with('error',$exception->getMessage());
            
        }
    }
    public function viewSubscription($id) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $userid = Auth::user();
        $addSubsc = Subscription::where('user_id', $userid->id)->where('invoice_id', $id)->orderBy('id', 'DESC')->first();
        if(empty($addSubsc)){
            return redirect()->back()->with('error', 'No Subscription found');
        }
        try{
                $invoice  = $stripe->invoices->retrieve(
                      $addSubsc->invoice_id,
                      []
                );

                if(!empty($invoice)){

                    $invoice_id = 'Invoice-'.$invoice->number;
                    $invoicedetail = @$invoice->lines->data;
                    $invoice_detail = collect($invoicedetail)->sortBy('count')->reverse()->toArray();
                    
                    return redirect($invoice->hosted_invoice_url);
                   /* dd($invoice_detail);

                    $plan =SubscriptionPlan::where('id',$addSubsc->plan_id)->first();
                    $userInfo =User::where('id',$userid->id)->first();
                    $data = [
                        'plan' => $plan,
                        'userInfo' => $userInfo,
                        'invoice_id' => $invoice_id,
                        'invoice_detail' => $invoice_detail,
                        
                    ];
                    
                    $fileName= ''.$invoice_id.'.pdf';
                    return view('invoice',$data);*/
                    

                } else{
                    return redirect()->back()->with('error', 'Opps! Something went wrong.');
                }
                
            
        }catch(Exception $exception) {
           return redirect()->back()->with('error',$exception->getMessage());
            
        }
    }

    public function getNextBillDetail() {
        $userid = auth()->user();
        $StripeSecret = env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($StripeSecret);
        $customerId = $userid->stripe_id;
        try{
        $upcomingInvoice = \Stripe\Invoice::upcoming(["customer" => $customerId]); // from https://stripe.com/docs/api/invoices/upcoming

        $nextPaymentAttemptTimestamp = $upcomingInvoice->next_payment_attempt;
        $nextPaymentAttemptDate = Carbon::createFromTimeStamp($nextPaymentAttemptTimestamp)->format('F jS, Y');

        return [
            'amount_due' => $upcomingInvoice->amount_due,
            'next_payment_attempt_date' => $nextPaymentAttemptDate,
            'full_invoice' => $upcomingInvoice,
        ];
      
      }catch(Exception $exception) {
           return [
            
            'next_payment_attempt_date' => '',
            
        ];
            
      }
    }

     public function store(Request $request)
    {
        /*$validated = $request->validate([
            'stripeToken' => 'required'
        ]);*/

        $user = auth()->user();
        $token =  $request->stripeToken;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $userinfo = Auth::user();
        $cust_id = (!empty($user)) ? $user->stripe_id : NULL;

        try
        {
            $paymentMethod = $request['payment-method'];
              if (empty($cust_id)) {
                // Create a Stripe customer
                $getCust = $stripe->customers->create([
                    'name'              => $user->name,
                    'phone'             => $user->phone,
                    'email'             => $user->email,
                    'description'       => 'New Customer added',
                ]);
                // Set the Stripe id for our user
                $cust_id = $getCust->id;
                $userinfo->stripe_id = $getCust->id;           
                $userinfo->stripe_customer_id = $getCust->id;           
                $userinfo->save();
                // Add our first card to our Stripe customer
                $payment = $stripe->paymentMethods->attach($paymentMethod,['customer' => $cust_id]);
            
                $system_card = $userinfo->payment_cards()->create([
                    'token' => $payment->id,
                    'name' => $request->card_holder_name,
                    'is_default' => false,
                    'details' => [
                        'brand' => $payment['card']['brand'],
                        'exp_month' => $payment['card']['exp_month'],
                        'exp_year' => $payment['card']['exp_year'],
                        'last4' => $payment['card']['last4']
                    ]
                ]);

                $updateDefault  = $user->updateDefaultPaymentMethod($paymentMethod);
            
            } else {
                $payment = $stripe->paymentMethods->attach($paymentMethod,['customer' => $cust_id]);
               //dd($payment);
                $system_card = $userinfo->payment_cards()->create([
                    'token' => $payment->id,
                    'name' => $request->card_holder_name,
                    'is_default' => false,
                    'details' => [
                        'brand' => $payment['card']['brand'],
                        'exp_month' => $payment['card']['exp_month'],
                        'exp_year' => $payment['card']['exp_year'],
                        'last4' => $payment['card']['last4']
                    ]
                ]);
            }

           return redirect()->back()->with('success', 'Card Save successfully.');
        }
        catch(\Exception $e)
        {
            return back()->withErrors(['message' => 'Error creating . ' . $e->getMessage() ]);
        }
    }

     public function destroy(Request $request)
    {
        $userinfo = Auth::user();
        $card = $userinfo->payment_cards()->where('id', $request->card_id)->where('user_id',Auth::user()->id)->first();
        if (empty($card)) {
            return redirect()->back()->with('error', 'Card not found.');
        }
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        if ($card->is_default === true) {
            return redirect()->back()->with('error', 'Unable to delete a users default card.');
        }
        try
        {
            $stripe->paymentMethods->detach(
                $card->token,
                []
            );
            $PaymentCard = PaymentCard::where('id',$request->card_id)->delete();
            return redirect()->back()->with('success', 'Card delete successfully.');
        }
        catch(\Exception $e)
        {
            return back()->withErrors(['message' => 'Error creating delete card. ' . $e->getMessage() ]);
        }
    }
    public function default(Request $request)
    {
        // Fetch our new default card
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $userinfo = Auth::user();
        $system_card = $userinfo->payment_cards()->where('id',$request->cardid)->where('user_id',Auth::user()->id)->first();

        if (empty($system_card)) {
            return redirect()->back()->with('error', 'Card not found.');
        }
        try
        {
            // Update Stripe customer
            $paymentMethod = $stripe->paymentMethods->retrieve(
              $system_card->token,
              []
            );
            $payment_method = $userinfo->updateDefaultPaymentMethod($paymentMethod);

            User::where('id',$userinfo->id)->update(['default_card'=>$system_card->token]);
            // Update locally stored customer data
            $userinfo->payment_cards()->where('is_default', true)->update(['is_default' => false]);
            $userinfo->payment_cards()->where('token', $system_card->token)->update(['is_default' => true]);

            return redirect()->back()->with('success', 'Set successfully.');
        }
        catch(\Exception $e)
        {
            return back()->withErrors(['message' => 'Error creating default setting. ' . $e->getMessage() ]);
        }
    }

     public function addCardModel(Request $request) 
    {
        return View('add-card-model');
        
    }


}
