<!-- //////////////video with caption/////////////////////////   -->
<style type="text/css">
   /*Copied from bootstrap to handle input file multiple*/
   .characters-counter{
   display:none;
   }
   .status-btn{
   display:none;
   }
   .btn {
   display: inline-block;
   padding: 6px 12px;
   margin-bottom: 0;
   font-size: 14px;
   font-weight: normal;
   line-height: 1.42857143;
   text-align: center;
   white-space: nowrap;
   vertical-align: middle;
   cursor: pointer;
   -webkit-user-select: none;
   -moz-user-select: none;
   -ms-user-select: none;
   user-select: none;
   background-image: none;
   border: 1px solid transparent;
   border-radius: 4px;
   }
   /*Also */
   .btn-success {
   border: 1px solid #c5dbec;
   background: #d0e5f5;
   font-weight: bold;
   color: #2e6e9e;
   }
   /* This is copied from https://github.com/blueimp/jQuery-File-Upload/blob/master/css/jquery.fileupload.css */
   .fileinput-button {
   position: relative;
   overflow: hidden;
   }
   .fileinput-button input {
   position: absolute;
   top: 0;
   right: 0;
   margin: 0;
   opacity: 0;
   -ms-filter: "alpha(opacity=0)";
   font-size: 200px;
   direction: ltr;
   cursor: pointer;
   }
   .thumb {
   height: 60px;
   width: 60px;
   border-radius: 15px;
   }
   ul.thumb-Images li {
   width: 69px;
   /*float: left;*/
   display: inline-block;
   /*vertical-align: top;
   height: 120px;*/
   }
   .img-wrap {
   position: relative;
   display: inline-block;
   font-size: 0;
   }
   .img-wrap .close {
   position: absolute;
   top: 2px;
   right: 2px;
   z-index: 100;
   background-color: #d0e5f5;
   padding: 5px 2px 2px;
   color: #000;
   font-weight: bolder;
   cursor: pointer;
   opacity: 0.5;
   font-size: 23px;
   line-height: 10px;
   border-radius: 50%;
   }
   .img-wrap:hover .close {
   opacity: 1;
   background-color: #ff0000;
   }
   .FileNameCaptionStyle {
   font-size: 12px;
   }
   ul#imgList {
   padding-left: 0px !important;
   }
   .form-group.float-right {
   float: right;
   }
   img.img-fluid {
   width: 100%;
   height: 200px;
   border-radius: 15px;
   }
   .textarea-highlighter { display: none !important; }

</style>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<div id="videocaption" style="display: none;">
   <form id="photostory-submit" enctype="multipart/form-data" method="post" action="<?php echo $C->SITE_URL;?>system/controllers/api/photostory.php">
      <div class='col-xs-12 col-md-12 col-md-offset-1'>
         <div class='col-xs-12 col-md-11' style='padding:0;'>
            <div class='col-xs-12 col-md-12'>
                <a id="close-videocaption" style="cursor:pointer" class="pull-right">Close</a>

               <h3>Create Photo News</h3>
               <div class="form-group">
<!--                  <input type="text" name="message" id="textarea_photo" placeholder="Write your Heading here..." class="form-control"  maxlength="144" onkeyup="limitText_photo()" autocomplete="off" >
-->            
                  <input type="text" name="message" id="message" placeholder="Write your Heading here..."  class="form-control" maxlength="144" onkeyup="limitText_photo()" autocomplete="off" required>
                  
<span name="charcount_photo" id="charcount_photo" class="pull-right"></span>

               </div>
            </div>
            <div class='col-xs-6 col-md-6'>
               <p>Upload images</p>
<!--               <span class="btn btn-success fileinput-button">
-->             <!--  <span><i class="fas fa-file-upload"></i>    Select Attachment</span>-->
<!--               </span>
-->               
               <output id="Filelist"></output>
               <span class="btn btn-success fileinput-button">
               <span><i class="fas fa-file-upload"></i>Select Attachment</span>
               <input onchange="return fileValidation()" type="file" name="file[]" id="files" accept="image/*"  multiple ><br />
               </span>
               <script type="text/javascript">
                  function fileValidation() {
                  
                    var fileInput = 
                        document.getElementById('files');
                      
                    var filePath = fileInput.value;
                  
                    // Allowing file type
                    var allowedExtensions = 
                            /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                      
                    if (!allowedExtensions.exec(filePath)) {
                        STX.showMessage("Only JPEG,JPG,PNG Images are allowed!", "success"); 
                        fileInput.value = '';
                        return false;
                    } 
                    else 
                    {
                      
                        // Image preview
                        if (fileInput.files && fileInput.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById(
                                    'imagePreview').innerHTML = 
                                    '<img src="' + e.target.result
                                    + '"/>';
                            };
                              checkfileloader(fileInput.files[0]["name"],fileInput.files);
                              
                            reader.readAsDataURL(fileInput.files[0]);
                        }
                    }
                  }
               </script>
            </div>
            <div class='col-xs-6 col-md-6'>
               <div id="demo">
                  <!-- <img class="img-fluid" src="1.jpg"> -->
               </div>
            </div>
            <div class="col-xs-12 col-md-12 mt-3">
               <!--<p>Caption</p> -->
               <div class="form-group">
                  <div id="demotext">
                     <input style="display:none" onkeyup="myFunction(this.value,this.id)" type="text" name="" placeholder="Add the story behind this photo..." class="form-control appendtext" id="">
                     
                  </div>
               </div>
                <div id="existfiles"></div>
               
               <!-- <textarea style="margin-bottom:15px" class="form-control" name="messages" placeholder="Enter Story" required></textarea> -->
            </div>
            <div class='col-xs-12 col-md-12'>
               <div class="form-group float-right">
                <!--  <input type="submit" name="save" value="Save Draft" class="btn blue small" style="padding: 8px 15px;color:#0076a3; margin-bottom:10px; border: solid 1px #0076a3;background-color:white;"> -->
                  <input type="submit" name="savesubmit" value="Buzz" class="btn blue small" style="padding: 8px 15px;color:white; margin-bottom:10px;background-color:#0076a3;" >
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<style>
   .activeimagecaption {
   border : red solid;
   }
</style>
<!-- partial -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
<script type="text/javascript">
   //I added event handler for the file upload control to access the files properties.
   document.addEventListener("DOMContentLoaded", init, false);
   
   //To save an array of attachments
   var AttachmentArray = [];
   
   //counter for attachment array
   var arrCounter = 0;
   
   //to make sure the error message for number of files will be shown only one time.
   var filesCounterAlertStatus = false;
   
   //un ordered list to keep attachments thumbnails
   var ul = document.createElement("ul");
   ul.className = "thumb-Images";
   ul.id = "imgList";
   
   function init() {
   //add javascript handlers for the file upload event
   document
     .querySelector("#files")
     .addEventListener("change", handleFileSelect, false);
   }
     function checkfileloader(id,files){
       setTimeout(function(){
       document.getElementById(id).click();
        uploaddocuments(files);
       },100);
       
   }
   function uploaddocuments(files){
        

		var form_data = new FormData();  
		var len =Object.keys(files).length;
       for (var index = 0; index < len ; index++) {
      form_data.append("files[]",files[index]);
   }			
      
         $.ajax({
					type:"POST",
				 data: form_data,   
					 dataType: 'text', 
					url:"<?php  echo $C->SITE_URL;?>system/controllers/api/uploaddocuments.php",
                     contentType: false,
                      processData:false,
                        success:function(msg){
                           var value =  $("#existfiles").val();
                          var responsedata =  JSON.parse(msg);
                          if(responsedata.status == true){
                              var files = responsedata.files;
                              var filekeys =Object.keys(files);
                              for(var k=0;k<filekeys.length;k++){
                                  console.log(filekeys[k]);
                                  var originalfilename = filekeys[k];
                                  var newfilename = files[originalfilename];
                                  var input;
                                  input='<input type="hidden" name="existfiles[]" id="exist'+originalfilename+'" value="'+newfilename+'">';
                                  $("#existfiles").append(input);
                                  
                                  
                                  
                              }
                             console.log( responsedata.files);
                         //  $("#existfiles").val(val);
                          }
                           
					  
						
						
					}
				});      
       
   }
   
   
   //the handler for file upload event
   function handleFileSelect(e) {
   //to make sure the user select file/files
   if (!e.target.files) return;
   
   //To obtaine a File reference
   var files = e.target.files;
   
   // Loop through the FileList and then to render image files as thumbnails.
   for (var i = 0, f; (f = files[i]); i++) {
     //instantiate a FileReader object to read its contents into memory
     var fileReader = new FileReader();
   
   storedFiles.push(f);
   
     // Closure to capture the file information and apply validation.
     fileReader.onload = (function(readerEvt) {
       return function(e) {
         //Apply the validation rules for attachments upload
         ApplyFileValidationRules(readerEvt);
   
         //Render attachments thumbnails.
         RenderThumbnail(e, readerEvt);
   
         //Fill the array of attachment
         FillAttachmentArray(e, readerEvt);
       };
     })(f);
   
     // Read in the image file as a data URL.
     // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.
     // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme
     fileReader.readAsDataURL(f);
   }
   document
     .getElementById("files")
     .addEventListener("change", handleFileSelect, false);
   }
   
   //To remove attachment once user click on x button
   jQuery(function($) {
   $("div").on("click", ".img-wrap .close", function() {
      
     var id = $(this)
       .closest(".img-wrap")
       .find("img")
       .data("id");
   document.getElementById('demotext').innerHTML = ' ';      
   document.getElementById('closeimg'+id).innerHTML = ' ';
   
     //to remove the deleted item from array
     var elementPos = AttachmentArray.map(function(x) {
         // alert(x.FileName);
       return x.FileName;
     }).indexOf(id);
     if (elementPos !== 1) {
         // alert(elementPos); 
       AttachmentArray.splice(elementPos, 1);
       storedFiles.splice(elementPos,1);
     }
   
     //to remove image tag
     $(this)
       .parent()
       .find("img")
       .not()
       .remove();
   
     //to remove div tag that contain the image
     $(this)
       .parent()
       .find("div")
       .not()
       .remove();
   
     //to remove div tag that contain caption name
     $(this)
       .parent()
       .parent()
       .find("div")
       .not()
       .remove();
   
     //to remove li tag
     var lis = document.querySelectorAll("#imgList li");
     for (var i = 0; (li = lis[i]); i++) {
       if (li.innerHTML == "") {
         li.parentNode.removeChild(li);
       }
     }
   });
   });
   
   //Apply the validation rules for attachments upload
   function ApplyFileValidationRules(readerEvt) {
   //To check file type according to upload conditions
   // if (CheckFileType(readerEvt.type) == false) {
   //   alert(
   //     "The file (" +
   //       readerEvt.name +
   //       ") does not match the upload conditions, You can only upload jpg/png/gif files"
   //   );
   //   e.preventDefault();
   //   return;
   // }
   
   //To check file Size according to upload conditions
   // if (CheckFileSize(readerEvt.size) == false) {
   //   alert(
   //     "The file (" +
   //       readerEvt.name +
   //       ") does not match the upload conditions, The maximum file size for uploads should not exceed 300 KB"
   //   );
   //   e.preventDefault();
   //   return;
   // }
   
   //To check files count according to upload conditions
   // if (CheckFilesCount(AttachmentArray) == false) {
   //   if (!filesCounterAlertStatus) {
   //     filesCounterAlertStatus = true;
   //     alert(
   //       "You have added more than 10 files. According to upload conditions you can upload 10 files maximum"
   //     );
   //   }
   //   e.preventDefault();
   //   return;
   // }
   }
   
   //To check file type according to upload conditions
   function CheckFileType(fileType) {
   if (fileType == "image/jpeg") {
     return true;
   } else if (fileType == "image/png") {
     return true;
   } else if (fileType == "image/gif") {
     return true;
   } else {
     return false;
   }
   return true;
   }
   
   //To check file Size according to upload conditions
   function CheckFileSize(fileSize) {
   if (fileSize < 300000) {
     return true;
   } else {
     return false;
   }
   return true;
   }
   
   //To check files count according to upload conditions
   function CheckFilesCount(AttachmentArray) {
   //Since AttachmentArray.length return the next available index in the array,
   //I have used the loop to get the real length
   var len = 0;
   for (var i = 0; i < AttachmentArray.length; i++) {
     if (AttachmentArray[i] !== undefined) {
       len++;
     }
   }
   //To check the length does not exceed 10 files maximum
   if (len > 9) {
     return false;
   } else {
     return true;
   }
   }
   
   //Render attachments thumbnails.
   function RenderThumbnail(e, readerEvt) {
   var li = document.createElement("li");
   ul.appendChild(li);
   li.innerHTML = [
     '<div class="img-wrap" id="'+readerEvt.name+'" onClick="reply_click(this.id)"> <span class="close">&times;</span>' +
       '<img id="youtubeimg'+readerEvt.name+'" class="thumb" src="',
     e.target.result,
     '" title="',
     escape(readerEvt.name),
     '" data-id="',
     readerEvt.name,
     '"/><div id="ss'+readerEvt.name+'"><input id="ren'+readerEvt.name+'" type="hidden" name="caption[]" class="form-control" value="" placeholder="Add caption...">' + "</div>"
   ].join("");
   
   var div = document.createElement("div");
   div.className = "FileNameCaptionStyle";
   // li.appendChild(div);
   div.innerHTML = [readerEvt.name].join("");
   document.getElementById("Filelist").insertBefore(ul, null);
   }
   
   //Fill the array of attachment
   function FillAttachmentArray(e, readerEvt) {
   AttachmentArray[arrCounter] = {
     AttachmentType: 1,
     ObjectType: 1,
     FileName: readerEvt.name,
     FileDescription: "Attachment",
     NoteText: "",
     MimeType: readerEvt.type,
     Content: e.target.result.split("base64,")[1],
     FileSizeInBytes: readerEvt.size
   };
   arrCounter = arrCounter + 1;
   }
   function reply_click(clicked_id)
   {
     
     // var list, index;
     //     list = document.getElementsByClassName("img-wrap");
     //     for (index = 0; index < list.length; ++index) {
     //         list[index].setAttribute('border', " ");
     //     }
         
   // document.getElementById('youtubeimg'+clicked_id).style.border = "red solid";
     
     $('.thumb').click(function() {
         // alert('pppppssss');
         $('.thumb').removeClass('activeimagecaption');
         $(this).toggleClass('activeimagecaption');
     });
     
     
     
     //  var btn = document.getElementsByClassName("appendtext").setAttribute("id", "div1"); 
      
   
   
     //  btn.setAttribute("id", "div1");
      
   var dInpdddut = document.getElementById("ren"+clicked_id).value;  
   
   // alert(dInpdddut);
   
   var n1 = document.getElementById('ss'+clicked_id).innerHTML;
   // alert(n1);
   var youtubeimgsrc = document.getElementById("youtubeimg"+clicked_id).src;
      // document.getElementById("demo").innerHTML = n1;
      document.getElementById("demo").innerHTML = '<div id="closeimg'+clicked_id+'"><img class="img-fluid" src="'+youtubeimgsrc+'"></div>';
   
   
     document.getElementById("demotext").innerHTML = '<input onkeyup="myFunction(this.value,this.id)" type="text" name="" placeholder="Add the story behind this photo..." class="form-control appendtext" value="'+dInpdddut+'" id="">';
     
     // var d = document.getElementById("clicked_idagaaa");  //   Javascript
     //     d.setAttribute('data-id' , clicked_id);
     
        var list, index;
         list = document.getElementsByClassName("appendtext");
         for (index = 0; index < list.length; ++index) {
             list[index].setAttribute('id', clicked_id);
         }
      
   }
   
   function myFunction(clicked_idnn,gertid) {
      document.getElementById("ren"+gertid).value = clicked_idnn;
   
   }
</script>
<script>
   // function validateForm(e) {
   //   var input = document.getElementsByName('caption[]');
   //   for (var i = 0; i < input.length; i++) {
   //     var a = input[i].value;
   //     if (a == "") {
   //       alert("Caption must be filled out");
   //       e.preventDefault();
   //     }  
   //   }
   // }
          $(document).ready(function() { 
           $("#newmyForm").on("submit", handleFormnew);
           // $("body").on("click", ".close", removeFile);
         });
           
   
       var storedFiles = [];
       
       function handleFormnew(e) {
           // validateForm(e);
           e.preventDefault();
           var data = new FormData();
           for(var i=0, len=storedFiles.length; i<len; i++) {
               data.append('file[]', storedFiles[i]);   
           }
           
            var input = document.getElementsByName('caption[]');
             for (var i = 0; i < input.length; i++) {
               var a = input[i].value;
               if (a == "") {
                //  alert("Caption must be filled out");
               //  break;
                 e.preventDefault();
               }else{
                 data.append('caption[]', a);    
               } 
             }
           var message=document.getElementById("message").value; 
           data.append('message', message);  
           var xhr = new XMLHttpRequest();
           xhr.open('POST', '<?php echo $C->SITE_URL;?>system/controllers/api/photostory.php', true);
           
           xhr.onload = function(e) {
               if(this.status == 200) {
                   // console.log(e.currentTarget.responseText);  
                   // alert(e.currentTarget.responseText + ' items uploaded.');
                   location.reload();
               }
           }
           
           xhr.send(data);
       }
       
       // function removeFile(e) {
       //     var file = $(this).data("files");
       //     for(var i=0;i<storedFiles.length;i++) {
       //         if(storedFiles[i].name === file) {
       //             storedFiles.splice(i,1);
       //             break;
       //         }
       //     }
       //     $(this).parent().remove();
       // }
</script> 
<!-- partial -->
<script>
   $(document).ready(function(){
       
        $("#photostory-submit").on("submit", function(){
           var fileInput = document.getElementById('files');
           var filePath = fileInput.value;
           if(!filePath){
           STX.showMessage("Please Select Image !!", "success"); 
           return false;
      }
 })
       
       
       
       
       
       
       
     $("#show-videocaption").click(function(){
       $(".characters-counter").css('display','none');
       $(".status-btn").css('display','none');
        $("#post").hide();
        $("#poll").css("display","none");
       $("#videocaption").toggle();
   
        $("#pollstore").css("display","none");
   
        $("#videoupload").css("display","none");
     });
     
       $("#close-videocaption").click(function(){
       $(".characters-counter").css('display','none');
       $(".status-btn").css('display','none');
        $("#post").hide();
        $("#poll").css("display","none");
       $("#videocaption").toggle();
   
        $("#pollstore").css("display","none");
   
        $("#videoupload").css("display","none");
     });
   });
           $(".status-btn").css('display','none');
       $(".characters-counter").css('display','none');
   $(document).ready(function(){
           $(".status-btn").css('display','none');
       $(".characters-counter").css('display','none');
     $("#show-newssection").click(function(){
       //   alert('-----');
       $(".characters-counter").css('display','none');
       
       $(".status-btn").css('display','block');
       
        $("#post").toggle();
        
        $("#poll").css("display","none");
   
        $("#videocaption").css("display","none");
        $("#pollstore").css("display","none");
   
        $("#videoupload").css("display","none");
     });
     
     
      $("#show-newssection-close").click(function(){
       //   alert('-----');
       $(".characters-counter").css('display','none');
       
       $(".status-btn").css('display','block');
       
        $("#post").toggle();
        
        $("#poll").css("display","none");
   
        $("#videocaption").css("display","none");
        $("#pollstore").css("display","none");
   
        $("#videoupload").css("display","none");
        $("#show-newssection-close1").css("display","none");
     });
     
           

      $("#show-newssection-close1").click(function(){
          setTimeout(function(){
           $('#coverimage').val('');
        }, 1500);
        
       //   alert('-----');
       $(".characters-counter").css('display','none');
       
       $(".status-btn").css('display','block');
       
        $("#post").toggle();
        
        $("#poll").css("display","none");
   
        $("#videocaption").css("display","none");
        $("#pollstore").css("display","none");
   
        $("#videoupload").css("display","none");
        $("#show-newssection-close1").css('display','none');
        setTimeout(function(){
           $('#textariatitle').val('');
           $('.ql-editor').html('');
        }, 2000);
       // $("#textariatitle").val("");
       // $(".ql-editor").val("");
     });
     
   });
</script>
<!-- //////////////////////////////////////////  -->
<!-- //////////////////////////////////////////  -->
<div id="videoupload" style="display: none;">
   <!--public_html/develop/system/controllers/api/videostoryapi.php-->
   <form enctype="multipart/form-data" method="post" action="<?php echo $C->SITE_URL;?>system/controllers/api/videostory.php" id="video_submit">
      <div class='col-xs-12 col-md-12 col-md-offset-1'>
         <div class='col-xs-12 col-md-11'>
            <div class='col-xs-12 col-md-12'>
                               <a id="close-video" style="cursor:pointer" class="pull-right">Close</a>

               <h3>Create Video News</h3>

               <div class="form-group">
                  <input type="text" name="video_discription" placeholder="Write your Heading here..." class="form-control" id="textarea_video" maxlength="144" onkeyup="limitText_video()" autocomplete="off" onchange="return fileValidationvidep(this)" required="">
                  <span name="charcount_video" id="charcount_video" class="pull-right"></span>

               </div>
            </div>
            <div class='col-xs-12 col-md-12'>
               <b>Upload video thumbnail</b> 
            </div>
            <div class='col-xs-6 col-md-4' style="margin-bottom:10px;">
               <span class="btn btn-success fileinput-button">
               <span><i class="fas fa-file-upload"></i> Choose a video thumbnail</span>
               <input type='file' id="filethums" name="filethum" onchange="readURL(this);" accept="image/*" /><br />
               </span>
               <div id="demovideothumb">
               </div>
            </div>
            <div class='col-xs-6 col-md-8'>
               <div id="appdemoappendvideothumb">
                  <!-- <img class="img-fluid" src="1.jpg"> -->
               </div>
            </div>
            <div class='col-xs-12 col-md-12'>
               <b>Upload video</b> 
            </div>
            <div class='col-xs-6 col-md-4' style="margin-bottom:10px;">
               <input type="file" name="file" class="form-control btn btn-xs btn-white active fileuploader" id="videouploads" value="" accept="video/mp4,video/x-m4v,video/*" onchange="return fileValidationvidep1(this)"  style="display: none;">
               <button class="fileuploader-btn btn btn-success fileinput-button"><span><i class="fas fa-file-upload"></i>  Choose a video</span></button>
               <div id="demoappendvideo">
               </div>
            </div>
            <div class='col-xs-6 col-md-8'>
               <div id="appdemoappendvideo">
                  <!-- <img class="img-fluid" src="1.jpg"> -->
               </div>
            </div>
            <div class="col-xs-12 col-md-12">
               <b>Video Caption</b>
            </div>
            <div class="col-xs-12 col-md-12">
               <div class="form-group">
                  <div id="demotext">
                     <input onkeyup="myFunction(this.value,this.id)" type="text" name="videocaption" placeholder="Enter Video Caption..." class="form-control appendtext" id="" onchange="return fileValidationvidep(this)" required="">
                  </div>
               </div>
            </div>
            <div class='col-xs-12 col-md-12'>
               <div class="form-group float-right">
                 <!-- <input type="submit" name="save" value="Save Draft" class="btn blue small" style="padding: 8px 15px;color:#0076a3; margin-bottom:10px; border: solid 1px #0076a3;background-color:white;"> -->
                  <input type="submit" name="savesubmit" value="Buzz" class="btn blue small" style="padding: 8px 15px;color:white; margin-bottom:10px;background-color:#0076a3;">
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<script type="text/javascript">

$(document).ready(function(){
       
        $("#video_submit").on("submit", function(){
           var fileInput = document.getElementById('videouploads');
           var filethumbe = document.getElementById('filethums');
           var filfilethumbe = filethumbe.value;
           var filePath = fileInput.value;
           if(!filfilethumbe){
               
           STX.showMessage("Please Select Thumbnail !!", "success"); 
           return false;
      }
           if(!filePath){
               
           STX.showMessage("Please Select Video !!", "success"); 
           return false;
      }
 });
 });

   function fileValidationvidep1(fileInput) {
   
   var fileInput = 
       document.getElementById('videouploads');
     
   var filePath = fileInput.value;
 
   
   // Allowing file type
   var allowedExtensions = 
           /(\.mp4|\.gif)$/i;
     
   if (!allowedExtensions.exec(filePath)) {
        
       fileInput.value = '';
       STX.showMessage("Only mp4, Video are allowed! hhhhh", "success");
       return false;
   } 
   }
   
   function fileValidationvidep12(fileInput) {
   
   var fileInput = 
       document.getElementById('videouploads2');
     
   var filePath = fileInput.value;
 
   
   // Allowing file type
   var allowedExtensions = 
           /(\.mp4|\.gif)$/i;
     
   if (!allowedExtensions.exec(filePath)) {
        
       fileInput.value = '';
       STX.showMessage("Only mp4, Video are allowed! hhhhh", "success");
       return false;
   } 
   }
   
   
   function readURL(input) {
   var fileInput = document.getElementById('filethums');
     
   var filePath = fileInput.value;
   
   // Allowing file type
   var allowedExtensions = 
           /(\.jpg|\.jpeg|\.png|\.gif)$/i;
     
   if (!allowedExtensions.exec(filePath)) {
       STX.showMessage("Only JPEG,JPG,PNG Images are allowed!", "success");
       fileInput.value = '';
       return false;
   } 
   
   
   
   if (input.files && input.files[0]) {
       var reader = new FileReader();
   
       reader.onload = function (e) {
            document.getElementById("demovideothumb").innerHTML = '<img onClick="reply_clickvideothumb(this.src)" src="'+e.target.result+'" width="100%" height="115px" style="border-radius:25px;margin-top: 10px;" alt="your image" />';
       };
   
       reader.readAsDataURL(input.files[0]);
   }
   }
   
   function reply_clickvideothumb(videothumbsrc){
   document.getElementById("appdemoappendvideothumb").innerHTML = '<img src="'+videothumbsrc+'" id="blah" width="90%" height="200px" style="border-radius:25px; margin-top: 45px; margin-left: 30px;" alt="your image" />';
   }
</script>
<script type="text/javascript">
   jQuery(document).ready(function($){
   
   // Click button to activate hidden file input
   $('.fileuploader-btn').on('click', function(){
   $('.fileuploader').click();
   });
   
   // Click above calls the open dialog box
   // Once something is selected the change function will run
   $('.fileuploader').change(function(){
     
   
   // Create new FileReader as a variable
   var reader = new FileReader();
   
   // Onload Function will run after video has loaded
   reader.onload = function(file){
   var fileContent = file.target.result;
   
      document.getElementById("demoappendvideo").innerHTML = '<video onClick="reply_clickvideo(this.src)" src="' + fileContent + '" width="100%" height="115px" style="border-radius:25px;"></video>';
      
   }
   
   // Get the selected video from Dialog
   reader.readAsDataURL(this.files[0]);
   
   });
   
   });
   
   function reply_clickvideo(videosrc){
     document.getElementById("appdemoappendvideo").innerHTML = '<video onClick="reply_clickvideo(this.src)" src="' + videosrc + '" width="90%" height="250px" style="border-radius:25px; margin-top: 10px; margin-left: 28px;" controls></video>';
   }
</script>
<script>
   $(document).ready(function(){
     $("#show-video").click(function(){
       $(".characters-counter").css('display','none');
       $(".status-btn").css('display','none');
        $("#post").hide();
        $("#poll").css("display","none");
       $("#videoupload").toggle();
   
        $("#pollstore").css("display","none");
   
        $("#videocaption").css("display","none");
     });
     
     
       $("#close-video").click(function(){
       $(".characters-counter").css('display','none');
       $(".status-btn").css('display','none');
        $("#post").hide();
        $("#poll").css("display","none");
       $("#videoupload").toggle();
   
        $("#pollstore").css("display","none");
   
        $("#videocaption").css("display","none");
     });
     
     
   });
</script>   
<!-- //////////////////////////////////////////  -->
<?php $show_group = (isset($this->page->request[0]) && $this->page->request[0] == 'dashboard'); ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 attachments-options">
   <span class="uploading"><?= $this->page->lang('activity_option_upload_txt') ?></span>
</div>
<!-- START : GROUP text filed -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  attachment-group-field-container" style="display: none;">
   <input type="text" class="attachment-group-field form-control" value="" title="<?= $this->page->lang('post_form_attachment_group_help') ?>" placeholder="Start  typing to get suggestion…">
</div>
<!--/ END : GROUP text filed -->
<!-- START : Desktop Screen -->
<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 editor-footer-icons zeropadding">
    
    <ul class="list-inline zeropadding">
     
      <li id="show-newssection">
         <!--   <img src="https://i.imgur.com/fsbmetw.png" class="grayscale-mob visible-xs" title="Create News"  style="cursor: pointer"> -->
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/newspaper.svg" title="Create News" style="cursor: pointer" width="25" height="25">
        <span class="visible-xs grayscale-mob">Create News</span>
      </li>
      <!--span class="file attachment-button"><img class="grayscale-mob visible-xs" src="<?php echo $C->SITE_URL; ?>static/images/icons/FILEUPLOAD-mob.png" title="File Upload"><span class="visible-xs grayscale-mob">Upload</span><img class="grayscale hidden-xs" src="<?php echo $C->SITE_URL; ?>static/images/icons/FILEUPLOAD.png" title="File Upload"><span class="tooltip"><span><?= $this->page->lang('activity_option_upload_options') ?></span></span>
   </span -->
      <!-- //////////////////////////////////////////  -->
      <li id="show-video">
         <!--   <img src="https://www.pinclipart.com/picdir/middle/405-4054031_png-file-svg-transparent-video-camera-icon-clipart.png" class="grayscale-mob visible-xs" title="videos"  style="cursor: pointer"> -->
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/video_news.svg" title="Video News" style="cursor: pointer;margin-left:14px;" width="25" height="25">
         <span class="visible-xs grayscale-mob">Video News</span>
      </li>
      <!-- //////////////////////////////////////////  -->
      <!-- /////////////video caption///////////////////////   -->
      <li id="show-videocaption">
         <!--   <img src="https://static.thenounproject.com/png/17840-200.png" class="grayscale-mob visible-xs" title="videos"  style="cursor: pointer"> -->
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/photo_story_new.jpg" title="Photo Story" style="cursor: pointer;margin-left:8px;" width="25" height="25">
         <span class="visible-xs grayscale-mob">Photo Story</span>
      </li>
      <!-- //////////////////////////////////////////  -->
      <li id="careatepoll">
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/POLLS-mob.png" class="grayscale-mob visible-xs" title="Polls"  style="cursor: pointer; "><span class="visible-xs grayscale-mob">Polls</span>
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/POLLS.png" class="grayscale hidden-xs" title="Polls" style="cursor: pointer">
      </li>
 
   
      <li id="event">
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/EVENTS-mob.png" class="grayscale-mob visible-xs" title="Create Event" style="cursor: pointer;"><span class="visible-xs grayscale-mob">Event</span>
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/EVENTS.png" class="grayscale hidden-xs" title="Create Event"  style="cursor: pointer;margin-left:-14px;">
      </li>
      
      <!--<li id="video">-->
      <!--<img src="https://www.pinclipart.com/picdir/middle/405-4054031_png-file-svg-transparent-video-camera-icon-clipart.png" class="grayscale-mob visible-xs" title="videos"  style="cursor: pointer"><span class="visible-xs grayscale-mob">video</span>-->
      <!--<img src="https://www.pinclipart.com/picdir/middle/405-4054031_png-file-svg-transparent-video-camera-icon-clipart.png" title="Video" style="cursor: pointer" width="25" height="25">-->
      <!--</li>-->
      <!-- <li id="intraday">
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/intraday-mob.png" class="grayscale-mob visible-xs" title="Intraday" style="cursor: pointer"><span class="visible-xs grayscale-mob">Intraday</span>
         <img src="<?php echo $C->SITE_URL; ?>static/images/icons/intraday.png" class="grayscale hidden-xs" title="Intraday" style="cursor: pointer">
         </li> -->
      
      <li id="location">
         <img data-toggle="tooltip"  src="<?php echo $C->SITE_URL; ?>static/images/icons/icon-location-event-mob.png" class="grayscale-mob visible-xs"  style="cursor: pointer"><span class="visible-xs grayscale-mob">Location</span>
        <!-- <img data-toggle="tooltip"  src="<?php // echo $C->SITE_URL; ?>static/images/icons/icon-location-event.png" class="grayscale hidden-xs" style="cursor: pointer">-->
      </li>
   </ul>
</div>


<script>
    
function limitText_video() {
  var ta= document.getElementById('textarea_video'),
      count= ta.value.length,
      ml= ta.maxLength,
      remaining= ml - count,
      cc= document.getElementById('charcount_video');

  if(remaining <= 0) {
    cc.innerHTML = ml+' character limit reached.' ;
  } else if(remaining <= 144) {
    cc.innerHTML = ml+' character limit, ' + remaining  + ' remaining.';
  } else {
    cc.innerHTML = '';
  }
}



function limitText_photo() {
  var ta= document.getElementById('message'),
      count= ta.value.length,
      ml= ta.maxLength,
      remaining= ml - count,
      cc= document.getElementById('charcount_photo');

  if(remaining <= 0) {
    cc.innerHTML = ml+' character limit reached.' ;
  } else if(remaining <= 144) {
    cc.innerHTML = ml+' character limit, ' + remaining  + ' remaining.';
  } else {
    cc.innerHTML = '';
  }
}


</script>
<!--/ END : Desktop Screen -->
<style>
   .grayscale-mob {
   font-size: 8px;
   color: #000;
   }
   img.grayscale-mob {
   width: 28px;
   height: 28px;
   opacity: 0.4;  
   }
   img.grayscale-mob:hover {
   width: 28px;
   height: 28px;
   opacity: 1;
   border: 1px solid #0698d0;
   background: #fff;
   opacity: 0.7;
   padding: 2px 4px;
   border-radius: 4px;
   }
   img.grayscale{ 
   filter: grayscale(100%) contrast(1%);
   -webkit-filter: grayscale(100%) contrast(1%);  /* For Webkit browsers */
   filter: gray;  /* For IE 6 - 9 */
   -webkit-transition: all .3s ease;  /* Transition for Webkit browsers */
   }
   img.grayscale:hover{
   filter: grayscale(0%);
   -webkit-filter: grayscale(0%);
   filter: none;
   }
   
   
@media screen and (max-width: 650px) {
  #location{
      display:none;
  }
}
   
</style>
