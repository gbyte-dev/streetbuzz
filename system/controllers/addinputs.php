<?php
$a ='	<div class="row">
  <div class="col-md-3"><input type="text" placeholder="Sequence" name="sequence[]"></div>

  <div class="col-md-3"><input type="text" placeholder="Post id" name="postid[]"></div>
   <div class="col-md-2"><select name="mainpost[]"><option>Main Post ?</option>
  <option value="YES">YES</option><option value="NO">NO</option>
  </select></div>

   <div class="col-md-4"><input type="button" value="Delete" class="delete" onclick="deletee34()"style="color: red;
    border: 1px solid red;
    border-radius: 5px;"></button></div>
</div>
<script>
$(".delete").click(function(){
 $(this).closest(".row").remove()});


</script>';
echo $a;

?>