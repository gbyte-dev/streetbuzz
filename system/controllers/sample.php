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
	
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'scs') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	  if($_SESSION['ads_status'] == 1){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admbrnd_frm_ok'), $this->lang('admbrnd_frm_ok_txt') ) );
	  }
       unset($_SESSION['ads_status']);
	
	     $check        = $db2->query('select * from ads_info order by id desc ');
	    
    // $checkres              =$db2->fetch_object($check);
    $datares='<div class="row" style="border-top:1px solid black"><div class="col-md-1">Sno</div><div class="col-md-3">Ad Image</div><div class="col-md-3">Customername</div><div class="col-md-3">Created Date</div><div class="col-md-2">Action</div></div>';
   $key =1;
    while($result    = $db2->fetch_object($check)){ 
        $customer_name =$result->customer_name;
        $contact_number =$result->contact_number;
        $created_date =$result->created_date;
        $adid         =$result->id;
        $small_image         =$result->sort_image;
      
        
        
    $datares    	.='<div class="row" style="border-top:1px solid black">
  <div class="col-md-1">'.$key++.'</div><div class="col-md-3"><img width="50px" src="'.$C->STORAGE_URL.'advs/'.$small_image.'" alt="No image"></div><div class="col-md-3">'.$customer_name.'('.$contact_number.')</div><div class="col-md-3">'.$created_date.'</div><div class="col-md-2"><a href="'.$C->SITE_URL.'admin/addsedit?id='.$adid.'" class="edit" style="background-color:orange;color:white;border:1px solid orange;padding:4px;">Edit</a></div></div>';
        
}

     
  
    
	$newscontent ='<div class="container-fluid"><b>Adds Management </b><span style="float:right;"><a href="'.$C->SITE_URL.'/admin/addsinsert" style="border:1px solid orange;padding: 5px;
    border-radius: 14px;
    background-color: orange;
    color: white;">Add</a></span></div>
'.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>';

$newscontent .='<script type="text/javascript">
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