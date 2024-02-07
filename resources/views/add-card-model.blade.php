
<div class="modal-header pd-x-20">
	<h6 class="modal-title">Add New Card  </h6>
  
  <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-3 pt-0">
   <div class="mt-4" id="add-list-body">
      <form action="{{route('save-card')}}"  method="post" id="payment-form">
      {{ Form::token() }}
      <div class="row mb-3">
         <div class="col-sm-12">
             <div class="form-row">
               <input class="StripeElement mb-6 card_holder_name" name="card_holder_name" placeholder="Card holder name"  style="width: 100%;">
               <div class="col-50">
                 <label for="card-element"></label>
                 <div id="card-element" class="form-control"></div>
               </div>
               <div id="card-errors" role="alert"></div>
             </div>
        </div>
   </div>
</form>
</div>
 <div class="modal-footer" id="topup-action">
<button class="btn bg-white border-dark rounded-4 shadow-none cancel-tools" data-bs-dismiss="modal">No,
   Cancel</button>
   <button class="btn btn-primary rounded-4 proceed-btn purchase-credit"  id="add-card-button" 
   >Save</button>
</div>

<script src="https://js.stripe.com/v3/"></script>
    <script src="/js/views/add-card-model.js"></script>