
<div class="modal-header pd-x-20">
	<h6 class="modal-title">Note:If you will used this tools its take some time to process a data and Scheduling campaign will available after whole data is process </h6>
  <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-3 pt-0">
   <div class="mt-4" id="add-list-body">
      <div class="row mb-3">
         <div class="col-sm-12">
         	 <div>
           <h6 class="text-muted fw-500 mt-1">
              File Name : 
              <span class="text-dark" id="list-name">{!! $realfilename !!}</span>
           </h6>
        </div>
        <div>
           <h6 class="text-muted fw-500 mt-1">
              Total Contact : 
              <span class="text-dark" id="list-name">{!! count($excelData) !!}</span>
           </h6>
        </div>
        <div>
           <h6 class="text-muted fw-500 mt-1">
              Imported Contact : 
              <span class="text-dark" id="list-name">{!! count($excelData) !!}</span>
           </h6>
        </div>
        
      <div>
     </div>Verify {!! count($excelData) !!} contacts to remove invalid, temporary and misconfigured emails. This will help in reducing email bounces and improve your email credibility.</div>
           <div class="mt-3">
             <input type="hidden"  name="total_website" id="total_website" value="{!! count($excelData) !!}">
           	 <input type="hidden"  name="is_only_domain" id="is_only_domain" value="{!! $is_only_domain !!}">
             @if($is_only_email == true)
               <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="validate_email"  value="3"  {{ (in_array('3',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Validate emails (1 credit per email validation)</span>
              </label><br>
             @elseif($is_email_domain == true)
             <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="domain_for_blog" value="1"  {{ (in_array('1',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Evaluate domains for 'Blog' (1 credit per domain)</span>
            </label><br>
            <span>Is Including Non Blog?</span>
               <div class="switch-field">
                  <input type="radio" class="NonBlog" id="radio-one" name="switch-one" value="1" >
                  <label for="radio-one">Yes</label>
                  <input type="radio"  class="NonBlog" id="radio-two" name="switch-one" value="0" checked/>
                  <label for="radio-two">No</label>
               </div>
             <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="validate_email" value="3"  {{ (in_array('3',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Validate emails (1 credit per email validation)</span>
            </label><br>
           
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="email_personalize" value="4"  {{ (in_array('4',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Extract personalization data like recent blog article, author name (1 credit per successful extraction)</span>
            </label><br>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="semrush" value="5"  {{ (in_array('5',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Compute SEMrush Traffic and Domain Authority for domain (1 credit per domain)</span>
            </label><br>
           

             @else
           	<label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="domain_for_blog" value="1"  {{ (in_array('1',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Evaluate domains for 'Blog' (1 credit per domain)</span>
            </label><br>
            <span>Is Including Non Blog?</span>
               <div class="switch-field">
                  <input type="radio" class="NonBlog" id="radio-one" name="switch-one" value="1" >
                  <label for="radio-one">Yes</label>
                  <input type="radio"  class="NonBlog" id="radio-two" name="switch-one" value="0" checked/>
                  <label for="radio-two">No</label>
               </div>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="email_extraction" value="2"  {{ (in_array('2',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Extract emails from domain (1 credit per successful extraction)</span>
            </label><br>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="validate_email" value="3"  {{ (in_array('3',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Validate emails (1 credit per email validation)</span>
            </label><br>
           
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="email_personalize" value="4"  {{ (in_array('4',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Extract personalization data like recent blog article, author name (1 credit per successful extraction)</span>
            </label><br>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="colorinput-input custom-control-input myCheckbox" id="semrush" value="5"  {{ (in_array('5',$feature_string)) ? 'checked' : '' }} >
                <span class="custom-control-label">Compute SEMrush Traffic and Domain Authority for domain (1 credit per domain)</span>
            </label><br>
            
            @endif
           </div>
           <div class="hstack mb-2">
			   <div class="row mb-3">
		         <div class="col-sm-12">
		            <ul class="list-group">
		               <li class="list-group-item justify-content-between">
		                  <span class="badge">User Credit :</span>
		                  <span class="badgetext badge badge-primary badge-pill">{{ auth()->user()->credits }}</span>
		               </li>
		                <li class="list-group-item justify-content-between {{ (!empty($credit) ) ? '' : 'd-none' }}" id="edit_credit_need" >
		                  <span class="badge">Total Credits need :</span>
		                  <span class="badgetext badge badge-primary badge-pill " id="edit_credit">{{ $credit }}</span>
		               </li>
		            </ul>
		            <input type ="hidden" name="totalCredit" id="totalCredit" value="" >
		            
		         </div>
		      </div>
			</div>
			<br>
			<hr>
			<div class="plandiv d-none" id="plandiv">
        <h5>The Credits required for using all given  features is low. Purchase more credits to use these features.</h5>
           <div class="mt-3">
            <input type="hidden"  name="user_credit" id="user_credit" value="{{ auth()->user()->credits }}">
            <input type="hidden"  name="credit_plan_id" id="credit_plan_id">
            @php  $creditsPlans =  Helper::CreditPlan(); @endphp
            @foreach($creditsPlans as $key => $plan)
              <input type="radio" class="btn-check" name="credit_id" id="credit_id"  value=" {{ $plan->id }}" checked  autocomplete="off">
              <label class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3 radio-alert "  data-plan="{{ $plan->id }}"  data-price="{{ $plan->price }}" data-credit="{{ $plan->credits }}" for="option1">
                 <div class="hstack mb-2">
                    
                    <div class="">
                       <h6 class="mb-0"> {{ $plan->title }}</h6>
                       <p class="mb-0 fw-bold"><small> Price : ${{ $plan->price }}</small>
                       </p>
                       <h6 class="mb-0 ">
                          <small> {{ $plan->comment }}</small>
                       </h6>
                    </div>
                 </div>
              </label>
              @endforeach
               </div>
         </div>
        
       <h6>By purchasing credits, you agree that verification credits are not refundable.</h6>
   	</div>
      </div>
     
   </div>
</div>
 <div class="modal-footer" id="topup-action">
<button class="btn bg-white border-dark rounded-4 shadow-none cancel-tools" data-bs-dismiss="modal">Close</button><button class="btn btn-primary rounded-4 buy-credit d-none" type="button"
   onclick="purchaseCredits()">Buy Credit</button>
   <button class="btn btn-primary rounded-4 proceed-btn-edit" 
   >Yes, Proceed</button>
</div>

<script type="text/javascript">
$(document).on('click', '.myCheckbox', function(){
    
    var totalCheckboxes = $('.myCheckbox:checked').length;
    var website = $('#total_website').val();
    //console.log(totalCheckboxes);
    var total = totalCheckboxes*website;
    $('#edit_credit_need').removeClass('d-none');
    var user_credit = $('#user_credit').val();
    $('#edit_credit_deduct').val(total);
    $('#edit_credit').text(total);
    if(user_credit < total ) {
      $('.plandiv').removeClass('d-none');
      $('.buy-credit').removeClass('d-none');
      $('.proceed-btn').addClass('d-none');
    } else {
       $('.proceed-btn').removeClass('d-none');
       $('.buy-credit').addClass('d-none');
       $('.plandiv').addClass('d-none');
    }
   /* var features = $(".myCheckbox:checked").map(function() {
                return this.value;
            }).get().join(', ');
    $('#edit_features').val(features);*/

    /*if(totalCheckboxes>0){
      $('.is_feature').val(1);
    } else {
       $('.is_feature').val(0);
    }*/
    
});
/*$(document).on('click', '.cancel-tools', function(){
  $('.is_feature').val(0);
});*/

$(".NonBlog").change(function(){
   var including_non_blog = $(this).val();
   $('#including_non_blog').val(including_non_blog);
});
</script>