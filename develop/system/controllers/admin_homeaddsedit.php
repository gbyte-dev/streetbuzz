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
	$montharr = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
	$month = date('m');
	if($month[0] == 0){
	    $month = $month[1];
	    
	}
	//$month = $month-1;
	$monthsoption ="";
	foreach($montharr as $monthkeys=>$monthvals){
	    if($monthkeys <= $month ){
	    $monthsoption .= "<option value=".$monthkeys.">".$monthvals."</option>";
	    }
	    
	}



	//require_once( $C->INCPATH.'helpers/func_images.php' );
	

	
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	$id    = $_GET['id'];
	$locationcheck        = $db2->query('select * from sb_location_master ');
	 while($row = $db2->fetch_object($locationcheck) ){
	     $locationresponse [] = $row;
    }

	$adscnt = $db2->query('select count(*) as cnt  from ads_links where ad_id="'.$id.'" ');
	$adscntcheck              =$db2->fetch_object($adscnt);


	$check        = $db2->query('select * from ads_info where id="'.$id.'" ');
	$checkres              =$db2->fetch_object($check);
	$tagcheck        = $db2->query('select * from ads_tags where ad_id="'.$id.'" ');
	$homeadsquery        = $db2->query('select * from home_ads_images where ad_id="'.$id.'" ');
	 while($row = $db2->fetch_object($tagcheck) ){
	      $r      = $db2->query('select username  FROM  users  WHERE id="' . $row->user_id . '"  LIMIT 1', FALSE);
        $result = $db2->fetch_object($r);
        $response[] = '@'.$result->username;
    }
   if(!empty($response)){
      $tags = implode(",",$response);
       
   }else{
       $tags ='';
       
   }
   $adslocationcheck        = $db2->query('select location_id from ads_home_locations
 where ad_id="'.$id.'" ');
	 while($row = $db2->fetch_object($adslocationcheck) ){
	     if(isset($row->location_id)){
	     $adslocationresponse [] = $row->location_id;
	     }
    }
    if(!empty($adslocationresponse)){
       $adslocationresponse =(array)$adslocationresponse; 
    }else{
        $adslocationresponse = [];
    }
	if(!empty($checkres->start_date)){
	    $epoch = $checkres->start_date;
       $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
       $start_date =  $dt->format('M d,y'); // output = 2017-01-01 00:00:00
	}else{
	     $start_date ='';
	    
	}
	if(!empty($checkres->end_date)){
      $epoch1 = $checkres->end_date;
       $dt1 = new DateTime("@$epoch1");  // convert UNIX timestamp to PHP DateTime
       $end_date =  $dt1->format('M d,y'); // output = 2017-01-01 00:00:00
	}else{
	     $end_date ='';
	    
	}
	 if($checkres->ad_display_type =="image"){
	     $imagedisplayselected ='selected';  
	   }else{
	     $imagedisplayselected ='';  
	   } 
	   if($checkres->ad_display_type =="video"){
	     $videodisplayselected ='selected';  
	   }else{
	     $videodisplayselected ='';  
	   } 

	if(!empty($checkres->status)){
	   if($checkres->status == 1){
	     $activeselected ='selected';  
	   }else{
	        $activeselected ='';  
	   } 
	   if($checkres->status == 2){
	     $inactiveselected ='selected';  
	   }else{
	     $inactiveselected ='';  
	   } 
	}else{
	    $activeselected ='';
	    $inactiveselected ='';  
	    
	}
		if(!empty($checkres->ads_type)){
	   if($checkres->ads_type == 1){
	     $commercialactiveselected ='selected';  
	   }else{
	        $commercialactiveselected ='';  
	   } 
	   if($checkres->ads_type == 2){
	     $officialactiveselected ='selected';  
	   }else{
	     $officialactiveselected ='';  
	   } 
	    if($checkres->ads_type == 3){
	     $para1activeselected ='selected';  
	   }else{
	     $para1activeselected ='';  
	   } 
	    if($checkres->ads_type == 4){
	     $para2activeselected ='selected';  
	   }else{
	     $para2activeselected ='';  
	   } 
	    if($checkres->ads_type == 5){
	     $para3activeselected ='selected';  
	   }else{
	     $para3activeselected ='';  
	   } 
	   if($checkres->ads_access_source == 1){
	       $whatsupselected ='selected';
	   }else{
	       $whatsupselected = '';
	   }
	   if($checkres->ads_access_source == 2){
	       $callnowselected ='selected';
	   }else{
	       $callnowselected = '';
	   }
	    if($checkres->ads_access_source == 3){
	       $knowmoreselected ='selected';
	   }else{
	       $knowmoreselected = '';
	   }
	   if($checkres->ads_type == 6){
	     $para4activeselected ='selected';  
	   }else{
	     $para4activeselected ='';  
	   } 
	   if($checkres->ads_type == 7){
	     $para5activeselected ='selected';  
	   }else{
	     $para5activeselected ='';  
	   } 
	   
	}else{
	    
	    $commercialactiveselected ='';
	    $officialactiveselected ='';
	    $para1activeselected = '';
	    $para2activeselected = '';
	    $para3activeselected = '';
	    $knowmoreselected = '';
	    $whatsupselected = '';
	     $callnowselected = '';
	     $para4activeselected ='';
	     $para5activeselected ='';
	    
	}

	$whatsapp_number = $checkres->whatsapp_number;
    $callnow_number = $checkres->callnow_number;
    $customer_district = $checkres->customer_district;
    $is_priority = $checkres->is_priority;
    $dropdownoptions = '';
    $districtselected = '';
    $publishedlocations ='';
    $is_priorityselectedyes = "";
     $is_priorityselectedno = "";
     if($is_priority == "yes"){
        $is_priorityselectedyes = "selected"; 
     }
      if($is_priority == "no"){
        $is_priorityselectedno = "selected"; 
     }
     if(count($locationresponse) > 0){
        foreach($locationresponse as $keys=>$vals){
            $districtvalue = $vals->location_district.','.$vals->location_state.','.$vals->location_country;
             if($customer_district == $vals->id){
                $districtselected = 'selected';
            }else{
                $districtselected = '';
            }
            $dropdownoptions .= "<option value=".$vals->id." ".$districtselected.">".$districtvalue."</option>";
             $selectedval = "";
            if(in_array($vals->id,$adslocationresponse)){
               $selectedval = "selected";  
            }
            $publishedlocations .= "<option value=".$vals->id." ".$selectedval." >".$districtvalue."</option>";
        }
        
    }
    $viewcnt ='';

	if($checkres->start_date != '' && $checkres->end_date != ''){
	    	$views = $db2->query('select p.id,p.user_id,p.date,SUM(pv.cnt) as cnt from ads_info as ad inner join ads_tags as at on at.ad_id= ad.id 
inner join posts as p ON p.user_id = at.user_id
inner join  post_views_list as pv ON p.id = pv.post_id

where ad.id="'.$checkres->id.'" AND p.date >= '.$checkres->start_date.' AND p.date <= '.$checkres->end_date.'  order by p.id desc');


if($views->num_rows > 0){
    $viewscheck              =$db2->fetch_object($views);
    if($viewscheck->cnt != ''){
        $viewcnt =  $viewscheck->cnt ;
    }

    
}
	    
	}
	if(file_exists($C->STORAGE_DIR.'advs/'.$checkres->big_image)){
	   $adsexist = 1; 
	}else{
	   $adsexist = 0;  
	}
	$homeads = "";
	if($imagedisplayselected != ""){
	 while($homenewsads = $db2->fetch_object($homeadsquery) ){
	    $homeads .='<img id="bigblah'.$homenewsads->id.'" style="padding-right:10px" src="'.$C->STORAGE_URL.'advs/'.$homenewsads->ads_image.'" alt="your image" width="300px" height="50px" /><a id="remove'.$homenewsads->id.'" rel-image ="'.$homenewsads->ads_image.'" rel-id= "'.$homenewsads->id.'" class="removeadd" onclick="removeadd("'.$homenewsads->ads_image.'","'.$homenewsads->id.'")"><span class="glyphicon glyphicon-trash color-red" style="color:red"></span></a>';
	 }
	}

	$fileexts = '<tr style="line-height:50px;"  > <td></td><td id="customadsimages">'.$homeads.'</td></tr>
					';
	if($videodisplayselected != ""){
	    while($homenewsads = $db2->fetch_object($homeadsquery) ){
	        //print_r($homenewsads);
	        
	    	$fileexts .= '<tr style="line-height:50px;" class="imgdesc" id="videobigblah1'.$homenewsads->id.'"> <td></td><td><video id="videobigblah'.$homenewsads->id.'" src="'.$C->STORAGE_URL.'advs/'.$homenewsads->ads_image.'" alt="your image" width="350px"   controls  autoplay /></td></tr>
					<tr class="imgdesc" >
					<td class="field-title">Remove Current</td>
					<td><a class="removeadd" rel-image ="'.$homenewsads->ads_image.'" rel-id= "'.$homenewsads->id.'" onclick="removeadd("'.$homenewsads->ads_image.'","'.$homenewsads->id.'")">remove current <span class="glyphicon glyphicon-trash" style="color:red"></span></a></td>
				</tr>';
	    }
	   
	    
	}
	
				

    
    $datares    	.='<div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			
			
		</div>
		<div id="center-container">
			
			<h3>Customer Info</h3><form action="'.$C->SITE_URL.'/admin/homeaddseditinsert" method="POST" enctype="multipart/form-data"><table class="form-container "><tbody>
			     <tr>
					<td class="field-title"><label for="network_name">Customer District<span style="
    color: red;
">*</span>:</label></td>
				  	<td><select name="district" autocomplete="off">
				  	 <option value="" >--</option>
					 "'.$dropdownoptions.'"
					</select></td>
				</tr>
			      <tr>
					<td class="field-title"><label for="network_name">Customer name<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="customer_name" name="customer_name"  maxlength="50" autocomplete="off" value="'.$checkres->customer_name.'"></td>
				</tr>
				 <tr>
					<td class="field-title"><label for="network_name">Sales Rep<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="salesperson" name="salesperson"  value="'.$checkres->sales_person.'" autocomplete="off"></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_title">Contact number<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="customer_number" name="customer_number"  maxlength="15" value="'.$checkres->contact_number.'"> </td>
				</tr><tr>
					<td class="field-title"><label for="network_intro_txt">Contact email:</label></td>
					<td><input type="text" id="contact_email" name="contact_email"  maxlength="50" autocomplete="off" value="'.$checkres->contact_email.'"></td>
				</tr>
				<tr><td><h4>Advt. Creative</h4></td></tr>
			
				<tr style="line-height:50px;">
					<td class="field-title"><label for="network_intro_txt">Image(700*500)<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="file" id="adbig" name="adbig[]" multiple></td>
				</tr>
				'.$fileexts.'
				
				
					
				<tr><td><h3>Ad Details</h3></td></tr>
			

                      <tr>
					<td class="field-title"><label for="network_name">Published Locations<span style="
    color: red;
">*</span>:</label></td>
				  	<td><select name="publishedlocations[]" id="publishedlocations" autocomplete="off" multiple>
				  	 <option value="" >--</option>
					 "'.$publishedlocations.'"
					</select></td>
				</tr>
					<tr>
					<td class="field-title"><label for="network_intro_txt">Ad Display Type<span style="
    color: red;
">*</span>:</label></td>
					<td><select name="ad_display_type"><option value="" >Select Ads Display Type</option><option value="image" '.$imagedisplayselected.' >Image</option><option value="video" '.$videodisplayselected.'>video</option></select></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_txt">Ad Status<span style="
    color: red;
">*</span>:</label></td>
					<td><select name="adsstatus"><option value="" >Select Status</option><option value="1" '.$activeselected.' >Active</option><option value="2" '.$inactiveselected.'>In Active</option></select></td>
				</tr>
					<tr>
					<td class="field-title"><label for="network_intro_txt">Is Priority<span style="
    color: red;
">*</span>:</label></td>
					<td><select name="is_priority"><option value="" >Select Status</option><option value="yes" '.$is_priorityselectedyes.' >Yes</option><option value="no" '.$is_priorityselectedno.'>No</option></select></td>
				</tr>
				
				 <tr  id="custombase" style="line-height:50px;"></tr>
				 <input type="hidden" name="custombasevalue" value="-1" id="custombasevalue">
				
				
				<input type="hidden" id="selectuser_ids" />
				<input type="hidden" id="adid" value="'.$id.'"  name="adid" />
			
			
				
			<tr>
					<td></td>
					
					<td><button  class="btn blue adsv"><span>Save</span></button></td>
				
				</tr></tbody></table></form>
			
				
			
		</div>
	</div>
	</div>';
  $newscontent =''.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>
';

$newscontent .='
<style>
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
var adsexist = "'.$adsexist.'";
if(adsexist == 0){
    $(".imgdesc").hide();
}

var sourcetype = "'.$checkres->ads_access_source.'";
if(sourcetype == 1){
      $(".whatsapp").css("display","block");
       $(".callnow").css("display","none");
   }
   if(sourcetype == 2){
       $(".whatsapp").css("display","none");
       $(".callnow").css("display","block");
   }
   if(sourcetype == 3){
       $(".whatsapp").css("display","none");
       $(".callnow").css("display","none");
   }
    if(sourcetype == ""){
       $(".whatsapp").css("display","none");
       $(".callnow").css("display","none");
   }
   
$( function() {
  
       	var url = "'.$C->SITE_URL.'/adstagsusers"; 

        $( "#multi_autocomplete" ).autocomplete({
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
                var terms = split( $("#multi_autocomplete").val() );
                
                terms.pop();
                
                terms.push( ui.item.label );
                
                terms.push( "" );
                $("#multi_autocomplete").val(terms.join( "," ));

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
$(".adsv").click(function(){
var custname = $("#customer_name").val();
if(custname == ""){
$("#customer_name").focus();
    return false;
}
var customer_number = $("#customer_number").val();
if(customer_number == ""){
$("#customer_number").focus();
    return false;
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
$(".removeadd").click(function(){
var imagename =$(this).attr("rel-image");
var adid = $(this).attr("rel-id");
var url = "'.$C->SITE_URL.'/admin/homeaddseditinsert"; 
			jQuery.ajax({
				method: "POST",
				 type:"POST",
				url: url,
				data:{imagename:imagename,adsid:adid,action:"delete"}
				}).done(function (response) {
			    if(response == 1){
			    $("#bigblah"+adid).remove();
			     $("#remove"+adid).remove();
			     $("#videobigblah1"+adid).remove();
			   

			    }

			});


});
$("#ads_access_source").change(function(){
   var value = $(this).val();
   if(value == 1){
      $(".whatsapp").css("display","block");
       $(".callnow").css("display","none");
   }
   if(value == 2){
       $(".whatsapp").css("display","none");
       $(".callnow").css("display","block");
   }
   if(value == 3){
       $(".whatsapp").css("display","none");
       $(".callnow").css("display","none");
   }
});
$("#adsmall").change(function() {
  readURL(this);
  $("#smallblah").css("display","block");
});
$("#adbig").change(function() {
  readURL1(this);
 // $(".imgdesc").show();
  //$("#bigblah").css("display","block");
});
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
   image.setAttribute("height","150px");
image.setAttribute("width","150px");
}
image.alt = "Alt information for image";

image.src= str;
//document.getElementById("customadsimages").appendChild(image);
newSpan.appendChild(image);
newSpan.appendChild(a);

      

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

.color-red{
    color:red;
    
}
</style>';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>
