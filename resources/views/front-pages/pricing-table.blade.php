@php
$plan_id ='' ;
$plan_type ='' ;
$headings ='' ;
$parallel_users ='' ;
$size_limit ='' ;
$templates ='' ;
$description ='' ;
$site_features ='' ;
$geo_locations ='' ;
$customer_support ='' ;
$chrome_addon ='' ;
$traffic_history ='' ;
$automation ='' ;
$mail_connect ='' ;
$reporting ='' ;
$stripe_plan_id ='' ;
$button ='' ;
$credits ='' ;
$m_credits ='' ;
$buy_more_credit ='' ;
$countN = 0 ;


@endphp

@foreach($plans as $plan)
<?php
   if($plan->plan_type =='1'){
      $type = 'mo';
   } else{
      $type = 'mo';
   }
   $class ="";
   $price = "$".$plan->monthly_price."/".$type."";
   if($plan->plan_type =="3"){
      $class ="bg-white";
      // $class = "active"
      $price= '';
   }
  
   $sitefeatures  = ($plan->site_features) ? 'Yes':'No';
   $geolocations  = ($plan->geo_locations) ? 'Yes':'No';
   $customersupport  = ($plan->customer_support) ? 'Yes':'No';
   $traffichistory  = ($plan->traffic_history) ? 'Yes':'No';
   $auto_mation  = ($plan->automation) ? 'Yes':'No';
   $mailconnects  = $plan->mail_connect;
   $reportings  = ($plan->reporting) ? 'Yes':'No';
   $buy_more_credits = ($plan->buy_more_credit) ? 'Yes':'No';
   $plan_id  .= $plan->id;
   $parallel_users  .= $plan->parallel_users;
   $size_limit  .= $plan->size_limit;
   $m_credits  .= $plan->credits;
   $templates  .= $plan->templates;
   $description  .= $plan->description;
   $site_features  .= $sitefeatures;
   $geo_locations  .= $geolocations;
   $customer_support  .= $customersupport;
   $traffic_history  .= $traffichistory;
   $automation  .= $auto_mation;
   $mail_connect  .= $mailconnects;
   $reporting  .= $reportings;
   $buy_more_credit  .= $buy_more_credits;
   $stripe_plan_id  .= $plan->stripe_plan_id;
   $credits  .= $plan->credits;
   $plan_type  .= $plan->plan_type;

   if (\Auth::check()) {
      $checkPlan = App\Models\Subscription::where('user_id',auth()->user()->id)->where('plan_id',$plan->id)->orderBy('id','DESC')->where('status','!=','4')->first();
   } else{
      $checkPlan = NULL;
   }
   $countN= $countN+1;


   
   ?>

<div class="col-md-4">
   <div class="inner holder @if($plan->name=="Starter") active @endif">
      <div class="hdng">
         <p>{{$plan->name}} </p>
      </div>
      <div class="price mt-2">
         <p class="mb-0">
            <b>
              @if ($plan->name=="Free Forever")
                  $0
              @else
              ${{$plan->monthly_price}}
              @endif 
            </b>
            <span> / mo</span></p>
      </div>
      <div class="mb-4">
         <!-- --------Free plan  -->
         @if (\Auth::check())
            @if (!empty($checkPlan) && $checkPlan->plan_id =="1")
           <!--  <button class="btn btn-green shadow-none rounded-pill px-5 disabled">
               InActive <i class="bi bi-chevron-double-right"></i>
            </button> -->
            @elseif  (!empty($checkPlan) && $checkPlan->plan_id == auth()->user()->plan)
            <button class="btn btn-green shadow-none rounded-pill px-5 disabled">
               Active <i class="bi bi-chevron-double-right"></i>
            </button> 
            @else
            @if(auth()->user()->plan =='1')
            <a href='{{ route("user-checkout",$plan->slug) }}' class="btn {{ ($countN =='2') ? 'btn-light':'btn-green'}} shadow-none rounded-pill px-5">
               Subscribe <i class="bi bi-chevron-double-right"></i>
            </a>
            @else
               <a href='{{ route("upgrade-user-plan") }}' class="btn {{ ($countN =='2') ? 'btn-light':'btn-green'}} shadow-none rounded-pill px-5">
               Subscribe <i class="bi bi-chevron-double-right"></i>
            </a>
            @endif   
            @endif
         @else
            <a href='{{ route("login") }}' class="btn {{ ($countN =='2') ? 'btn-light':'btn-green'}} shadow-none rounded-pill px-5">
            Subscribe <i class="bi bi-chevron-double-right"></i>
         </a>
         @endif
        
      </div>

      <div class="info">
         <p>Parallel users - {{ $plan->parallel_users }}</p>
         <p>List size limit - {{ $plan->size_limit }}</p>
         <p>Monthly credits - {{ $plan->credits }}</p>
         <p>Email accounts <i class="bi bi-info-circle-fill text-primary" role="button" tabindex="0"
               data-bs-toggle="tooltip"
               data-bs-title="This describes the number of email accounts you can connect for outreach automation"></i>
            - {{ $plan->mail_connect }}</p>
         <p>'Import sites' feature - {{ $sitefeatures }}</p>
         <p>Skype & chat support - {{ $customersupport }}</p>
         <p>Personalized Outreach templates - {{ $plan->templates }}</p>
         <p>Geo-location and TLD filters - {{ $geolocations }}</p>
         <p>Site traffic history - {{ $traffichistory }}</p>
         <p>Performance Reporting - {{ $reportings }}</p>
         <p>Website metrics - {{ $plan->description }}</p>
      </div>
   </div>
</div>
@endforeach


