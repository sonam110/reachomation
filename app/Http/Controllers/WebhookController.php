<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Stripe;
use Log;
use Helper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayementNotification;
use App\Notifications\CreditNotification;
use App\Mail\SubscriptionMail;
use App\Mail\CreditRechargeMail;
class WebhookController extends Controller
{
    public function stripeWebhook(Request $request)
    {

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try
        {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } 
        catch(\UnexpectedValueException $e)
        {
            Log::channel('webhook')->info('Invalid payload');
            Log::channel('webhook')->info($payload);
            http_response_code(400);
            exit();
        }
        catch(\Stripe\Exception\SignatureVerificationException $e)
        {
            Log::channel('webhook')->info('Invalid signature');
            Log::channel('webhook')->info($e);
            http_response_code(400);
            exit();
        }

        
        Log::channel('webhook')->info($event->type);
        if ($event->type == "customer.subscription.created")
        { 
            $subscriptionSchedule = $event->data->object;
            $subscription_id = $subscriptionSchedule->id;
            $this->customerSubscriptionCreatedUpdated($subscription_id, $subscriptionSchedule);
            Log::channel('webhook')->info($subscription_id);
            Log::channel('webhook')->info($subscriptionSchedule);
          // Log::channel('webhook')->info($subscriptionSchedule);
        }
        if ($event->type == "customer.subscription.updated")
        { 
            $subscriptionSchedule = $event->data->object;
            $subscription_id = $subscriptionSchedule->id;
            $this->customerSubscriptionCreatedUpdated($subscription_id, $subscriptionSchedule);
            Log::channel('webhook')->info($subscription_id);
            Log::channel('webhook')->info($subscriptionSchedule);
          // Log::channel('webhook')->info($subscriptionSchedule);
        }
        elseif ($event->type == "customer.subscription.deleted" || $event->type == "subscription_schedule.aborted" || $event->type == "subscription_schedule.canceled") {
            $subscriptionSchedule = $event->data->object;
            $subscription_id = $subscriptionSchedule->id;
            $this->abortedSubscription($subscription_id);
            //Log::channel('webhook')->info($subscriptionSchedule);
        }
        elseif ($event->type == "invoice.payment_succeeded") {
            $subscriptionSchedule = $event->data->object;
            $invoice_id = $subscriptionSchedule->id;
            $status = $subscriptionSchedule->status;
            $finalized_date = @$subscriptionSchedule->status_transitions->finalized_at;
            $this->paymentStatus($invoice_id, $status, $finalized_date);
            Log::channel('webhook')->info($subscriptionSchedule);
        }
        elseif ($event->type == "invoice.payment_failed") {
            $subscriptionSchedule = $event->data->object;
            $invoice_id = $subscriptionSchedule->id;
            $status = $subscriptionSchedule->status;
            $finalized_date = @$subscriptionSchedule->status_transitions->finalized_at;
            $this->paymentStatus($invoice_id, $status, $finalized_date);
            Log::channel('webhook')->info($subscriptionSchedule);
        }
        elseif ($event->type == "checkout.session.completed") {
            $paymentData = $event->data->object;
            $status = $paymentData->status;
            $this->paymentCreditPurchaseStatus($status, $paymentData);
            Log::channel('webhook')->info($paymentData);
        }
        elseif ($event->type == "checkout.session.expired") {
            $paymentData = $event->data->object;
            Log::channel('webhook')->info($paymentData);
        }

        /*elseif ($event->type == "invoice.created") {
            $subscriptionSchedule = $event->data->object;
            $subscription_id = $subscriptionSchedule->id;
            Log::channel('webhook')->info($subscriptionSchedule);
        }
        elseif ($event->type == "invoice.finalized") {
            $subscriptionSchedule = $event->data->object;
            $subscription_id = $subscriptionSchedule->id;
            Log::channel('webhook')->info($subscriptionSchedule);
        }*/

        //Log::channel('webhook')->info('payload');
        //Log::channel('webhook')->info($payload);
        
        http_response_code(200);
    }

   
    private function customerSubscriptionCreatedUpdated($subscription_id, $subscriptionSchedule) 
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

       
        //Log::channel('webhook')->info($subscription_id);
        \Log::info($subscription_id);
        \Log::info('s--------------------------------------');
        \Log::info($subscriptionSchedule);
        $subscribedPackage = Subscription::where('status', 1)
            ->where('subscription_id', $subscription_id)
            ->orderBy('id', 'DESC')
            ->first();
        if(!empty($subscribedPackage)){
            $subscribedPackage->status = 0;
            $subscribedPackage->save();
        }
        $findUser = User::where('stripe_id',$subscriptionSchedule->customer)->first();
        
        if(!empty($findUser) && $subscriptionSchedule->pending_update== NULL)
        {
            
            $plan_id = ($subscriptionSchedule->items->data[0]->plan->id);
            $plan = SubscriptionPlan::where('stripe_plan_id',$plan_id)->first();
            // create UserPackageSubscription
            \Log::info('trigger webhook and added new Subscription. please recheck');
        
            $userPackageSubscription = new Subscription;
            $userPackageSubscription->user_id = $findUser->id;
            $userPackageSubscription->subscription_id = $subscription_id;
            $userPackageSubscription->name = $plan->name;
            $userPackageSubscription->stripe_id = $plan->stripe_plan_id;
            $userPackageSubscription->plan_id = $plan->id;
            $userPackageSubscription->stripe_status = $subscriptionSchedule->status;
            $userPackageSubscription->stripe_price = $plan->stripe_plan_id;
            $userPackageSubscription->quantity = '1';
            $userPackageSubscription->invoice_id =  $subscriptionSchedule->latest_invoice;
            $userPackageSubscription->full_name = $findUser->name;
            $userPackageSubscription->email = $findUser->email;
            $userPackageSubscription->country = $findUser->country;
            $userPackageSubscription->status = ($subscriptionSchedule->status=='active') ? '1':'2';
            $userPackageSubscription->start_at = date('Y-m-d', $subscriptionSchedule->current_period_start);
            $userPackageSubscription->ends_at = date('Y-m-d', $subscriptionSchedule->current_period_end);
            $userPackageSubscription->state = $findUser->state;
            $userPackageSubscription->city = $findUser->city;
            $userPackageSubscription->postal_code = $findUser->postal_code;
            $userPackageSubscription->address = $findUser->address;
            $userPackageSubscription->save();
            if($subscriptionSchedule->status=='active'){

                Helper::transactionHistory($findUser->id,'2','2',$plan->price,$plan->credits,'Your account has been upgraded to '.$plan->name.' ','1');
                Helper::transactionHistory($findUser->id,'1','1',$plan->price,$plan->credits,' Monthly credits','1');
                
                $findUser->plan = $plan->id;
                $findUser->duration = $plan->plan_type;
                $findUser->plan_started_at = date('Y-m-d', $subscriptionSchedule->current_period_start);
                $findUser->plan_expired_at = date('Y-m-d', $subscriptionSchedule->current_period_end);
                $findUser->credits = $findUser->credits + $plan->credits;
                $findUser->status = '1';
                $findUser->save();

                $invoice  = $stripe->invoices->retrieve(
                      $userPackageSubscription->invoice_id,
                      []
                );
               
                if(!empty($invoice)){
                    $invoice_id = 'Invoice-'.$invoice->number;
                    $invoicedetail = @$invoice->lines->data;
                    $invoice_detail = collect($invoicedetail)->sortBy('count')->reverse()->toArray();
                    $fileName= ''.$invoice_id.'.pdf';
                    
                    $content = [
                        'user_id' => $findUser->id,
                        'plan_id' => $plan->id,
                        'invoice_id' => $invoice_id,
                        'invoice_detail' => $invoice_detail,
                        'email' => $findUser->email,
                        'name' => $findUser->name,
                        'plan_name' => $plan->name,
                        'invoice' => $invoice,
                        'fileName' => $fileName
                    ];
                    
                    
                    if (env('IS_MAIL_ENABLE', false) == true) {
                       
                        Mail::to($findUser->email)->send(new SubscriptionMail($content));
                    }
                    
                }
                
                

            }
            
            return 1;
            
        }
        
    }

    private function abortedSubscription($subscription_id) 
    {
        $subscribedPackage = Subscription::where('subscription_id',$subscription_id)->first();
        \Log::info($subscription_id);
        if(!empty($subscribedPackage))
        {
            $subscribedPackage->is_canceled = '1';
            $subscribedPackage->status = '4';
            $subscribedPackage->canceled_date = date('Y-m-d');
            $subscribedPackage->save();
            $userId = $subscribedPackage->user_id;
            //if no active package the assign free package 
            $checkIsPlanExist = Subscription::where('user_id',$userId)->where('status', 1)->orderBy('id', 'DESC')->first();
            if(!$checkIsPlanExist)
            {
                $user =User::where('id',$userId)->first();
                createFreePackage($user);

                if (env('IS_NOTIFY_ENABLE', false) == true) {
                    Notification::send($user, new PayementNotification($subscribedPackage));
                }
            }
        }
    }
    private function paymentStatus($invoice_id, $status, $finalized_date) 
    {
        $subscribedPackage = Subscription::where('invoice_id', $invoice_id)->first();
        if($subscribedPackage)
        {
           
            $subscribedPackage->stripe_status = $status;
            $subscribedPackage->save();
            if($status)
            {

            }
        }

    }


    private function paymentCreditPurchaseStatus($status,$finalized_date) 
    {
        $findUser = User::where('stripe_id',$finalized_date->customer)->first();
        if(!empty($findUser) && $status=='complete'){
            $credits = (@$finalized_date->metadata) ? @$finalized_date->metadata->credits:0;
            $price = (@$finalized_date->metadata) ? @$finalized_date->metadata->price:0;
            if(!empty(@$finalized_date->metadata->price)){
                Helper::transactionHistory($findUser->id,'1','1',$price,$credits,''.$credits.' credits purchased for $'.$price.'','1');

                $findUser->credits = $findUser->credits + $credits;
                $findUser->save();

                $content = [
                    'name' => $findUser->name,
                    'credits' => $credits,
                    'total' => $findUser->credits,
                    'price' => $findUser->price,

                ];
                if (env('IS_MAIL_ENABLE', false) == true) {
                    Mail::to($findUser->email)->send(new CreditRechargeMail($content));
                }
            }
            

        } 

    }
}
