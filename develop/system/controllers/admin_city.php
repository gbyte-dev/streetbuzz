<?php

  global $db2,$C;


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
  if(isset($_POST['savesubmit'])){

    $city=$_POST['city']; 
    $state_id=$_POST['state_id'];
    
if($_FILES['files']['name']){
			$temp= explode('.',$_FILES['files']['name']);
			$extension = end($temp);
			$orignalName = $_FILES['files']['name'];
			$randFile = rand(100000000,999999999).rand(1000000,9999999).".".$extension;
			 $rootPath = $_SERVER['DOCUMENT_ROOT'];;
	
		  	 $directoryPath=$rootPath.'/newsapp/assets/images/'.$randFile;
			
		move_uploaded_file($_FILES['files']['tmp_name'],$directoryPath);
			   
		
		}else{
		    $randFile='';
		    $orignalName='';
		}


     $query = $db2->query('SELECT state.name as sname,state.capital,state.coutry_id,country.name  FROM state LEFT JOIN country ON state.coutry_id=country.id WHERE state.id="'.$state_id.'"');


    foreach ($query as $value) {

$sname = $value['sname'];
$capital = $value['capital'];
$coutry_id = $value['coutry_id'];
$c_name = $value['name'];

}
    $db2->query('INSERT INTO `sb_location_master`(`location`,`location_district`,`location_capital`,`location_state`,`location_country`,`orignal_fileName`,`fileName`,`state_id`,`country_id`) VALUES ("'.$city.'","'.$city.'","'.$capital.'","'.$sname.'","'.$c_name.'","'.$orignalName.'","'.$randFile.'","'.$state_id.'","'.$coutry_id.'")');


  }
  
  if(isset($_POST['updatesubmit'])){
    $id=$_POST['id'];
    $city=$_POST['city'];
    if($_FILES['files']['name']){
			$temp= explode('.',$_FILES['files']['name']);
			$extension = end($temp);
			$orignalName = $_FILES['files']['name'];
			$randFile = rand(100000000,999999999).rand(1000000,9999999).".".$extension;
			 $rootPath = $_SERVER['DOCUMENT_ROOT'];;
	
		  	 $directoryPath=$rootPath.'/newsapp/assets/images/'.$randFile;
			
		move_uploaded_file($_FILES['files']['tmp_name'],$directoryPath);
	$db2->query('UPDATE sb_location_master SET location_district="'.$city.'" ,
	location="'.$city.'",orignal_fileName="'.$orignalName.'",fileName="'.$randFile.'" WHERE id= "'.$id.'"');	   
		}else{

    $db2->query('UPDATE sb_location_master SET location_district="'.$city.'" , location="'.$city.'" WHERE id= "'.$id.'"');
    }
  }


    if (isset($_GET['action'])){
        $id=$_GET['id'];
               $db2->query('DELETE FROM `sb_location_master` where id="'.$id.'"');

  }

    $datares='
    <form method="POST" enctype="multipart/form-data">
    <div class="row">
  <div class="col-xs-6 col-md-4"><input class="form-control" type="text" name="city" placeholder="City Name" required/></div>
  <div class="col-xs-6 col-md-4">
  <select class="form-control" name="state_id">
<option value="">-- Select State -- </option>
  ';
   $querry=$db2->query('SELECT * FROM `state`');

    foreach ($querry as $value) {
      $datares.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
    }
  $datares.='</select>
  </div>
  <div class="col-xs-6 col-md-4">
<input class="form-control" type="file" name="files"  />
</div>
  <div class="col-xs-6 col-md-12"><button type="submit"  name="savesubmit" class="btn btn-primary mb-3">Submit</button></div>
 </div>  
 <div class="row">
  <div class="col-xs-6 col-md-3"></div>
  <div class="col-xs-6 col-md-3"></div>
  <div class="col-xs-6 col-md-3"></div>
  
</div></form><br>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <table class="table table-hover" id="example">
  <thead>
    <tr>
      <th scope="col">Sr.No.</th>
      <th scope="col">City</th>
      <th scope="col">State</th>
      <th scope="col">State Capital</th>
       <th scope="col">Country</th>
       <th scope="col">Image</th>
       <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>';

    $querry=$db2->query('SELECT * FROM `sb_location_master`');
  $x=1;
  $aa="'Are You Sure'";
  $fordelete='onclick="return confirm('.$aa.')"';
    foreach ($querry as $value) {
        $datares.='<tr>
        <td>'.$x.'</td>
        <td>'.$value['location'].'</td>
        <td>'.$value['location_state'].'</td>
        <td>'.$value['location_capital'].'</td>
        <td>'.$value['location_country'].'</td>
        <td>';
        if($value["fileName"]){
  $datares.='<img style="width:50px;height:50;" alt="Image3" src="'.$C->SITE_URL.'assets/images/'.$value['fileName'].'">';
  }else{
    $datares.='  <img style="width:50px;height:50;" alt="default" src="https://streetbuzz.co/develop/storage/attachments/1/627e45394271d.png">';
  }
  
  $datares.='
  </td>
        <td><a class="btn btn-danger" '.$fordelete.' href="city?id='.$value['id'].'&action=delete"> Delete</a>
          <button type="button" data-id="'.$value['id'].'" data-city="'.$value['location'].'" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Edit</button></td>
        </tr>';
        $x++; }
  $datares.='</tbody>
</table>


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
     
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit city</h4>
        </div>
        <div class="modal-body">
           <form method="POST" enctype="multipart/form-data">
    <div class="row">
  <div class="col-xs-6 col-md-6">
  <input class="form-control city-name" type="text" name="city" placeholder="city" required/>

<input class="form-control city-id" type="hidden" name="id" placeholder="city" required/>
  </div>
  <div class="col-xs-6 col-md-6">
<input class="form-control" type="file" name="files"  />
</div>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button><button type="submit" name="updatesubmit" class="btn btn-primary mb-3">Update</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>

';  
    
  $newscontent ='<div class="container-fluid col-md-4"><b>Add city </b></div>

    <div class="container-fluid col-md-4"><b>Select State</b></div>
     <div class="container-fluid col-md-4"><b>Choose Files</b></div>

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


<script>
  $('div#scroll-mob-nav').removeAttr('id');
  $(document).on("click", ".btn-info", function () {
     var id = $(this).data('id');
     var city = $(this).data('city');
    $(".city-name").val(city);
        $(".city-id").val(id);



     // As pointed out in comments, 
     // it is unnecessary to have to manually call the modal.
     // $('#addBookDialog').modal('show');
});
</script>
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