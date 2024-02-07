<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use Stripe;
use Mail;
use App\Mail\PlanExpireMail;
class DeactivateUserPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:deactivate';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $allUsers = User::where('status','1')->get();
        foreach ($allUsers as $key => $user) {
        if($user->plan !='' && $user->plan !='1' && date('Y-m-d',strtotime($user->plan_expired_at)) < date('Y-m-d')){
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $subs = Subscription::where('user_id',$user->id)->whereNotNull('subscription_id')->first();
            
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
                    $content = [
                        'user_id' => $user->id,
                        'plan_id' => $user->plan,
                        'start_date' => $user->plan_started_at,
                        'end_date' => $user->plan_expired_at,
                       
                    ];
                    if (env('IS_MAIL_ENABLE', false) == true) {
                        Mail::to($user->email)->send(new PlanExpireMail($content));
                    }
                    
                    
                }     
                
            }
        }
    }
}
