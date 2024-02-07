 
<h4> Site's List </h4>
@if(count($sites)>0)
<div class="table-responsive" style="max-height: 350px; overflow: scroll;">
   <table class="table table-hover table-bordered table-sm">
      <thead class="thead-dark">
         <tr>
            <th scope="col">S. No.</th>
            <th scope="col">Author</th>
            <th scope="col">Website</th>
            <th scope="col">Category</th>
         </tr>
      </thead>
      <tbody id="domain-list">
         @if(count($sites)>0)
         <?php $site_ids = []; 
            $credit_cost = 0;
         ?>
         @foreach($sites as $key=> $site)
         @php
               $extension = pathinfo($site->website, PATHINFO_EXTENSION);
               $domain = substr($site->website, 0, strrpos($site->website, '.'));
               $length = strlen($domain);
               $firsttwochar = substr($domain,0,2);
               $lastchar = substr($domain, -1);
               $star = '';
               for ($i=0; $i <= $length ; $i++) { 
               $star .= '*';
               }
               @endphp
         <tr>
            <td>{{ ($key+1) }}</td>
            <td>{{ $site->author }}</td>
            @if($reveal_or_not =='1')
            <td><a href="#" class="text-decoration-none" target="_blank">{{ $site->website }}</a></td>
            @else
            <td><a href="#" class="text-decoration-none" target="_blank">{{ucfirst($firsttwochar).$star.$lastchar.'.'.$extension}}</a></td>
            @endif
           
               <td>@php
                 $cat = explode(',', $site->tag_category);
                 $categories = array_slice($cat, 0, 2);
                 foreach ($categories as $category) {
                 echo "<span class='badge rounded-pill ms-2 lastDate text-dark px-3'>$category</span>";
                 }
                 @endphp </td>
            
         </tr>
          <?php $site_ids[] = $site->domain_id; 
          $credit_cost += $site->credit_cost;


          ?>
         @endforeach
         @endif

      </tbody>
   </table>
</div>

 <hr>
<div class="hstack mb-2">
   <div class="row mb-3">
         <div class="col-sm-12">
            
             @if($reveal_or_not !='1')
            <input type ="hidden" class="credit_need" name="credit_need" id="credit_need" value="{{ $credit_cost }}" >
            @else
            <input type ="hidden" class="credit_need" name="credit_need" id="credit_need" value="0" >
            @endif
            <input type ="hidden" name="domain_ids" id="domain_ids" value="{{ implode(',',$site_ids)}}" >
            <input type ="hidden" name="size" id="size" value="{{ $size }}" >
            <input type ="hidden" class="total_sites" name="total_sites" id="total_sites" value="{{ count($sites) }}" >
            <input type ="hidden" name="revealor_not" id="revealor_not" value="{{ $reveal_or_not }}" >
            
         </div>
      </div>
</div>
@else
 <input type ="hidden" class="total_sites" name="total_sites" id="total_sites" value="0" >
 <input type ="hidden" class="credit_need" name="credit_need" id="credit_need" value="0" >
<p> No Domain Found---------- </p>
@endif

