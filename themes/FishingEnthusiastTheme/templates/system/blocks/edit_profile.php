{%success%}
{%unsuccess%}

<!-- <div id="center-container"> -->
<div>
<?php  $year =date("Y");
?>
	
<div class="box">
                     <div class="box-inner">
                     <div class="box-title">
           Profile Information
            </div>
            <div class="box-sub-desc">
                
            
    <form action="" method="POST" enctype="multipart/form-data">
    <!-- start form -->
    <div class="row">
    <div class="col-md-2 hidden-xs hidden-sm">
    <!-- for center alignment and spacing -->
    </div>
    <div class="col-md-8">
    <div class="col-md-12">

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Name</span> <br />
    {%fullname%}
    </div>

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Location</span> <br />
    {%location%}
    </div>

    <div class="col-md-11" style="padding:0">
    <ul class="list-inline">
    <li>
    <span class="add-more">Birthday</span> <br />
    <select id="profile_birth_day" name="day" class="select-form" style="width:100px">
    {%birthdayday%}
    </select>
    </li>
    <li>
    <select id="profile_birth_month" name="profile_birth_month" class="select-form" style="width:120px">
    {%month%}
    </select>
    </li>
    <li>
    <select id="profile_birth_year" name="profile_birth_year" class="select-form" style="width:120px">
    {%birthdayyear%}
    </select>
    </li>
    </ul>
    </div>

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Current Picture</span> <br />
    {%img%}
    </div>

    <div class="col-md-11" style="padding:0; padding;top:10px;">
    <span class="add-more">Change Picture</span>
    <div class="choose_file"> 
    <span><span class="glyphicon glyphicon-upload"></span> Choose File</span>
    <input type="file" class="form-control-file"   capture="camera" name="profile_avatar" onchange="readURL(this);" >
    </div> 
    <span class="add-more" style="color:red;">(JPEG, GIF or PNG; 200x200px or larger)</span>
    </div>
<input id="imageHolder" type="file" accept="image/*" capture />
    

    <div class="col-md-11" style="padding:0; padding-top:20px;">
    <span class="add-more">Referral Type</span> <br />
    <select id="referal_type" name="referal_type" class="select-form">
    {%refer%}
    </select>
    </div>

    <div class="col-md-11" style="padding:20px 0px 50px 0px">
    <button type="submit" name="sbm" class="btn blue"><span>Save</span></button>
    </div>

    

    </div>
    </div>
    </div>
    </form>

            </div>          
            
            </div></div>






</div><!--/ center-container -->



		<script type="text/javascript">
		function sample(){
			window.open();
		}
		  function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(50)
                        .height(50);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
		</script>


	<style>
	.select-form {
	font-size: 12px;
    border-radius: 5px;
    display: block;
    margin-bottom: 10px;
    border: 1px solid #BFE0EC;
    padding-left: 5px;
    color: #666;
    width:100%
	}
	.form-control-file {
	border: 1px solid #BFE0EC;
	}

	.choose_file{
    position:relative;
    display:block;    
    width: 120px;
    border-radius:8px;
    border:#ebebeb solid 1px;
    padding: 4px 6px 4px 8px;
    font: normal 14px Myriad Pro, Verdana, Geneva, sans-serif;
    color: #7f7f7f;
    margin-top: 2px;
    background:white
}
.choose_file input[type="file"]{
    -webkit-appearance:none; 
    position:absolute;
    top:0; left:0;
    opacity:0; 
}
	</style>