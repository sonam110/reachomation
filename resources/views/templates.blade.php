@extends('layouts.master')
@section('content')
   <div class="py-3">
      <div class="hstack border-bottom gap-3 mb-3 pb-3">
         <div>
            <h3 class="fw-bold mb-0">
               Your Templates
            </h3>
         </div>
         <div class="ms-auto">
            <button type="button" class="btn btn-green shadow-sm fw-500" onclick="opencreateModal()"><i class="bi bi-plus-lg"></i> Create Template</button>
         </div>
      </div>
      @if(count($templates)>0)
      <div class="card shadow-sm">
         <div class="card-body pb-0">

            <div class="hstack gap-3 mb-3 border-bottom pb-2">
              
               <div class="ms-auto">
                  <form method="GET" action="{{route('templates')}}">
                     @csrf
                     <div class="input-group shadow-sm">
                        <input type="search" class="form-control shadow-none" placeholder="Search by subject or group" name="q" aria-describedby="button-addon2" size="25px" aria-label="Search" value="">
                        <button class="btn btn-success shadow-none" type="submit" id="button-addon2"><i class="bi bi-search text-white"></i></button>
                     </div>
                  </form>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-4">
                  <h6 class="pb-2 mb-2 border-bottom">
                     <i class="bi bi-folder2-open"></i> Groups
                  </h6>
                  <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white">
                     <div class="list-group list-group-flush border-bottom scrollarea">
                        @foreach($groups as $group)
                        <a href="/templates?q={{$group->name}}"
                        class="list-group-item list-group-item-action {{ ($group->name == @$_GET['q']) ? 'active':''}} py-2 lh-tight" aria-current="true">
                           <div class="d-flex w-100 align-items-center justify-content-between">
                              <h6 class="mb-0"><i class="bi bi-folder2"></i> {{$group->name}}</h6>
                              <small><span class="badge bg-theme text-white border border-white">{{$group->total}}</span></small>
                           </div>
                        </a>
                        @endforeach
                     </div>
                  </div>

                  
               <div class="col-md-8">
                  @if(count($templates)>0)
                  <div class="hstack pb-2 mb-4 border-bottom">
                     <div>
      
                        <h6 class="mb-0">
                           All Templates
                        </h6>  
                       
                     </div>
                     <div class="ms-auto">
                      <!--   @if(isset($_GET['q']))
                        <h6 class="fw-500 mb-0">Showing {{$templates->firstItem()}} - {{$templates->lastItem()}} of {{$templates->total()}} results for "<span class="text-primary">{{$_GET['q']}}</span>"</h6>
>>>>>>> parent of 1c6af2b... Css and JS
                        @else
                        <h6 class="fw-500 mb-0">
                           Showing {{$templates->firstItem()}} - {{$templates->lastItem()}} of {{$templates->total()}} results
                        </h6> 
                        @endif -->

                     </div>
                  </div>
                  @foreach($templates as $template)
                  <div class="row">
                     <div class="col-md-9">
                        <h6>
                           <span class="text-muted "> </span> {!! strip_tags(substr($template->subject,0,900))!!} <span class="badge bg-warning rounded-pill ms-2 text-dark px-2" style="font-size: 9px;">{{$template->name}}</span>
                        </h6>
                       
                     </div>
                     
                     <div class="col-md-3 text-center">
                        <ul class="list-inline mt-2 mb-0">
                           <li class="list-inline-item">
                              <i class="bi bi-star-fill setDefault {{ (auth()->user()->default_tid == $template->id ) ?'text-warning' :'' }}" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Set as default"  data-value="" onclick="setDefault('{{$template->id}}')" id="setdefault_{{$template->id}}"></i>
                           </li>
                           @if($template->user_id == auth()->user()->id)
                           <li class="list-inline-item">
                              <i class="bi bi-pencil-square" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit" onclick="editTemplate('{{$template->id}}')"></i>
                           </li>
                           @endif
                           <li class="list-inline-item">
                              <i class="bi bi-files" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Copy" onClick="copyTemplate('{{$template->id}}')" id="copy-bi_{{$template->id}}"></i>
                           </li>
                          @if($template->user_id == auth()->user()->id)
                           <li class="list-inline-item">
                              <i class="bi bi-trash text-danger" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" onclick="deleteAlert('{{$template->id}}')"></i>
                            @endif
                            <li class="list-inline-item">
                              <i class="bi bi-eye-fill" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Preview Template"  data-value="" onclick="mailPreviewTemp('{{$template->id}}')" data-id="{{$template->id}}"></i>
                           </li>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <hr>  
                  @endforeach
                  <div class="d-flex justify-content-end">
                     @if(count($templates)>0)
                        {{ $templates->links() }}
                     @endif
                  </div>
                  @else
                  <center>Nothing found for <b>@if(isset($_GET['q']))
                     {{$_GET['q']}}
                  @endif</b></center>
                  @endif
                  
               </div>
            </div>

         </div>
      </div>   
      @else
      <div class="card">
         <div class="card-body text-center">
            <img src="{{asset('images/empty.svg')}}" class="mb-3 mx-auto" width="320" alt="...">
            <h5 class="mt-3">You're yet to create your first template</h5>
            <button type="button" class="btn btn-primary btn-lg shadow-sm fw-500 mt-3" onclick="opencreateModal()"><i class="bi bi-plus-lg"></i> Create New Template</button>
         </div>
      </div>   
      @endif
   </div>
           
   {{-- toast start --}}
   <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99999">
      <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
         <div class="d-flex">
            <div class="toast-body toast-msg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
   </div>
   {{-- toast end --}}

   {{-- Create template modal start --}}
   <div class="modal fade" id="create-modal" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
         <div class="modal-content">
            <div class="modal-header py-2">
               <h5 class="modal-title fw-bold" id="modal-title"></h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-8">
                     <div class="row mb-3">
                        <div class="col-sm-6">
                           <div class="form-floating mb-3">
                             <textarea class="form-control rounded-4 shadow-none form-control-sm  subject"  name="subject" data-rid="1" id="subject" rows="2" autocomplete="off"></textarea>
                           </div>
                        </div>
                           <div class="col-sm-6">
                              <div class="form-floating mb-3">
                                 <div class="input-group">
                                    <input type="text" class="form-control shadow-none rounded-4" placeholder="Group Name" id="group-name" autocomplete="off" onkeyup="group()" disabled>
                                    <button class="btn btn-green btn-sm dropdown-toggle shadow-none rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 50px;">Select Group</button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="width: 360px;">
                                       <div style="max-height: 260px;overflow:auto;">
                                          @if(count($groups)>0)
                                          @foreach($groups as $group)
                                          <li>
                                             <a class="dropdown-item d-flex gap-2 align-items-center" href="javascript:void(0)" onclick="selectGroup('{{ucfirst($group->name)}}')">
                                                {{ucfirst($group->name)}}
                                             </a>
                                          </li>  
                                          @endforeach 
                                          @else
                                          <li>
                                             <span class="dropdown-item-text">No Group found</span>
                                          </li> 
                                          @endif
                                       </div>
                                       <li class="bg-light">
                                          <a class="dropdown-item fw-bold" href="javascript:void(0)" onclick="createGroup()">
                                             <i class="bi bi-plus-lg"></i> Create Group
                                          </a>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                        </div>
                  
                  </div>
                     <textarea id="summernote" name="htmlckeditor" class="form-control message summernote" data-rid="1"></textarea>
                     
                     <div class="hstack gap-3 mt-3">
                        <input type="hidden" name="fallback_text"  class="fallback_text"> 
                        <div class="input-group">
                           <label class="input-group-text fw-600" for="from_email">From</label>
                           <select class="form-select form-select-sm shadow-none" id="from_email" name="from_email">
                              @foreach($emailCollection as $email)
                              <option value="{{ $email->email }}" {{($email->is_default == '1') ? 'selected' :''}}>{{ $email->email }}</option>
                              @endforeach
                           </select>
                         </div>

                        {{-- <input type="hidden" name="fallback_text"  class="fallback_text"> 
                         From:<select class="form-select form-select-sm shadow-none" id="from_email" name="from_email">
                           @foreach($emailCollection as $email)
                           <option value="{{ $email->email }}" {{($email->is_default == '1') ? 'selected' :''}}>{{ $email->email }}</option>
                           @endforeach
                        </select> --}}
                        <input class="form-control form-control-sm me-auto shadow-none" type="text" placeholder="Send test mail" id="test-email" value="{{Auth::user()->email}}" onkeyup="testEmail()">
                        <button class="btn btn-primary btn-sm text-nowrap shadow-sm fw-500 d-none" type="button" disabled id="wait-test-btn">
                           <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                           <span class="">Sending...</span>
                        </button>
                        <button type="button" class="btn btn-green btn-sm text-nowrap shadow-sm fw-500" id="test-btn" onclick="sendtestMail()">Send test mail</button>
                     </div>
                  </div>
                  <div class="col-sm-4 customTag" id="customTag-1">
                <div class="card rounded-0 shadow-sm border-0 h-100">
                  <div class="card-header fw-500">
                     Personalization tags
                  </div>
                  <div class="card-body">
                     <div class=" alert-info p-2 shadow-sm mb-3" >
                        <p class="mb-0"><small><i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters:</small></p>
                     </div>

                     <div class="row mb-3">
                        <div class="col-sm-8">
                           <div class=" alert-light border-dark py-1 mb-0 rounded-0 " >
                              <span class="fw-500"><small>Name</small></span>
                           </div>
                        </div>
                        <div class="col-sm-4">
                     
                           <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg fallback-Name"  data-pid="1" id="custom-ele-1"  data-customval="Name"><i class="bi bi-plus-lg"></i></button>
                           <!--  <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback"   data-pid="1"  id="custom-ele-1"  data-customval="Name"><i class="bi bi-pencil"></i></button> -->
                        </div>
                     </div>

                     <div class="row mb-3">
                        <div class="col-sm-8">
                           <div class=" alert-light border-dark py-1 mb-0 rounded-0" >
                              <span class="fw-500"><small>Website</small></span>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           
                           <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element  elementMsg fallback-Website"   data-pid="1"  id="custom-ele-1" data-customval="Website" ><i class="bi bi-plus-lg"></i></button>
                         <!--  <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback"  data-pid="1"  id="custom-ele-1"  data-customval="Website"><i class="bi bi-pencil"></i></button>  -->
                        </div>
                     </div>

                     <!-- <div class="row mb-3">
                        <div class="col-sm-8">
                           <div class=" alert-light border-dark py-1 mb-0 rounded-0" >
                              <span class="fw-500"><small>Title</small></span>
                           </div>
                        </div>
                        <div class="col-sm-4">
                          
                           <button type="button" class="btn btn-outline-success btn-sm fw-500  element elementMsg fallback-Title" data-pid="1"  id="custom-ele-1" data-customval="Title" ><i class="bi bi-plus-lg"></i></button>
                           <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback"  data-pid="1"  id="custom-ele-1"  data-customval="Title"><i class="bi bi-pencil"></i></button>
                        </div>
                     </div> -->
                  </div>
                  <!-- <div class="card-footer text-muted">
                     <button type="button" class="btn btn-outline-dark btn-sm fw-500" onClick="unSubscribeTag(1)" id="unsubscribe-tag-1" data-pid="1">
                        <i class="bi bi-plus-lg"></i> Use Unsubscribe tag</button>
                  </div> -->
               </div>
            </div>
               </div>
            </div>
            <div class="modal-footer py-2" id="template-action">
               
            </div>
         </div>
      </div>
   </div>
   {{-- Create template modal end --}}

   {{-- Modal for alert start --}}
   <div class="modal modal-alert deleteAlert" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content rounded-4 shadow">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Once deleted, you will not be able to recover this template.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none" id="delete-btn"><strong>Yes, delete it</strong></button>
               <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none" data-bs-dismiss="modal">No thanks</button>
            </div>
         </div>
      </div>
   </div>
   {{-- Modal for alert end --}}
  {{-- Modal for fallback text --}}
      <div class="modal fade" tabindex="-1" role="dialog" id="fallback-modal">
         <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
               
               <div class="modal-header p-4 pb-2 border-bottom-0">
                  <h3 class="fw-bold mb-0">Merge tag</h3>
                  <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body p-3 pt-0">
                  <div class="mt-4" id="add-list-body">
                     <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="mobile" class="form-label">Recipient field <span class="requiredLabel"></span></label>
                                 {!! Form::text('recipient_feild','',array('id'=>'recipient_feild','class'=> $errors->has('recipient_feild') ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'Recipient field ', 'autocomplete'=>'off','readonly'=>'readonly')) !!}
                                 
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="mobile" class="form-label">Fallback text <span class="requiredLabel">*</span></label>
                                 {!! Form::text('fallback_txt','',array('id'=>'fallback_txt','class'=> $errors->has('fallback_txt') ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'', 'autocomplete'=>'off')) !!}
                                 
                              </div>
                           </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  {!! Form::button('Save', array('class'=>'btn btn-primary saveFallbackTxt')) !!} 
                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
               </div>
             
            </div>
         </div>
      </div>
   {{-- Modal for fallBack --}}


{{-- Modal for show mail start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="mail-preview-modal" aria-modal="true">
     <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow"  id="message-preview-section"style="border-radius: 0.75rem!important;">
              
      </div>
   </div>
</div>

{{-- Modal for show mail end --}}

@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
{!! Html::script('js/template.js') !!}

   <!-- Bootstrap Bundle with Popper -->
<script>
  
$(document).ready(function(){

          $(".subject").summernote(
              {
                 toolbar: [],
                  
                  placeholder: `Subject`,
                  //followingToolbar: false   ,
                 // focus: true,
                  disableResizeEditor: true ,
                  //: 300,
                  height: '48px',
                  callbacks: {
                    onKeyup: function (contents, $editable) {
                       var rid = $(this).data('rid');
                        $(".element").addClass('elementSub');
                        $(".element").removeClass('elementMsg');

                        $(".resetBt").addClass('resetBtsub');
                        $(".resetBt").removeClass('resetBtnmsg');
                    }

                  }
              }
          );
           $(".message").summernote(
              {
                  toolbar: [ ["style", ["style"]], ["font", ["bold", "italic", "underline", "fontname", "clear"]], ["color", ["color"]], ["paragraph", ["ul", "ol", "paragraph"]], ["table", ["table"]], ["insert", ["link", "picture", "video"]], ["view", ["codeview", "fullscreen"]], ],
                  placeholder: `Hi there,<br><br>I saw your site and liked the content. I am keen to explore advertising opportunities with you.<br>Could I know your charges for publishing a guest post on . We pay via PayPal.<br><br>Looking forward to hearing from you. `,
                  tabsize: 2,
                  height:'300px',
                  callbacks: {
                    onKeyup: function (contents, $editable) {
                       var rid = $(this).data('rid');
                        $(".element").removeClass('elementSub');
                        $(".element").addClass('elementMsg');
                        $(".resetBt").removeClass('resetBtsub');
                        $(".resetBt").addClass('resetBtnmsg');
                    }
                  }
              }
          );

      });
   $(document).on('click', '.addFallback',function () {
   var dynamic_field_val = $(this).data('customval');
   $('#recipient_feild').val(dynamic_field_val);
   var new_fallbac_text_value = 'there';
   var fallbac_text_value = $('.fallback-'+dynamic_field_val).val();
   //console.log(fallbac_text_value);
   if(fallbac_text_value!='' &&  fallbac_text_value != undefined){
     newVal =  fallbac_text_value.split('|');
     //console.log(newVal);
     new_fallbac_text_value = newVal[1];
   }
   $('#fallback_txt').val(new_fallbac_text_value);
   $('#fallback-modal').modal('show');
});

$(document).on('click', '.saveFallbackTxt',function () {
   var recipient_feild = $('#recipient_feild').val();
   var fallback_txt = $('#fallback_txt').val();
   if(fallback_txt!=''){
      var fallbck_text = ''+recipient_feild+'|'+fallback_txt+'';
   }else{
      var fallbck_text = ''+recipient_feild+'';
   }
  
   $('.fallback-'+recipient_feild).val(fallbck_text);
   $('.custom-data-'+recipient_feild).text(fallbck_text);
  // $('.resetText-'+recipient_feild).data('customval', fallbck_text);
  
  /* var additem = fallbck_text;
   $(".fallback_text").val(($(".fallback_text").val() + ', ' + additem).replace(/^, /, ''));*/
   fallBackTxt();
   $('#fallback-modal').modal('hide');

    toastr.success('Tag updated');
   
});

function fallBackTxt(){
    var a = [];
   $('.element').each(function(){
        a.push($(this).val()); // Push all values in array
   });
   var unique = a.filter(function(itm, i, a) {
    return i == a.indexOf(itm);
   });
   var fallback_text = unique.join(','); // Concatenate array by comma separated string
   $(".fallback_text").val(fallback_text);
}

const createGroup = () =>{
   document.getElementById("group-name").removeAttribute("disabled");
   document.getElementById("group-name").value = "";
   document.getElementById("group-name").focus();
}


</script>
   @endsection
        
