<link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/sb_ui.css">
<!-- start Scroll -->
<div class="col-md-12 col-lg-12 col-xs-12 scroll-bar">
	<?php

if(!empty($D->gmailcontacts[0])){?>
<div class="scroll-title">Suggestions for you select all <input type="checkbox" checked  id="chk"></div>
	<?php }else{ ?>
	<div class="scroll-title">Suggestions for you </div>

<?php }?>
<div class="col-md-8">
<div class="panel panel-primary">
<div class="panel-body scroll-limit" id="Panel1">


<div class="">

<?php
if(!empty($D->gmailcontacts[0])){
foreach($D->gmailcontacts as $keys=>$fetchresasw){
if(!empty($D->gmailcontacts[$keys])){

 ?>
	

<!-- row 1 starts -->     
<div class="col-md-12 col-lg-12 sugg-outer" id="hide-<?php echo $result->id;?>">
<!-- start column 1 -->
<div class="col-md-1 col-lg-1 sugg">
<?php  if($fetchresasw->avatar !="" ){
	$image = $fetchresasw->avatar;
	
}else{
	$image ="noimage.png";
	
}
	?>
<img src="<?php echo $C->SITE_URL; ?>storage/avatars/thumbs3/<?php echo $image;?>" class="img-responsive">
</div>
<!-- end column 2 -->

<!-- start column 1 -->
<div class="col-md-8 col-lg-8">
<?php echo $fetchresasw->fullname; ?><span class="link_blue_text">@<?php echo $fetchresasw->username; ?></span> <br /> <span class="text-small-dark-blue">
<?php echo $fetchresasw->about_me;?></span>

</div>
<!-- end column 2 -->

<!-- start column 1 -->
<div class="col-md-2 col-lg-2 sugg-hit-miss">
<input type="checkbox" name="followers" class="che" checked>
</div>
<!-- end column 2 -->


</div>
<!-- row 1 ends --> 



<?php } } }else{
	echo "Friends not found in Streetbuzz";
}


 ?>

</div>

</div>
</div>
</div>


</div>
<!-- end Scroll -->


<div class="col-md-12 col-lg-12 reg-desc-big">
<?php if(!empty($D->gmailcontacts[0])){?>

<input type="submit" class="btn btn-default btn-blue" value="Follow">
	<?php } ?>
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
$("#chk").click(function(){
            if($(this).prop("checked") == true){
            $(".che").prop("checked",true);
            }else{
            $(".che").attr("checked",false);
            }





});
$(".che").click(function(){
     var len =$(".che:checked").length;
     var cnt = <?php echo $D->finarraycount;  ?>;
     if(len == cnt){
      $("#chk").prop("checked",true);
     }else{
      $("#chk").prop("checked",false);
     }
});

});

</script>