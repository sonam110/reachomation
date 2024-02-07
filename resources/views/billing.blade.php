@extends('layouts.master')
@section('content')
   <link rel="stylesheet" href="{{asset('css/views/billing.css')}}">
<div class="py-3">
   <div class="hstack border-bottom gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0">
            Payment & Billing
         </h3>
      </div>
   </div>
   <div class="card shadow-sm mb-3 rounded-0 border-0">
      <div class="card-body">
         <div class="border-bottom pb-3">
            @if(!empty(auth()->user()->plan))
            <h5 class="fw-bold">Subscription Info : @if (Auth::user()->plan==1)
               Free Forever
               @else
               {{ ($userplan) ? $userplan->name : '' }}
               @endif <a href="{{ route('pricing') }}" target="_blank"> <span
                     class="badge bg-green text-white rounded-0">Upgrade Plan</span></a> <a
                  href="{{ route('pricing') }}" target="_blank"> <span class="badge bg-green text-white rounded-0">Add
                     Payment Method</span></a></h5>

            <div class="d-flex">

               @if(!empty($next_bill_date))<p class="fw-500 mb-1"><span class="text-secondary">Next payment:</span> {{
                  $next_bill_date }}</p>

               <div class="vr me-2 ms-2" style="width: 2px;"></div>
               @endif
               <p class="fw-500 mb-1"><span class="text-secondary">Status:</span> Active</p>

               @if(!empty(Auth::user()->plan) && Auth::user()->plan!='1' )
               <div class="vr me-2 ms-2" style="width: 2px;"></div>
               <p class="fw-500 mb-1"><span class="text-secondary">Credits:</span> {{ auth()->user()->credits }} <span
                     class="badge bg-green text-white rounded-0" role="button" tabindex="0" onclick="buyCredits()">Buy
                     More</span>
                  @endif
               </p>
            </div>
         </div>
         @endif
         <ul class="nav nav-pills mb-3 d-flex mt-2" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active fs-6 fw-bold pills" id="pills-summary-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-summary" type="button" role="tab" aria-controls="pills-summary"
                  aria-selected="true">Summary</button>
            </li>

            <!--  <li class="nav-item" role="presentation">
               <button class="nav-link fs-6 fw-bold pills" id="pills-cards-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-cards" type="button" role="tab" aria-controls="pills-cards"
                  aria-selected="true">Saved Cards</button>
            </li> -->

         </ul>
         <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane active fade show" id="pills-summary" role="tabpanel"
               aria-labelledby="pills-summary-tab">
               <div class="card shadow-sm">
                  <div class="card-header py-3">
                     <h6 class="mb-0">Subscribed Plan : <span class="text-primary">
                           @if (Auth::user()->plan==1)
                           Free Forever
                           @else
                           {{ ($userplan) ? $userplan->name : '' }} - ${{ ($userplan) ? $userplan->price : '' }}/
                           (Billed {{$type }})
                           @endif
                        </span>
                     </h6>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered">
                           <thead>
                              <tr>
                                 <th scope="col" width="5%">#</th>
                                 <th scope="col">Plan Name</th>
                                 <th scope="col">Plan Price</th>
                                 <th scope="col">Start Date</th>
                                 <th scope="col" width="10%">End Date</th>
                                 <th scope="col" width="10%">Stripe Status</th>
                                 <th scope="col" width="10%" class="text-nowrap">Plan Status</th>
                                 <th scope="col" width="10%">Invoice</th>
                              </tr>
                           </thead>
                           <tbody>
                              @php $i = 0 @endphp
                              @foreach($subscriptions as $rows)
                              <?php
                                    $type = 'Free';
                                     if(isset($rows->plan) && $rows->plan->plan_type =='1'){
                                       $type  =" Mo";
                                    }
                                    if(isset($rows->plan) && $rows->plan->plan_type =='2'){
                                       $type  =" Year";
                                    }
                                   
                                 ?>
                              <tr>
                                 <td>{!! ++$i !!}</td>
                                 <td>{!! ($rows->plan) ? $rows->plan->name :'' !!}({{ $type }})</td>
                                 @if($type== 'Free')
                                 <td>Free</td>
                                 @else
                                 <td>${!! ($rows->plan) ? $rows->plan->price :'' !!}/Mo</td>
                                 @endif

                                 <td>{!! date('Y-m-d',strtotime($rows->start_at)) !!}</td>
                                 <td>{!! date('Y-m-d',strtotime($rows->ends_at)) !!}</td>
                                 <td>{!! $rows->stripe_status !!}</td>

                                 <td>{!! ($rows->status =='1') ?'Active' :'Inactive' !!}</td>

                                 <td><a href="{{ route('download-invoice',$rows->invoice_id) }}"
                                       target="_blank">Download
                                    </a>
                                 </td>
                                 <!--
                                 <td>  <a
                                    href="{{ route('cancel-subscription',array('id'=>$rows->subscription_id)) }}"
                                    onClick="return confirm('Are you sure you want to Unsubscribe this plan?');">Cancel
                                    Subscription </a>  
                                    <a
                                    href="{{ route('view-subscription',array('id'=>$rows->invoice_id)) }}"  class="btn btn-outline-success btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View"
                                    ><i class="bi bi-eye-fill"></i></a> </a>
                                    
                                 </td>
                                -->
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                     @if(count($subscriptions)>0)
                     {!! $subscriptions->links() !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="tab-pane  fade show" id="pills-cards" role="tabpanel" aria-labelledby="pills-cards-tab">
               <div class="card shadow-sm">
                  <div class="card-header py-3">
                     <a class="btn btn-primary shadow-sm fw-500" onClick="addCard();">Add New Card</h6>
                     </a>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered">
                           <thead>
                              <tr>
                                 <th scope="col" width="5%">#</th>
                                 <th scope="col">Card Detail</th>
                                 <th scope="col">Card Expiry</th>

                                 <th scope="col" width="10%">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @php $i = 0 @endphp
                              @if(count($cards) >0)
                              @foreach($cards as $card)
                              <tr>
                                 <td>{!! ++$i !!}</td>
                                 <td><span class="badge badge-warning  badge-pill default-badge">{!!
                                       @$card->card['brand'] !!}</span>**** **** **** **** {!! @$card->card['last4'] !!}
                                 </td>
                                 <td>{!! @$card->card['exp_month'] !!}/{!! @$card->card['exp_year'] !!} <span
                                       class="badge badge-warning  badge-pill default-badge">{{ ($default_payment_method
                                       == $card->id ) ? 'default' :'' }}</span></td>
                                 <td> <a id="show-delete-card" data-id="{{ $card->id }}"> <i
                                          class="bi bi-trash-fill text-primary" data-bs-toggle="tooltip"
                                          data-bs-placement="top" data-bs-original-title="Delete" aria-label="Delete"
                                          role="button" tabindex="0"></i>
                                    </a>
                                    <a id="make-default-card" data-id="{{ $card->id }}"> <i
                                          class="bi bi-pencil-square text-primary" data-bs-toggle="tooltip"
                                          data-bs-placement="top" data-bs-original-title="Make as Default"
                                          aria-label="Make as Default" role="button" tabindex="0"></i>
                                    </a>

                                 </td>
                              </tr>
                              @endforeach
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>

</div>
{{-- Modal for alert start --}}
<div class="modal modal-alert" tabindex="-1" role="dialog" id="deleteCard">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
         <form action="{{ route('card-delete')}}" method="post">
            @csrf
            <input type="hidden" name="card_id" id="card_id">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Once deleted, you will not be able to recover this Account.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="submit"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none"
                  id="delete-btn"><strong>Yes, delete it</strong></button>
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none"
                  data-bs-dismiss="modal">No thanks</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="modal modal-alert" tabindex="-1" role="dialog" id="defaultCard">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
         <form action="{{ route('set-default-card')}}" method="post">
            @csrf
            <input type="hidden" name="cardid" id="cardid">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Your want to make as default card.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="submit"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none"
                  id="delete-btn"><strong>Yes</strong></button>
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none"
                  data-bs-dismiss="modal">No thanks</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- Modal for alert end --}}
<div class="modal fade" tabindex="-1" role="dialog" id="add-cards">
   <div class="modal-dialog  modal-md" role="document">
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <form action="{{route('save-card')}}" method="post" id="payment-form">

            {{ Form::token() }}
            <div class="modal-header p-4 pb-2 border-bottom-0">
               <h3 class="fw-bold mb-0">Add New Card</h3>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 pt-0">
               <div class="mt-4" id="add-list-body">
                  <div class="row">
                     <input type="hidden" name="edit_id" id="edit_id">
                     <div class="form-row">
                        <input class="StripeElement mb-6 card_holder_name" name="card_holder_name"
                           placeholder="Card holder name" style="width: 100%;">
                        <div class="col-50">
                           <label for="card-element"></label>
                           <div id="card-element" class="form-control"></div>
                        </div>
                        <div id="card-errors" role="alert"></div>
                     </div>

                  </div>
               </div>
               <div class="stripe-errors"></div>
               <div class="modal-footer">
                  <button id="card-button" class="btn btn-primary mt-1 fw-500"
                     data-secret="{{ $intent->client_secret }}"> Save</button>

                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>


               </div>
               {{ Form::close() }}
            </div>
      </div>
   </div>
</div>

{{-- Cancel subscription --}}
<div class="modal fade" tabindex="-1" role="dialog" id="cancel-subscription-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Cancel Subscription
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>

         <div class="modal-body p-3 pt-0">
            <p class="fw-semibold mb-3 mt-3 lh-base text-center">
               You're about to downgrade to a free forever plan.
            </p>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-dark fw-600" data-bs-dismiss="modal">Will do later</button>
            <button type="button" class="btn btn-lg btn-danger fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600">Yes, Please cancel</button>
        </div>
      </div>
   </div>
</div>




   @endsection
   @section('extrajs')
   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


   {!! Html::script('js/list.js') !!}

   <script src="https://js.stripe.com/v3/"></script>
   <script src="/js/views/billing.js"></script>
   @endsection
