
<!-- Mail Preview modal -->
   
 <div class="modal-header p-4 pb-2 position-relative border-bottom">
     <h5 class="fw-semibold mb-0 mt-1" id="mail-subject">{!! ($subject!='') ? $subject:'' !!}</h5>
 

     <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1"
         data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <div class="modal-body p-4 pt-2">

     <p class="leads" id="mail-message">
        {!! ($message!='') ? $message:'' !!}
     </p>

 </div>
 <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
     <button type="button"
         class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red"
         data-bs-dismiss="modal">No,
         Cancel</button>
         @if($send_id !=0)
          <button class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover wait-btn  d-none" type="button"
            disabled id="wait-test-btn">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="">Sending...</span>
         </button>
   
     <button type="button"
         class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover test-btn preview-mail" onclick="sendPreviewMail('{{ $send_id }}')" >Send
         Mail</button>
    @endif
 </div>
            
