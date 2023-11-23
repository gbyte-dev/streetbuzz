<?php
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');

	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();

   $categeory_masterquery        = $db2->query('select * from categeory_master ');
   $categeory_masterhtml ='';
   if($categeory_masterquery->field_count > 0){
       while($categeory_row = $db2->fetch_object($categeory_masterquery) ){
          $categeory_masterhtml .="<option value=".$categeory_row->cat_id.">".$categeory_row->cat_name."</option>";
           
       }
   }
    $location_masterquery        = $db2->query('select * from  sb_location_master ');
   $location_masterhtml ='';
   if($location_masterquery->field_count > 0){
       while($location_row = $db2->fetch_object($location_masterquery) ){
          $location_masterhtml .="<option value=".$location_row->id.">".$location_row->location."</option>";
           
       }
   }
    $language_masterquery        = $db2->query('select * from  sb_languages ');
   $language_masterhtml ='';
   if($language_masterquery->field_count > 0){
       while($language_row = $db2->fetch_object($language_masterquery) ){
          $language_masterhtml .="<option value=".$language_row->id.">".$language_row->language_name."</option>";
           
       }
   }
    if($_SESSION['ads_status'] == 1){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admbrnd_frm_ok'), $this->lang('admbrnd_frm_ok_txt') ) );
	  }
       unset($_SESSION['ads_status']);


    $datares    	.='<div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			
			
		</div>
		<form action="'.$C->SITE_URL.'/admin/topicajax" method="POST" enctype="multipart/form-data" id="topicsform">
		<div id="center-container">
		       <h3>Manage Topic Defination</h3><table class="form-container "><tbody>
			    <tr>
					<td class="field-title"><label for="network_name">Topic Literal<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="customer_name" class="errormes" name="topic_name"  maxlength="50" autocomplete="off" value=""></td>
				</tr>
					<tr>
					<td class="field-title"><label for="network_name">Topic Description<span style="
    color: red;
">*</span>:</label></td>
				
            <td><textarea type="text"  id="description" class="errormes" style="width:500px;height:100px" name="description"></textarea></td>
        </tr>
			<tr>
					<td class="field-title"><label for="network_name">Upload Image/Video<span style="
    color: red;
">*</span>:</label></td>
					<td><label for="files" class="custom-file-upload">
  Browse File
</label><input type="file" id="files" name="files"  class="errormes"  > <span class="txt-class">Image Size 1200 * 1200 < 5MB</span></td>		</tr>
			
				<tr>
					<td class="field-title"><label for="network_name">Tags<span style="
    color: red;
">*</span>:</label></td>
				
            <td><textarea type="text"  id="hashtags"  class="errormes"  style="width:500px;height:100px" name="tags"></textarea></td>
        </tr>
        	<tr>
					<td class="field-title"><label for="network_intro_txt">Category:</label></td>
					<td><select name="tpic_category"><option value="">Select Category</option>
				    '.$categeory_masterhtml.'
					</select></td>
				</tr>
					
					<tr>
					<td class="field-title"><label for="network_intro_txt">Attach Location:</label></td>
					<td><select name="topic_location"><option value="">Select Location</option>
				   '.$location_masterhtml.'
					</select></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_txt">Attach Language:</label></td>
					<td><select name="topic_language"><option value="">Select Language</option>
				   '.$language_masterhtml.'
					</select></t
					d>
				</tr>
         <tr>
					<td class="field-title"><label for="network_intro_title">Valid From<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="start_date"  class="errormes"  name="start_date"  maxlength="15" value=""> </td>
				</tr><tr>
					<td class="field-title"><label for="network_intro_txt">Valid Till<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="end_date"  class="errormes"  name="end_date"  maxlength="50" autocomplete="off" value=""></td>
				</tr>
			
				
				<input type="hidden" id="selectuser_ids" />

			<tr>
					<td></td>
					
					<td><button  class="btn blue adsv"><span>Save</span></button></td>
				</tr></tbody></table>

		</div>
	</div></form>
	</div>';
  $newscontent =''.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery-ui.js?v=3.6.0"></script>
';

$newscontent .='
<style>
.errorcls{
    border-color:red !important;
}
input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    border-color:#0084B4;
    color:#0084B4;
}
.txt-class{
    margin-left:10px;
}
htmlarea-ac-container-replay
/* Hashtag */
.hashtag-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.hashtag-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.hashtag-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.hashtag-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -30px; padding:3px;}
.hashtag-dropdown ul li.hover {background:#0076a3; color: #fff;}
.hashtag-dropdown ul li.selection {color: #6E6E6E;}
/* Usertype */
.usertype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.usertype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -54px; padding:1px;}
.usertype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.usertype-dropdown ul li.selection {color: #6E6E6E;}
.usertype-dropdown ul li.selection:hover {color: #fff;}
/* Usertype */
.grptype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.grptype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: 0px; padding:1px;}
.grptype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.grptype-dropdown ul li.selection {color: #6E6E6E;}
.grptype-dropdown ul li.selection:hover {color: #fff;}


.pac-container {
    /* put Google geocomplete list on top of Bootstrap modal */
    z-index: 9999;
}
/* Hashtag */
.htmlarea-ac-container-replay
 { margin-top: 0px; width:100%; z-index:50; display:none; background:#fff;}
.htmlarea-ac-container-replay
 {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.htmlarea-ac-container-replay
 ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.htmlarea-ac-container-replay
 ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -30px; padding:3px;}
.htmlarea-ac-container-replay
 ul li.hover {background:#0076a3; color: #fff;}
.htmlarea-ac-container-replay
 ul li.selection {color: #6E6E6E;}
</style>
<script type="text/javascript">
$( "#start_date" ).datepicker();
$( "#end_date" ).datepicker();


$( function() {
  
       	var url = "'.$C->SITE_URL.'topichashtags"; 

        $( "#hashtags" ).autocomplete({
            source: function( request, response ) {
                
                var searchText = extractLast(request.term);
                $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    data: {
                        search: searchText
                       
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function( event, ui ) {
                var terms = split( $("#hashtags").val() );
                
                terms.pop();
                
                terms.push( ui.item.label );
                
                terms.push( "" );
                $("#hashtags").val(terms.join( "," ));

                // Id
                var terms = split( $("#selectuser_ids").val() );
                
                terms.pop();
                
                terms.push( ui.item.value );
                
                terms.push( "" );
                $("#selectuser_ids").val(terms.join( ", " ));

                return false;
            }
           
        });
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 jQuery("#customer_number").on("input", function (event) { 
    var len         =jQuery(this).length;
    this.value = this.value.replace(/[^0-9"]/g, "");
if(len == 1 && (this.value ==0 || this.value ==1 || this.value ==2 || this.value ==3 || this.value ==4 || this.value == 5 )){
 jQuery(this).val("");
}
    
});
$(".errormes").keyup(function(){
   $(this).removeClass("errorcls"); 
});
$(".adsv").click(function(event){
event.preventDefault();
var errorlist = [];
$(".errormes").each(function(){
if($(this).val() == "" ){
$(this).addClass("errorcls");
errorlist.push("1");
    
}
});
if(errorlist.length == 0){
 $("#topicsform").submit();
 /*var file_data = $("#files").prop("files")[0];   
    var form_data = new FormData();                  
    form_data.append("filsewee", file_data);
    
	var url = "'.$C->SITE_URL.'/admin/topicajax"; 
			jQuery.ajax({
				type:"POST",
				data:{data:form_data},
				cache:false,
				dataType: "json",
				 processData: false,
               contentType: false,
               url: url
				}).done(function (response) {
          				if(response.status == 200){
          				
				    
				}
	            });   */
}


});
$("#adsmall").change(function() {
  readURL(this);
  $("#smallblah").css("display","block");
});
$("#adbig").change(function() {
  readURL1(this);
  $("#bigblah").css("display","block");
});
function readURL1(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $("#bigblah").attr("src", e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $("#smallblah").attr("src", e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}



</script>';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>