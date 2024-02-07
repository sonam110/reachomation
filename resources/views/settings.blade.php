@extends('layouts.master')
@section('extracss')
<link href="/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

<div class="py-3">
   <div class="hstack border-bottom gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0">
            Settings
         </h3>
      </div>
   </div>

   <div class="card border-0 rounded-0 shadow-sm mb-3">
      <div class="card-body">

         <ul class="nav nav-pills mb-3 d-flex" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link  fs-6 fw-bold pills" id="pills-profile-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                  aria-selected="true">Profile</button>
            </li>
            @if(auth()->user()->oauth_provider !='Google')
            <li class="nav-item" role="presentation">
               <button class="nav-link  fs-6 fw-bold pills" id="pills-password-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password"
                  aria-selected="true">Change Password</button>
            </li>
            @endif
            <li class="nav-item" role="presentation">
               <button class="nav-link active fs-6 fw-bold pills" id="pills-emails-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-emails" type="button" role="tab" aria-controls="pills-emails"
                  aria-selected="true">Connected Emails</button>
            </li>
             <li class="nav-item" role="presentation">
               <a class="nav-link  fs-6 fw-bold pills" href="{{ route('payment-billing') }}" >Billing Information</a>
            </li>
         </ul>

         <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane  fade show" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
               <form action="{{ route('update-profile') }}" method="post" id="update-profile">
                  @csrf

                  <ul class="list-group">
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Login Email</h6>
                           </div>
                           <div class="col-sm-6">
                              <p class="mb-0 fw-500">
                                 {{Auth::user()->email}}
                              </p>

                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Name</h6>
                           </div>
                           <div class="col-sm-6">
                              <!-- <p class="mb-0 fw-500">{{ Auth::user()->phone }}</p> -->
                              <input type="text" id="name" name="name" placeholder="name"
                                 value="{{ Auth::user()->name }}" class="form-control">
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Preferred Niches</h6>
                           </div>
                           <div class="col-sm-6">
                              <!--  <p class="mb-0 fw-500">{{ join(', ', array_map('ucfirst', explode(',', Auth::user()->niches))) }}</p> -->
                              <select name="niches[]" id="niches" class="selectNiches" multiple="multiple" required>
                                 @foreach($niches as $nich)
                                 <option value="{{ $nich->niche  }}" {{ (in_array($nich->niche, explode(',',
                                    Auth::user()->niches))) ? 'selected' : '' }} >
                                    {{ ucfirst($nich->niche) }}
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="col-sm-2 text-end">


                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Phone</h6>
                           </div>
                           <div class="col-sm-6">
                              <!-- <p class="mb-0 fw-500">{{ Auth::user()->phone }}</p> -->
                              <input type="text" id="phone" name="phone" placeholder="Phone"
                                 value="{{ Auth::user()->phone }}" class="form-control">
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Company</h6>
                           </div>
                           <div class="col-sm-6">
                              <!-- <p class="mb-0 fw-bold">{{ Auth::user()->company }} </p> -->
                              <input type="text" id="company" name="company" placeholder="company"
                                 value="{{ Auth::user()->company }}" class="form-control">

                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Country</h6>
                           </div>
                           <div class="col-sm-6">

                              <select name="country" id="country" class="form-control country" style="height: 47px;"
                                 onChange="getState();">
                                 @foreach($countries as $county)
                                 <option value="{{ $county->shortcode }}" countryid="{{ $county->id }}"
                                    @if(Auth::user()->country == $county->shortcode ) selected @endif>
                                    {{ ucfirst($county->country) }}
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">State</h6>
                           </div>
                           <div class="col-sm-6">
                              <select name="state" id="state" class="form-control state" style="height: 47px;">
                                 <option value="" selected>Select State</option>
                                 @if(count($statsList) >0)
                                 @foreach($statsList as $state)
                                 <option value="{{ $state->name }}" @if(Auth::user()->state == $state->name ) selected
                                    @endif >{{ ucfirst($state->name) }}</option>

                                 @endforeach
                                 @endif
                              </select>

                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">City</h6>
                           </div>
                           <div class="col-sm-6">
                              <!--     <p class="mb-0 fw-500">{{ Auth::user()->city }}</p> -->
                              <input type="text" id="city" name="city" placeholder="New York"
                                 value="{{ Auth::user()->city }}" class="form-control">
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Address</h6>
                           </div>
                           <div class="col-sm-6">
                              <!--  <p class="mb-0 fw-500">{{ Auth::user()->line1 }}</p> -->
                              <textarea type="text" id="address" name="address" placeholder="New York"
                                 value="{{ Auth::user()->line1 }}"
                                 class="form-control">{{ Auth::user()->line1 }}</textarea>
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Zipcode</h6>
                           </div>
                           <div class="col-sm-6">
                              <!--  <p class="mb-0 fw-500">{{ Auth::user()->postal_code }}</p> -->
                              <input type="text" id="postal_code" name="postal_code" placeholder="10024"
                                 value="{{ Auth::user()->postal_code }}" class="form-control">
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Skype ID/Whatsapp/Telegram</h6>
                           </div>
                           <div class="col-sm-6">
                              <!--  <p class="mb-0 fw-500">pallav@skype</p> -->
                              <input type="text" id="skype" name="skype" placeholder="user@skype"
                                 value="{{ Auth::user()->skype }}" class="form-control">
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Email Notifications</h6>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-check">
                                 <input class="form-check-input" type="checkbox" value="1" id="is_email_notify"
                                    name="is_email_notify" required {{ (Auth::user()->is_email_notify =='1') ? 'checked'
                                 :''}}>
                                 <label class="form-check-label" for="notification">
                                    Send me important account notifications.
                                 </label>
                              </div>
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">

                           </div>
                           <div class="col-sm-6">
                              <button type="submit" class="btn btn-primary shadow-sm fw-500">UPDATE</button>
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                  </ul>

               </form>
            </div>
            <div class="tab-pane  fade show" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
               <form action="{{ route('user-save-password') }}" method="post" id="change-password">
                  @csrf

                  <ul class="list-group">
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Old Password</h6>
                           </div>
                           <div class="col-sm-6">
                              {!!
                              Form::password('old_password',array('id'=>'old_password','class'=>$errors->has('old_password')
                              ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'Old Password',
                              'autocomplete'=>'off','required'=>'required')) !!}
                              @if ($errors->has('old_password'))
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('old_password') }}</strong>
                              </span>
                              @endif
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">New Password</h6>
                           </div>
                           <div class="col-sm-6">
                              {!!
                              Form::password('new_password',array('id'=>'new_password','class'=>$errors->has('new_password')
                              ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'New Password',
                              'autocomplete'=>'off','required'=>'required')) !!}
                              @if ($errors->has('new_password'))
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('new_password') }}</strong>
                              </span>
                              @endif
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">
                              <h6 class="mb-0 text-muted">Confirm Password</h6>
                           </div>
                           <div class="col-sm-6">
                              {!!
                              Form::password('new_password_confirmation',array('id'=>'new_password_confirmation','class'=>$errors->has('new_password_confirmation')
                              ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'Confirm
                              Password', 'autocomplete'=>'off','required'=>'required')) !!}
                              @if ($errors->has('new_password_confirmation'))
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                              </span>
                              @endif
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>

                     <li class="list-group-item py-3">
                        <div class="row">
                           <div class="col-sm-4">

                           </div>
                           <div class="col-sm-6">
                              <button type="submit" class="btn btn-primary shadow-sm fw-500">UPDATE</button>
                           </div>
                           <div class="col-sm-2 text-end">

                           </div>
                        </div>
                     </li>
                  </ul>

               </form>
            </div>

            <div class="tab-pane active fade show" id="pills-emails" role="tabpanel" aria-labelledby="pills-emails-tab">

               <ul class="list-group rounded-0">
                  <li class="list-group-item py-3 border-0">
                     @if (count($emailCollection) >0)
                     <div class="row">
                        <div class="col-sm-9">
                        </div>
                        <div class="col-sm-3">
                           <button type="button" class="btn btn-green shadow-none fw-500"
                              onClick="connectEmailAccount();">Connect Email Account</h6>
                           </button>
                        </div>
                     </div>
                     <br>
                     @endif

                     @if(count($emailCollection) >0)
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="table-responsive">
                              <table class="table table-hover table-bordered table-sm">
                                 <thead class="thead-dark">
                                    <tr>
                                       <th scope="col">S. No.</th>
                                       <th scope="col"> Sender name and email</th>
                                       <th scope="col">Type</th>
                                       <th scope="col">Status</th>
                                       <th scope="col">Daily Limit</th>
                                       <th scope="col">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    @foreach($emailCollection as $key=> $collection)

                                    <tr>
                                       <td>{{ ($key+1) }}</td>
                                       <td>{{ $collection->name }} <br> {{ $collection->email }}
                                          @if($collection->status=='0')
                                          <a href="javascript:;" onClick="connectEmailAccount();"> <i
                                                class="bi bi-exclamation-circle-fill" style="color:red"></i> </a>
                                          @endif
                                          <span class="badge bg-dark text-white badge-pill default-badge">{{
                                             ($collection->is_default == '1') ? 'default' :'' }}</span>
                                       </td>
                                       <td>{{ ($collection->account_type =='1') ? 'Gmail': 'Outlook'}}</td>
                                       <td> <small
                                             class="{{ ($collection->status =='1') ? ' text-dark': ' text-danger'}}">{{
                                             ($collection->status =='1') ? 'Active': 'Inactive'}}</small> </td>
                                       <td>{{ $collection->daily_limit }}</td>
                                       <td>
                                          <div class="col-sm-2 text-end">
                                             <div class="d-flex">
                                                <i class="bi bi-pencil-square text-primary edit-account"
                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                   data-bs-original-title="Edit" aria-label="Edit" role="button"
                                                   tabindex="0" data-isdefault="{{ $collection->is_default }}"
                                                   data-id="{{ $collection->id }}" data-email="{{ $collection->email }}"
                                                   data-status="{{ $collection->status }}"
                                                   data-name="{{ $collection->name }}" id="edit-account "></i>
                                                @if($collection->is_default!='1')
                                                <a id="show-delete-model" data-id="{{ $collection->id }}"
                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                   data-bs-original-title="Delete"> <i
                                                      class="bi bi-trash-fill text-primary" data-bs-toggle="tooltip"
                                                      data-bs-placement="top" data-bs-original-title="Edit"
                                                      aria-label="Edit" role="button" tabindex="0"></i>
                                                </a>
                                                @else
                                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                   data-bs-original-title="You can't delete your default email account">
                                                   <i class="bi bi-trash-fill text-primary" data-bs-toggle="tooltip"
                                                      data-bs-placement="top" data-bs-original-title="delete"
                                                      aria-label="delete" role="button" tabindex="0"></i>
                                                </a>
                                                @endif
                                             </div>
                                          </div>
                                       </td>
                                    </tr>

                                    @endforeach


                                 </tbody>
                              </table>
                           </div>
                        </div>

                     </div>
                     @else
                     <div class="d-grid">
                        <img src="{{ asset('images/elements/mailbox.svg') }}" class="mb-3 mx-auto" width="240"
                           alt="mailbox">
                     </div>
                     <div class="text-center">
                        <button type="button" class="btn btn-green btn-lg px-5 shadow-sm fw-500"
                           onClick="connectEmailAccount();">Connect Email Account</h6>
                        </button>
                     </div>
                     @endif
                  </li>
               </ul>
            </div>
         </div>

      </div>
   </div>

</div>

{{--oepn account type --}}
<div class="modal fade" tabindex="-1" role="dialog" id="edit-email-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         {{ Form::open(array('url' => 'edit-mail-acount', 'class'=> 'form-horizontal')) }}
         {{ Form::token() }}
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Edit Email
            </h4>
            @php
            $id = (!empty(Session::get('id'))) ?  Session::get('id'): NULL;
            $name = (!empty(Session::get('name'))) ?  Session::get('name'): NULL;
            $email = (!empty(Session::get('email'))) ?  Session::get('email'): NULL;
            $is_default = (!empty(Session::get('is_default'))) ?  Session::get('is_default'): NULL;

            @endphp
            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         {{-- <div class="modal-header p-4 pb-2 border-bottom-0">
            <h3 class="fw-bold mb-0">Edit Email</h3>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
         </div> --}}

         <div class="modal-body p-3 pt-0">
            <div class="mt-4" id="add-list-body">
               <div class="row">

                  <input type="hidden" name="edit_id" id="edit_id" value="{{ @$id }}">
                  <div class="col-md-12">
                     <div class="form-group mb-3">
                        <label for="mobile" class="form-label">Email <span class="requiredLabel"></span></label>
                        {!! Form::text('email',$email,array('id'=>'edit_email','class'=> $errors->has('email') ?
                        'form-control is-invalid state-invalid' : 'form-control shadow-none', 'placeholder'=>'Email',
                        'autocomplete'=>'off','readonly'=>'readonly')) !!}

                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group mb-3">
                        <label for="mobile" class="form-label">Display Name <span class="requiredLabel">*</span></label>
                        {!! Form::text('name',$name,array('id'=>'edit_name','class'=> $errors->has('name') ? 'form-control
                        is-invalid state-invalid' : 'form-control shadow-none', 'placeholder'=>'Display Name',
                        'autocomplete'=>'off','required'=>'required')) !!}

                     </div>
                  </div>
                  <!--  <div class="col-md-4">
                           <div class="form-group statusId">
                              <label for="mobile" class="form-label">Status <span class="requiredLabel">*</span></label>
                              <select name="status" id="status" class="form-control">
                                 <option value="1">Active</option>
                                 <option value="0"> Inactive</option>
                              </select>
                              
                           </div>
                        </div> -->
                  <div class="col-md-12">
                     <div class="form-group">
                        <div class="form-check form-check-inline">
                           <input class="form-check-input shadow-none rounded-0" type="checkbox" name="is_default" id="is_default" value="1" {{ ($is_default=='1') ?'checked':'' }}>
                           <label class="form-check-label" for="first">
                               <small>
                                 Default
                               </small>
                           </label>
                       </div>
                        {{-- <label class="custom-control custom-checkbox">
                           <input type="checkbox" class="colorinput-input custom-control-input rounded-0" id="is_default"
                              name="is_default" value="1">
                           <span class="custom-control-label">
                              <small>Default</small>
                           </span>
                        </label> --}}
                     </div>
                  </div>



               </div>

            </div>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red fw-600" data-bs-dismiss="modal">Close</button>
            {!! Form::submit('Submit', array('class'=>'btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600')) !!}
        </div>
         {{-- <div class="modal-footer">
            {!! Form::submit('Submit', array('class'=>'btn btn-primary')) !!}
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
         </div> --}}
         {{ Form::close() }}
      </div>
   </div>
</div>


{{-- Modal for alert start --}}
<div class="modal modal-alert" tabindex="-1" role="dialog" id="deleteAccount">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
         <form action="{{ route('account-delete')}}" method="post">
            @csrf
            <input type="hidden" name="account_id" id="account_id">
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
{{-- Modal for alert end --}}

{{-- toast start --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
   <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive"
      aria-atomic="true">
      <div class="d-flex">
         <div class="toast-body toast-msg"></div>
         <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
      </div>
   </div>
</div>
{{-- toast end --}}

@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


{!! Html::script('js/jquery.validate.js') !!}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
@if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
<script>
$(function() {
    $('#edit-email-modal').modal('show');
});
</script>
@endif
<script>



   /* $('document').ready(function() {
        getState();
         });*/
      $(document).on("click", "#show-delete-model", function(event){
         var id = $(this).data('id');
         $('#account_id').val(id);
         $("#deleteAccount").modal('show');
      });

      function getState(){
          var country_id =  $("#country option:selected").attr('countryid');
          $.ajax({
            url: appurl+"get-state",
            type: "post",
            data: {country_id :country_id },
            success: function(text) {
                $(".state").html(text);
          
            }
          });
         
        }
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      $("#sidebarMenu").niceScroll(); 

       function formatSelection(val) {
          return val.id;
        }

        $(function() {
            $('.selectNiches').select2({
               placeholder: "Select Niches",
              width: '100%'
            });
           
            
        
        });
</script>
@endsection