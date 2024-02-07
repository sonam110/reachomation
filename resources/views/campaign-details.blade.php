@extends('layouts.master')
@section('content')
   <div class="py-3">
      <div class="card mt-3 shadow-sm">
         <div class="card-body">
            <div
               class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
               <h1 class="h4"><a href="{{ route('campaigns') }}"><i class="bi bi-arrow-left"></i></a> 
                  {{ @$data->name }}
                 </h1>
               
               <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="d-flex">
                        <h5 style="color:red;">Sender Mail: {{ @$data->from_email }}</h5>&nbsp;&nbsp;&nbsp;
                        @if(@$data->status != '7') 
                            <a href="{{ route('campaign-details',@$data->uuid) }}" type="button" class="btn btn-outline-success btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View"><i class="bi bi-eye-fill"></i></a>
                         @endif
                         @if(@$data->status == '7' && @$data->is_file_read == 'Y') 
                            <a href="{{ @$data->final_upload_csv }}" class="btn btn-outline-danger btn-sm fw-500"  data-bs-original-title="Download" download  target="_blank"><i class="bi bi-download"></i></a>
                         @endif
                        
                        @if(@$data->status=='0' || @$data->status=='1' || @$data->status=='6' || (@$data->status=='5' &&  @$checkMailsend == '0')) 
                         <a href="{{ route('edit-campaign',$data->uuid) }}" type="button" class="btn btn-outline-primary btn-sm fw-500 me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit"><i class="bi bi-pencil-fill" ></i></a>
                        @endif
                        
                       
                       @if(@$data->status=='0' || @$data->status=='1' || @$data->status=='6') 
                        <button type="button" id="show-delete-model" data-id="{{ @$data->id }}" class="btn btn-outline-danger btn-sm fw-500" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" ><i class="bi bi-trash-fill"></i></button>
                       @endif
                        @if(@$data->status=='8' ) 
                         <a href="{{ route('download-report',[@$data->uuid,NULL]) }}"  class="btn btn-outline-danger btn-sm fw-500" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download" ><i class="bi bi-download"></i></a>
                        @endif
                     </div>
                 
               </div>
            </div>
         </div>
         <div id="statics"></div>
      </div>
   </div>

   <div class="card shadow-sm mb-3 mt-3">
      @if ($data->status != '7')
      <div class="card-body">
         <ul class="nav nav-pills mb-2 d-flex border-bottom pb-2" id="pills-tab" role="tablist">
            <?php $downLoadButton = '';
               $ijk = 0;
               $tkey = 0;  
            ?>
            @foreach (@$attemptsCamp as $key => $atm)
               <?php $ijk++; ?>
               <li class="nav-item" role="presentation">
                  <button class="nav-link {{ @$key + 1 == '1' ? 'active' : '' }} fs-6 fw-bold pills" id="pills-attempt{{ @$key + 1 }}-tab" data-bs-toggle="pill" data-bs-target="#pills-attempt{{ $key + 1 }}" type="button" role="tab" aria-controls="pills-attempt{{ @$key + 1 }}" aria-selected="true">
                     Attempt {{ @$key + 1 }}
                  </button>
               </li>
            @endforeach
         </ul>
         <div class="tab-content" id="pills-tabContent">
            @foreach (@$attemptsCamp as $tkey => $atm)
               <?php $i = $tkey + 1; ?>
               <div class="tab-pane   {{ $i == '1' ? 'fade show active' : '' }}" id="pills-attempt{{ $i }}" role="tabpanel" aria-labelledby="pills-attempt{{ $i }}-tab">
                  <ul class="nav nav-pills mb-2 d-flex border-bottom pb-2" id="pills-tab" role="tablist">
                     <?php   
                        if (@$atm['is_parent'] == '') {
                              $is_parent = @$data->id;
                        } else {
                              $is_parent = @$atm['is_parent'];
                        }
                        $OutreachfollowUps = App\Models\Campaign::where('is_parent', @$is_parent)
                              ->whereNull('is_attempt')
                              ->orWhere('id', @$data->id)
                              ->get();
                        
                     ?>
                     @foreach (@$OutreachfollowUps as $key => $follow)
                        @if (@$follow['is_parent'] == '')
                           <li class="nav-item" role="presentation">
                              <button class="nav-link {{ $key + 1 == '1' ? 'active' : '' }} fs-6 fw-bold pills follow-class followclass{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }} get-send-mail" id="pills-stage{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }}-tab" data-bs-toggle="pill" data-bs-target="#pills-stage{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }}" type="button" role="tab" aria-controls="pills-stage{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }}" aria-selected="true">Outreach </button>
                           </li>
                        @else
                           <li class="nav-item" role="presentation">
                              <button class="nav-link   fs-6 fw-bold pills follow-class followclass{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }} get-send-mail" id="pills-stage{{ $i }}{{ $follow['stage'] }}{{ @$follow['attemp'] }}-tab" data-bs-toggle="pill" data-bs-target="#pills-stage{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }}" type="button" role="tab" aria-controls="pills-stage{{ $i }}{{ @$follow['stage'] }}{{ @$follow['attemp'] }}" aria-selected="true">Followup {{ $key }}</button>
                           </li>
                        @endif
                     @endforeach
                  </ul>
                  <div class="tab-content" id="pills-tabContent">
                     @foreach (@$OutreachfollowUps as $nakey => $attemp)
                        <?php
                           $allsendData = [];
                                    
                           if ($i == '1') {
                              if (@$attemp->is_followup == '1') {
                                 $campaign = App\Models\Campaign::where('stage', @$attemp->stage)
                                       ->where('attemp', $i)
                                       ->where('is_parent', @$attemp->is_parent)
                                       ->first();
                              } else {
                                 $campaign = App\Models\Campaign::where('stage', @$attemp->stage)
                                       ->where('attemp', $i)
                                       ->where('id', @$attemp->id)
                                       ->first();
                              }
                           } else {
                              $campaign = App\Models\Campaign::where('stage', @$attemp->stage)
                                 ->where('attemp', $i)
                                 ->where('is_parent', @$attemp->id)
                                 ->first();
                           }
                           
                           if (!empty($campaign)) {
                              $allsendData = App\Models\SendCampaignMail::where('camp_id', @$campaign->id)->orderby('id', 'ASC');
                              if (isset($request->keyword) && @$request->keyword != '') {
                                 $allsendData = $allsendData->where('to_email', 'like', '%' . @$request->keyword . '%');
                              }
                              if (isset($request->mystatus) && @$request->mystatus != '') {
                                 if (@$request->mystatus == '1') {
                                       $allsendData = $allsendData->where('is_open', '>', '0');
                                 }
                                 if ($request->mystatus == '2') {
                                       $allsendData = $allsendData->where('is_click', '>', '0');
                                 }
                                 if ($request->mystatus == '3') {
                                       $allsendData = $allsendData->where('is_reply', '>', '0');
                                 }
                                 if ($request->mystatus == '4') {
                                       $allsendData = $allsendData->where('status', '1');
                                 }
                                 if ($request->mystatus == '5') {
                                       $allsendData = $allsendData->where('status', '3');
                                 }
                                 if ($request->mystatus == '6') {
                                       $allsendData = $allsendData->where('status', '4');
                                 }
                                 if ($request->mystatus == '7') {
                                       $allsendData = $allsendData->where('status', '2');
                                 }
                              }
                           
                              $allsendData = $allsendData->paginate(20);
                           }
                           
                        ?>

                        <div class="tab-pane {{ $nakey + 1 == '1' ? 'fade show active' : '' }}" id="pills-stage{{ $i }}{{ @$attemp['stage'] }}{{ @$attemp['attemp'] }}" id="pills-stage{{ $i }}{{ @$attemp['stage'] }}{{ @$attemp['attemp'] }}" role="tabpanel" aria-labelledby="pills-stage{{ $i }}{{ @$attemp['stage'] }}{{ @$attemp['attemp'] }}" id="pills-stage{{ $i }}{{ @$attemp['stage'] }}{{ @$attemp['attemp'] }}-tab">
                           <input type="hidden" id="send_camp_id" value="{{ @$campaign->id }}">

                           <?php
                           if (!empty($campaign)) {
                              $downLoadButton .= '<a  href="' . route('download-report', [@$campaign['uuid'], @$campaign['attemp']]) . '"  id="download-report-' . @$campaign['attemp'] . '-' . @$campaign['id'] . '"  class="bi bi-download fs-4 reportLevel " data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download Report" role="button" tabindex="0"></a>';
                           }
                           ?>
                           <div class="table-responsive">
                              <div class="hstack border-bottom pb-2 mb-3">
                                 <div>
                                    <form method="GET" action="">
                                       @csrf
                                       <div class="input-group shadow-sm">
                                          <input type="search" class="form-control shadow-none" placeholder="Search & Hit Enter" aria-describedby="button-addon2" size="25px" aria-label="Search" id="keyword" name="keyword" value="{{ @$_GET['keyword'] }}">

                                       </div>

                                    </div>
                                    &nbsp;
                                    <div>
                                       <select class="form-select shadow-none" name="mystatus" id="mystatus">
                                          <option value="" selected>Status</option>
                                          <option value="1" {{ @$_GET['mystatus'] == '1' ? 'selected' : '' }}>Opens</option>
                                          <option value="2" {{ @$_GET['mystatus'] == '2' ? 'selected' : '' }}">Clicks </option>
                                          <option value="3"
                                                {{ @$_GET['mystatus'] == '3' ? 'selected' : '' }}">Replies
                                          </option>
                                          <option value="4"
                                                {{ @$_GET['mystatus'] == '4' ? 'selected' : '' }}">Sent
                                          </option>
                                          <option value="5"
                                                {{ @$_GET['mystatus'] == '5' ? 'selected' : '' }}">Bounced
                                          </option>
                                          <option value="6"
                                                {{ @$_GET['mystatus'] == '6' ? 'selected' : '' }}">Invalid
                                                Email</option>
                                          <option value="7"
                                                {{ @$_GET['mystatus'] == '7' ? 'selected' : '' }}">
                                                Unsubscribed</option>
                                       </select>
                                    </div>
                                    &nbsp;
                                    <button class="btn btn-success shadow-none" type="submit" id="button-addon2">
                                       <i class="bi bi-search text-white"></i>
                                    </button>
                                 </form>

                                 <div class="ms-auto">
                                    @if (!empty(@$campaign))
                                       <a href=" {{ route('download-report', [@$campaign['uuid'], @$campaign['attemp']]) }}" id="download-report- {{ @$campaign['attemp'] }}-{{ @$campaign['id'] }}" class="bi bi-download fs-4 reportLevel " data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download Report" role="button" tabindex="0"></a>
                                    @endif

                                 </div>
                              </div>

                              <table class="table table-bordered mb-0 table-hover">
                                    <thead>
                                       <tr>

                                          <th scope="col">S.No. </th>
                                          <th scope="col">Email</th>
                                          <th scope="col">Website</th>
                                          <th scope="col">Status</th>
                                          <th scope="col">Opened</th>
                                          <th scope="col">Clicked</th>
                                          <th scope="col">Replied</th>
                                          <th scope="col">Action </th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if (count($allsendData) > 0)
                                          @foreach ($allsendData as $nkey => $sdata)
                                                <?php
                                                $status = 'Pending';
                                                if ($sdata->status == '1') {
                                                   $status = 'Sent';
                                                }
                                                if ($sdata->status == '2') {
                                                   $status = 'Unsubscribed';
                                                }
                                                if ($sdata->status == '3') {
                                                   $status = 'Bounced';
                                                }
                                                if ($sdata->status == '4') {
                                                   $status = 'Invalid Email';
                                                }
                                                if ($sdata->status == '5') {
                                                   $status = 'In Process';
                                                }
                                                $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
                                                ?>
                                                <tr>
                                                   <th scope="row">{{ $nkey + $page * 20 - 19 }}
                                                   </th>

                                                   <td>{{ @$sdata->to_email }}</td>
                                                   <td>{{ @$sdata->website }}</td>
                                                   <td>
                                                      <p class="mb-0">
                                                            {{ $status }}
                                                      </p>
                                                      @if (@$sdata->mail_send_date != '')
                                                            <small>
                                                               {{ date('l d M Y H:i:s A', strtotime(@$sdata->mail_send_date)) }}
                                                            </small>
                                                      @endif
                                                   </td>
                                                   <td>{{ @$sdata->is_open > 0 && @$sdata->status == '1' ? 'Y' : '-' }}
                                                   </td>
                                                   <td>{{ @$sdata->is_click > 0 && @$sdata->status == '1' ? 'Y' : '-' }}
                                                   </td>
                                                   <td>{{ @$sdata->is_reply > 0 && @$sdata->status == '1' ? 'Y' : '-' }}
                                                   </td>
                                                   <td>
                                                      <a class="open-sent-mail" href="javascript:void(0);" data-opensentmail="{{ @$sdata->id }}">
                                                         <i class="bi bi-envelope-fill" role="button" tabindex="0"></i>
                                                      </a>
                                                   </td>
                                                </tr>
                                          @endforeach
                                       @else
                                          <tr>
                                             <td>
                                                Data will be visible after the completion of the previous outreach
                                             </td>
                                          </tr>
                                       @endif



                                    </tbody>
                              </table>
                           </div>
                           @if (count($allsendData) > 0)
                              {{ $allsendData->appends(['keyword' => @$_GET['keyword'], 'mystatus' => @$_GET['mystatus']])->links() }}
                           @endif
                        </div>
                     @endforeach

                  </div>

               </div>
            @endforeach
         </div>
      </div>
      @else
         <div class="card-body">
            <h5> Data in process please wait for some time -------------</h5>
         </div>
      @endif
   </div>
     
</div>

{{-- Hidden input tags start --}}
<input type="hidden" id="dataId" value="{{$data->id}}">
<input type="hidden" id="dataUuid" value="{{ $data->uuid }}">
{{-- Hidden input tags end --}}

{{-- Modal for alert start --}}
<div class="modal modal-alert" tabindex="-1" role="dialog" id="deleteCampaign">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
            <form action="{{ route('delete-campaign') }}" method="post">
               @csrf
               <input type="hidden" name="campid" id="campid">
               <div class="modal-body p-4 text-center">
                  <h5 class="mb-2">Are you sure?</h5>
                  <p class="mb-0">Once deleted, you will not be able to recover this Campaign.</p>
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

               
{{-- Modal for alert start --}}
<div class="modal modal-alert" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Once deleted, you will not be able to recover this list.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none"
                  id="delete-btn"><strong>Yes, delete it</strong></button>
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none"
                  data-bs-dismiss="modal">No thanks</button>
            </div>
      </div>
   </div>
</div>
{{-- Modal for alert end --}}
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

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    {!! Html::script('js/list.js') !!}
<script type="text/javascript">
function openSentMail(id) {
  $.ajax({
        url: appurl+"preview-message",
        type: "post",
        data: {id:id},
        success: function(text) {
            $("#mail-preview-modal").modal('show');
            $('#message-preview-section').html(text);
        }
    });
  
}

function strip_tags (input, allowed) {

    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}
function showStatics(id,uuid){
     $.ajax({
        url: appurl+"show-statics",
        type: "post",
        data: {id:id,uuid:uuid},
        success: function(html) {
          $('#statics').html(html)
           
        }
    });
 
}



 $(document).on("click", "#show-delete-model", function(event){
         var id = $(this).data('id');
         $('#campid').val(id);
         $("#deleteCampaign").modal('show');
      });

$(document).ready(function(){
   var id  = '{{ $data->id }}';
   var uuid  = '{{ $data->uuid }}';
   showStatics(id,uuid);
});
function getDownloadLink(i,id,key){
  
    $('.reportLevel').addClass('d-none');
    $('#download-report-'+i+'-'+id).removeClass('d-none');
    $('.attempt-class').removeClass('fade show active');
    $('.atmclass'+i+id).addClass('fade show active');

}
function playPauseCamp(id,cond){
   if(cond ==='play'){
      $('#pause-btn-'+id).hide();
      $('#play-btn-'+id).show();
   } else{
      $('#pause-btn-'+id).show();
      $('#play-btn-'+id).hide();
   }
   console.log(cond);
    $.ajax({
        url: appurl+"play-pause-campaign",
        type: "post",
        data: {id:id,cond:cond},
        success: function(text) {
         if(text['type']=='success'){
            $('#status-'+id).text(text['status']);

         }
        }
    });
}
</script>
@endsection
