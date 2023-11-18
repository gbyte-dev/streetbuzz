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

    
$datares='';

 $month_year=$_GET["month_year"] ?? "";
 $transaction_type=$_GET["transaction_type"] ?? "";
 $userId1=$_GET["userId"] ?? "";
 $refrence_id=$_GET["refrence_id"] ?? "";
    
    if($userId1){
    $id=$value["user_id"];
    $u=$db2->query("SELECT * FROM `users` where id=$userId1");
    $ro= $db2->fetch_object($u);
   
    $uname=$ro->username;
    }else{
        $uname="";
    }
  $newscontent ='
  <style>
  .m{
      margin-top: 15px;
  }
  </style>
  <div class="row">
  <form action="" method="GET">
  <h2>Manage Payment</h2>
  <br>
  
  
  <div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;&nbsp Payment Month & Year :</label>
    <div class="col-md-7">
      <input type="month" name="month_year" class="form-control" value="'.$month_year.'">
    </div>
  </div>
  <div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
  Transaction Type :</label>
    <div class="col-md-7">
      
<select class="form-control" name="transaction_type">
<option value="">Select Transaction Type</option>
<option>Fixed Amount</option>
<option>Advt commission</option>
<option>Viewership</option>

</select>
    </div>
  </div>
  
  <div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
  User Name :</label>
    <div class="col-md-7">
      
<input type="text"  id="user" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
<div id="userList"></div>     </div>
  </div>
  
  <input type="hidden" name="userId" id="user_id" value="'.$userId1.'">
  
  
<div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
  Refrence Id :</label>
    <div class="col-md-7">
      
<input type="text"  name="refrence_id" class="form-control mt-2" placeholder="Enter Refrence Id" value="'.$refrence_id.'"/>  
    </div>
  </div>
  
  
 
  
  <div class="row">
  <div class="col-md-12">
 <br>
  <button type="submit" class="btn btn-success" name="filter">Find</button>
  <a href="'.$C->SITE_URL.'admin/manage_payment" class="btn btn-primary">Reset</a>

  <a href="'.$C->SITE_URL.'admin/addPayment" class="btn btn-success pull-right">Add Payement</a>
  </div>
  </div>
  </form>
  <br>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">

  <div>

  <table class="table" id="example1">
  <thead>
    <tr>
      <th scope="col"></th>
      <th scope="col">User Name</th>
      
      <th scope="col">Payment</th>
      <th scope="col">Refrence Number</th>
      <th scope="col">Payment Month and Year</th>
      <th scope="col">Transaction Type</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>';
  
  if(isset($_GET["filter"])){
      
       $user_id=$_GET["userId"];
       $month_year=$_GET["month_year"];
      $transaction_type=$_GET["transaction_type"];
      $refrence_id=$_GET["refrence_id"];
     
      if($user_id !="" && $month_year !="" && $transaction_type !="" && $refrence_id !=""){
         
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where user_id=$user_id and transaction_type='$transaction_type' and payemnt_month_year='$month_year' and refrence_id like '%$refrence_id%'");
     
      }else if($user_id !="" && $month_year !="" && $transaction_type !=""){
         
          $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where user_id=$user_id and transaction_type='$transaction_type' and payemnt_month_year='$month_year'");
      }
    else if($user_id !="" && $month_year !=""){
       
          $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where user_id=$user_id and payemnt_month_year='$month_year'");
      }
    else  if($month_year !="" && $transaction_type !="" && $refrence_id !=""){
          
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where transaction_type='$transaction_type' and payemnt_month_year='$month_year' and refrence_id like '%$refrence_id%'");
      }else  if($month_year !="" && $transaction_type !=""){
          
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where transaction_type='$transaction_type' and payemnt_month_year='$month_year'");
      }else if($transaction_type !="" && $refrence_id !=""){
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where transaction_type='$transaction_type' and refrence_id like '%$refrence_id%'");
      }else if($transaction_type !="" && $user_id !=""){
          
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where transaction_type='$transaction_type' and user_id=$user_id");
      }
      else if($transaction_type !=""){
         
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where transaction_type='$transaction_type'");
      }else if($refrence_id !=""){
         
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where refrence_id like '%$refrence_id%'");
      }else if($user_id !=""){
    
          $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where user_id=$user_id");
          
      } else  if($month_year !=""){
      
     $qq=$db2->query("SELECT * FROM `sb_reporterpayment` where  payemnt_month_year='$month_year'");
      }

  }else{
     
  $qq=$db2->query("SELECT * FROM `sb_reporterpayment`");
    }
      foreach ($qq as $value) {
         
          $id=$value["user_id"];
    $u=$db2->query("SELECT * FROM `users` where id=$id");
    $row = $db2->fetch_object($u);
 
    $newscontent.='<tr>
      <td></th>
      <td>'.$row->username.'</td>
      <td>₹ '.$value["payment_amount"].'</td>
      <td>'.$value["refrence_id"].'</td>
      <td>'.$value["payemnt_month_year"].'</td>
      <td>'.$value["transaction_type"].'</td>
     
      <td><a href="'.$C->SITE_URL.'admin/editPayment?id='.$value["id"].'" class="btn btn-info">Edit</a></td>
    </tr>';
      }
      
  $newscontent.='</tbody>
</table>
  </div>
  
  
  
  </div>

<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>';

$newscontent .='

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

<script type="text/javascript">


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


<script>
  $('div#scroll-mob-nav').removeAttr('id');
  $(document).on("click", ".btn-info", function () {
     var id = $(this).data('id');
     var state = $(this).data('state');
          var capital = $(this).data('capital');

    $(".state-capital").val(capital);

    $(".state-name").val(state);
        $(".state-id").val(id);





     // As pointed out in comments, 
     // it is unnecessary to have to manually call the modal.
     // $('#addBookDialog').modal('show');
});
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

var table = $("#example1").DataTable({
select: false,
"columnDefs": [{
className: "Name",
"targets": [0],
"visible": false,
"searchable": false
}]
}); //End of create main table


$("#example1 tbody").on("click", "tr", function() {

// alert(table.row( this ).data()[0]);

});
});
</script>
<script>
function readuser_byid(id){
    $("#user_id").val(id);
}
</script>
   