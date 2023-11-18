<?php
if(isset($_POST['input'])){
$reason =$_POST['input'];
$categeory =$_POST['predict'];
$historical_query            =$db2->query('SELECT reason FROM predict_reason WHERE reason like "%'.$reason.'%" AND  categeory like"%'.$categeory.'%"  order by id desc LIMIT 5');
	


?>
<ul id="country-list">
<?php while($res = $db2->fetch_object($historical_query)){ ?>

<li onClick="selectCountry('<?php echo $res->reason; ?>');"><?php echo $res->reason; ?></li>
<?php } ?>
</ul>
<?php }
if(isset($_POST['group'])){
	$grp = $_POST['group'];
	$group_query            =$db2->query('SELECT id,title FROM  groups WHERE groupname like "%'.$grp.'%" OR 	title like "%'.$grp.'%"    order by id desc LIMIT 5');
	?>
	<ul id="country-list">
<?php while($res = $db2->fetch_object($group_query)){ ?>

<li onClick="selectgrp('<?php echo $res->title; ?>');"><?php echo $res->title; ?></li>
<?php } ?>
</ul>

	
<?php }
if(isset($_POST['poll_group'])){
	$grp = $_POST['poll_group'];
	$group_query            =$db2->query('SELECT id,title FROM  groups WHERE groupname like "%'.$grp.'%" OR 	title like "%'.$grp.'%"    order by id desc LIMIT 5');
	?>
		<ul id="country-list">
<?php while($res = $db2->fetch_object($group_query)){ ?>

<li onClick="selectpollgrp('<?php echo $res->title; ?>');"><?php echo $res->title; ?></li>
<?php } ?>
</ul>
	
<?php }

 ?>
