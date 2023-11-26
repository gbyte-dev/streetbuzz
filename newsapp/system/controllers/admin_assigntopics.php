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
       
       $cur_date = date('Y-m-d');
       $weekdate = date('Y-m-d', strtotime('-7 days'));


       $start_date = strtotime($weekdate.'00:00:01');;
       $end_date = strtotime($cur_date.'23:59:59');;
       $post        = $db2->query(" select id,message,topic_id from posts where date between $start_date AND $end_date order by id desc ");
        if( !empty($_POST['topic_status']) && empty($_POST['post_id'])){
           if($_POST['topic_status'] == "assigned"){
              $post        = $db2->query(" select id,message,topic_id from posts where topic_id is not null order by id desc limit 100"); 
           }else{
                           $post        = $db2->query(" select id,message,topic_id from posts where (topic_id is null or topic_id= 0)  order by id desc LIMIT 100 "); 
  
           }
       }
       if( empty($_POST['topic_status']) && !empty($_POST['post_id'])){
           $postid = $_POST['post_id'];
           
              $post        = $db2->query(" select id,message,topic_id from posts where id= $postid  order by id desc "); 
        }
        if( !empty($_POST['topic_status']) && !empty($_POST['post_id'])){
           $postid = $_POST['post_id'];
           
              $post        = $db2->query(" select id,message,topic_id from posts where id= $postid  order by id desc "); 
        }
      
      $check        = $db2->query(" select sbt.id,sbt.topic_literal,sbt.topic_description,sbt.topic_gallery from sb_topics as sbt
	     where sbt.valid_till >= $start_date
	     order by sbt.id desc");
	     
	     $optionshtml = '';
	    
	     	    while($topics_results    = $db2->fetch_object($check)){ 
	         $optionshtml .="<option value=".$topics_results->id.">".$topics_results->topic_literal."</option>";
	          $optionsres[] = $topics_results;
	         
}
  $topic_location = $_POST['topic_location'];
  $post_id = $_POST['post_id'];
  $post_language = $_POST['post_language'];
  if($_POST['topic_status'] == "assign"){
      $assignstatus = 'selected';
  }else{
       $assignstatus = '';
  }
  if($_POST['topic_status'] == "assigned"){
      $assignedstatus = 'selected';
  }else{
       $assignedstatus = '';
  }
    $datares='<div> <h4>Select Buzzes to  Assign / Re-assign Topics </h4> </div><div id="successmessage"> Successfully Updated</div> <div id="failmessage"> Failure to  Update</div>
    <form method="POST" action="'.$C->SITE_URL.'/admin/assigntopics" >
    <div class="row">
  <div class="col-xs-6 col-md-3"><select name="topic_status" ><option value="">Select Topic Status</option><option '.$assignstatus.' value="assign">Assign</option><option '.$assignedstatus.' value="assigned">Assigned</option></select></div>
  <div class="col-xs-6 col-md-3"><input type="text" value ="'.$topic_location.'" name="topic_location" placeholder="Location" /></div>
  <div class="col-xs-6 col-md-3"><input type="text" value ="'.$post_id.'" name="post_id" placeholder="PostId" /></div>
  <div class="col-xs-6 col-md-3"><input type="text" value ="'.$post_language.'" name="post_language"placeholder="Post Language"  /></div>
</div>
 <div class="row">
  <div class="col-xs-6 col-md-3"></div>
  <div class="col-xs-6 col-md-3"></div>
  <div class="col-xs-6 col-md-3"></div>
  <div class="col-xs-6 col-md-3"><input type="submit" value="Find"  class="btnfind"  /></div>
</div></form>
    <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Post ID</th>
      <th scope="col">Headline</th>
       <th scope="col">Topic</th>
       <th scope="col"></th>
       <th scope="col"></th>
    </tr>
  </thead>
  <tbody>';
   $key =1;
   if($post->num_rows > 0){
    while($result    = $db2->fetch_object($post)){ 
        $postid =$result->id;
        $headline =$result->message;
        //print_r($post->topic_id);exit;
        if($result->topic_id != ''){
          $optionshtml = '';
          foreach($optionsres as $keys=>$vals){
            if($vals->id == $result->topic_id ){
                $optionshtml .="<option selected value=".$vals->id.">".$vals->topic_literal."</option>";
            }else{
                $optionshtml .="<option  value=".$vals->id.">".$vals->topic_literal."</option>";
            }
          }
        }
        $datares    	.=' <tr>
      <th scope="row">'.$postid.'</th>
      <td>'.$headline.'</td>
      
     
      <td><select class="topicval-'.$postid.' "><option value="">Select Topic</option>'.$optionshtml.'</select></td>
    <td><button  class="savebtn" rel="'.$postid.'"  >Save</button></td>
    <td ><span class="mes message-'.$postid.'">Updated Successfully</span></td>
     <td ><span class="errormessage erromessage-'.$postid.'">Failure to Update</span></td>
    </tr>';
        
}
}else{
    $datares    	.=' <tr> <th scope="row">No Topics Found </th></tr>';
}
 $datares    	.='  </tbody>
</table>';

     
  
    
	$newscontent ='<div class="container-fluid"><b>Assign Topics </b></div>
'.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>';

$newscontent .='<script type="text/javascript">
 $("#successmessage").hide(); $("#failmessage").hide();
 $(".mes").hide();
 $(".errormessage").hide();
$(".savebtn").click(function(){
    var postId = $(this).attr("rel");
    var topicId = $(".topicval-"+postId).val();
    var url = "'.$C->SITE_URL.'/admin/assign_topic_ajax";
    	$.ajax({
			
			type:"POST",
			method:"text/html",
			url:url,
			data:{postId:postId,topicId:topicId},
			cache:false,
			success:function(response){
			   if(response == 200){
			   $(".message-"+postId).show();
			   $(".erromessage-"+postId).hide();
		          //$("#successmessage").show(); 
		         // $("#failmessage").hide();
		        }
		        if(response == 401){
		           $(".message-"+postId).hide();
			   $(".erromessage-"+postId).show();
		        // $("#failmessage").show(); 
		         //$("#successmessage").hide(); 
		        }
		        setTimeout(function(){ $("#failmessage").hide(); $("#successmessage").hide();  $(".mes").hide(); $(".errormessage").hide(); }, 3000);

				
			
			}
			
			
		});
    
});

</script><style>
#successmessage,.mes{
    color:green;
}
#failmessage,.errormessage{
    color:red;
}
.btnfind{
   border:1px solid #22abdd !important;
background-color: #22abdd !important;
color:white;
border-radius:20px;
width:120px;
}
.savebtn{
border:1px solid #22abdd !important;
background-color: white !important;
color:#22abdd;
border-radius:3px;
    }
    .success{
        color:green;
    }
    .fail{
        color:red;
    }
    </style>';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>