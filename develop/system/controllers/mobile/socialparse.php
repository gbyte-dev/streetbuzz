<?PHP
 $res = $_POST['data']['feed']['entry'];


foreach($res as $reskeys=>$resvals){ 
 $result[] = $resvals['gd$email'][0]['address'];

}
$res     =implode(',',$result);
?>
<form id="gmailform" method="POST" action="<?php echo $C->SITE_URL; ?>gmailsocialparse">
 <input type="hidden" name="simply" value="<?php echo $res;?>"></input>


</form>


<script type="text/javascript">
 $("#gmailform").submit();
</script>