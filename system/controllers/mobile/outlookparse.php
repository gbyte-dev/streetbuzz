<?PHP
 $res = $_POST['data'];
  $userid = $_POST['userid'];
  $password= ($_POST['password']);


foreach($res as $reskeys=>$resvals){ 
 $result[] = $resvals['emails']['personal'];

}

$res     =implode(',',$result);
?>
<form id="gmailform" method="POST" action="<?php echo $C->SITE_URL; ?>gmailregister">
 <input type="hidden" name="simply" value="<?php echo $res;?>"></input>
  <input type="hidden" name="userid" value="<?php echo $userid;?>"></input>
   <input type="hidden" name="password" value="<?php echo $password;?>"></input>


</form>


<script type="text/javascript">
 $("#gmailform").submit();
</script>