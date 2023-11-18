<?php

global $db2,$C;

if( !$this->network->id ){
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
 
if(isset($_POST['addPeyment'])){
   
    
    $userId=$_POST['uname_id'];
    $transtion_type=$_POST['transtion_type'];
    $transtion_amount=$_POST['transtion_amount'];
    $refrence_number=$_POST['refrence_number'];
    $date=$_POST['date'];
   $month_year=$_POST['month_year'];

    //$balance=$transtion_amount;
    // $q_a=$db2->query('SELECT * FROM user_earning_transactions where user_id='.$userId.' order by uet_id desc limit 1');

    // $ro_d= $q_a->fetch_assoc();
    // if($ro_d){
    //     $balance+=$ro_d['balance'];
    // }
    
    $db2->query("INSERT INTO sb_reporterpayment (user_id,transaction_type,payemnt_month_year, payment_amount,refrence_id,payment_date,entry_date)
VALUES ($userId,'$transtion_type','$month_year',$transtion_amount,'$refrence_number','$date',now())");
}





$datares='';
$newscontent='
<div class="row">
<h3 class="text-center">Add Payment</h3>
<br>
<form action="" method="POST">
<div class="col-md-4">
<b>Username <span style="color:red">*</span></b>
<input type="text" id="user" class="form-control" name="uname" placeholder="Enter Username" required>
<input type="hidden" id="user_id"  name="uname_id" >
<div id="userList"></div>
</div>
<div class="col-md-4">
<b>Transaction Type<span style="color:red">*</span></b>

<select class="form-control" name="transtion_type" required>
<option>--Select Transtion Type--</option>
<option>Fixed Amount</option>
<option>Advt Commission</option>
<option>Viewership</option>
</select>
</div>

<div class="col-md-4">
<b>Transaction Amount<span style="color:red">*</span></b>
<input type="text" class="form-control" name="transtion_amount" placeholder="Enter Transaction Amount" required>
</div>

<div class="col-md-4">
<b>Refrence Number<span style="color:red">*</span></b>
<input type="text" class="form-control" name="refrence_number" placeholder="Refrence Number" required>
</div>
<div class="col-md-4">
<b>Payment Date<span style="color:red">*</span></b>
<input type="datetime-local" class="form-control" name="date" required>
</div>

<div class="col-md-4">
<b>Payment Month and Year<span style="color:red">*</span></b>
  <input type="month" class="form-control"  name="month_year" requiered>
</div>

<div class="col-md-4 " style="margin-top: 15px;">
<button type="submit" class="btn btn-info btn-lg pull-right" name="addPeyment">Submit</button>
</div>
</form>

</div>
';









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
 