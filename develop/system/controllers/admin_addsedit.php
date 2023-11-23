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
   $present_startdate = "";
	if(!empty($checkres->start_date)){
	    $epoch = $checkres->start_date;
       $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
       $start_date =  $dt->format('M d,y'); // output = 2017-01-01 00:00:00
       $present_startdate =  $dt->format('m');
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
    $dropdownoptions = '';
    $districtselected = '';
     if(count($locationresponse) > 0){
        foreach($locationresponse as $keys=>$vals){
            $districtvalue = $vals->location_district.','.$vals->location_state.','.$vals->location_country;
             if($customer_district == $vals->id){
                $districtselected = 'selected';
            }else{
                $districtselected = '';
            }
            $dropdownoptions .= "<option value=".$vals->id." ".$districtselected.">".$districtvalue."</option>";
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
        if($viewcnt > 0){
            $viewcnt = ($viewcnt)*103;
        }else{
            $viewcnt = 103;
        }
    }

    
}
	    
	}
	if(file_exists($C->STORAGE_DIR.'advs/'.$checkres->big_image)){
	   $adsexist = 1; 
	}else{
	   $adsexist = 0;  
	}
	$montharr = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
	$month = date('m');

	if($month[0] == 0){
	    $month = $month[1];
	    
	}
	$startmonth = -1;
	if($present_startdate != ""){
	    if($present_startdate[0] == 0){
	    $startmonth = $present_startdate[1];
	    
	}
	    
	    
	}
	//$month = $month-1;
	$monthsoption ="";
	foreach($montharr as $monthkeys=>$monthvals){
	    if($monthkeys >= $startmonth && $monthkeys <= $month ){
	    $monthsoption .= "<option value=".$monthkeys.">".$monthvals."</option>";
	    }
	    
	}

	$fileexts = '<tr style="line-height:50px;" class="imgdesc" > <td></td><td><img id="bigblah" src="'.$C->STORAGE_URL.'advs/'.$checkres->big_image.'" alt="your image" width="100px" style=" ';
	if($checkres->big_image==""){
	   $fileexts .='display:none;'; 
	}
	$fileexts .='"/></td></tr>
					<tr class="imgdesc"  style=" ';
					if($checkres->big_image==""){
	   $fileexts .='display:none;'; 
	}
					$fileexts .='">
					<td class="field-title">Remove Current</td>
					<td><a class="removeadd" onclick="removeadd("'.$checkres->big_image.'","'.$id.'")">remove current <span class="glyphicon glyphicon-trash" style="color:red"></span></a></td>
				</tr>';
    	if($videodisplayselected != ""){
	    	$fileexts = '<tr style="line-height:50px;" class="imgdesc" > <td></td><td><video id="videobigblah" src="'.$C->STORAGE_URL.'advs/'.$checkres->big_image.'" alt="your image" width="350px"   controls  autoplay style=" ';
	    	
	    	if($checkres->big_image==""){
	   $fileexts .='display:none;'; 
	}
	$fileexts .='"/></td></tr>
					<tr class="imgdesc" style=" ';
					if($checkres->big_image==""){
	   $fileexts .='display:none;'; 
	}
					$fileexts .='">
					<td class="field-title">Remove Current</td>
					<td><a class="removeadd" onclick="removeadd("'.$checkres->big_image.'","'.$id.'")">remove current <span class="glyphicon glyphicon-trash" style="color:red"></span></a></td>
				</tr>';
	    
	}
	
				

    
    $datares    	.='<div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			
			
		</div>
		<div id="center-container">
			
			<h3>Customer Info</h3><form action="'.$C->SITE_URL.'/admin/addseditinsert" method="POST" enctype="multipart/form-data"><table class="form-container "><tbody>
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
					<td class="field-title"><label for="network_intro_txt">Image/Video<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="file" id="adbig" name="adbig"></td>
				</tr>
				'.$fileexts.'
				
				
					
				<tr><td><h3>Ad Details</h3></td></tr>
				<tr>
					<td class="field-title"><label for="network_name">Published Users<span style="
    color: red;
">*</span>:</label></td>
				
            <td><textarea type="text"  id="multi_autocomplete" style="width:500px;height:100px" name="tags">'.$tags.'</textarea></td>
        </tr>

         <tr>
					<td class="field-title"><label for="network_intro_title">Start date<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="start_date" name="start_date"  maxlength="15" value="'.$start_date.'"> </td>
				</tr><tr>
					<td class="field-title"><label for="network_intro_txt">End date<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="end_date" name="end_date"  maxlength="50" autocomplete="off" value="'.$end_date.'"></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_txt">Ad Status<span style="
    color: red;
">*</span>:</label></td>
					<td><select name="adsstatus"><option value="" >Select Status</option><option value="1" '.$activeselected.' >Active</option><option value="2" '.$inactiveselected.'>In Active</option></select></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_txt">Ad Action<span style="
    color: red;
">*</span>:</label></td>
					<td><select id="ads_access_source" name="ads_access_source"><option value="" >Select Source</option><option value="1" '.$whatsupselected.' >Whatsup</option><option value="2" '.$callnowselected.'>Call Now</option><option value="3" '.$knowmoreselected.'>Know More</option></select></td>
				</tr>
				<tr style="line-height:50px;">
					<td class="field-title"><label for="network_intro_txt">Display URL:</label></td>
					<td><input type="text" id="display_url" name="display_url"  maxlength="50" autocomplete="off" value="'.$checkres->display_url.'"></td>
				</tr>
				<tr >
					<td class="field-title"><label for="network_intro_txt">WhatsApp<span style="
    color: red;
">*</span>:</label></td>
					<td><input  type="text"  name="whatsapp_number"  autocomplete="off" maxlength="20" value="'.$whatsapp_number.'"></td>
				</tr>
					<tr style="line-height:50px;" >
					<td class="field-title"><label for="network_intro_txt">Call Now:</label></td>
					<td><input type="text" id="callnow_number" name="callnow_number"  maxlength="50" autocomplete="off" value="'.$callnow_number.'"></td>
				</tr>
				<tr>
					<td class="field-title"><label for="network_intro_txt">Ad Display Type<span style="
    color: red;
">*</span>:</label></td>
					<td><select name="ad_display_type"><option value="" >Select Ads Display Type</option><option value="image" '.$imagedisplayselected.' >Image</option><option value="video" '.$videodisplayselected.'>video</option></select></td>
				</tr>
				
					<tr>
					<td class="field-title"><label for="network_intro_txt">Ad Type<span style="
    color: red;
">*</span>:</label></td>
					<td><select name="adstype"><option value="">Select Ad Type</option><option value="1" '.$commercialactiveselected.'>Classified</option><option value="2" '.$officialactiveselected.' >Banner Advt</option>
					  <option value="3" '.$para1activeselected.' >Paragraph1</option>
					   <option value="4" '.$para2activeselected.' >Paragraph2</option>
					    <option value="5" '.$para3activeselected.' >Paragraph3</option>
					    <option value="6" '.$para4activeselected.' >Paragraph4</option>
					     <option value="7" '.$para5activeselected.' >Paragraph5</option>
					</select></td>
				</tr>
				<input type="hidden" id="selectuser_ids" />
				<input type="hidden" id="adid" value="'.$id.'"  name="adid" />
				<tr>
				<td>No of Clicks:</td><td>'.(( $adscntcheck->cnt > 0 ? ($adscntcheck->cnt)*103 :0)).'</td>
				</tr>
				<tr>
				<td>No of Views:</td><td>'.$viewcnt.'</td>
				</tr>
				
			<tr>
					<td></td>
					
					<td><button  class="btn blue adsv" style="float: right;"><span>Save</span></button></td>
				
				</tr></tbody></table></form>
				<br>
				<br>
				<form class="downloadform" action="'.$C->SITE_URL.'api/newsperson/mpdf" method="POST">
				<input type="hidden"  value="'.$id.'"  name="adid" />
				<td><select name="month_report">'.$monthsoption.'</select></td>
				<td><button  class="btn blue"><span>Download</span></button></td>
				</form>
				
			
		</div>
	</div>
	</div>';
  $newscontent =''.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>
';

$newscontent .='
<style>
.downloadform{
    margin-top:5px;
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
$(".removeadd").click(function(){

var imagename = "'.$checkres->big_image.'";
var adid = "'.$id.'";
var url = "'.$C->SITE_URL.'/admin/addseditinsert"; 
			Swal.fire({
  title: "Are you sure?",
  text: "You was not be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText:  "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
    jQuery.ajax({
				method: "POST",
				 type:"POST",
				url: url,
				data:{imagename:imagename,adid:adid,action:"delete"}
				}).done(function (response) {
			   // if(response == 1){
			    $("#bigblah").attr("src", " ");
			    $(".imgdesc").hide();

			   // }

			});
  }
})


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
  $(".imgdesc").show();
  $("#bigblah").css("display","block");
});
function readURL1(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    $(".imgdesc").css("display","revert");
    reader.onload = function(e) {
        var ext = input.files[0]["type"];
       if(ext.indexOf("mp4") > -1){
       $("#bigblah").css("display","none");
        $("#videobigblah").css("display","block");
         $("#videobigblah").attr("src", e.target.result);

           
       }else{

      $("#bigblah").attr("src", e.target.result);
       }
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
$("#addnews").click(function(){
	var url = "'.$C->SITE_URL.'/addinputs"; 
			jQuery.ajax({
				method: "POST",
				url: url
				}).done(function (response) {
			$(".addrow").append(response);
			
			});
});


</script>';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" ></script>
