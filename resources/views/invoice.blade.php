
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice</title>    
    <link href='/css/css6.css' rel='stylesheet'>
       <link rel="stylesheet" href="{{asset('css/views/invoice.css')}}">
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{asset('images/reachomation_logo_black.png')}}" width="100px" >
        
      </div>
      <div id="company">
        <h2 class="name">{!! $appSetting->app_name !!}</h2>
        <div>{!! $appSetting->address !!} India</div>
        <div>M : {!! $appSetting->mobilenum !!} | E : <a href="{!! $appSetting->email !!}">{!! $appSetting->email !!}</a></div>  
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">INVOICE TO:</div>
          <h2 class="name">{!! $userInfo ->name !!}</h2>
          <div class="address"> {!! $userInfo ->line1 !!}<br>
          {!! $userInfo ->city !!}, {!! $userInfo ->postal_code !!} {!! $userInfo ->country !!}</div>
          <div class="email"><a href="{!! $userInfo ->email !!}">{!! $userInfo ->email !!}</a></div>
        </div>
        <div id="invoice">
          <h4>INVOICE : {{ $invoice_id }}</h4>
          <div class="date">Date of Invoice:  {{date('d M Y')}}</div>
          <div class="date">Due Date: </div>
         
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
         <tr>
              <th class="no">#</th>
              <th class="desc"><div>Description</div></th>
              <th class="desc"><div>Qty</div></th>
              <th class="desc"><div>Unit Price</div></th>
              <th class="qty"><div>Amount</div></th>
             
            </tr>
        </thead>
        <tbody>
        <?php
          $total = 0;
          $count = 0;
        ?>

       @foreach($invoice_detail as $key => $invoice)
        <?php
          $count++;
          $total += $invoice->amount;
          if($count == '1'){
            $price =  abs($invoice['amount'])/100;
             $amount = '$'.round($price, 2);
          } else{
            $price =  abs($invoice['amount'])/100;
            $amount = '-$'.round($price, 2);
          }
          
         
          $unitp =  @$invoice->price->unit_amount/100;
          $unit_price =  round($unitp, 2);
        ?>
         <tr>
            <td class="no"></td>
            <td class="desc">{{ $invoice['description'] }}</td>
            <td class="desc">1</td>
            <td class="desc">${{ $unit_price }}</td>
            <td class="unit">{{ $amount }}</td>
          </tr>
         @endforeach
        </tbody>
         <tfoot>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">SUBTOTAL</td>
            <td>${{ round($total/100, 2) }}</td>
          </tr>
          
          <tr>
            <td colspan="2"></td>
            <td colspan="2">TOTAL</td>
            <td>${{ round($total/100, 2) }}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">AMOUNT DUE</td>
            <td>${{ round($invoice->amount_due/100, 2) }}</td>
          </tr>
        </tfoot>
       
      </table>
      <div id="thanks">Thank you!</div>
      <div id="notices">
        
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>
