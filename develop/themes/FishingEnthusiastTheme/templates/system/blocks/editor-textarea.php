<div id="post" style="display:none;">
    <div>
<!--<div class="off"><a href="#" class="toggler off">&nbsp;</a></div>-->
<a id="show-newssection-close" style="cursor:pointer" class="pull-right">Close</a>

<h1>Write a News</h1>


<br>
<p>Title</p>
<input type="text" id="textariatitle" name="textariatitle" class="form-control" placeholder="Enter your text here"  maxlength="144" onkeyup="limitText()" autocomplete="off" required>
    
<input type="hidden" name="coverimage" id="coverimage" value="<?php echo $C->SITE_URL;?>static/images/logo.png">

<!--<span id="char_left" class="pull-right"></span>
-->
<span name="charcount_text" id="charcount_text" class="pull-right"></span>
<br>
<div class="user-status-field htmlarea">
    
  <div class="textarea-wrap">
  <!--<textarea id="test1"></textarea>-->

  <!--  <textarea class="editpost" name="message" tabindex="1"  placeholder="<?= $this->page->lang('activity_text_box_shate_txt') ?> {%in_group%}...">000000</textarea>-->



<style type="text/css">
  #editor-container {
  height: 300px;
}
.activity-container .activity-content img{
  width: 100%;  
}
</style>

<link rel='stylesheet' href='<?php echo $C->SITE_URL;?>static/css/quill_snow.css'>

 <textarea name="content" id="content" style="display:none;"></textarea>
 
<!-- partial:index.partial.html -->
<div id="editor-container"></div>


<!--<input type="file" id="filethum_longread" name="filethum_longread" class="form-control btn btn-xs btn-info active"  style="margin-left:0px;margin-top:10px;">
-->

<!--input type="file" id="filethum'.$x.'" name="filethum" class="form-control btn btn-xs btn-info active"  onchange="return validateInputfile(this,this.id)" style="margin-left:0px;margin-top:10px;" -->

<!-- partial -->
<script src='<?php echo $C->SITE_URL;?>static/js/quillmin.js'></script>
<!--<script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>-->

 <script>

// var IMGUR_API_URL = 'upload.php';

// function imageHandler(image, callback) {
//   var data = new FormData();
//   data.append('image', image);
  
  
//   var xhr = new XMLHttpRequest();
//   xhr.open('POST', IMGUR_API_URL, true);
  
//   xhr.onreadystatechange = function() {
      
//     if (xhr.readyState === 4) {

//       callback(xhr.responseText);

//     }
//   }
//   xhr.send(data);

// }

// var quill = new Quill('#editor-container', {
//   modules: {
//     toolbar: [
//       [{ header: [1, 2, false] }],
//       ['bold', 'italic', 'underline'],
//       ['image', 'code-block']
//     ]
//   },
//   placeholder: 'Insert an image...',
//   theme: 'snow',
//   imageHandler: imageHandler
// });

// quill.on("text-change", function() {
//   var editor_content = quill.container.firstChild.innerHTML ;
// //   alert(editor_content);
//   $("#content").val(editor_content);
// });
 </script>


    
    <div class="textarea-highlighter"><span></span></div>
  </div>
</div>


<!-- <div class="pull-right">
<label>Cover Image</label><br>
<span class="file attachment-button" style="margin-left:0px;" ><img class="grayscale-mob visible-xs" src="<?php // echo $C->SITE_URL; ?>static/images/icons/FILEUPLOAD-mob.png" title="File Upload"><span class="visible-xs grayscale-mob">Upload</span><img class="grayscale hidden-xs" src="<?php // echo $C->SITE_URL; ?>static/images/icons/FILEUPLOAD.png" title="File Upload"><span class="tooltip"><span><?php // echo $this->page->lang('activity_option_upload_options') ?></span></span>
   </span>
   </div>  -->
<br>
<div>
        
    </div>

  


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
        
     
    </script> -->

<!-- START : Group Name -->
<div class="col-md-12 col-lg-12 group-attchment" style="display:none;">
<a><span class="txt-group">Buzzing in group <span class="group-att box-sub-desc" style="font-weight: bold; font-size: 11px;"></span>

<span class="glyphicon glyphicon-remove deletegrpforhome" style="cursor: pointer; color: #B6B6B4; font-size: 10px;" title="Delete Group"></span></span></a>
</div>
<!--/ END : Group Name -->
<!--
  <div class="st_text htmlarea" id="st_text">
    <textarea style="height: 100px;" id="myArea1"></textarea>
  </div> -->
  <input type="hidden" value="off" id="switch">
  </div>
  
  </div>
  
  
  
  <div id="poll" style="display:none;">
  </div>
    <div id="pollstore" style="display:none;">
  </div>
  <div class="htmlarea-ac">
  <div class="htmlarea-ac-container"></div>
  
</div>

<input type="hidden" id="asset_val" value="1"></input>
<input type="hidden" id="ticker1">
<div id="slide"></div>
<input type="hidden" id="imagecnt" value="" />

<input type="text" style="display:none"  id="googlecity" class="geo"/>
<input type="text" style="display:none"  id="pageurlpage" />




  

<script src="<?php echo $C->SITE_URL;?>static/js/textareaeditor.js?v=3.6.0"></script>


<!--script>
  $(document).ready(function () {
            $(".longread_thumbnail").click(function () {
                alert("are you want to select cover image!");
            });
        });
        
</script-->

<script type="text/javascript">


function limitText() {
  var ta= document.getElementById('textariatitle'),
      count= ta.value.length,
      ml= ta.maxLength,
      remaining= ml - count,
      cc= document.getElementById('charcount_text');

  if(remaining <= 0) {
    cc.innerHTML = ml+' character limit reached.' ;
  } else if(remaining <= 144) {
    cc.innerHTML = ml+' character limit, ' + remaining  + ' remaining.';
  } else {
    cc.innerHTML = '';
  }
}


    $(document).ready(function(){
      
/*      $("#textariatitle").on("keyup change", function(e) {

var data = $("#textariatitle").val().length;
var left_data = 144- data ;


 $("#char_left").html(left_data);
 
 if(left_data=="0"){
     alert('done limit');
 }

          
          
      });*/
      
      
      
      $(".st_text").hide();
      $(".user-status-field").show();
      var area1, area2;
 
  function toggleArea1() {
        if(!area1) {
                area1 = new nicEditor({fullPanel : true}).panelInstance('myArea1',{hasPanel : true});
        } else {
                area1.removeInstance('myArea1');
                area1 = null;
        }
  }
 
  function addArea2() {
        area2 = new nicEditor({fullPanel : true}).panelInstance('myArea2');
  }
  function removeArea2() {
        area2.removeInstance('myArea2');
  }
 
  bkLib.onDomLoaded(function() { toggleArea1(); });
      
    });
    
        //var a  =$(".nicEdit-main").html();

$('a.toggler').click(function(){
        $(this).toggleClass('off');
    var dfggfg  = $("#switch").val();
    if(dfggfg =='off'){
      var switch1 ="ON";
      $("#characterff").css("display","none");
      $("#character").css("display","block");
      $(".st_text").show();
      $(".user-status-field").hide();
      
    }else{
      var switch1 ="off";
      $("#characterff").css("display","block");
      $("#character").css("display","none");
      $(".st_text").hide();
      $(".user-status-field").show();
      
    }
    $("#switch").val(switch1);
    
    });
    </script>
    
  <script type="text/javascript">
  
  
   

  
 
  </script>
<script type="text/javascript">
$(document).ready(function(){
  $(".accept").click(function(){
    var accept =$(this).val();
    var acceptarr =accept.split('-');
    var attach    ="attach-"+acceptarr[0];
    var attchmentid  =$("#"+attach).val();
    if(acceptarr[1] ==1){
    var str ="Are you sure want to accept the event !";
    }
    if(acceptarr[1] ==3){
      var str ="Are you sure want to   Reject the event !";
      
    }
    var r = confirm(str);

    if(r == true){
      $.ajax({
     
      method:"POST",
      data:{postid:acceptarr[0],status:acceptarr[1],attachid:attchmentid},
       url:"<?php echo $C->SITE_URL;?>/dashboard",

      success:function(response){
        $("#acc-"+acceptarr[0]).hide();
        if(acceptarr[1] == 1){
          $("#accept-"+acceptarr[0]).css("display","block");
          
        }
        if(acceptarr[1] == 3){
          $("#reject-"+acceptarr[0]).css("display","block");
          
        }
        
      }
      
    }); 
    }   
    
  });
  $(".download").click(function(){
    var pid = $(this).attr('rel');
     $.ajax({
     
      method:"POST",
      data:{pid:pid},
       url:"<?php echo $C->SITE_URL;?>/dashboard",

      success:function(response){
        
        
      }
      
    }); 
  });
});

</script>

<script type="text/javascript">
function changeurl(a,b)
{
  //alert(b);
  $('#optionerror'+b).hide();
  var id=$(".option"+a).attr("id");
  var iurl=$("#suboption"+id).attr("href");
  $("#suboption"+a).attr( 'href', '<?php echo $C->SITE_URL;?>plugin/poll/admin?action=answer&poll_id='+a+'&answerid='+b+'');   
  // alert(iurl);
  // alert(id);
}
</script>
<script>
function myFunction(val) {
  
  var str =""+val+"";
  var leng = str.length;
  var correctlen  = parseInt(leng)-parseInt(1);
    var postid = str.substring(0, correctlen);
    var status = str.charAt(correctlen);
  var attach    ="attach-"+postid;
  var attchmentid  =$("#"+attach).val();
  if(status ==1){
    var str ="Are you sure want to accept the event !";
  }
  if(status ==3){
    var str ="Are you sure want to   Reject the event !";
    
  }
  var r = confirm(str);
  if(r == true){
      $.ajax({
     
      method:"POST",
      data:{postid:postid,status:status,attachid:attchmentid},
       url:"<?php echo $C->SITE_URL;?>/dashboard",

      success:function(response){
        $("#acc-"+postid).hide();
        if(status == 1){
          $("#accept-"+postid).css("display","block");
          
        }
        if(status == 3){
          $("#reject-"+postid).css("display","block");
          
        }
        
      }
      
    }); 
    }   
  

  
}
</script>


    
    
