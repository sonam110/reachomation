@extends('layouts.master')
@section('content')
<div class="py-3">
   <div class="hstack border-bottom gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0">
            AI Blog Finder
         </h3>
      </div>
   </div>

   <div class="card rounded-0 border-0 shadow-sm mb-3">
      <div class="card-body d-flex align-items-center py-5">
         <div class="d-flex flex-column mx-auto">
            <div class="text-center">
               <h2 class="fw-bold display-6 mb-3">Identify blogs from your list Instantly</h2>
               <h5 class="mb-3">Upload list of sites and our AI blog finder will find blogs out of them</h5>
            </div>
            <div class="row justify-content-center">
               <div class="col-6 col-offset-3 mb-3">
                  <input class="form-control form-control-lg shadow-none" type="text" placeholder="Enter description">
               </div>
            </div>
            <div class="mx-auto">
               <form class="form-container" enctype='multipart/form-data'>
                  @csrf
                  <div class="upload-files-container">
                     <div class="drag-file-area">
                        <span class="material-icons-outlined upload-icon"> file_upload </span>
                        <h3 class="dynamic-message mb-2"> Drag & drop file here </h3>
                        <label class="label d-flex flex-column">
                           <p class="mb-4">or</p>
                           <span class="browse-files">
                              <input type="file" class="default-file-input" /> <span class="browse-files-text">browse
                                 file</span> <span>from device</span>
                           </span>
                        </label>
                     </div>
                     <span class="cannot-upload-message"> <span class="material-icons-outlined">error</span> Please
                        select a file first <span class="material-icons-outlined cancel-alert-button">cancel</span>
                     </span>
                     <div class="file-block">
                        <div class="file-info"> <span class="material-icons-outlined file-icon">description</span> <span
                              class="file-name"> </span> | <span class="file-size"> </span> </div>
                        <span class="material-icons remove-file-icon">delete</span>
                        <div class="progress-bar"> </div>
                     </div>
                     <button type="button" class="upload-button"> Upload </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <div class="card rounded-0 border-0 shadow-sm">
      <div class="card-body">
         <table class="table table-striped">
            <thead>
               <tr>
                  <th scope="col">S. No.</th>
                  <th scope="col">Date</th>
                  <th scope="col">Description</th>
                  <th scope="col">List size</th>
                  <th scope="col">Credits spent</th>
                  <th scope="col">Status</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <th scope="row">1</th>
                  <td>MM/DD/YYYY</td>
                  <td>lorem epsum</td>
                  <td>100</td>
                  <td>100</td>
                  <td>10%/Download</td>
               </tr>
               <tr>
                  <th scope="row">1</th>
                  <td>MM/DD/YYYY</td>
                  <td>lorem epsum</td>
                  <td>100</td>
                  <td>100</td>
                  <td>10%/Download</td>
               </tr>
               <tr>
                  <th scope="row">1</th>
                  <td>MM/DD/YYYY</td>
                  <td>lorem epsum</td>
                  <td>100</td>
                  <td>100</td>
                  <td>10%/Download</td>
               </tr>
               <tr>
                  <th scope="row">1</th>
                  <td>MM/DD/YYYY</td>
                  <td>lorem epsum</td>
                  <td>100</td>
                  <td>100</td>
                  <td>10%/Download</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>

</div>

@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
var isAdvancedUpload = function() {
var div = document.createElement('div');
return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

let draggableFileArea = document.querySelector(".drag-file-area");
let browseFileText = document.querySelector(".browse-files");
let uploadIcon = document.querySelector(".upload-icon");
let dragDropText = document.querySelector(".dynamic-message");
let fileInput = document.querySelector(".default-file-input");
let cannotUploadMessage = document.querySelector(".cannot-upload-message");
let cancelAlertButton = document.querySelector(".cancel-alert-button");
let uploadedFile = document.querySelector(".file-block");
let fileName = document.querySelector(".file-name");
let fileSize = document.querySelector(".file-size");
let progressBar = document.querySelector(".progress-bar");
let removeFileButton = document.querySelector(".remove-file-icon");
let uploadButton = document.querySelector(".upload-button");
let fileFlag = 0;

fileInput.addEventListener("click", () => {
	fileInput.value = '';
	// console.log(fileInput.value);
});

fileInput.addEventListener("change", e => {
	// console.log(" > " + fileInput.value)
	uploadIcon.innerHTML = 'check_circle';
	dragDropText.innerHTML = 'File Dropped Successfully!';
	document.querySelector(".label").innerHTML = `drag & drop or <span class="browse-files"> <input type="file" class="default-file-input" style=""/> <span class="browse-files-text" style="top: 0;"> browse file</span></span>`;
	uploadButton.innerHTML = `Upload`;
   let name = fileInput.files[0].name;
	fileName.innerHTML = name.slice(0, 15)+'...';
	fileSize.innerHTML = (fileInput.files[0].size/1024).toFixed(1) + " KB";
	uploadedFile.style.cssText = "display: flex;";
	progressBar.style.width = 0;
	fileFlag = 0;
});

uploadButton.addEventListener("click", () => {
	let isFileUploaded = fileInput.value;
	if(isFileUploaded != '') {
		if (fileFlag == 0) {
    		fileFlag = 1;
    		var width = 0;
    		var id = setInterval(frame, 50);
    		function frame() {
      			if (width >= 390) {
        			clearInterval(id);
					uploadButton.innerHTML = `<span class="material-icons-outlined upload-button-icon"> check_circle </span> Uploaded`;
      			} else {
        			width += 5;
        			progressBar.style.width = width + "px";
      			}
    		}
  		}
	} else {
		cannotUploadMessage.style.cssText = "display: flex; animation: fadeIn linear 1.5s;";
	}
});

cancelAlertButton.addEventListener("click", () => {
	cannotUploadMessage.style.cssText = "display: none;";
});

if(isAdvancedUpload) {
	["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach( evt => 
		draggableFileArea.addEventListener(evt, e => {
			e.preventDefault();
			e.stopPropagation();
		})
	);

	["dragover", "dragenter"].forEach( evt => {
		draggableFileArea.addEventListener(evt, e => {
			e.preventDefault();
			e.stopPropagation();
			uploadIcon.innerHTML = 'file_download';
			dragDropText.innerHTML = 'Drop your file here!';
		});
	});

	draggableFileArea.addEventListener("drop", e => {
		uploadIcon.innerHTML = 'check_circle';
		dragDropText.innerHTML = 'File Dropped Successfully!';
		document.querySelector(".label").innerHTML = `<p class="mb-4">or</p><span class="browse-files"><input type="file" class="default-file-input" /> <span class="browse-files-text">browse file</span> <span>from device</span></span>`;
		uploadButton.innerHTML = `Upload`;
		
		let files = e.dataTransfer.files;
		fileInput.files = files;
		// console.log(files[0].name + " " + files[0].size);
		// console.log(document.querySelector(".default-file-input").value);
		fileName.innerHTML = files[0].name;
		fileSize.innerHTML = (files[0].size/1024).toFixed(1) + " KB";
		uploadedFile.style.cssText = "display: flex;";
		progressBar.style.width = 0;
		fileFlag = 0;
	});
}

removeFileButton.addEventListener("click", () => {
	uploadedFile.style.cssText = "display: none;";
	fileInput.value = '';
	uploadIcon.innerHTML = 'file_upload';
	dragDropText.innerHTML = 'Drag & drop file here';
	document.querySelector(".label").innerHTML = `<p class="mb-4">or</p><span class="browse-files"><input type="file" class="default-file-input" /> <span class="browse-files-text">browse file</span> <span>from device</span></span>`;
	uploadButton.innerHTML = `Upload`;
});
</script>
@endsection
