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
	
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	  if($_SESSION['ads_status'] == 1){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admbrnd_frm_ok'), $this->lang('admbrnd_frm_ok_txt') ) );
	  }
	  $locationcheck        = $db2->query('select * from sb_location_master ');
	 while($row = $db2->fetch_object($locationcheck) ){
	     $locationresponse [] = $row;
    }
     $districtselected = '';
     $dropdownoptions ='';
     if(count($locationresponse) > 0){
        foreach($locationresponse as $keys=>$vals){
            $districtvalue = $vals->location_district;
            if($_POST['district'] != '' ){
                 if($_POST['district'] == $vals->id){
                     $districtselected = 'selected';
                 }else{
                    $districtselected =''; 
                 }
            }else{
                $districtselected ='';
            }
            $dropdownoptions .= "<option value=".$vals->id." ".$districtselected.">".$districtvalue."</option>";
        }
        
    }
       unset($_SESSION['ads_status']);
        $activeselected ='selected';
        $inactiveselected =''; 
        $mainstartdate = '';
        $mainenddate = '';
        $customer_district = '';
        $customername = '';
        $salessearch_person = '';
        $joincondition = '';
        $handle = '';
        $selectuser_ids ="";
        
       if(!empty($_POST['submit'])){
           $status = $_POST['status'];
           	$wherecondition = 'status ='.$status;
           	if($status == 1){
           	   $activeselected ='selected'; 
           	    $inactiveselected ='';
           	    $newselected = '';
           	}
           	if($status == 2){
           	   $inactiveselected ='selected'; 
           	   $activeselected ='';
           	   $newselected ='';
           	}
           	if($status == 3){
           	   $newselected ='selected'; 
           	   $activeselected ='';
           	    $inactiveselected = '';
           	}
           	if($_POST['start_date'] != '' && $_POST['end_date']  ){
           	    $mainstartdate = $_POST['start_date'];
           	     $mainenddate = $_POST['end_date'];
           	    $start_date=date_create($_POST['start_date'] );
                $start_date = date_format($start_date,"Y-m-d");
                $start_date    = strtotime($start_date.' 01:59:59');
                $end_date   = date_create($_POST['end_date'] );
                $end_date = date_format($end_date,"Y-m-d");
                $end_date    = strtotime($end_date.' 23:59:59');
                $condition = ' AND start_date > "'.$start_date.'" AND end_date < "'.$end_date.'" ';
                $wherecondition = $wherecondition.$condition;
           	}
           	if($_POST['district'] != '' ){
           	    $customer_district = $_POST['district'];
           	     $condition = ' AND customer_district = "'.$customer_district.'" ';
           	     $wherecondition = $wherecondition.$condition;
           	}
           	if($_POST['customername'] != '' ){
           	    $customername = $_POST['customername'];
           	     $condition = ' AND customer_name = "'.$customername.'" ';
           	     $wherecondition = $wherecondition.$condition;
           	}
           	if($_POST['salesperson'] != '' ){
           	    $salessearch_person = $_POST['salesperson'];
           	     $condition = ' AND sales_person = "'.$salessearch_person.'" ';
           	     $wherecondition = $wherecondition.$condition;
           	}
           	if($_POST['handle'] != '' ){
           	    $handle = $_POST['handle'];
           	    $selectuser_ids = rtrim($_POST['selectuser_ids'], ", ");
           	     $joincondition = 'INNER JOIN  `ads_tags` as adt ON adt.ad_id=ad.id ';
           	     $condition = ' AND adt.user_id IN('.$selectuser_ids.') ';
           	     $wherecondition = $wherecondition.$condition;
           	}

       }else{
        	$wherecondition = 'status = 3';
        	 $newselected ='selected'; 
           	 $activeselected ='';
           	 $inactiveselected = '';

       }
      
	     $check        = $db2->query('select ad.* from ads_info as ad '.$joincondition.'  WHERE '.$wherecondition.' order by ad.id desc ');

	  if (isset($_GET['action'])){
	   
        $id=$_GET['id'];
       $db2->query('DELETE FROM `ads_info` where id="'.$id.'"');
       
       $this->redirect('admin/addsdisplay');
  }
	    
    // $checkres              =$db2->fetch_object($check);
    //$datares='<div class="row" style="border-top:1px solid black"><div class="col-md-1">Sl. No</div><div class="col-md-1">Image</div><div class="col-md-2">Customer name</div><div class="col-md-2">District  customer</div><div class="col-md-2">Ad Position</div><div class="col-md-2">Published Users</div><div class="col-md-2">Action</div></div>';
    $datares='<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <table class="table table-hover" id="example">
  <thead>
    <tr>
      <th scope="col">Sr.No.</th>
      <th scope="col">Image</th>
       <th scope="col">Customer name</th>
       <th scope="col">District  customer</th>
       <th scope="col">Ad Position</th>
       <th scope="col">Published Users</th>
       <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>';
   $key =1;
    while($result    = $db2->fetch_object($check)){ 
        $customer_name =$result->customer_name;
        $contact_number =$result->contact_number;
        $created_date =$result->created_date;
        $adid=$result->id;
        $small_image=$result->big_image;
        $sales_person         =$result->sales_person;
        if($result->customer_district != ''){
        $locationcheck        = $db2->query('select * from sb_location_master where id= '.$result->customer_district.' LIMIT 1 ');
        $locationrow = $db2->fetch_object($locationcheck);
         $locationname = $locationrow->location_district;
        }else{
            $locationname = '';
        }
        if(!empty($result->start_date)){
           
	    $epoch = $result->start_date;
       $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
       $start_date =  $dt->format('M d,y'); // output = 2017-01-01 00:00:00
	}else{
	     $start_date ='';
	    
	}
		if(!empty($result->end_date)){
      $epoch1 = $result->end_date;
       $dt1 = new DateTime("@$epoch1");  // convert UNIX timestamp to PHP DateTime
       $end_date =  $dt1->format('M d,y'); // output = 2017-01-01 00:00:00
	}else{
	     $end_date ='';
	    
	}
	$ads_type         =$result->ads_type;
	$adstypedata = '';
	if($ads_type == "1"){
	    $adstypedata = "Below News Media";
	    
	}
	if($ads_type == "2"){
	    $adstypedata = "Below Headline";
	    
	}
	if($ads_type == "3"){
	    $adstypedata = "Paragraph1";
	    
	}
	if($ads_type == "4"){
	    $adstypedata = "Paragraph2";
	    
	}
	if($ads_type == "5"){
	    $adstypedata = "Paragraph3";
	    
	}
	if($ads_type == "6"){
	    $adstypedata = "Paragraph4";
	    
	}
	if($ads_type == "7"){
	    $adstypedata = "Paragraph5";
	    
	}
if($result->ad_display_type =="image"){
	     $imagedisplayselected ='selected';  
	   }else{
	     $imagedisplayselected ='';  
	   } 
	   if($result->ad_display_type =="video"){
	     $videodisplayselected ='selected';  
	   }else{
	     $videodisplayselected ='';  
	   } 


	$tagcheck        = $db2->query('select * from ads_tags where ad_id="'.$result->id.'" ');
		if($tagcheck->num_rows > 0){
		    $response = array();
		    
    	   while($row = $db2->fetch_object($tagcheck) ){
    	      
    	      $r      = $db2->query('select username  FROM  users  WHERE id="' . $row->user_id . '"  LIMIT 1', FALSE);
            $userresult = $db2->fetch_object($r);
            $response[] = '@'.$userresult->username;
        }
        if(!empty($response)){
          $tags = implode(",",$response);
           
        }else{
           $tags ='';
           
        }
	}else{
	    $tags ='';
	}
	
	$ads_access_source         =$result->ads_access_source;
	$ads_access_sourcedata = '';
	if($ads_access_source == "1"){
	   $ads_access_sourcedata ='Whatsup'; 
	}
	if($ads_access_source == "2"){
	   $ads_access_sourcedata ='Call Now'; 
	}
	if($ads_access_source == "3"){
	   $ads_access_sourcedata ='Know More'; 
	}
	$adscnt = $db2->query('select count(*) as cnt  from ads_links where ad_id="'.$result->id.'" ');
	if($tagcheck->num_rows > 0){
    	$adscntcheck              =$db2->fetch_object($adscnt);
    	$adscntcheck = ((($adscntcheck->cnt)*3)+rand(1,9));
	}else{
	    $adscntcheck = (0+rand(1,9));
	    
	}
	$viewcnt ='';

	if($result->start_date != '' && $result->end_date != ''){
	    	$views = $db2->query('select p.id,p.user_id,p.date,SUM(pv.cnt) as cnt from ads_info as ad inner join ads_tags as at on at.ad_id= ad.id 
inner join posts as p ON p.user_id = at.user_id
inner join  post_views_list as pv ON p.id = pv.post_id

where ad.id="'.$result->id.'" AND p.date >= '.$result->start_date.' AND p.date <= '.$result->end_date.'  order by p.id desc');


if($views->num_rows > 0){
    $viewscheck              =$db2->fetch_object($views);
    if($viewscheck->cnt != ''){
        $viewcnt =  $viewscheck->cnt ;
    }

    
}
	    
	}
      
        
        
    /*$datares    	.='<div class="row" style="border-top:1px solid black">
  <div class="col-md-1"><p>'.$key++.'</p></div><div class="col-md-1"><p><img width="50px" src="'.$C->STORAGE_URL.'advs/'.$small_image.'" alt="No image"></p></div><div class="col-md-2"><p>'.$customer_name.'</p></div><div class="col-md-2"><p>'.$locationname.'</p></div><div class="col-md-2"><p>'.$adstypedata.'</p></div><div class="col-md-2"><p class="wordwrap">'.$tags.'</p></div><div class="col-md-2"><span><a href="'.$C->SITE_URL.'admin/addsedit?id='.$adid.'" class="edit" style="background-color:orange;color:white;border:1px solid orange;padding:4px;">Edit</a></span></div></div>';*/
   $datares    	.='<tr>
        <td>'.$key++.'</td>';
        if($videodisplayselected != ""){
        $datares.='<td><video width="50px" src="'.$C->STORAGE_URL.'advs/'.$small_image.'" alt="No image"></td>';
        }else{
            $datares.='<td><img width="50px" src="'.$C->STORAGE_URL.'advs/'.$small_image.'" alt="No image"></td>';
        }
        
        $datares.='<td>'.$customer_name.'</td>
        <td>'.$locationname.'</td>
        <td>'.$adstypedata.'</td>
        <td>'.$tags.'</td>
        <td><a href="'.$C->SITE_URL.'admin/addsedit?id='.$adid.'" class="edit" style="background-color:orange;color:white;border:1px solid orange;padding:4px;">Edit</a><br><a style="background-color:red;color:white;border:1px solid orange;padding:4px;"  onclick="delete_a('.$adid.')"> Delete</a></td>
        </tr>';
        
}

     
  
    
	$newscontent ='<div class="container-fluid"><b>Advt.Mgmt.Dashboard </b><span style="float:right;"><a href="'.$C->SITE_URL.'/admin/addsinsert" style="border:1px solid orange;padding: 5px;
    border-radius: 14px;
    background-color: orange;
    color: white;">Add</a></span></div>
   <div class="row">
   <form action="" method="POST">
  <div class="col-xs-6 col-md-2"><select name="status" style="width: 100%;"><option value="3" '.$newselected.' >New</option><option value="1" '.$activeselected.' >Active</option><option value="2" '.$inactiveselected.'>InActive</option></select></div>
   <div class="col-xs-6 col-md-2"><select name="district" style="width: 100%;">
   <option value="">Select District</option>
   '.$dropdownoptions.'
   </select></div>
    <div class="col-xs-6 col-md-2"><input name="customername" placeholder="Customer Name" value="'.$customername.'" style="width: 100%;"></div>
    
       <div class="col-xs-6 col-md-3"><input id ="multi_autocomplete" name="handle" placeholder=" Handle" value="'.$handle.'" style="width: 100%;"></div>
        
  <div class="col-xs-6 col-md-2"><input type="text"  autocomplete="off" id="from_date" name="start_date"  maxlength="15" value="'.$mainstartdate.'" placeholder="From Date"></div>
  <div class="col-xs-6 col-md-3"><input type="text" autofocus id="to_date" placeholder="To Date"  name="end_date" value="'.$mainenddate.'" maxlength="50" autocomplete="off"></div>
   <div class="col-xs-6 col-md-2"><input name="salesperson" placeholder="Sales Rep" value="'.$salessearch_person.'" autocomplete="off" style="width:100%"></div>
  <div class="col-xs-6 col-md-2"><input type="submit" name="submit"  value="Search" style="width:100%"/></div>
  <div class="col-xs-6 col-md-2"><input type="submit"name="clear" value="Clear" /></div>
  	<input type="hidden" id="selectuser_ids" name="selectuser_ids" value="'. $selectuser_ids.'" />
  </form>
</div>
'.$datares.'
</tbody>
</table>';

$newscontent .='<script type="text/javascript">
    $( "#from_date" ).datepicker(); $( "#to_date" ).datepicker();

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
 


</script><style>.wordwrap{
    word-wrap:break-word;
}</style>
';


		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

var table = $('#example').DataTable({
select: false,
"columnDefs": [{
className: "Name",
"targets": [0],
"visible": false,
"searchable": false
}]
}); //End of create main table


$('#example tbody').on('click', 'tr', function() {

// alert(table.row( this ).data()[0]);

});
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" ></script>

<script>
function delete_a(id){
   
   
Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.isConfirmed) {
     
   window.location.href = 'admin/addsdisplay?id='+ id +'&action=delete';
  }
})
	}

</script>