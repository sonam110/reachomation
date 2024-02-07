<table class="table table-bordered">
   @php  
   $plan_id  ='' ;
   $plan_type  ='' ;
   $headings  ='' ;
   $parallel_users  ='' ;
   $size_limit  ='' ;
   $templates  ='' ;
   $description  ='' ;
   $site_features  ='' ;
   $geo_locations  ='' ;
   $customer_support  ='' ;
   $chrome_addon  ='' ;
   $traffic_history  ='' ;
   $automation  ='' ;
   $mail_connect  ='' ;
   $reporting  ='' ;
   $stripe_plan_id  ='' ;
   $button  ='' ;
   $credits  ='' ;
   $m_credits  ='' ;
   $buy_more_credit  ='' ;
 
  
   $userPlan = auth()->user()->plan;
   

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
      $price= '';
   }

   $headings .= '<th scope="col" class="text-center '.$class.'">
            <h5 class="mb-2">'.$plan->name.'</h5>
            <h2 class="mb-0 fw-bold" id="std-duration">'.$price.'</h2>
         </th>'; 
   $sitefeatures  = ($plan->site_features) ? 'Yes':'No';
   $geolocations  = ($plan->geo_locations) ? 'Yes':'No';
   $customersupport  = ($plan->customer_support) ? 'Yes':'No';
   $chromeaddon  = ($plan->chrome_addon) ? 'Yes':'No';
   $traffichistory  = ($plan->traffic_history) ? 'Yes':'No';
   $auto_mation  = ($plan->automation) ? 'Yes':'No';
   $mailconnects  = $plan->mail_connect;
   $reportings  = ($plan->reporting) ? 'Yes':'No';
   $buy_more_credits  = ($plan->buy_more_credit) ? 'Yes':'No';
   $plan_id  .= $plan->id;
   $parallel_users  .= '<td>'.$plan->parallel_users.'</td>';
   $size_limit  .= '<td>'.$plan->size_limit.'</td>';
   $m_credits  .= '<td>'.$plan->credits.'</td>';
   $templates  .= '<td>'.$plan->templates.'</td>';
   $description  .= '<td>'.$plan->description.'</td>';
   $site_features  .= '<td>'.$sitefeatures.'</td>';
   $geo_locations  .= '<td>'.$geolocations.'</td>';
   $customer_support  .= '<td>'.$customersupport.'</td>';
   $chrome_addon  .= '<td>'.$chromeaddon.'</td>';
   $traffic_history  .= '<td>'.$traffichistory.'</td>';
   $automation  .= '<td>'.$auto_mation.'</td>';
   $mail_connect  .= '<td>'.$mailconnects.'</td>';
   $reporting  .= '<td>'.$reportings.'</td>';
   $buy_more_credit  .= '<td>'.$buy_more_credits.'</td>';
   $stripe_plan_id  .= $plan->stripe_plan_id;
   $credits  .= $plan->credits;
   $plan_type  .= $plan->plan_type;

   $checkPlan = App\Models\Subscription::where('user_id',auth()->user()->id)->where('plan_id',$plan->id)->orderBy('id','ASC')->first();
     
     
            $button  .= '<td><div class="d-grid">
                  <a href="'.route("user-subscription",$plan->slug).'"  class="btn btn-warning btn-sm fw-bold d-grid"  >Subscribe</a>
               </div></td>';
      
      
      
  
   
   ?>
   @endforeach 
   <thead style="background: yellow;">
      <tr class="align-middle">
         <th scope="col" class="text-center" style="width: 30%;">Type</th>
         {!! $headings !!}
        
      </tr>
   </thead>
   <tbody>
      <tr>
         <td colspan="4" class="bg-light">
            <strong>Features</strong>
         </td>
      </tr>
     
      <tr class="text-center">
         <th scope="row" class="fw-normal">Parallel users</th>
        {!!  $parallel_users  !!}
      </tr>

      <tr class="text-center">
         <th scope="row" class="fw-normal">List size limit</th>
        {!!  $size_limit  !!}
         
      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">Credits</th>
       {!! $m_credits !!}
         
      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">Email Account</th>
       {!! $mail_connect !!}
         
      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">'Import sites' feature</th>
        {!!  $site_features  !!}

      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">Additional credit purchase</th>
        {!!  $buy_more_credit  !!}

      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">Chat & Skype support</th>
        {!!  $customer_support  !!}
         
      </tr>
       <tr class="text-center">
         <th scope="row" class="fw-normal">Geo-location and TLD filters</th>
        {!!  $geo_locations  !!}
         
      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">Personalized Outreach templates</th>
        {!!  $templates  !!}
       
      </tr>
       <tr class="text-center">
         <th scope="row" class="fw-normal">Site traffic history</th>
       {!! $traffic_history !!}
      </tr>
       <tr class="text-center">
         <th scope="row" class="fw-normal">Performance Reporting</th>
         {!! $reporting !!}
        
      </tr>
      <tr class="text-center">
         <th scope="row" class="fw-normal">Website Metrics</th>
        {!! $description !!}
      </tr>
      
      <!-- <tr class="text-center">
         <th scope="row" class="fw-normal">Outreach automation/scheduling</th>
        {!! $automation !!}
        
      </tr> -->
      
     
   </tbody>
   <tfoot>
      <tr class="text-center">
         <th scope="row">Price</th>

               {!! $button !!}
            
         
      </tr>
   </tfoot>
</table>
                 