<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#0f172a">
   <!-- Bootstrap CSS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet" >

   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
      <link rel="stylesheet" href="{{asset('css/views/send-gmail.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}"/>
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}"/>
   <!-- JQuery -->
   <script src="https://unpkg.com/@popperjs/core@2"></script>
   <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script></script>
   <script src="/js/ckeditor.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert"></script>
   <title>Gmail - Reachomation</title>
</head>

<body class="bg-light">
   @include('sections.navbar')
   
   <div class="container-fluid">
      <div class="row g-0">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            @include('flash-message')
            <div class="alert alert-success" role="alert" id="successDiv" style="display:none;"></div>
            <div class="alert alert-danger" role="alert" id="errorDiv" style="display:none;"></div>
            <div class="py-3">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h3 class="fw-bold mb-0">
                        Send Mail
                     </h3>
                  </div>
                  <div class="ms-auto">
                     <a href="{{ route('gmail_list') }}" type="button" class="btn btn-primary shadow-sm fw-500"> Mail List</a> 
                      
                  </div>
               </div>
            </div>
            <div class="loading" style="display:none;">Loading&#8230;</div>
            <div class="py-3">
               <form method="post" onsubmit="return validateData();" action="{{ url('mail_gmail_send') }}" enctype="multipart/form-data">
                  @csrf
                 <div class="row">
                     <label for="from_mail" class="col-sm-2 col-form-label">From Email</label>
                     <div class="col-sm-10">
                         <select class="form-control" id="from_mail" name="from_mail">
                           <?php 
                           foreach($allGmailUser as $user){
                              $sel = '';
                              if(isset($_SESSION['gmailauthuesrid'])){
                                 if($_SESSION['gmailauthuesrid'] == $user->id){
                                    $sel = 'selected';
                                 }
                              }
                            ?>
                              <option <?php echo $sel; ?> value="<?php echo $user->id;?>"><?php echo $user->email;?></option>
                           <?php }
                           ?>
                        </select>
                     </div>
                 </div>
                 <div class="row mt-2">
                   <label for="email" class="col-sm-2 col-form-label">Email</label>
                   <div class="col-sm-10">
                     <input type="text" class="form-control" id="email" placeholder="demo@gmail.com,demo1@gmail.com" name="email">
                     <span class="error" id="emailerr"></span>
                   </div>
                 </div>
                 <div class="row mt-2">
                   <label for="ccemail" class="col-sm-2 col-form-label">CC</label>
                   <div class="col-sm-10">
                     <input type="text" class="form-control" id="ccemail" placeholder="demo@gmail.com,demo1@gmail.com" name="ccemail">
                   </div>
                 </div>
                 <div class="row mt-2">
                   <label for="bccemail" class="col-sm-2 col-form-label">BCC</label>
                   <div class="col-sm-10">
                     <input type="text" class="form-control" id="bccemail" placeholder="demo@gmail.com,demo1@gmail.com" name="bccemail">
                   </div>
                 </div>
                 <div class="row mt-2">
                   <label for="subject" class="col-sm-2 col-form-label">Subject</label>
                   <div class="col-sm-10">
                     <input type="subject" class="form-control" id="subject" placeholder="Subject" name="subject">
                   </div>
                 </div>
                 <div class="row mt-2">
                   <label for="body" class="col-sm-2 col-form-label">Body</label>
                   <div class="col-sm-10">
                     <textarea name="body" id="body"></textarea>
                   </div>
                 </div>
                 <div class="row mt-2">
                   <label for="imgMultipleInput" class="col-sm-2 col-form-label">Attachment</label>
                   <div class="col-sm-10">
                     <input class="form-control d-block" name="formFile[]" type="file" id="imgMultipleInput" multiple>
                  </div>
                 </div>
                 <div class="row mt-2">
                   <div class="col-sm-10">
                     <button type="submit" class="btn btn-primary">Send Mail</button>
                   </div>
                 </div>
                 <input type="hidden" id="remove_val" name="remove_val" value="">
                 <input type="hidden" id="drivelink" name="drivelink" value="">
                 <input type="hidden" id="insertID" name="insertID" value="">
               </form>
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

      $(document).ready(function(){
         CKEDITOR.replace( 'body' );

         $("#imgMultipleInput").change(function () {
            readURLMultiple(this);
         });

         var readURLMultiple = function (input) {
            //   console.log(input.files);
              let imageurl = [];
              let _image_input = $('#imgMultipleInput');
              if (input.files) {
                  var filesAmount = input.files.length;
                  var totalSize = 0;
                  var form_data = new FormData();
                  form_data.append('drivelink',$('#drivelink').val());
                  form_data.append('insertID',$('#insertID').val());
                  
                    for (var j = 0; j < filesAmount; j++) {
                     totalSize += input.files[j].size;
                     form_data.append("formFile[]", document.getElementById('imgMultipleInput').files[j]);
                  }
                  if(totalSize >= '26214400'){
                     if(confirm("File Size is more than 25 MB. Are u sure want to upload in Google Drive??")){
                        $('.loading').css('display','block');
                        $.ajax({
                              type: 'POST',
                              url: "<?php echo url('gdriveupload');?>",
                              data: form_data,
                              dataType: 'json',
                              contentType: false,
                              //cache: false,
                              processData:false,
                              success: function(resp){
                                 $('.loading').css('display','none');
                                  if(resp.status == '1'){
                                    $('#drivelink').val(resp.weblinkArr);
                                    $('#insertID').val(resp.insertIDArr);
                                    
                                    var bodyhtml = '<div dir="ltr">';
                                    for(var f=0;f<resp.weblinkArr.length;f++){
                                       bodyhtml += '<div class="gmail_chip gmail_drive_chip" style="width:396px;height:18px;max-height:18px;background-color:#f5f5f5;padding:5px;color:#222;font-family:arial;font-style:normal;font-weight:bold;font-size:13px;border:1px solid #ddd;line-height:1"><a href="'+resp.weblinkArr[f]+'" target="_blank" style="display:inline-block;max-width:366px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-decoration:none;padding:1px 0;border:none" aria-label="'+resp.filenamearr[f]+'"><span dir="ltr" style="color:#15c;text-decoration:none;vertical-align:bottom">'+resp.filenamearr[f]+'</span></a></div>';
                                    }
                                    
                                    bodyhtml += '</div>';
                                    //CKEDITOR.instances.body.insertHtml('');
                                    CKEDITOR.instances.body.insertHtml(bodyhtml); 
                                      $('#imgMultipleInput').val('');
                                      $('#successDiv').css('display','block');
                                      $('#successDiv').html('Successfully file upload in drive');
                                  }else if(resp.status == '0'){
                                    $('#imgMultipleInput').val('');
                                    $('#errorDiv').css('display','block');
                                      $('#errorDiv').html('File not upload in drive');
                                  }
                              }
                        });
                     }else{
                        $('#imgMultipleInput').val('');
                     }
                  }else{
                     for (var i = 0; i < filesAmount; i++) {
                         const file = input.files[i];
                         if (file) {

                             var reader = new FileReader();
                             reader.fileName = file.name;

                             var fileType = file["type"];
                             
                             var imagextarr = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/svg+xml"];

                             if(imagextarr.includes(fileType)){
                              reader.onload = function (event) {
                                 
                                 $("<div class='inline' id=\"remove_" + event.target.fileName +"\" ><span class=\"pip\">" +
                                 "<a href=\"" + event.target.result + "\" target='_blank'><img class=\"imageThumb\" src=\"" + event.target.result + "\" title=\"" + event.target.fileName + "\"/>" +
                                 "</a><br/><span  data-title=\"" + event.target.fileName + "\" onclick=removeData(\"" + event.target.fileName + "\") class=\"remove\" style=\"margin-right: 10px;\"><i class=\"bi bi-trash\"></i></span></div>").insertAfter("#imgMultipleInput");
                              }
                              reader.readAsDataURL(input.files[i]);
                             }else{
                              reader.onload = function (event) {
                                 
                                 $("<div class='inline' id=\"remove_" + event.target.fileName +"\" ><span class=\"pip\">" +
                                 "<a href=\"" + event.target.result + "\" target='_blank'>"+event.target.fileName +"</a><br/><span  data-title=\"" + event.target.fileName + "\" onclick=removeData(\"" + event.target.fileName + "\") class=\"remove\" style=\"margin-right: 10px;\"><i class=\"bi bi-trash\"></i></span></div>").insertAfter("#imgMultipleInput");
                              }
                              reader.readAsDataURL(input.files[i]);
                             }
                         }
                     }
                  }
              }
          }
      });

      var removeArray = [];
      function removeData(id){
          document.getElementById('remove_'+id).style.display = "none";
          removeArray.push(id);
          document.getElementById('remove_val').value = removeArray;
      }

      function validateData(){
      var flag = [];
      var email = $('#email').val();
      if(email == ''){
         flag.push(1);
         $('#email').focus();
         $('#emailerr').html('Email Address is required!!');
      }else{
         flag.push(0);
         $('#emailerr').html('');
      }

      if(!flag.includes(1)){
         $('.loading').css('display','block');
         return true;
      }else{
         return false;
      }

    }
   </script>
</body>
</html>
