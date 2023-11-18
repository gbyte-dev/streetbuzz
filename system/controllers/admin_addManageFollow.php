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
     $location=$_POST['location'];
     $user_handles=""; $capital_handles=''; $country_handles=""; $national_handles=""; $state_handles=""; $international_handles='';
     
     if(isset($_POST['user_handles'])){
     $user_handles=implode(",",$_POST['user_handles']);
     
     
     }
     if(isset($_POST['state_handles'])){
     
     $state_handles=implode(",",$_POST['state_handles']);
    
     }
     if(isset($_POST['capital_handles'])){
     
     $capital_handles=implode(",",$_POST['capital_handles']);
    
     }
    //  if(isset($_POST['country_handles'])){
     
    //  $country_handles=implode(",",$_POST['country_handles']);
    
    //  }
     if(isset($_POST['national_handles'])){
     $national_handles=implode(",",$_POST['national_handles']);
     
     }
     if(isset($_POST['international_handles'])){
     $international_handles=implode(",",$_POST['international_handles']);
     
     }
    // die("======");
       $qu_check = $db2->query('SELECT * FROM sb_location_handle where location_id='.$location); 
     $ro_check = $qu_check->fetch_assoc();
   
     if($ro_check){
          $db2->query("UPDATE  sb_location_handle set user_handles='$user_handles',state_handles='$state_handles',capital_handles='$capital_handles',country_handles='$national_handles',national_handles='$national_handles',international_handles='$international_handles' where location_id=$location ;"); 
     } 
     else{
        
        $db2->query("INSERT INTO sb_location_handle (location_id,user_handles,state_handles,capital_handles,country_handles,national_handles,international_handles) VALUES ($location,'$user_handles','$state_handles','$capital_handles','$national_handles','$national_handles','$international_handles')"); 
     }
     
     header('Location: ' . $C->SITE_URL . '/admin/addManageFollow?location_id=' . $location);

     // $u_id=implode(",",$_POST['userId']); 
      
      
      
      
      
  //  $db2->query("INSERT INTO reporter_auto_follow (location_id,reporter)
//VALUES ($location,'$u_id')");




 }
 
 
 
if(isset($_GET['location_id'])){
    $location=$_GET['location_id'];
$qu_check = $db2->query('SELECT * FROM sb_location_handle where location_id='.$location); 
     $ro_check = $qu_check->fetch_assoc();
     $user_handles = $ro_check['user_handles'];
     if($user_handles!=''){
     $array_user = explode(",", $user_handles);
     }
     
     $state_handles = $ro_check['state_handles'];
      if($state_handles!=''){
     $array_state = explode(",", $state_handles);
      }
      
     $capital_handles = $ro_check['capital_handles'];
     if($capital_handles!=''){
     $array_capital = explode(",", $capital_handles);
     }
     
     $country_handles = $ro_check['country_handles'];
     if($country_handles!=''){
     $array_country = explode(",", $country_handles);
     }
      
     $national_handles = $ro_check['national_handles'];
      if($national_handles!=''){
     $array_national = explode(",", $national_handles);
       }
     $international_handles = $ro_check['international_handles'];
       if($international_handles!=''){
     $array_international = explode(",", $international_handles);
       }
}

  





$datares='';

$newscontent='
 <style>
  .m{
      margin-top: 15px;
      
  }
  .cowidth{
      width:initial;
  }
  </style>
<form method="POST">';

if (!isset($_GET['location_id'])) {
$newscontent.='<div class="row m">
<label  class="col-md-5 col-form-label"><i class="fa fa-map-marker" aria-hidden="true"></i>
&nbsp;&nbsp
  <b>Choose Location</b> :</label>
 <div class="col-md-7">

<select class="form-control" name="location">
<option value="0" >Choose Location</option>';
$qu1 = $db2->query('SELECT * FROM sb_location_master'); 

while ($ro1 = $qu1->fetch_assoc()){
  
    $newscontent .= '<option value="' . $ro1["id"] . '">' . $ro1["location"] . '</option>';
}

/*foreach($array_user as $uhandle){
    


$news.='<p id="aaa_1">'.$uhandle.' &nbsp;<i class="fa fa-trash" onclick="remove_(1)" style="color:red"></i><input type="hidden" name="user_handles[]" id="uId" value="128"></p>';
}*/

//echo $news; die;
$newscontent.='</select >
</div>
</div>';

}
$location_name='';
if(isset($location)){
$qu_check12 = $db2->query('SELECT * FROM sb_location_master where id='.$location); 
$qu_check12 = $qu_check12->fetch_assoc();
 $location_name = $qu_check12['location'];

$newscontent.='<h3>State Handle for '.$location_name.'</h3><a onclick="return url()" href="'.$C->SITE_URL.'admin/manageFollow?location_id='.$location.'" class="btn btn-primary pull-right"style="margin-top:-20px;margin-right: 10px;
">Back</a><br>';

}
$newscontent.='<div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
  <b>Location Handle</b> :</label>
    <div class="col-md-7">';
    if (isset($location)){
      $newscontent.='<input type="hidden"  id="state" class="form-control mt-2" name="location" value="'.$location.'"/>';
    }
$newscontent.='<input type="text"  id="user" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
<div id="showUserName" class="row">';
if(isset($array_user)){
    $i =0;
  foreach($array_user as $uhandle){
      
$qu_check1 = $db2->query('SELECT * FROM users where id='.$uhandle); 
$ro_check1 = $qu_check1->fetch_assoc();
$username= $ro_check1['username'];
$newscontent.='<div class="col-md-4 cowidth"><span id="aaa_'.$i.'"><a href="'.$C->SITE_URL.$username.'" target="_blank" style="color: #3D979B;">@'.$username.'</a> &nbsp;<i class="fa fa-trash" onclick="remove_('.$i.')" style="color:red"></i><input type="hidden" name="user_handles[]" id="uId" value="'.$uhandle.'"></span></div>

';
$i++;
}
}

 $newscontent.='</div>
<div id="userList"></div>     </div>
  </div> 
  <div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
  <b>State Handle</b> :</label>
    <div class="col-md-7">
      
<input type="text"  id="statehan" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
<div id="showState" class="row">';
if(isset($array_state)){
    $p =10;
  foreach($array_state as $shandle){
      $qu_check2 = $db2->query('SELECT * FROM users where id='.$shandle); 
$ro_check2 = $qu_check2->fetch_assoc();
$username= $ro_check2['username'];
$newscontent.='<div class="col-md-4 cowidth"><span id="aaa_'.$p.'"><a href="'.$C->SITE_URL.$username.'" target="_blank" style="color: #3D979B;">@'.$username.'</a> &nbsp;<i class="fa fa-trash" onclick="remove_('.$p.')" style="color:red"></i><input type="hidden" name="state_handles[]" id="uId" value="'.$shandle.'"></span></div>
';

$p++;
}
}
  $newscontent.='</div>
<div id="stateList"></div>     </div>
  </div> 
  
 
  
  <div class="row m">
  
    <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
  <b>Capital Handle</b> :</label>
    <div class="col-md-7">
      
<input type="text"  id="capital" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
<div id="showCapital" class="row">';
if(isset($array_capital)){
    $k =20;
  foreach($array_capital as $chandle){ 
 $qu_check3 = $db2->query('SELECT * FROM users where id='.$chandle); 
$ro_check3 = $qu_check3->fetch_assoc();
$username= $ro_check3['username'];
$newscontent.='<div class="col-md-4 cowidth"><span id="aaa_'.$k.'"><a href="'.$C->SITE_URL.$username.'" target="_blank" style="color: #3D979B;">@'.$username.'</a> &nbsp;<i class="fa fa-trash" onclick="remove_('.$k.')" style="color:red"></i><input type="hidden" name="capital_handles[]" id="uId" value="'.$chandle.'"></span></div>
';
$k++;
}
}
  $newscontent.='</div>
<div id="capitalList"></div>
</div>
</div> ';
  
//   <div class="row m">
  
// <label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
// <b>Country Handle</b> :</label>
//     <div class="col-md-7">
      
// <input type="text"  id="country" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
// <div id="showCountry" class="pull-right">';
// if(isset($array_country)){
//     $l =30;
//   foreach($array_country as $cohandle){ 
//       $qu_check4 = $db2->query('SELECT * FROM users where id='.$cohandle); 
// $ro_check4 = $qu_check4->fetch_assoc();
// $username= $ro_check4['username'];
// $newscontent.='<p id="aaa_'.$l.'"style="text-align:right;"><a href="'.$C->SITE_URL.$username.'" target="_blank" style="color: #3D979B;">@'.$username.'</a> &nbsp;<i class="fa fa-trash" onclick="remove_('.$l.')" style="color:red"></i><input type="hidden" name="country_handles[]" id="uId" value="'.$cohandle.'"></p>

// ';
// $l++;
// }
// }
//   $newscontent.='</div>
// <div id="countryList"></div>
// </div>
//   </div> ';
  $newscontent.='<div class="row m">
  
<label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
<b>National Handle</b> :</label>
    <div class="col-md-7">
      
<input type="text"  id="national" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
<div id="showNational" class="row">';
if(isset($array_national)){
    $m =40;
  foreach($array_national as $nhandle){ 
      $qu_check5 = $db2->query('SELECT * FROM users where id='.$nhandle); 
$ro_check5 = $qu_check5->fetch_assoc();
$username= $ro_check5['username'];
$newscontent.='<div class="col-md-4 cowidth"><span id="aaa_'.$m.'" style="width:33%;"><a href="'.$C->SITE_URL.$username.'" target="_blank" style="color: #3D979B;">@'.$username.'</a> &nbsp;<i class="fa fa-trash" onclick="remove_('.$m.')" style="color:red"></i><input type="hidden" name="national_handles[]" id="uId" value="'.$nhandle.'"></span></div>
';
$m++;
}
}
  $newscontent.='</div>
<div id="nationalList"></div>
</div>
  </div> 
  <div class="row m">
  
<label  class="col-md-5 col-form-label"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp
<b>Internaional Handle</b> :</label>
    <div class="col-md-7">
      
<input type="text"  id="internaional" class="form-control mt-2" placeholder="Enter User Name" value="'.$uname.'"/>  
<div id="showInternaional" class="row">';
if(isset($array_international)){
    $n =50;
  foreach($array_international as $inhandle){  
$qu_check6 = $db2->query('SELECT * FROM users where id='.$inhandle); 
$ro_check6 = $qu_check6->fetch_assoc();
$username= $ro_check6['username'];      
$newscontent.='<div class="col-md-4 cowidth"><span id="aaa_'.$n.'"><a href="'.$C->SITE_URL.$username.'" target="_blank" style="color: #3D979B;">@'.$username.'</a> &nbsp;<i class="fa fa-trash" onclick="remove_('.$n.')" style="color:red"></i><input type="hidden" name="international_handles[]" id="uId" value="'.$inhandle.'"></span></div>
';
$n++;
}
}
  $newscontent.='</div>
<div id="internaionalList"></div>
</div>
  </div> 
  

  <button type="submit" name="submit" class="btn btn-success pull-right">Submit</button>

  </form>
';



  
  






$newscontent .='
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>



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
<script>  
$(document).ready(function(){  
    
$("#user").keyup(function(){  
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchuser_1",  
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
}); 

function remove_(val){
  
    $("#aaa_"+val).remove();
};


var j=0;
function readuser_byid(id,u_name){
   var j= Math.floor(Math.random() * 1000000);
$("#user").val(""); 

$("#showUserName").append("<span id='aaa_"+j+"'>"+u_name+" &nbsp;<i class='fa fa-trash' onclick='remove_("+j+")' style='color:red'></i><input type='hidden' name='user_handles[]' id='uId'  value='"+id+"'></span>");
$("#userList").fadeOut();  
};
</script>
<script>
$(document).ready(function(){  
    
$("#statehan").keyup(function(){  
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchState",  
method:"POST",  
data:{query:query},  
success:function(data)  
{  
$("#stateList").fadeIn();  
$("#stateList").html(data);  
}  
});  
}  
});  
});

function removeSt_(val){
  
    $("#st_"+val).remove();
};


var k=0;
function readState_byid(id,u_name){
    k++;
$("#statehan").val(""); 

$("#showState").append("<span id='st_"+k+"'>"+u_name+" &nbsp;<i class='fa fa-trash' onclick='removeSt_("+k+")' style='color:red'></i><input type='hidden' name='state_handles[]' id='uId'  value='"+id+"'></span>");
$("#stateList").fadeOut();  
};
</script>

<script>
$(document).ready(function(){  
    
$("#capital").keyup(function(){  
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchCapital",  
method:"POST",  
data:{query:query},  
success:function(data)  
{  
$("#capitalList").fadeIn();  
$("#capitalList").html(data);  
}  
});  
}  
});  
});

function removeCap_(val){
  
    $("#cap_"+val).remove();
};


var l=0;
function readCapital_byid(id,u_name){
    l++;
$("#capital").val(""); 

$("#showCapital").append("<span id='cap_"+l+"'>"+u_name+" &nbsp;<i class='fa fa-trash' onclick='removeCap_("+l+")' style='color:red'></i><input type='hidden' name='capital_handles[]' id='uId'  value='"+id+"'></span>");
$("#capitalList").fadeOut();  
};
</script>


 <script>
$(document).ready(function(){  
    
$("#country").keyup(function(){  
    
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchCountry",  
method:"POST",  
data:{query:query},  
success:function(data)  
{  
$("#countryList").fadeIn();  
$("#countryList").html(data);  
}  
});  
}  
});  
});

function removeCountry_(val){
  
    $("#country_"+val).remove();
};


var m=0;
function readCountry_byid(id,u_name){
    m++;
$("#country").val(""); 

$("#showCountry").append("<span id='country_"+m+"'>"+u_name+" &nbsp;<i class='fa fa-trash' onclick='removeCountry_("+m+")' style='color:red'></i><input type='hidden' name='country_handles[]' id='uId'  value='"+id+"'></span>");
$("#countryList").fadeOut();  
};
</script>
<script>
$(document).ready(function(){  
    
$("#national").keyup(function(){  
    
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchNational",  
method:"POST",  
data:{query:query},  
success:function(data)  
{  
$("#nationalList").fadeIn();  
$("#nationalList").html(data);  
}  
});  
}  
});  
});

function removeNational_(val){
  
    $("#national_"+val).remove();
};


var x=0;
function readNational_byid(id,u_name){
   
    x++;
$("#national").val(""); 

$("#showNational").append("<span id='national_"+x+"'>"+u_name+" &nbsp;<i class='fa fa-trash' onclick='removeNational_("+x+")' style='color:red'></i><input type='hidden' name='national_handles[]' id='uId'  value='"+id+"'></span>");
$("#nationalList").fadeOut();  
};
</script>
<script>
$(document).ready(function(){  
    
$("#internaional").keyup(function(){  
    
var query = $(this).val();  
if(query != "")  
{  
$.ajax({  
url:"searchInternaional",  
method:"POST",  
data:{query:query},  
success:function(data)  
{  
$("#internaionalList").fadeIn();  
$("#internaionalList").html(data);  
}  
});  
}  
});  
});

function removeInternaional_(val){
  
    $("#internaional_"+val).remove();
};


var y=0;
function readInternaional_byid(id,u_name){
   
    y++;
$("#internaional").val(""); 

$("#showInternaional").append("<span id='internaional_"+y+"'>"+u_name+" &nbsp;<i class='fa fa-trash' onclick='removeInternaional_("+y+")' style='color:red'></i><input type='hidden' name='international_handles[]' id='uId'  value='"+id+"'></span>");
$("#internaionalList").fadeOut();  
};
</script>

