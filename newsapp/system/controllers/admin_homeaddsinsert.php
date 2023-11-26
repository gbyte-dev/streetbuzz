<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	if(!empty($_POST)){

	}
	$locationcheck        = $db2->query('select * from sb_location_master ');
	 while($row = $db2->fetch_object($locationcheck) ){
	     $response [] = $row;
    }
    if(count($response) > 0){
        $optionhtml = '';
        foreach($response as $keys=>$vals){
            $districtvalue = $vals->location_district.','.$vals->location_state.','.$vals->location_country;
            $optionhtml .= "<option value=".$vals->id.">".$districtvalue."</option>";
        }
        
    }

  
	
	//require_once( $C->INCPATH.'helpers/func_images.php' );
	

	
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	$homeads = 0;
	if($_GET["q"] !== undefined && $_GET["q"] == "home"){
		$homeads = 1;
	}

        
        
    $datares    	.='<div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			
			
		</div>
		<div id="center-container">
			
			<h3>Customer Info</h3><form action="'.$C->SITE_URL.'/admin/homeaddsdatainsert" method="POST" enctype="multipart/form-data"><table class="form-container "><tbody>
			       <tr>
					<td class="field-title"><label for="network_name">Customer District <span style="
    color: red;
">*</span>:</label></td>
					<td><select name="district" autocomplete="off">
					  <option value="">Select</option>
					  '.$optionhtml.'
					</select></td>
				</tr>
			       <tr>
					<td class="field-title"><label for="network_name">Customer name<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="customer_name" name="customer_name"  maxlength="50" autocomplete="off"></td>
				</tr>
				 <tr>
					<td class="field-title"><label for="network_name">Sales Rep<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="salesperson" name="salesperson"   autocomplete="off"></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_title">Contact number<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="customer_number" name="customer_number"  maxlength="15"></td>
				</tr><tr>
					<td class="field-title"><label for="network_intro_txt">Contact email:</label></td>
					<td><input type="text" id="contact_email" name="contact_email"  maxlength="50" autocomplete="off"></td>
				</tr>
				<tr><td><h3>Ad Info</h3></td></tr>
				
			
				<tr style="line-height:50px;"> <td></td><td><img id="smallblah" src="#" alt="your image" width="100px"style="display:none;"  /></td></tr>
				<tr style="line-height:50px;">
					<td class="field-title"><label for="network_intro_txt">Image(700*500)/video<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="file" id="adbig" name="adbig[]" multiple></td>
				</tr>
				
					<tr style="line-height:50px;"> <td></td><td><img id="bigblah" src="#" alt="your image" width="100px"style="display:none;"  /></td></tr>
						<tr style="line-height:50px;"> <td></td><td><video id="videobigblah" src="#" alt="your image" width="100px"style="display:none;"  /></td></tr>
				    <tr   style="line-height:50px;"><td></td><td id="customadsimages"></td></tr>
						
				
					<tr style="line-height:50px;" >
					<td class="field-title"><label for="network_intro_txt">Display URL:</label></td>
					<td><input type="text" id="display_url" name="display_url"  maxlength="50" autocomplete="off"></td>
				</tr>
				
			<tr>
					<td><input type="hidden" id="homeads" name="home_ads" value="'.$homeads.'" autocomplete="off"></td>
					<td><button  class="btn blue adsv"><span>Save</span></button></td>
				</tr>
				 <tr  id="custombase" style="line-height:50px;"></tr>
				 <input type="hidden" name="custombasevalue" value="-1" id="custombasevalue">
					
				</tbody></table></form>
			
		</div>
	</div>
	</div>';
  $newscontent =''.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>';

$newscontent .='<script type="text/javascript">
 jQuery("#customer_number").on("input", function (event) { 
    var len         =jQuery(this).length;
    this.value = this.value.replace(/[^0-9"]/g, "");
if(len == 1 && (this.value ==0 || this.value ==1 || this.value ==2 || this.value ==3 || this.value ==4 || this.value == 5 )){
 jQuery(this).val("");
}
    
});
jQuery(".removehomeimage").live("click", function() { 
 if($(this).attr("data-id") !== undefined){
   var dataid = $(this).attr("data-id");
   $(".mainimage"+dataid).remove();
   $(".maincustombase"+dataid).remove();
   var custombasevalue = $("#custombasevalue").val();
   if(val > 0 ){
       var newvalue = parseInt(custombasevalue)-parseInt(1);
   }else{
       var newvalue = 0;
   }
   $("#custombasevalue").val(newvalue);
     
 }
});
$(".adsv").click(function(){
var custname = $("#customer_name").val();
if(custname == ""){
$("#customer_name").focus();
    return false;
}
var salesperson = $("#salesperson").val();
if(salesperson == ""){
$("#salesperson").focus();
    return false;
    
}
var customer_number = $("#customer_number").val();
if(customer_number == ""){
$("#customer_number").focus();
    return false;
}


});
/*$("#adsmall").change(function() {
  readURL(this);
  $("#smallblah").css("display","block");
}); */
$("#adbig").change(function() {
  readURL1(this);
  //$("#bigblah").css("display","block");
});
function removehomeimage(){
    
}
function readURL1(input) {
 var finalfiles = input.files;
 var finalstr =[];
 
if(finalfiles.length > 0){
    for(var k=0;k<finalfiles.length;k++){
  if (input.files && input.files[k]) {
    var reader = new FileReader();
    
    var extension =finalfiles[k]["name"].replace(/^.*\./, "");
   
    reader.onload = function(e) {
     var str = e.target.result;
      var newvalue =$(".custombase").length;
      $("#custombasevalue").val(newvalue);
      var str = e.target.result;
      var textbox = document.createElement("input");
      textbox.type= "hidden";
      textbox.name= "baseval"+newvalue;
      textbox.setAttribute("class", "custombase maincustombase"+newvalue);

      textbox.value=str;
      document.getElementById("custombase").appendChild(textbox);

        var newSpan = document.createElement("span");
        newSpan.setAttribute("class", "mainimage"+newvalue);

         document.getElementById("customadsimages").appendChild(newSpan);
         var a = document.createElement("a");
         a.setAttribute("class", "removehomeimage");
          a.setAttribute("data-id", newvalue);
          a.setAttribute("onClick", "removehomeimage()");
       var spanchild = document.createElement("span");
       spanchild.setAttribute("class", "glyphicon glyphicon-trash color-red removehomeimage");

        a.appendChild(spanchild); 
if(extension=="mp4"){
var image = document.createElement("video");
image.setAttribute("controls","true");
image.setAttribute("height","150px");
image.setAttribute("width","150px");
image.setAttribute("class", "video customphoto1");
image.setAttribute("autoplay", "true");
}else{
   var image = document.createElement("IMG"); 
   image.setAttribute("class", "photo customphoto");
   
}
image.alt = "Alt information for image";

image.src= str;
//document.getElementById("customadsimages").appendChild(image);
newSpan.appendChild(image);
//newSpan.appendChild(a);

      

    }
    
    reader.readAsDataURL(input.files[k]);
  }
    }
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
$("#addnews").click(function(){
	var url = "'.$C->SITE_URL.'/addinputs"; 
			jQuery.ajax({
				method: "POST",
				url: url
				}).done(function (response) {
			$(".addrow").append(response);
			
			});
});


</script>
<style>
.customphoto{
   width: 200px;
    height: 150px; 
}
.color-red{
    color:red;
    
}
</style>

';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>
