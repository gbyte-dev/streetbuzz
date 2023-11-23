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
 
 if(isset($_POST['submit'])){
     
     $userId=$_POST['userId'];
     $is_reporter=$_POST['is_reporter'];
     
     $language=$_POST['language'];
     $category=$_POST['category'];
     $location=$_POST['location'];
     $date=$_POST['date'];
     $date_strtotime=strtotime($date);
     
     
     
     ////update user table------------
     $db2->query('UPDATE users
SET is_reporter="1",reporter_status=1,reporter_reg_date="'.$date.'"
WHERE id='.$userId.'');

     if($is_reporter==0){
         
     ////-insert data in history table--------
     
      $db2->query("INSERT INTO sb_reporter_status_history (user_id, reporter_status, start_date, end_date)
VALUES ($userId, 1, '$date', '$date')");
}else if($is_reporter==1){
    
    //---update data in history table--------
    /*$q = $db2->query("UPDATE sb_reporter_status_history
SET reporter_status = 1, start_date = '$date', end_date = '$date'
WHERE user_id = $userId");*/

}

if($is_reporter==0){
    ////-insert data in catogery table--------
      $db2->query("INSERT INTO sb_reporter_coverage_category (user_id, category_id, added_date)
VALUES ($userId,$category, $date_strtotime)");
}else if($is_reporter==1){
   
   $q = $db2->query("UPDATE sb_reporter_coverage_category
SET category_id = '$category', added_date = '$date_strtotime'
WHERE user_id = $userId");


}



if($is_reporter==0){
    ////-insert data in location table--------
    foreach($location as $loc){
     $db2->query("INSERT INTO sb_reporter_coverage_location (user_id, location_id, added_date)
VALUES ($userId,$loc, $date_strtotime)");
}
}else if($is_reporter==1){
    
    $db2->query("DELETE FROM sb_reporter_coverage_location WHERE user_id=$userId");

    
    foreach($location as $loc){
       
     $db2->query("INSERT INTO sb_reporter_coverage_location (user_id, location_id, added_date)
VALUES ('$userId', '$loc', '$date_strtotime')");

        

}
}

if($is_reporter==0){
    ////-insert data in language table--------
     $db2->query("INSERT INTO sb_reporter_coverage_language (user_id, language_id, added_date)
VALUES ('$userId', '$language', '$language')");
}else if($is_reporter==1){
    $q = $db2->query("UPDATE sb_reporter_coverage_language
SET language_id = $language, added_date = $date_strtotime
WHERE user_id = $userId");

}


 }
   if(isset($_POST['accountName'])){
     
     
      $userId=$_POST['userId'];
     
     $accountName=$_POST['accountName'];
     $bankName=$_POST['bankName'];
     $accountNumber=$_POST['accountNumber'];
     $ifsc_code=$_POST['ifsc_code'];
     $upi_id=$_POST['upi_id'];
     $q1=$db2->query('SELECT * FROM sb_reporter_bank_detail where user_id='.$userId.''); 
     
 $num= $q1->num_rows;
 
   if($num>0){
        $q=$db2->query('UPDATE sb_reporter_bank_detail
SET account_name="'.$accountName.'",bank_name="'.$bankName.'",account_number="'.$accountNumber.'",ifsc_code="'.$ifsc_code.'",upi_id="'.$upi_id.'"
WHERE user_id='.$userId.'');
   }else{
     
$qu = $db2->query("INSERT INTO sb_reporter_bank_detail (user_id, account_name, bank_name, account_number, ifsc_code,upi_id)
VALUES ($userId, '$accountName', '$bankName', '$accountNumber', '$ifsc_code','$upi_id')");

   }


 }
 
 if(isset($_POST['reporter_status'])){
     
     
      $reporter_status=$_POST['reporter_status'];
     $userId=$_POST['userId'];
      
      $q=$db2->query('UPDATE users
SET reporter_status="'.$reporter_status.'" WHERE id='.$userId.'');
      
      
      $db2->query("INSERT INTO sb_reporter_status_history (user_id, reporter_status, start_date, end_date) 
VALUES ('$userId', '$reporter_status', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')");

 }
 
 
 
  $id=$_GET['id'];
$q=$db2->query('SELECT * FROM users where id='.$id.'');

$ro= $q->fetch_assoc();
   //print_r($ro); 
//die('=====');
$is_reporter='';
$btn_lable='';
if($ro['is_reporter']==1){
    $is_reporter='<span style="color:green">Yes</span>';
    $btn_lable='Update Reporter';
}else if($ro['is_reporter']==0){
     $is_reporter='<span style="color:red">No</span>';
     $btn_lable='Register Reporter';
}


$option='';
$reporter_status='';
if($ro['reporter_status']==0){
    $reporter_status='Suspened';
    $option='<option value="">Select Reporter Status</option>
    <option value="1">Active</option>
<option value="2">Re-registered</option>
<option value="3">Deactive</option>';
}else if($ro['reporter_status']==1){
     $reporter_status='Active';
     $option='<option value="">Select Reporter Status</option>
     <option value="0" >Suspened</option>
     <option value="3">Deactive</option>';
}else if($ro['reporter_status']==2){
     $reporter_status='De-registered';
     
     $option='<option value="">Select Reporter Status</option>
     <option value="1">Active</option>
     <option value="3">Deactive</option>';
}else if($ro['reporter_status']==3){
     $reporter_status='Deactive';
     
     $option='<option value="">Select Reporter Status</option>
     <option value="1">Active</option>';
}





$datares='';

$newscontent='<div class="row">
<a onclick="return url()" href="'.$C->SITE_URL.'admin/manage_registration" class="btn btn-primary">Back</a>
<button type="button" class="btn btn-info btn-lg pull-right" data-toggle="modal" data-target="#myModal">Bank Detail</button>

<button type="button" class="btn btn-info btn-lg pull-right " data-toggle="modal" data-target="#reporter_history" style="margin-right: 10px;">Reporter History</button>


<div class="col-md-12"><b>Name :</b> '.$ro['fullname'].'</div>
<div class="col-md-12"><b>User Name :</b> '.$ro["username"].'</div>
<div class="col-md-12"><b>Email :</b> '.$ro["email"].'</div>
<div class="col-md-12"><b>Phone No. :</b> '.$ro["phone_no"].'</div>
<div class="col-md-12"><b>Is Reporter. :</b> '.$is_reporter.'</div>';



$ql=$db2->query('SELECT * FROM sb_reporter_coverage_language where user_id='.$id.'');
$ro_1= $ql->fetch_assoc();
$language_id=$ro_1['language_id'];

$qlo=$db2->query('SELECT * FROM sb_reporter_coverage_location where user_id='.$id.'');
$location_id=[];
while($ro_2= $qlo->fetch_assoc()){

array_push($location_id,$ro_2['location_id']);
}

$qc=$db2->query('SELECT * FROM sb_reporter_coverage_category where user_id='.$id.'');
$ro_3= $qc->fetch_assoc();
$category_id=$ro_3['category_id'];

 $newscontent.='
 <form action="" method="POST">
 <input type="hidden"  name="userId" value="'.$_GET['id'].'">
 <input type="hidden"  name="is_reporter" value="'.$ro['is_reporter'].'">
 <div class="col-md-6">
<b>Choose Language</b>
<select class="form-control" name="language">
<option value="0" >Choose Language</option>';

$qu1 = $db2->query('SELECT * FROM sb_languages'); 
while ($ro1 = $qu1->fetch_assoc()) {
  $select_lang='';
    if($language_id==$ro1["id"]){
        $select_lang='selected';
    }
    $newscontent .= '<option value="' . $ro1["id"] . '" '.$select_lang.'>' . $ro1["language_name"] . '</option>';
}

$newscontent.='</select >
</div>
<div class="col-md-6">
<b>Choose Category</b>
<select class="form-control" name="category">
<option value="0" >Choose Category</option>';

$qu2=$db2->query('SELECT * FROM categeory_master'); 
while($ro2= $qu2->fetch_assoc()){
    $selected_cat='';
    if($category_id==$ro2['cat_id']){
    $selected_cat='selected';
    }
$newscontent.='<option value="'.$ro2['cat_id'].'" '.$selected_cat.'>'.$ro2['cat_name'].'</option>';
}
$newscontent.='</select >
</div>
<div class="col-md-6">
<b>Choose Location</b>
<select class="form-control" name="location[]" multiple style="height: 100px;">
<option value="0" >Choose Location</option>';

$qu2=$db2->query('SELECT * FROM sb_location_master'); 
while($ro2= $qu2->fetch_assoc()){
    $select_loc="";
   
    if (in_array($ro2['id'], $location_id)){
        $select_loc="selected";
        
    }
    
$newscontent.='<option value="'.$ro2['id'].'" '.$select_loc.' >'.$ro2['location'].'</option>';
}
$newscontent.='</select >
</div>

<div class="col-md-6">
<b>Reporter Register Date</b> 
<input type="date" name="date" class="form-control" value="'.$ro['reporter_reg_date'].'">
</div>

<div class="col-md-12">  
<br>
          <button type="submit" name="submit" class="btn btn-primary pull-right">'.$btn_lable.'</button>
</div>
</form>

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Bank Detail</h4>
        </div>';
        $q2=$db2->query('SELECT * FROM sb_reporter_bank_detail where user_id='.$id.''); 
   if($q2->num_rows>0){
       $ro1= $q2->fetch_assoc();
   }
        
        
        $newscontent.='<div class="modal-body">
        <form action="" method="POST">
         <input type="hidden"  name="userId" value="'.$_GET['id'].'">
        <label for="html">Account Name</label>
          <input type="text" class="form-control" name="accountName" placeholder="Enter account name" value="'.$ro1['account_name'].'">
          <label for="html">Bank Name</label>
          <input type="text" class="form-control" name="bankName" placeholder="Enter bank name" value="'.$ro1['bank_name'].'">
          <label for="html">Account Number</label>
          <input type="text" class="form-control" name="accountNumber" placeholder="Enter account number" value="'.$ro1['account_number'].'">
          <label for="html">IFSC Code</label>
          <input type="text" class="form-control" name="ifsc_code" placeholder="Enter ifsc code" value="'.$ro1['ifsc_code'].'">
          
          <label for="html">UPI ID</label>
          <input type="text" class="form-control" name="upi_id" placeholder="Enter UPI ID" value="'.$ro1['upi_id'].'">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>

        </div>
        </form>
      </div>
      
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="reporter_history" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Reporter History</h4>
        </div>
        <div class="modal-body">
         <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Status</th>
      <th scope="col">Date</th>
      
    </tr>
  </thead>
  <tbody>';
  
        $q2=$db2->query('SELECT * FROM sb_reporter_status_history where user_id='.$id.' order by history_id desc '); 
   if($q2->num_rows>0){
       $j=0;
    while($row1= $q2->fetch_assoc()){
  $j++;
  
  $reporter_s='';
if($row1['reporter_status']==0){
    $reporter_s='Suspened';
}else if($row1['reporter_status']==1){
     $reporter_s='Active';
}else if($row1['reporter_status']==2){
     $reporter_s='De-registered';
}else if($row1['reporter_status']==3){
     $reporter_s='Deactive';
}
  
    $newscontent.='<tr>
      <th scope="row">'.$j.'</th>
      <td>'.$reporter_s.'</td>
      <td>'.date("d-m-Y", strtotime($row1["start_date"])).'</td>
      
    </tr>';
   } }
  $newscontent.='</tbody>
</table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
';
if($ro['is_reporter']==1){
 $newscontent.='<div class="col-md-12" style="border: 1px solid #c9c5c5;margin-top: 10px; padding: 5px; border-radius: 5px;">
 <br>
 
 <p><b>Reporter Current Status : </b> '.$reporter_status.'<p>
 
<form action="" method="POST">

<div class="col-md-3">
<b>Reporter Status :</b>
</div>
<div class="col-md-6">

<select class="form-control" name="reporter_status">
'.$option.'
</select >
</div>
<div class="col-md-3">
<input type="hidden"  name="userId" value="'.$_GET['id'].'">
<button type="submit" class="btn btn-primary pull-right">Update Status</button>

</div>
</form>
</div>';
}

/*$newscontent ='<div class="row">
<h2 class="text-center">Search User For Registration</h2>
<br>
<form action="" method="GET">
<div class="row m">
<div class="col-md-4">
<label>User Name</label>
<input type="text" name="user_name" id="user" value="'.$uname.'" class="form-control" placeholder="Enter User Name" />  

</div>

<div class="col-md-4">
<label>Phone No.</label>
<input type="number" name="phone_no" id="user" class="form-control" placeholder="Enter Phone No" value="'.$p_no.'" />  

</div>

<div class="col-md-4">
<label>Email</label>
<input type="text" name="email_id" id="user" class="form-control" placeholder="Enter Email Id" value="'.$e_id.'"/>  
</div>
<div class="col-md-1"></div>
<div class="col-md-5">
<label>Is Reporter</label>
<select name="is_reposter" class="form-control">  
<option value="">Select Reporter</option>
<option value="0">No</option>
<option value="1">Yes</option>
</select>
</div>

<div class="col-md-4" style="margin-top: 25px;">
<button type="submit" class="btn btn-primary" name="search">Search</button>
  
</div>



</div>
</form>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">User Name</th>
      <th scope="col">Email Id</th>
      <th scope="col">Phone No.</th>
      <th scope="col">Is Reporter</th>
    </tr>
  </thead>
  <tbody>';
  $i=0;
   
  if($query_s){
  while($row_a = $query_s->fetch_assoc()){
    $i++;
       if($row_a['is_reporter']==1){
           $reporter="Yes";
       }else if($row_a['is_reporter']==0){
           $reporter="No";
       }else{
           $reporter="";
       }
        
  
    
    $newscontent.='<tr>
      <th scope="row">'.$i.'</th>
      <td>'.$row_a['username'].'</td>
      <td>'.$row_a['email'].'</td>
      <td>'.$row_a['phone_no'].'</td>
      <td> '.$reporter.' </td>
    </tr>';
    
    }}
  $newscontent.='</tbody>
</table>


';*/




$newscontent .='
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>
<script>  
$(document).ready(function(){  
$("#user").keyup(function(){  
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchuser",  
method:"POST",  
data:{query:query},  
success:function(data)  
{  
$("#userList").fadeIn();  
$("#userList").html(data);  
}  
});  
}  
});  
$(document).on("click", "li", function(){  
$("#user").val($(this).text());  
$("#userList").fadeOut();  
});  
});  
</script>


<script>

function runajex(){   

var array={};
    array["user_id"]=$("#user_id").val();
    array["registration_date"]=$("#date").val();
    array["coverage_category"]=$("#category").val();
    
    array["coverage_location"]=$("#location").val();
    array["coverage_language"]=$("#language").val();
    
   
    

var myJsonString = JSON.stringify(array);
 console.log(myJsonString);
    $.ajax
({
url: "'.$C->SITE_URL.'api/newsperson/registerweb", 
type: "post",
dataType:"json",
data: {arr:myJsonString},

success: function(response){
    if(response==1){
        alert("Added Successfully !");
    }
    
}

});

}
</script>

<script type="text/javascript">
function readuser_byid(id){
$.ajax({  
url:"searchuser_byid",  
method:"POST",  
data:{id:id},  
dataType: "JSON",
success:function(data)  
{  
 $("#user_username").html(data.username);  
$("#user_email").html(data.email);  
$("#user_phone_no").html(data.phone_no);  
$("#user_id").val(id);  
}  
});  
}


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

</script>
<style>
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

 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
 