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
 
 

$q=$db2->query('SELECT * FROM sb_location_master');


$category=$db2->query('SELECT * FROM groups_categories');

$languages=$db2->query('SELECT * FROM languages');

if(isset($_GET['search'])){
      
    $user_name=$_GET['user_name'];
    $phone_no=$_GET['phone_no'];
    $email_id=$_GET['email_id'];
    $is_reposter=$_GET['is_reposter'];
   
   if($user_name !="" && $phone_no !="" && $email_id !="" && $is_reposter != ""){
      $query_s=$db2->query('SELECT * FROM users where username LIKE "%'.$user_name.'%" AND email LIKE "%'.$email_id.'%" AND phone_no LIKE "%'.$phone_no.'%"  AND is_reporter='.$is_reposter.'');
   }else if($user_name !="" && $phone_no !="" && $email_id !=""){
      
       $query_s=$db2->query('SELECT * FROM users where username LIKE "%'.$user_name.'%" AND email LIKE "%'.$email_id.'%" AND phone_no LIKE "%'.$phone_no.'%"');
   }else if($user_name !="" && $phone_no !=""){
       $query_s=$db2->query('SELECT * FROM users where username LIKE "%'.$user_name.'%" AND email LIKE "%'.$email_id.'%" AND phone_no LIKE "%'.$phone_no.'%"');
   }else if($user_name !=""){
       $query_s=$db2->query('SELECT * FROM users where username LIKE "%'.$user_name.'%" ');
   }else{
       $query_s="";
   }
   
 
}


$datares='';

$uname=$_GET['user_name']??'';
$p_no=$_GET['phone_no']??'';
$e_id=$_GET['email_id']??'';



$newscontent ='<div class="row">
<h2 class="text-center">Search User For Registration</h2>
<br>
<form action="" method="GET">
<div class="row m">
<div class="col-md-4">
<label>User Name</label>
<input type="text" name="user_name" id="user" value="'.$uname.'" class="form-control" placeholder="Enter User Name" />  
<div id="userList"></div> 
</div>

<div class="col-md-4">
<label>Phone No.</label>
<input type="text" name="phone_no" id="user" class="form-control" placeholder="Enter Phone No" value="'.$p_no.'" />  

</div>

<div class="col-md-4">
<label>Email</label>
<input type="text" name="email_id" id="user" class="form-control" placeholder="Enter Email Id" value="'.$e_id.'"/>  
</div>
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
      <th scope="col">Action</th>
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
      <td><a href="'.$C->SITE_URL.'admin/reporterDetail?id='.$row_a["id"].'" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true" style="color: white;"></i>
</a>
</td>
    </tr>';
    
    }}
  $newscontent.='</tbody>
</table>


';

/*
$newscontent ='
<style>
.m{
margin-top: 15px;
}
</style>
<div class="row">
<h2 class="text-center">Search User For Registration</h2>
<br>
<form action="" method="GET">
<div class="row m">
<div class="col-md-4">
<label>User Name</label>
<input type="text" name="user_name" id="user" class="form-control" placeholder="Enter User Name" />  

</div>

<div class="col-md-4">
<label>Phone No.</label>
<input type="text" name="phone_no" id="user" class="form-control" placeholder="Enter Phone No" />  

</div>

<div class="col-md-4">
<label>Email</label>
<input type="text" name="email_id" id="user" class="form-control" placeholder="Enter Email Id" />  
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
<button type="submit" class="btn btn-primary">Search</button>
  
</div>



</div>
</form>



<form action="" method="POST">
<div class="row m">

<label  class="col-md-5 col-form-label">Select the user : </label>
<div class="col-md-7">
<input type="text" name="user" id="user" class="form-control mt-2" placeholder="Enter User Name" />  
<div id="userList"></div>       
</div>
</div>

<input type="hidden" id="user_id">
<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-user-o" aria-hidden="true" style="color:black;"></i>&nbsp;&nbsp User Name :</label>
<div class="col-md-7" id="user_username">
 </div>
</div>

<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp Email :</label>
<div class="col-md-7" id="user_email">
 </div>
</div>

<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp Phone :</label>
<div class="col-md-7" id="user_phone_no">
 
</div>
</div>



<div class="row">
<div class="col-md-12">
<button type="button" class="btn btn-info">Register</button>
<button type="button" class="btn btn-outline-info">Edit</button>
<button type="button" class="btn btn-warning">Suspend</button>
</div>
</div>

<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;&nbsp Registration Date :</label>
<div class="col-md-7">

<input type="date" name="date" class="form-control" id="date">
</div>
</div>

<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp Enter Coverage Location :</label>
<div class="col-md-7">

<select class="form-control" multiple id="location">
<option>Select Location</option>';

while($row = $q->fetch_assoc()){


$newscontent .='<option value='.$row['id'].'>'.$row['location'].'</option>';
}

$newscontent.='</select>
</div>
</div>

<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
News Coverage Category :</label>
<div class="col-md-7">

<select class="form-control" multiple id="category">
<option>Select Category</option>';

while($row_cate = $category->fetch_assoc()){
$newscontent .='<option value='.$row_cate['id'].'>'.$row_cate['title'].'</option>';
}
$newscontent.='</select>
</div>
</div>
<div class="row m">

<label  class="col-md-5 col-form-label"><i class="fa fa-language" aria-hidden="true"></i>&nbsp;&nbsp
News Coverage Language :</label>
<div class="col-md-7">

<select class="form-control" multiple id="language">
<option>Select Category</option>';

while($row_lang = $languages->fetch_assoc()){
$newscontent .='<option value='.$row_lang['id'].'>'.$row_lang['langkey'].'</option>';
}
$newscontent.='</select>

</div>
</div>
<div class="row m">

<label  class="col-md-2 col-form-label"> Status :</label>
<div class="col-md-2">

<select class="form-control">
<option>Active</option>
<option>Deactive</option>
</select>
</div>
</div>

<div class="row">
<div class="col-md-12">
<button type="button" class="btn btn-default">Cancle</button>
<button type="button" class="btn btn-success"  onclick="runajex()" >Save</button>
</form>
</div>
</div>
</div>';*/


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
 