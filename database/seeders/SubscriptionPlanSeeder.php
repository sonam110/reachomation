<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use Str;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deletePackages = SubscriptionPlan::get();
        foreach ($deletePackages as $key => $pack) {
        	if(!empty($pack->stripe_plan_id)) {
	            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
	            $stripe->plans->delete(
	                $pack->stripe_plan_id,
	                []
	            );
	        }
	        $package = SubscriptionPlan::where('id',$pack->id)->truncate();
        }
        $plan = new SubscriptionPlan;
        $plan->name = 'Free Forever';
        $plan->slug = Str::slug('Free Forever');
        $plan->price = '0';
        $plan->monthly_price = '0';
        $plan->credits = '100';
        $plan->description = 'DA, TF, Social profiles, SEMrush data, Publishing Frequency';
        $plan->parallel_users = '1';
        $plan->size_limit = '10000';
        $plan->templates = '30+';
        $plan->plan_type = '3';
        $plan->site_features = '0';
        $plan->import_sites = '0';
        $plan->geo_locations = '1';
        $plan->customer_support = '0';
        $plan->chrome_addon = '1';
        $plan->traffic_history = '1';
        $plan->automation = '0';
        $plan->mail_connect = '1';
        $plan->reporting = '1';
        $plan->buy_more_credit = '0';
        $plan->save();

        $plan1 = new SubscriptionPlan;
        $plan1->name = 'Starter';
        $plan1->slug = Str::slug('monthly-Starter');
        $plan1->price = '99';
        $plan1->monthly_price = '99';
        $plan1->credits = '15000';
        $plan1->description = 'DA, TF, Social profiles, SEMrush data, Publishing Frequency';
        $plan1->parallel_users = '2';
        $plan1->size_limit = '10000';
        $plan1->templates = '30+';
        $plan1->plan_type = '1';
        $plan1->site_features = '1';
        $plan1->import_sites = '1';
        $plan1->geo_locations = '1';
        $plan1->customer_support = '1';
        $plan1->customer_support = '1';
        $plan1->chrome_addon = '1';
        $plan1->traffic_history = '1';
        $plan1->automation = '1';
        $plan1->mail_connect = '4';
        $plan1->reporting = '1';
        $plan1->buy_more_credit = '1';
        $plan1->save();

        $plan2 = new SubscriptionPlan;
        $plan2->name = 'Premium';
        $plan2->slug = Str::slug('monthly-Premium');
        $plan2->price = '199';
        $plan2->monthly_price = '199';
        $plan2->credits = '35000';
        $plan2->description = 'DA, TF, Social profiles, SEMrush data, Publishing Frequency';
        $plan2->parallel_users = '10';
        $plan2->size_limit = '10000';
        $plan2->templates = '30+';
        $plan2->plan_type = '1';
        $plan2->site_features = '1';
        $plan2->import_sites = '1';
        $plan2->geo_locations = '1';
        $plan2->customer_support = '1';
        $plan2->customer_support = '1';
        $plan2->chrome_addon = '1';
        $plan2->traffic_history = '1';
        $plan2->automation = '1';
        $plan2->mail_connect = 'Unlimited';
        $plan2->reporting = '1';
        $plan2->buy_more_credit = '1';
        $plan2->save();

        $plan3 = new SubscriptionPlan;
        $plan3->name = 'Starter';
        $plan3->slug = Str::slug('annual-Starter');
        $plan3->price = '948';
        $plan3->monthly_price = '79';
        $plan3->credits = '15000';
        $plan3->description = 'DA, TF, Social profiles, SEMrush data, Publishing Frequency';
        $plan3->parallel_users = '10';
        $plan3->size_limit = '10000';
        $plan3->templates = '30+';
        $plan3->plan_type = '2';
        $plan3->site_features = '1';
        $plan3->import_sites = '1';
        $plan3->geo_locations = '1';
        $plan3->customer_support = '1';
        $plan3->customer_support = '1';
        $plan3->chrome_addon = '1';
        $plan3->traffic_history = '1';
        $plan3->automation = '1';
        $plan3->mail_connect = '4';
        $plan3->reporting = '1';
        $plan3->buy_more_credit = '1';
        $plan3->save();

        $plan4 = new SubscriptionPlan;
        $plan4->name = 'Premium';
        $plan4->slug = Str::slug('annual-Premium');
        $plan4->price = '1908';
        $plan4->monthly_price = '159';
        $plan4->credits = '35000';
        $plan4->description = 'DA, TF, Social profiles, SEMrush data, Publishing Frequency';
        $plan4->parallel_users = '10';
        $plan4->size_limit = '10000';
        $plan4->templates = '30+';
        $plan4->plan_type = '2';
        $plan4->site_features = '1';
        $plan4->import_sites = '1';
        $plan4->geo_locations = '1';
        $plan4->customer_support = '1';
        $plan4->customer_support = '1';
        $plan4->chrome_addon = '1';
        $plan4->traffic_history = '1';
        $plan4->automation = '1';
        $plan4->mail_connect = 'Unlimited';
        $plan4->reporting = '1';
        $plan4->buy_more_credit = '1';
        $plan4->save();

        $getAllPlans = SubscriptionPlan::get();
        foreach ($getAllPlans as $key => $value) {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $planId = null;
            $imagePath = "".env('APP_URL')."/images/reachomation_white_logo.png";
            if($value->plan_type =='1'){
            	$interval = 'month';
            } else{
            	$interval = 'year';
            }
            $createProduct = $stripe->products->create([
                'images'    => [$imagePath],
                'name'      => ucfirst($value->name),
                'type'      => 'service',
                'active'    => ($value->status==1) ? true : false
            ]);
         	$planCreate = $stripe->plans->create([
                'amount'          => $value->price * 100,
                'currency'        => 'USD',
                'interval'        => $interval,
                'product'         => $createProduct->id,
            ]);
            $planId = $planCreate->id;

            $updateStripeId = SubscriptionPlan::where('id',$value->id)->update(['stripe_plan_id'=>$planId]);
        	
        }


    }
}
