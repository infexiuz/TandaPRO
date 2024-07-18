const form = document.querySelector(".content-area"),
   fileInput = document.querySelector(".file-input"),
   progressArea = document.querySelector(".progress-area"),
   uploadedArea = document.querySelector(".uploaded-area");

if (form) {
   form.addEventListener("click", () => {
      fileInput.click();
   });
}
if (fileInput) {
   fileInput.onchange = ({
      target
   }) => {
      let file = target.files[0];
      if (file) {
         let fileName = file.name;
         if (fileName.length >= 12) {
            let splitName = fileName.split('.');
            fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
         }
         //uploadFile(fileName);
         const image = document.getElementById('img-dragzone');
         const icon = document.getElementById('icon-dragzone');
         const content = document.querySelector('.content-area p');

         if (icon) { icon.remove(); }

         image.src = URL.createObjectURL(file);
         content.innerHTML = fileName;
      }
   }
}

//custom upload multiple files
function uploadFile(name) {
   let xhr = new XMLHttpRequest();
   xhr.open("POST", base_url + "Backoffice/uploadImageBlog");
   xhr.upload.addEventListener("progress", ({
      loaded,
      total
   }) => {
      let fileLoaded = Math.floor((loaded / total) * 100);
      let fileTotal = Math.floor(total / 1000);
      let fileSize;
      (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";
      let progressHTML = `<li>
                           <i class="fas fa-file-alt"></i>
                           <div class="content">
                              <div class="details">
                                 <span class="name">${name} • Uploading</span>
                                 <span class="percent">${fileLoaded}%</span>
                              </div>
                              <div class="progress-bar">
                                 <div class="progress" style="width: ${fileLoaded}%"></div>
                              </div>
                           </div>
                           </li>`;
      uploadedArea.classList.add("onprogress");
      progressArea.innerHTML = progressHTML;
      if (loaded == total) {
         progressArea.innerHTML = "";
         let uploadedHTML = `<li>
                              <div class="content upload">
                                 <i class="fas fa-file-alt"></i>
                                 <div class="details">
                                 <span class="name">${name} • Uploaded</span>
                                 <span class="size">${fileSize}</span>
                                 </div>
                              </div>
                              <i class="fas fa-check"></i>
                           </li>`;
         uploadedArea.classList.remove("onprogress");
         uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
      }
   });
   let data = new FormData(form);
   xhr.send(data);
}
// function to dragzone file con aajax
$(function () {
   var dropzone = $('.dropzone');
   var fileInput = $('.file-input');

   $(document).on('dragenter', function (e) {
      e.stopPropagation();
      e.preventDefault();
   });
   $(document).on('dragover', function (e) {
      e.stopPropagation();
      e.preventDefault();
   });
   $(document).on('drop', function (e) {
      e.stopPropagation();
      e.preventDefault();
   });

   dropzone.on('dragover', function () {
      dropzone.addClass('dragover');
   });
   dropzone.on('dragleave', function () {
      dropzone.removeClass('dragover');
   });

   dropzone.on('drop', function (e) {
      e.preventDefault();
      dropzone.removeClass('dragover');

      var files = e.originalEvent.dataTransfer.files;
      var objectURL = URL.createObjectURL(files[0]);

      fileInput.prop('files', files);
      
      $('.content-area p').text(files[0].name);
      $("#img-dragzone").attr('src', objectURL);
      $("#icon-dragzone").remove();

      /* uploadFile(files[0].name); */
   });
});