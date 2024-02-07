<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#0f172a">
   <!-- Bootstrap CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet" >
   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}"/>
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}"/>
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert"></script>
   <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
   <link rel="stylesheet" src="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
   <script src="/js/dataTables.bootstrap4.min.js"></script>
   <script src="/js/dataTables.responsive.min.js"></script>
   <script src="/js/dataTables.fixedHeader.min.js"></script>
   <script src="/js/responsive.bootstrap.min.js"></script>
   <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="/css/fixedHeader.bootstrap.min.css">
   <link rel="stylesheet" href="/css/responsive.bootstrap.min.css">
   <title>Outlook - Reachomation</title>
</head>

<body class="bg-light">
   @include('sections.navbar')
   <?php 
   //echo "<PRE>";print_R($_SESSION);die;
   $get_all_cookies = Cookie::get();
   //echo "<PRE>";print_R($get_all_cookies);die;
   ?>
   <div class="container-fluid">
      <div class="row g-0">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            @include('flash-message')
            <div class="alert alert-success" role="alert" id="sucessdiv" style="display: none;"></div>
            <div class="py-3">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h3 class="fw-bold mb-0">
                        Mail List
                     </h3>
                  </div>
                  <div class="ms-auto">
                     <a href="javascript:void(0);" type="button" class="btn btn-primary shadow-sm fw-500" onclick="refreshMail()"> Refresh Mail</a> 
                     <a href="{{ route('send_outlook_mail') }}" type="button" class="btn btn-primary shadow-sm fw-500"> Send Mail</a> 
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <label class="mb-2" for="outlok_auth_user">Select Outlook User</label>
                     <select class="form-control" id="outlok_auth_user" name="outlok_auth_user">
                        <?php 

                        foreach($allOutlookUser as $user){ 
                           $sel = '';
                           if(isset($_SESSION['authuesrid'])){
                           
                              if($_SESSION['authuesrid'] == $user->id){
                                 $sel = 'selected';
                              }
                           }
                           
                           ?>
                           <option <?php echo $sel;?> value="<?php echo $user->id;?>"><?php echo $user->email;?></option>
                        <?php }
                        ?>
                     </select>
                 </div>
                 <div class="col-md-2 mt-4">
                    <a href="<?php echo $authurl;?>">Add another account</a>
                 </div>
            </div>
            <div class="loading" style="display:none;">Loading&#8230;</div>
            <div class="py-3">
               <table id="example" class="table table-striped table-bordered" style="width:100%">
                   <thead>
                       <tr>
                        <th>#</th>
                           <!-- <th>Message ID</th> -->
                           <th>Mail From</th>
                           <th>Subject</th>
                           <th>Delivered Date</th>
                           <th>Action</th>
                       </tr>
                   </thead>
                   <!-- <tbody>
                       <?php if(isset($maildata)) { 
                        $i = 1;
                        foreach($maildata as $mail) {
                        $removestr = str_replace('Email','',$mail->mail_from);
                        $removestr1 = str_replace('<','',$removestr);
                        $removestr2 = str_replace('>','',$removestr1);

                       ?>
                       <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $removestr2;?></td>
                        <td><?php echo $mail->subject;?></td>
                        <td><?php echo date('Y-m-d',strtotime($mail->Date));?></td>
                        <td><a href="javascript:void(0);" onclick="DeleteMail('<?php echo $mail->messageid; ?>')">Delete</a> | <a href="{{ url('view_outlook_mail/'.$mail->id)}}"> View </a></td>
                       </tr>
                       <?php $i++; }  }  ?>
                   </tbody> -->
               </table>
            </div>
            @include('sections.footer')
         </main>
      </div>
   </div>


   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="js/list.js"></script>
   <script>
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })
       var dataTable
      $(document).ready(function() {
         /*$('#example').DataTable({
            responsive: true
         });*/
         dataTable = $('#example').DataTable({
             'processing': true,
             'serverSide': true,
             'serverMethod': 'post',
             'stateSave':true,
             //'searching': false, // Remove default Search Control
             'ajax': {
                'type': 'POST',
                'url':'<?php echo url('get_outlook_data');?>',
                'data': function(data){

                  var user = $('#outlok_auth_user').val();
                  data.user = user;
                }
             },
             'columns': [
                { data: 'id' },
                { data: 'mail_from' },
                { data: 'subject' }, 
                { data: 'Date' },
                { data: 'action' },
             ]
           });

         $('#outlok_auth_user').change(function(){
            dataTable.draw();
        });
      } );

      function DeleteMail(mailid){
         var authuser = $('#outlok_auth_user').val();
      if (confirm('Are you sure want to delete??')) {
         $('.loading').css('display','block');
         $.ajax({
              url: "<?php echo url('delete_outlook_mail');?>",
              type: 'POST',
              dataType: 'html',  
              data: {mailid:mailid,authuser:authuser},
              success: function(res) {
               dataTable.ajax.reload();
               $('.loading').css('display','none');
               $('#sucessdiv').css('display','block');
               $('#sucessdiv').html("Delete Successfully");
              }
          });
      }
   }

   function refreshMail(){
      var authuser = $('#outlok_auth_user').val();
      $('.loading').css('display','block');
      var url = '<?php echo url('/');?>';
      window.location.href = url+'/outlook_mail_get/1/'+authuser;
   }
   </script>
</body>
</html>
