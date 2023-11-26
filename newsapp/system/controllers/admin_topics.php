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
	
	     $check        = $db2->query('select sbt.id,sbt.topic_literal,sbt.topic_description,sbt.topic_gallery,sblm.location from sb_topics as sbt
	     left join sb_location_master as sblm ON sbt.topic_attach_location=sblm.id
	     order by sbt.id desc limit 20 ');
	    
    // $checkres              =$db2->fetch_object($check);
    $datares='<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Sno</th>
      <th scope="col">Post ID</th>
      <th scope="col">Headline</th>
       <th scope="col">Preview</th>
      <th scope="col">Description</th>
      <th scope="col">Location</th>
       <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>';
   $key =1;
    while($result    = $db2->fetch_object($check)){ 
        $topicid =$result->id;
        $headline =$result->topic_literal;
        $description =$result->topic_description;
        $gallery         =$result->topic_gallery;
        $location         =$result->location;
        if($gallery !=''){
         $img ='<img width="50px" src="'.$C->STORAGE_URL.'topics/'.$gallery.'" alt="No image">';   
        }else{
            $img ='No Image';
        }
        
      $url =$C->SITE_URL.'admin/edittopics?postid='.$topicid;
    $datares    	.=' <tr>
      <th scope="row">'.$key++.'</th>
      <th scope="row">'.$topicid.'</th>
      <td>'.$headline.'</td>
      <td>'.$img.'</td>
      <td>'.$description.'</td>
      <td>'.$location.'</td>
    <td><a href="'.$url.'" style="border:1px solid orange;padding: 5px;
    border-radius: 14px;
    background-color: orange;
    color: white;">Edit</a></td>
    </tr>';
        
}
 $datares    	.='  </tbody>
</table>';

     
  
    
	$newscontent ='<div class="container-fluid"><b>Topics </b><span style="float:right;"><a href="'.$C->SITE_URL.'admin/searchtopics" style="border:1px solid orange;padding: 5px;
    border-radius: 14px;
    background-color: orange;
    color: white;">Find Topics</a></span>
    <span style="float:right;"><a href="'.$C->SITE_URL.'admin/addtopics" style="border:1px solid orange;padding: 5px;
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