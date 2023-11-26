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

    $state=$_POST['state']; 
    $coutry_id=$_POST['coutry_id'];
     $capital=$_POST['capital'];
     $native=$_POST['native'];



if($_FILES['file']['name']){
			$temp= explode('.',$_FILES['file']['name']);
			$extension = end($temp);
			$orignalName = $_FILES['file']['name'];
			$randFile = rand(100000000,999999999).rand(1000000,9999999).".".$extension;
			 $rootPath = $_SERVER['DOCUMENT_ROOT'];;
	
		  	 $directoryPath=$rootPath.'/newsapp/assets/images/'.$randFile;
			
		move_uploaded_file($_FILES['file']['tmp_name'],$directoryPath);
			   
		
		}else{
		    $randFile='';
		    $orignalName='';
		}


    $db2->query('INSERT INTO `state`(`name`,`coutry_id`,`capital`,`orignal_fileName`,`fileName`,`native`) VALUES ("'.$state.'","'.$coutry_id.'","'.$capital.'","'.$orignalName.'","'.$randFile.'","'.$native.'")');


  }
  if(isset($_POST['updatesubmit'])){

    $id=$_POST['id'];
    $state=$_POST['state'];
    $capital=$_POST['capital'];
    $native=$_POST['native'];

if($_FILES['file']['name']){
			$temp= explode('.',$_FILES['file']['name']);
			$extension = end($temp);
			$orignalName = $_FILES['file']['name'];
			$randFile = rand(100000000,999999999).rand(1000000,9999999).".".$extension;
			 $rootPath = $_SERVER['DOCUMENT_ROOT'];;
	
		  	 $directoryPath=$rootPath.'/newsapp/assets/images/'.$randFile;
			
		move_uploaded_file($_FILES['file']['tmp_name'],$directoryPath);
		$db2->query('UPDATE state SET name="'.$state.'" , capital="'.$capital.'",orignal_fileName="'.$orignalName.'",fileName="'.$randFile.'",native="'.$native.'" WHERE id= "'.$id.'"');	   
		
		}else{
		    $db2->query('UPDATE state SET name="'.$state.'" , capital="'.$capital.'", native="'.$native.'" WHERE id= "'.$id.'"');
		}

    

  }

 if (isset($_GET['action'])){
        $id=$_GET['id'];
       $db2->query('DELETE FROM `state` where id="'.$id.'"');
  }


    $datares='
    <form method="POST" enctype="multipart/form-data">
    <div class="row">

  <div class="col-xs-6 col-md-4"><input class="form-control" type="text" name="state" placeholder="State" required/></div>
   
  <div class="col-xs-6 col-md-4"><input class="form-control" type="text" name="capital" placeholder="State Capital" required/></div>
  
  <div class="col-xs-6 col-md-4"><input class="form-control" type="text" name="native" placeholder="State Name Native" required/></div>
  




  <div class="col-xs-6 col-md-3">
<label><b>Select Country</b></label>
  <select class="form-control" name="coutry_id" required>
 <option value="">-- Select Country --</option>
  ';
 $country=$db2->query('SELECT * FROM `country`');
    foreach ($country as $value) {
      $datares.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
    }
  $datares.='</select>
  </div>
  
  <div class="col-xs-6 col-md-5">
 <label><b>Choose Files</b></label>
<input class="form-control" type="file" name="file"  />
</div>

  <div class="col-xs-6 col-md-2" style="margin-top: 15px;"><button type="submit"  name="savesubmit" class="btn btn-primary mb-3">Submit</button></div>
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
      <th scope="col">State</th>
      <th scope="col">State Capital</th>
      <th scope="col">Country</th>
      <th scope="col">Native</th>
      <th scope="col">Image</th>
       <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>';
 
    $querry=$db2->query('SELECT * FROM `state`');

  $x=1;
  $aa="'Are You Sure'";
  $fordelete='onclick="return confirm('.$aa.')"';
    foreach ($querry as $value) {
        $datares.='<tr>
        <td>'.$x.'</td>
        <td>'.$value['name'].'</td>
                <td>'.$value['capital'].'</td>

         <td>';
          $c_name=$db2->query('SELECT * FROM `country` where id="'.$value['coutry_id'].'"');
       //   echo 'SELECT * FROM `country` where id="'.$value['coutry_id'].'"'; die;
              foreach ($c_name as $c_name_c) {
        $co =  $c_name_c['name'];
        }
           ///  echo $c_name['name'];
  $datares.=''.$co.'</td>
  
 <td>'.$value['native'].'</td>
  <td>';
  
  if($value["fileName"]){
  $datares.='<img style="width:50px;height:50;" alt="Image3" src="'.$C->SITE_URL.'assets/images/'.$value['fileName'].'">';
  }else{
    $datares.='  <img style="width:50px;height:50;" alt="default" src="https://streetbuzz.co/develop/storage/attachments/1/627e45394271d.png">';
  }
  
  $datares.='
  </td>
        <td><a class="btn btn-danger" '.$fordelete.' href="state?id='.$value['id'].'&action=delete"> Delete</a>
          <button type="button" data-id="'.$value['id'].'" data-state="'.$value['name'].'" data-capital="'.$value['capital'].'" data-native="'.$value['native'].'" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Edit</button></td>
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
          <h4 class="modal-title">Edit state</h4>
        </div>
        <div class="modal-body">
           <form method="POST" enctype="multipart/form-data">
    <div class="row">
  <div class="col-md-4"><input class="form-control state-name" type="text" name="state" placeholder="state" required/>

</div>
  <div class="col-md-4"><input class="form-control state-capital
  " type="text" name="capital" placeholder="capital" required/>
</div>
  <div class="col-md-4"><input class="form-control state-native
  " type="text" name="native" placeholder="Enter Native" required/>
</div>

<div class="col-md-5"><input class="form-control state-capital
  " type="file" name="file" />
</div>

<input class="form-control state-id" type="hidden" name="id" placeholder="state" required/>
  <div class="col-xs-6 col-md-3"></div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          
          <button type="submit" name="updatesubmit" class="btn btn-primary mb-3">Update</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>

';  
    
  $newscontent ='<div class="container-fluid col-md-4"><b>Add state </b></div>
  <div class="container-fluid col-md-4"><b>State Capital </b></div>
  <div class="container-fluid col-md-4"><b>State Native  </b></div>
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
    </style>
    

    ';

    $tpl->layout->setVar('main_content',$newscontent);


  
$tpl->display();
?>


<script>
  $('div#scroll-mob-nav').removeAttr('id');
  $(document).on("click", ".btn-info", function () {
     var id = $(this).data('id');
      $(".state-id").val(id);
     var state = $(this).data('state');
      $(".state-name").val(state);
      var native = $(this).data('native');
      $(".state-native").val(native);
      
          var capital = $(this).data('capital');

    $(".state-capital").val(capital);

   
       





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