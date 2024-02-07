
  <div class="col-sm-12" id="recipientsdata">
    <?php 
    $credit_cost = 0;
    $site_ids = [];
 ?>
    <div class="col-sm-10">
                        
         <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1 bg-light">
                  <div class="card-body py-2">
                     <div>
                        <p class="mb-0 text-muted fw-600" >
                           <small>List Name</small>
                        </p>
                        <h4 class="mb-0 fw-bold" >
                          {{ $list->name }}
                        </h4>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1 bg-light">
                  <div class="card-body py-2">
                     <div>
                        <p class="mb-0 text-muted fw-600">
                           <small>Total Contact</small>
                        </p>
                        <h4 class="mb-0 fw-bold" id="total-import">
                           {{ count($collections)  }}
                        </h4>
                     </div>
                  </div>
               </div>
            </div>

            
         </div>
         
      </div>
    
        <input type="hidden" name="total_count" id="total_count" class="total_count" value="{{ count($collections)  }}">
        
      <hr>
     <div class="table-responsive">
         <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr class="bg-blue text-white">
                 <th scope="col">S. No.</th>
                 <th scope="col">Author</th>
                 <th scope="col">Website</th>
                 <th scope="col">Email</th>
                 <th scope="col">Tags</th>
              </tr>
           </thead>
               <tbody id="domain-list">
                @if(count($collections)>0)
            
             @foreach($collections as $key=> $site)
             <?php
              $emails = '';
              $emls = json_decode($site->emails,true);
              $countN = Helper::countEmails($emls);
              /*---*/
               if (is_array($emls) && count($emls) > 0) {
               foreach($emls as $k => $eml) {
                  if(is_array($eml)){
                     //$countN =  $countN+count(@$emls[$k]);
                     $moreBtn =  ($countN >1) ? '<span class="badge bg-primary">'.($countN-1).' more</span>' : '';
                     $emails  = @$emls[$k][0].$moreBtn;
                  } else {
                      $moreBtn =  ($countN >1) ? '<span class="badge bg-primary">'.($countN-1).' more</span>' : '';
                      $emails  = @$eml.$moreBtn;
                      
                  }
                  
                  
                }
              }
               
             ?>


             <tr>
                <td>{{ ($key+1) }}</td>
                <td>{{ $site->author }}</td>
               <td><a href="{{ $site->website }}" class="text-decoration-none" target="_blank">{{ $site->website }}</a></td>
                <td>{!! $emails !!}</td>
                
                   <td>@php
                     $cat = explode(',', $site->tag_category);
                     $categories = array_slice($cat, 0, 2);
                     foreach ($categories as $category) {
                     echo "<span class='badge bg-theme rounded-pill text-capitalize me-2'>$category</span>";
                     }
                     @endphp </td>
                    
                
             </tr>
              <?php  
              $credit_cost += $site->credit_cost;
              $site_ids[] = $site->domain_id;

              ?>
             @endforeach
             @endif
           </tbody>
        </table>
        <input type="hidden" name="domain_ids" id="domain_ids" class="domain_ids" value="{{ implode(',',$site_ids)}}">
         <input type="hidden" name="import_contact" id="valid_contact" value="{{ count($collections) }}">
         <input type="hidden" name="invalid_contact" id="invalid_contact" value="0">
         <input type="hidden" name="duplicate_contact" id="duplicate_contact" value="0">

     </div>
  </div>