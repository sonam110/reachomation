<div class="col-sm-12" id="recipientsdata">
   <div class="col-sm-10">
             
      <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
         <div class="col">
            <div class="card h-100 shadow-sm border-0 rounded-1 bg-light">
               <div class="card-body py-2">
                  <div>
                     <p class="mb-0 text-muted fw-600" >
                        <small>Total Contact</small>
                     </p>
                     <h4 class="mb-0 fw-bold" id="total-contact">
                        {!! count($excelData) !!}
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
                        <small>Imported Contact</small>
                     </p>
                     <h4 class="mb-0 fw-bold" id="total-import">
                        {!! count($excelData) !!}
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
                        <small>Duplicate Contact</small>
                     </p>
                     <h4 class="mb-0 fw-bold" id="duplicate-contact">
                        0
                     </h4>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <a href="javascript:;" type="button" class="btn btn-primary shadow-sm fw-500 fw-semibold rounded-1 validate-contact refine-list">
         <i class="bi bi-plus-lg"></i> Refine your list
      </a> 
      
   </div>

      
      <input type="hidden" name="total_count" id="total-count" class="total-count" value="{!! count($excelData) !!}"> 
      <hr>
      <div class="table-responsive">
         <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr class="bg-blue text-white">
                  <th scope="col">S.NO</th>
                  <?php 
                     $row = ''; 
                     $dcounts = 0;
                     $validContact = 0;
                     $inValidContact = 0;
                     $duplicateCount = 0;
                     $inValidEmail = 0;
                     $emailsArray = [];

                  ?>
                  @if(count($heading)>0)
                     @foreach($heading as $key => $head)
                     
                     <?php 
                        if(ucfirst($head) == 'Email'){
                           $row = $key;
                        }
                      ?>
                      @if(!empty($head))
                     <th scope="col">{{$head}}</th>
                     @endif
                     @endforeach
                  @endif


               </tr>
            </thead>
             <tbody>
                 @if(count($excelData)>0)
                 @foreach($excelData as $key=>  $excel)

                  <?php
                     $is_valid_emai = false;
                     $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix';
                     if(preg_match($pattern,trim(@$excel[$row])) == 1){
                           $is_valid_emai = true;
                     }

                     if($is_valid_emai== false){
                        $inValidEmail++;
                        \Log::info(@$excel[$row]);
                     }

                        
                     
                 ?>
                  <tr>
                    
                     @if(!empty($excel[$row]) )
                     @php 
                     array_push($emailsArray,ucfirst(@$excel[$row]));
                     $dcounts = array_count_values($emailsArray);
                     @endphp
                     @if($dcounts[ucfirst($excel[$row])] == '1')
                     @if($is_valid_emai== true)
                     @php $validContact++;  @endphp
                     <td>{!! $validContact  !!}  </td>
                     @foreach($excel as $k => $data)
                       
                     
                        <td>{!! $data !!} </td>
                      
                     @endforeach 
                     @endif
                     @else
                      @php $duplicateCount++;  @endphp
                     @endif
                     @else
                     @php  $inValidContact++ @endphp
                     @endif
                  </tr>
                  @endforeach 
                  @endif
                 
                     
               </tbody>
         </table>
         <input type="hidden" name="import_contact" id="valid_contact" value="{{ $validContact }}">
         <input type="hidden" name="invalid_contact" id="invalid_contact" value="{{ $inValidEmail }}">
         <input type="hidden" name="duplicate_contact" id="duplicate_contact" value="{{ $duplicateCount }}">
         <input type="hidden" name="inValidEmail" id="inValidEmail" value="{{ $inValidEmail }}">

      </div> 
   </div>
<script type="text/javascript">
$(document).ready(function(){
  
   var valid_contact = $('#valid_contact').val();
   var invalid_contact = $('#invalid_contact').val();
   var duplicate_contact = $('#duplicate_contact').val();
 
   $('#total-import').text(valid_contact);
   $('#invalid-contact').text(invalid_contact);
   $('#duplicate-contact').text(duplicate_contact);
   $('#validcontact').val(valid_contact);
   
  
});
$(document).on('click', '.validate-contact', function(){

  var id = $('#edit_id').val();
  var is_file_change = $('#is_file_change').val();
  if(id !='' && is_file_change=='0'){
     var features = $('#edit_features').val();
     var credit = $('#edit_credit_deduct').val();
    creditModel();
 }else {


     creditModel();
 }
      
  
   
});
</script>

