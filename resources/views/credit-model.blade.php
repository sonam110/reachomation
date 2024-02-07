
                <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
                    <h4 class="fw-bold mb-0 mx-auto">Refine Data</h4>
                     <input type="hidden"  name="user_credit" id="user_credit" value="{{ auth()->user()->credits }}">
                      <input type="hidden"  name="total_website" id="total_website" value="{!! $totalCount !!}">

                    <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <h6 class="fw-bold mt-1">Choose the actions that you wish to perform on the uploaded list
                        [Optional] :</h6>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input shadow-none rounded-0 myckx" type="checkbox" name="first"
                                id="domain_for_blog" value="1" {{ (in_array('1',$feature_string)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="first">
                                <small>
                                    Identify and exclude 'non-blogs' from list of <span class="fw-bold">domains</span>
                                </small>
                            </label>
                        </div>
                    </div>

                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input shadow-none rounded-0 myckx" type="checkbox" name="second"
                                id="validate_email" value="3" {{ (in_array('3',$feature_string)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="second">
                                <small>
                                    Validate all <span class="fw-bold">emails</span> to exclude invalid contacts
                                </small>
                            </label>
                        </div>
                    </div>

                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input shadow-none rounded-0 myckx" type="checkbox" name="third"
                               id="email_personalize" value="4" {{ (in_array('4',$feature_string)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="third">
                                <small>Extract 'author name' of <span class="fw-bold">domains</span> (30% success
                                    rate)</small>
                            </label>
                        </div>
                    </div>

                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input shadow-none rounded-0 myckx" type="checkbox" name="fourth"
                               id="semrush" value="5" {{ (in_array('5',$feature_string)) ? 'checked' : '' }} disabled>
                            <label class="form-check-label d-flex" for="fourth">
                                <small>
                                    Exclude <span class="fw-bold">Domains</span> with 
                                    <p class="fw-semibold text-red mt-3">
                                    coming soon <span class="fw-bold">(DA Semrush option)</span> 
                                    </p>
                                </small>
                            </label>
                        </div>
                        <div class="d-flex ms-4">
                            <select class="form-select form-select-sm shadow-none shadow-sm w-25 me-3" id="semrus_traff" disabled>
                                <option value="" selected="">SEMRUSH TRAFFIC (US)</option>
                                 <option value="100" {{ ('100' == $semrus_traffic) ? 'selected' : '' }}>100+</option>
                                 <option value="250" {{ ('250' == $semrus_traffic) ? 'selected' : '' }}>250+</option>
                                 <option value="500" {{ ('500' == $semrus_traffic) ? 'selected' : '' }}>500+</option>
                                 <option value="1000" {{ ('1000' == $semrus_traffic) ? 'selected' : '' }}>1000+</option>
                                 <option value="2500" {{ ('2500' == $semrus_traffic) ? 'selected' : '' }}>2500+</option>
                                 <option value="5000" {{ ('5000' == $semrus_traffic) ? 'selected' : '' }}>5K+</option>
                                 <option value="10000" {{ ('10000' == $semrus_traffic) ? 'selected' : '' }}>10K+</option>
                                 <option value="15000" {{ ('15000' == $semrus_traffic) ? 'selected' : '' }}>15K+</option>
                                 <option value="25000" {{ ('25000' == $semrus_traffic) ? 'selected' : '' }}>25K+</option>
                                 <option value="50000" {{ ('50000' == $semrus_traffic) ? 'selected' : '' }}>50K+</option>
                                 <option value="100000" {{ ('10000' == $semrus_traffic) ? 'selected' : '' }}>100K+</option>   
                             </select>

                             <select class="form-select form-select-sm shadow-none shadow-sm w-25" id="domain_autho"  disabled>
                                 <option value="" selected="">Domain Authority</option>
                                 <option value="10" {{ ('10' == $domain_authority) ? 'selected' : '' }}>10+</option>
                                 <option value="20" {{ ('20' == $domain_authority) ? 'selected' : '' }}>20+</option>
                                 <option value="30" {{ ('30' == $domain_authority) ? 'selected' : '' }}> 30+</option>
                                 <option value="40" {{ ('40' == $domain_authority) ? 'selected' : '' }}>40+</option>
                                 <option value="50" {{ ('50' == $domain_authority) ? 'selected' : '' }}>50+</option>
                                 <option value="60" {{ ('60' == $domain_authority) ? 'selected' : '' }}>60+</option>
                                 <option value="70" {{ ('70' == $domain_authority) ? 'selected' : '' }}>70+</option>
                                 <option value="80" {{ ('80' == $domain_authority) ? 'selected' : '' }}>80+</option> 
                             </select>
                        </div>
                    </div>

                    <p class="fw-semibold text-red mt-3">
                        * All of the above processes <span class="fw-bold">cost 1 credit per unit</span> of data
                        processing
                    </p>

                    <div class="d-flex flex-row">
                        <div class="p-2 border">
                            <span class="fw-bold">Credit Balance: <span class="text-primary fw-bold">{{ auth()->user()->credits }}</span></span>
                        </div>
                        <div class="p-2 border {{ ($feature_string !='') ?  :'d-none'}}" id="credit_need">
                            <span class="fw-bold">Credits Required: <span
                                    class="text-primary fw-bold credit-val" id="credit">{{ $credit_deduct}}</span> </span>
                        </div>
                    </div>
                    <input type ="hidden" name="totalCredit" id="totalCredit" value="" >
                    <div class="alert alert-info fw-semibold rounded-0 mb-0 mt-3 p-2" role="alert">
                        <small>
                            <span class="fw-bold">Note:</span> It will take some time to process the data as per your
                        selection and hence, campaign scheduling options will be available only after data processing
                        gets completed. You will be notified via email regarding the same.
                        </small>
                    </div>

                </div>
                <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
                    <button type="button" 
                        class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red cancel-tools">No,
                        Cancel</button>
                    <button   type="button"
                        class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover proceed-btn"  {{ (count($feature_string) >0) ?  :'disabled'}} >Yes,
                        Proceed</button>
                </div>


<script type="text/javascript">
$(document).on('click', '.myckx', function(){
    $('.purchase-credit').prop("disabled", false);
    var totalCheckboxes = $('.myckx:checked').length;
    var website = $('#total_website').val();
   // console.log(totalCheckboxes);
    var total = totalCheckboxes*website;
    $('#credit_need').removeClass('d-none');
    var user_credit = $('#user_credit').val();
    $('.credit-val').text(total);
    $('#totalCredit').val(total);
    if(user_credit >= total ) {
      $( ".proceed-btn" ).prop( "disabled", false );
      
    } else {
      $( ".proceed-btn" ).prop( "disabled", true );
      $('#credit-modal').modal('hide');
      $('#credit-modal').modal('hide');
      $('#credit-cost').html(total);
      $('#title_txt').html('You dont have enough credits to perfom this action');
      $('#balance-low-modal').modal('show');


    }
   /* var features = $(".myckx:checked").map(function() {
                return this.value;
            }).get().join(', ');
    $('.features').val(features);*/
   /* if(totalCheckboxes>0){
      $('.is_feature').val(1);
    } else {

      $('.is_feature').val(0);
    }*/
    if(totalCheckboxes <=0){
      $('.purchase-credit').prop("disabled", true);
      $('.proceed-btn').prop("disabled", true);
    }
    
});
$(document).on('click', '.cancel-tools', function(){
  // $('.show-features').addClass('d-none');
   $('.domain_authority').val('');
   $('.semrus_traffic').val('');
   $('.features').val(''); 
   $(".is_feature").val(0);
   $('.show-features').html('');
   $('#schedule-div').removeClass('d-none');
   $('#schedule-note-div').addClass('d-none');
   $('.refine-list').removeClass('d-none');
   $('#credit-modal').modal('hide');

  
});

$(".NonBlog").change(function(){
   var including_non_blog = $(this).val();
   $('#including_non_blog').val(including_non_blog);
});
</script>