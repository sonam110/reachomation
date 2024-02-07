<div class="row">
   <div class="col-12 mb-2">
      <div class="hstack">
         <div>
            <h5 class="mb-4">Campaign Status</h5>
         </div>
         <div class="ms-auto">
            <select class="form-select shadow-none"  id="static-stage" onChange="showStatics(this.value,'{{ $uuid }}')">
                @foreach($OutreachfollowUps as $key => $follow)
                <<?php 
                     $j = $key;
                     $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                     if (($j %100) >= 11 && ($j%100) <= 13)
                     $term =  'th';
                     else
                     $term =  $ends[$j % 10];

                 ?>
               <option  value="{{ $follow['id'] }}" {{ ($data->id == $follow['id']) ? 'selected' :'' }}>
               @if($key+1 =='1')
               Outreach
               @else
               {{ $j }}{{ $term }} Followup  -Statistics
               @endif
              </option>
               @endforeach
            </select>
         </div>
      </div>
      <div class="d-flex">
          @if($activeCamp->status!='4' && $activeCamp->status!='6' )
         <div class="circle circle-flex" id="controls">
            
             <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" color="white" height="2em" width="2em" xmlns="http://www.w3.org/2000/svg" id="pause-btn-{{ $activeCamp->id }}" onclick="playPauseCamp('{{ $activeCamp->id }}','play')"  style="display:{{ ($activeCamp->status == 5) ? 'block':'none' }};">
               <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z">
               </path>
            </svg>
         
            <svg stroke="currentColor" fill="white" stroke-width="0" viewBox="0 0 16 16" color="white" height="2em" width="2em" xmlns="http://www.w3.org/2000/svg" onclick="playPauseCamp('{{ $activeCamp->id }}','pause')" id="play-btn-{{ $activeCamp->id }}" style="display: {{ ($activeCamp->status != 5) ? 'block':'none' }};">
               <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"></path>
            </svg>
           
         </div>
         @endif
         <div class="ms-3">
            <?php $fname =  ($activeCamp->is_parent==NULL)  ? 'outreach'  :'followup' ; 
            $j =  $activeCamp->attemp;
            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
            if (($j %100) >= 11 && ($j%100) <= 13)
            $term =  'th';
            else
            $term =  $ends[$j % 10];
            

            ?>
            @if($ostatus == '5')
            <h5 class="fw-bold" id="status-{{ $activeCamp->id }}">{{ $j }}{{ $term }} attempt {{ $fname }} {{ $status }}</h5>
             <p class="mb-0 fw-500"><small style="color:red">{{ (!empty($activeCamp->start_date)) ? date('l d M Y',strtotime($activeCamp->start_date)) : '' }}</small></p>
            <p class="mb-0 fw-500">
            <small style="color:red"> {{ $activeCamp->reason }} </small>
           </p>
            @elseif($ostatus == '6')
            <h5 class="fw-bold" id="status-{{ $activeCamp->id }}"> {{ $status }}</h5>
             <p class="mb-0 fw-500"><small style="color:red">{{ (!empty($activeCamp->start_date)) ? date('l d M Y',strtotime($activeCamp->start_date)) : '' }}</small></p>
            <p class="mb-0 fw-500">
           </p>
            @elseif($ostatus == '8')
            <h5 class="fw-bold" id="status-{{ $activeCamp->id }}"> {{ $status }}</h5>
             <p class="mb-0 fw-500"><small style="color:red">{{ (!empty($activeCamp->start_date)) ? date('l d M Y',strtotime($activeCamp->start_date)) : '' }}</small></p>
            <p class="mb-0 fw-500">
           </p>
            @elseif($ostatus == '7')
            <h5 class="fw-bold" id="status-{{ $activeCamp->id }}"> {{ $status }}</h5>
             <p class="mb-0 fw-500"><small style="color:red">{{ (!empty($activeCamp->start_date)) ? date('l d M Y',strtotime($activeCamp->start_date)) : '' }}</small></p>
            <p class="mb-0 fw-500">
           </p>
            @else
             <h5 class="fw-bold" id="status-{{ $activeCamp->id }}">{{ $j }}{{ $term }} attempt {{ $fname }} {{ $status }}</h5>
             <p class="mb-0 fw-500"><small style="color:red">{{ (!empty($activeCamp->start_date)) ? date('l d M Y',strtotime($activeCamp->start_date)) : '' }}</small></p>
            @endif
            @if((!empty($activeCamp->start_date)))
            <?php
            $minutes = $totalEmails;
            $totalHours = date("l d M Y H:i A",  strtotime($activeCamp->start_date . "+ ".$totalEmails." minutes"));

            ?>
            <p class="mb-0 fw-500"><small style="color:red">Estimated time: {{ $totalHours }}</small></p>
            @endif
         </div>
      </div>
   </div>
</div>
<hr>
<div class="row row-cols-1 row-cols-md-6 g-1">
   <div class="col">
      <div class="card h-100 shadow-sm">
         <div class="card-body py-2">
            <div class="hstack gap-3">
               <div>
                  <p class="mb-0 text-muted" style="font-weight: 600;">
                     <small>Total Lists</small>
                  </p>
                  <h4 class="mb-0 fw-bold">{{ $totalEmails }} </h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card h-100 shadow-sm" style="background: #e7f8ff;">
         <div class="card-body py-2">
            <div class="hstack gap-3">
               <div>
                  <p class="mb-0 text-muted" style="font-weight: 600;">
                     <small>Mails sent: {{  $totalSendMail  }}</small>
                  </p>
                  <h4 class="mb-0 fw-bold">{{ round($mailSend,2) }}%</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card h-100 shadow-sm" style="    background: #f4fff4;">
         <div class="card-body py-2">
            <div class="hstack gap-3">
               <div>
                  <p class="mb-0 text-muted" style="font-weight: 600;">
                     <small>Opened : {{  $totalOpenMail  }}</small>
                  </p>
                  <h4 class="mb-0 fw-bold">  {{ round($mailOpen,2) }}%</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card h-100 shadow-sm" style="background: #ffe7e7;">
         <div class="card-body py-2">
            <div class="hstack gap-3">
               <div>
                  <p class="mb-0 text-muted" style="font-weight: 600;">
                     <small>Clicked : {{  $totalClickMail  }}</small>
                  </p>
                  <h4 class="mb-0 fw-bold">
                       {{ round($mailClick,2) }}%
                  </h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card h-100 shadow-sm" style="background: #ffffe1;">
         <div class="card-body py-2">
            <div class="hstack gap-3">
               <div>
                  <p class="mb-0 text-muted" style="font-weight: 600;">
                     <small>Replied : {{  $totalReplyMail  }}</small>
                  </p>
                  <h4 class="mb-0 fw-bold">
                       {{ round($mailReply,2) }}%
                  </h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card h-100 shadow-sm" style="background: #ededed;">
         <div class="card-body py-2">
            <div class="hstack gap-3">
               <div>
                  <p class="mb-0 text-muted" style="font-weight: 600;">
                     <small>Failed : {{  $totalFailedMail  }} </small>
                  </p>
                  <h4 class="mb-0 fw-bold">
                     {{ round($mailFailed,2) }}%
                  </h4>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
        