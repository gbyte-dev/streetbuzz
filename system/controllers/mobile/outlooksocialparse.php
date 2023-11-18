<?php

 $res = $_POST['data'];
 foreach($res as $reskeys=>$resvals){ 
  $email[]= $resvals['emails']['personal'];
  
  
  }
  $res     =implode(',',$email);


?>
<form id="gmailform" method="POST" action="<?php echo $C->SITE_URL; ?>outlookfindpeopleparse">
 <input type="hidden" name="simply" value="<?php echo $res;?>"></input>


</form>


<script type="text/javascript">
 $("#gmailform").submit();
</script>