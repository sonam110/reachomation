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
   <title>Gmail - Reachomation</title>
</head>

<body class="bg-light">
   @include('sections.navbar')
   <?php 
   //echo "<PRE>";print_R($data);die;

   ?>
   <div class="container-fluid">
      <div class="row g-0">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            <div class="py-3">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h3 class="fw-bold mb-0">
                        Mail Detail View
                     </h3>
                  </div>
               </div>
            </div>
            <a class="btn btn-primary mb-3" href="{{ url('gmail_list') }}"> Back </a>
            <div class="py-3">
                <h4 class="card-title"><?php echo $maildata->subject;?></h4>
				<div class="card">
					<div class="card-body">
						<span><?php echo $maildata->mail_From;?></span>
						<h6><?php echo date('D m/d/Y h:i A' , strtotime($maildata->Date));?></h6>
						<h6><?php echo 'To : You'?></h6>
						<?php 
						//echo $maildata->body;die;
						$attachdata = DB::select("SELECT * FROM `gmail_inbox_attachments` where `mail_id` = ".$maildata->id);
						//echo "<PRE>";print_R($maildata);die;
						$imageArr = array();
						$messageIdArr = array();
						if(count($attachdata) !== 0){
							
							foreach($attachdata as $at){
								array_push($imageArr,$at->filename);
								array_push($messageIdArr,$maildata->messageid);
							}
							$doc = new DOMDocument();
						    $doc->loadHTML(html_entity_decode($maildata->body));
						    $imageTags = $doc->getElementsByTagName('img');
						    //echo "<PRE>";print_R($imageTags);die;
						    if($imageTags->length != 0){
						        foreach($imageTags as $k => $tag) {
						        		$srcval = $tag->getAttribute('src');
						        		if (strpos($srcval, 'cid:') !== false) {
						        			$key = 'gmailattchment/'.$messageIdArr[$k].'/'.$imageArr[$k];
							            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
							            $bucket = env('AWS_BUCKET');

							            $command = $client->getCommand('GetObject', [
							                 'Bucket' => $bucket,
							                 'Key' => $key
							            ]);
							            $request = $client->createPresignedRequest($command, '+20 minutes');
							            $presignedUrl = (string)$request->getUri();

						        			//$newsrc = url('public/gmailattchment/'.$messageIdArr[$k].'/'.$imageArr[$k]);
							            //echo $newsrc;die;
							            $tag->setAttribute('src',$presignedUrl); 
							            $htmlString = $doc->saveHTML();
						        		}
						            

						        }
						        //echo $htmlString;die;
						    }else{
						    	echo html_entity_decode($maildata->body,ENT_NOQUOTES, 'UTF-8');
						    }
						    
						}else{
							echo html_entity_decode($maildata->body,ENT_NOQUOTES, 'UTF-8');
						}

						?>
						<?php
						$attachdata = DB::select("SELECT * FROM `gmail_inbox_attachments` where `mail_id` = ".$maildata->id);
						if(count($attachdata) !== 0) { 
							echo '<div class="row">';
							$i = 0;
							$imagextarr = array('jpg','jpeg','jfif','pjpeg','pjp','png','svg','webp','apng','avif','bmp','ico','cur','tif','tiff');
							foreach($attachdata as $at){
						?>
							<div class="col-md-3">
								<?php $ext = pathinfo($at->filename, PATHINFO_EXTENSION);
								if(in_array($ext,$imagextarr)) { ?>
									<a href="{{ url('force_download/'.$at->id.'/'.$maildata->messageid) }}">
										<?php
											$key = 'gmailattchment/'.$maildata->messageid.'/'.$at->filename;
							            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
							            $bucket = env('AWS_BUCKET');

							            $command = $client->getCommand('GetObject', [
							                 'Bucket' => $bucket,
							                 'Key' => $key
							            ]);
							            $request = $client->createPresignedRequest($command, '+20 minutes');
							            $presignedUrl = (string)$request->getUri();
										?>
										<img src="{{$presignedUrl}}" height="100px" width="100px"></a>

								<?php }else { ?>
									<a href="{{ url('force_download/'.$at->id.'/'.$maildata->messageid) }}"><?php echo $at->filename;?></a>
								<?php }
								?>
								
							</div>
						<?php  $i++; 
							if($i == 3){
								echo '<div><div class="row">';
							}
							} 
							echo '</div>';
						}
						?>
					</div>
				</div>
            </div>
            @include('sections.footer')
         </main>
      </div>
   </div>


   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

   <script src="/js/list.js"></script>
   <script>
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })

   </script>
</body>
</html>
