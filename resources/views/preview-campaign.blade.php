   
<?php 
    $userCredits = auth()->user()->credits ;
    $totalContacts = $data['total_count']  ;
    $valid_contact = $data['import_contact']  ;
    $totalcredtitSpend = $data['total_count'] ;
    $is_credit = true;
    if($userCredits <= $totalcredtitSpend){
        $is_credit = false;
        $creditRemain = 0;

    }else{
        $creditRemain = $totalcredtitSpend - $userCredits;
    }
    $counArr = [];
    $totalCount = 0;
    $level = '';
    $allEmailsLeval = [];
    $allEmails = [];
    foreach($collections as $key => $site){
        $arr = json_decode($site->emails, true);
        $allEmails = Helper::getAllEmail($arr);
        $counArr[] = count($allEmails) ;
        $totalCount = count($allEmails) ;
        $arr = json_decode($site->emails, true);
        $textTotalContact = count($allEmails);
        foreach ($allEmails as $nkey => $val) {
            $level  = $nkey+1;
            $allEmailsLeval[] = $val.'-'.$level;
                    
        }
    }
    
    $totalCounArr = array_count_values($counArr);
    ksort($totalCounArr);
    //\Log::info($counArr);
 
     //print_r($totalCounArr);
     //print_r($totalCount);
    //die;

    if(@$data['non_stop'] =='1'){
        $start = date('h A');  
        $end =  date('h A', strtotime('+ 24 hours'));
    } else{
        $start = date('h A',strtotime($data['starttime']));  
        $end = date('h A',strtotime($data['endtime']));
    }
    ?>
   <!-- Campaign Preview modal -->

    <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
        <h3 class="fw-bold mb-0 mt-1 mx-auto">Campaign Preview</h3>


        <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1"
            data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4 pt-2">

        <div class="card rounded-0 border-0 shadow-sm bg-light mt-3 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h5">
                       {{ $data['campaign_name'] }}
                    </h1>  
                </div>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <span class="fw-500 fw-bold fw-size" style="font-size: 18px;">
                     Sender Email:  {{ @$data['from_email'] }}
                    </span>  
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-1">
                    <div class="col">
                       <div class="card h-100 shadow-sm rounded-0 border-0">
                          <div class="card-body py-2">
                             <div class="hstack gap-3">
                                <div>
                                   <p class="mb-0 text-muted" style="font-weight: 600;">
                                      <small>Campaign Start at</small>
                                   </p>
                                   <h5 class="mb-0 fw-bold">{{ date('M d',strtotime($data['start_date'])) }}</h5>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                    @if(@$data['is_feature']!='1')
                    <div class="col">
                       <div class="card h-100 shadow-sm rounded-0 border-0">
                          <div class="card-body py-2">
                             <div class="hstack gap-3">
                                <div>
                                   <p class="mb-0 text-muted" style="font-weight: 600;">
                                      <small>Sending Window</small>
                                   </p>
                                   <h5 class="mb-0 fw-bold">{{ $start }} To {{ $end }}</h5>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                    @endif
                    <div class="col">
                       <div class="card h-100 shadow-sm rounded-0 border-0">
                          <div class="card-body py-2">
                             <div class="hstack gap-3">
                                <div>
                                   <p class="mb-0 text-muted" style="font-weight: 600;">
                                      <small>Total Contacts</small>
                                   </p>
                                   <h5 class="mb-0 fw-bold">{{ $valid_contact }}</h5>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                   
                 </div>

            </div>
        </div>

        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
          
            @for ($i = 1; $i <= $nofAttempts; $i++)
             
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ ($i=='1') ?'active' :''}} px-2" id="pills-attempt{{ $i }}-tab" data-bs-toggle="pill" data-bs-target="#pills-attempt{{ $i }}" type="button" role="tab" aria-controls="pills-attempt{{ $i }}" aria-selected="true">Attempt - {{ $i }}</button>
            </li>
             @endfor
           
        </ul>
        <div class="tab-content" id="pills-tabContent">
           
            @for ($i = 1; $i <= $nofAttempts; $i++)
             <?php 
                $levelArray =[];
                $j =0;

                foreach($excelData as  $excel) {
                    if(!empty($excel[4]) ){
                        array_push($levelArray,$excel[4]);
                    }
                }
                $dcounts = array_count_values($levelArray);
                
                  
              ?>
            <div class="tab-pane fade show {{ ($i=='1') ?'active' :''}}" id="pills-attempt{{ $i }}" role="tabpanel" aria-labelledby="pills-attempt{{ $i }}-tab">
                <div class="row">
                 
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm rounded-0 border-0 bg-light">
                            <div class="card-body py-2">
                               <div class="hstack gap-3">
                                  <div>
                                     <p class="mb-0 text-muted" style="font-weight: 600;">
                                        <small>Outreach</small>
                                     </p>
                                     <h5 class="mb-0 fw-bold">
                                       @if(!empty($dcounts))
                                        {{ @$dcounts[$i] }}
                                        @else
                                        {{ $valid_contact }}
                                        @endif
                                      
                                     </h5>
                                  </div>
                               </div>
                            </div>
                         </div>
                    </div>
                   
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm rounded-0 border-0 bg-light">
                            <div class="card-body py-2">
                               <div class="hstack gap-3">
                                  <div>
                                     <p class="mb-0 text-muted" style="font-weight: 600;">
                                        <small>Cooling Period</small>
                                     </p>
                                     <h5 class="mb-0 fw-bold">
                                          {{ $data['cooling_period'] }} hrs
                                     </h5>
                                  </div>
                               </div>
                            </div>
                         </div>
                    </div>
                </div>
                 @foreach($data['is_followup'] as $key => $value)
                  @if($value == '1')
                  <?php   $j++ ;
                 
                    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                    if (($j %100) >= 11 && ($j%100) <= 13)
                    $term =  'th';
                    else
                    $term =  $ends[$j % 10];

                   ?>
                 <div class="row">
                 
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm rounded-0 border-0 bg-light">
                            <div class="card-body py-2">
                               <div class="hstack gap-3">
                                  <div>
                                     <p class="mb-0 text-muted" style="font-weight: 600;">
                                        <small >{{ $j }}{{ $term }} Followup </small>
                                     </p>
                                     <h5 class="mb-0 fw-bold">
                                       @if(!empty($dcounts))
                                        {{ @$dcounts[$i] }}
                                        @else
                                        {{ $valid_contact }}
                                        @endif
                                      
                                     </h5>
                                  </div>
                               </div>
                            </div>
                         </div>
                    </div>
                   
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm rounded-0 border-0 bg-light">
                            <div class="card-body py-2">
                               <div class="hstack gap-3">
                                  <div>
                                     <p class="mb-0 text-muted" style="font-weight: 600;">
                                        <small>Cooling Period</small>
                                     </p>
                                     <h5 class="mb-0 fw-bold">
                                          {{ $data['cooling_period'] }} hrs
                                     </h5>
                                  </div>
                               </div>
                            </div>
                         </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

             @endfor
          
        </div>

    </div>
    <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
        <button type="button"
            class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red"
            data-bs-dismiss="modal">Close</button>
        <button type="button"
            class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover " id="launch-campaign"name="Launch">Launch Campaign</button>
    </div>
           