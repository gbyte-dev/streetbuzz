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
 
 
 
 
 
 
  





$datares='';

$newscontent='<div class="row"> <div class="col-sm-8" ><select onchange="updateurl(this.value)" class="form-control" name="location" id="location">
<option value="0" >Choose Location</option>';

$qu1 = $db2->query('SELECT * FROM sb_location_master'); 

while ($ro1 = $qu1->fetch_assoc()){
 
    $newscontent .= '<option value="' . $ro1["id"] . '"';
    if (isset($_GET['location_id']) && $ro1["id"] == $_GET['location_id']) {$newscontent .= ' selected';}
  $newscontent .='>' . $ro1["location"] . '</option>';
}

$newscontent.='</select ></div>';

// $newscontent.='
// <div class="col-sm-4"><a onclick="return url()" href="'.$C->SITE_URL.'admin/manageFollow" class="btn btn-primary pull-right">Find</a></div>
// ';

if(isset($_GET['location_id'])){
    $location=$_GET['location_id'];
}
else{
    $location=0;
}
$qu_check12 = $db2->query('SELECT * FROM sb_location_master where id='.$location); 
$qu_check12 = $qu_check12->fetch_assoc();
 $location_name = $qu_check12['location'];
$qu_check = $db2->query('SELECT * FROM sb_location_handle where location_id='.$location); 
     $ro_check = $qu_check->fetch_assoc();
  
     $user_handles = $ro_check['user_handles'];
     if($user_handles!=""){
     $array_user = explode(",", $user_handles);
     }
     $state_handles = $ro_check['state_handles'];
     if($state_handles!=""){
     $array_state = explode(",", $state_handles);
     }
     $capital_handles = $ro_check['capital_handles'];
     if($capital_handles!=""){
     $array_capital = explode(",", $capital_handles);
     }
    //  $country_handles = $ro_check['country_handles'];
    //  if($country_handles!=""){
    //  $array_country = explode(",", $country_handles);
    //  }
     $national_handles = $ro_check['national_handles'];
     if($national_handles!=""){
     $array_national = explode(",", $national_handles);
     }
     $international_handles = $ro_check['international_handles'];
     if($international_handles!=""){
     $array_international = explode(",", $international_handles);
     }
 
$newscontent.="<br><br><table class='table' style='border:1px solid black'>";
if($ro_check){
$newscontent.="<tr><th>location</th>
";
 
$newscontent.="
<td>".$location_name."</td></tr>";
$newscontent.="
<tr><th>User Handle</th>
";

$newscontent.="<td>";
if(isset($array_user)){
  foreach($array_user as $uhandle){
      
$qu_check1 = $db2->query('SELECT * FROM users where id='.$uhandle); 
$ro_check1 = $qu_check1->fetch_assoc();
$username= $ro_check1['username'];

$newscontent.="<a href='$C->SITE_URL$username' target='_blank'style='color:#3D979B''>@".$username.",";
}
}
$newscontent.="</a></td></tr>";
$newscontent.="
<tr><th>State Handle</th>
";
$newscontent.="<td>";
if(isset($array_state)){
  foreach($array_state as $shandle){
      
$qu_check2 = $db2->query('SELECT * FROM users where id='.$shandle); 
$ro_check2 = $qu_check2->fetch_assoc();
$username= $ro_check2['username'];

$newscontent.="<a href='$C->SITE_URL$username' target='_blank'style='color:#3D979B''>@".$username.",";
}
}
$newscontent.="</a></td></tr>";
$newscontent.="
<tr><th>Capital Handle</th>
";
$newscontent.="<td>";
if(isset($array_capital)){
  foreach($array_capital as $chandle){
      
$qu_check3 = $db2->query('SELECT * FROM users where id='.$chandle); 
$ro_check3 = $qu_check3->fetch_assoc();
$username= $ro_check3['username'];

$newscontent.="<a href='$C->SITE_URL$username' target='_blank'style='color:#3D979B''>@".$username.",";
}
}
$newscontent.="</a></td></tr>";
// $newscontent.="
// <tr><th>Country Handle</th>
// ";
// $newscontent.="<td>";
// if(isset($array_country)){
//   foreach($array_country as $cohandle){
      
// $qu_check4 = $db2->query('SELECT * FROM users where id='.$cohandle); 
// $ro_check4 = $qu_check4->fetch_assoc();
// $username= $ro_check4['username'];

// $newscontent.="<a href='$C->SITE_URL$username' target='_blank'style='color:#3D979B''>@".$username.",";
// }
// }
// $newscontent.="</a></td></tr>";
$newscontent.="
<tr><th>National Handle</th>
";
$newscontent.="<td>";
if(isset($array_national)){
  foreach($array_national as $nhandle){
      
$qu_check5 = $db2->query('SELECT * FROM users where id='.$nhandle); 
$ro_check5 = $qu_check5->fetch_assoc();
$username= $ro_check5['username'];

$newscontent.="<a href='$C->SITE_URL$username' target='_blank' style='color:#3D979B''>@".$username.",";
}
}
$newscontent.="</a></td></tr>";
$newscontent.="<tr><th>International Handle</th>";
$newscontent.="<td>";
if(isset($array_international)){
  foreach($array_international as $inhandle){
      
$qu_check6 = $db2->query('SELECT * FROM users where id='.$inhandle); 
$ro_check6 = $qu_check6->fetch_assoc();
$username= $ro_check6['username'];

$newscontent.="<a href='$C->SITE_URL$username' target='_blank'style='color:#3D979B''>@".$username.",";
}
}
$newscontent.="</a></td></tr>";
}
else{
   $newscontent.="<tr><th>User Handle</th><th>User Handle</th><th>State Handle</th><th>Capital Handle</th><th>National Handle</th><th>Internaional Handle</th><th>Action</th></tr><tr><td></td><td></td><td></td><td style='width:20%;'>No Handle Added</td><td></td><td></td><td></td></tr>";
}
$newscontent .="</table>";
if (isset($location_name) && $_GET['location_id'] != 0) {
    $newscontent .= "
    <a class='btn btn-primary pull-right' href='" . $C->SITE_URL . "admin/addManageFollow?location_id=" . $_GET['location_id'] . "'>Edit</a>";
}
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
function url(){
  var locationid = $("#location").val();  
  
  if(locationid!=0){
 var location="'.$C->SITE_URL.'admin/manageFollow?location_id="+locationid;
 window.location.href=location;
   return false;   
  }
}

function updateurl(locationid){
   var location="'.$C->SITE_URL.'admin/manageFollow?location_id="+locationid;
 window.location.href=location; 
}

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
 