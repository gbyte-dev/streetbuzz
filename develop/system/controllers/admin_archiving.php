<?php
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
	if(!empty($_POST)){

	}

	
	//require_once( $C->INCPATH.'helpers/func_images.php' );
	  $archiveres = $db2->query('SELECT * FROM `archive_info` order by id DESC LIMIT 10 ');

	  $archivalhtml ='';

	  if($archiveres->num_rows > 0){
	      $row =0;
	  while($dbarchiveres =$db2->fetch_object($archiveres) ){
	       $epoch1 = $dbarchiveres->end_date;
	       $epoch2 = $dbarchiveres->start_date;
	        $epoch3 = $dbarchiveres->created_date;
       $dt1 = new DateTime("@$epoch1");  // convert UNIX timestamp to PHP 
       $end_date =  $dt1->format('M d,y'); // output = 2017-01-01 00:00:00
       $dt2 = new DateTime("@$epoch2");  // convert UNIX timestamp to PHP 
       $start_date =  $dt2->format('M d,y'); // output = 2017-01-01 00:00:00
         $dt3 = new DateTime("@$epoch3");  // convert UNIX timestamp to PHP 
       $created_date =  $dt3->format('M d,y H:i:s A');
	      $archivalhtml .='<tr>
    <td>'.($row+1).'</td>
    <td>'.$start_date.'</td>
    <td>'.$end_date.'</td>
     <td>'.$created_date.'</td>
  </tr>';
   $row++;

	  }
	  }


	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();

        
        
    $datares    	.='<div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			
			
		</div>
		<div id="center-container">
		<div class="system-message success suc-mes" style="display:none">
					<strong>Done</strong>Information was saved.
				</div>
			
			<h3>Archiving Info</h3>(Better to take 15 days data)<form ><table class="form-container "><tbody>
				<tr>
					<td class="field-title"><label for="network_intro_title">Start date<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="month_start_date" name="start_date" autocomplete="off"> </td>
				</tr><tr>
					<td class="field-title"><label for="network_intro_txt">End date<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" id="month_end_date" name="end_date"  maxlength="50" autocomplete="off" ></td>
				</tr>
		
				
				<tr>
				<td></td>
				<td><input type="button"  class="btn blue cron" value="Save"></input></td>
				</tr></tbody></table></form>
				<div><h3>Recent Activities:</h3></div>
			<table>
  <tr>
    <th>Sno</th>
    <th>Start Date</th>
    <th>End Date</th>
     <th>Created Date</th>
  </tr>
  '.$archivalhtml.'
 

</table>

			
		</div>
	</div>
	</div>
	
	';
  $newscontent =''.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery-ui.js?v=3.6.0"></script>';


$newscontent .='
<style>
.loader {
  border: 12px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<script type="text/javascript">
$( "#month_start_date" ).datepicker();
//$( "#month_end_date" ).datepicker();
/* $( "#month_start_date" ).datepicker({
maxDate: "-3M",
        onSelect: function() {
            var date = $(this).val();
            $( "#month_end_date" ).val(date);
        }
    });*/
  //  $( "#month_end_date" ).datepicker({maxDate: "-3M"});
  $( "#month_end_date" ).datepicker();

$(".cron").click(function(){
$(this).attr("disabled",true);
$(this).addClass("loader");
var month_start_date =$("#month_start_date").val();
var month_end_date =$("#month_end_date").val();


	var url = "'.$C->SITE_URL.'/addarchiving"; 
			jQuery.ajax({
				type:"POST",
				data:{month_start_date:month_start_date,month_end_date:month_end_date},
				cache:false,
				dataType: "json",
               url: url
				}).done(function (response) {
          				if(response.status == 200){
          				$(".suc-mes").css("display","block");
          				$(".cron").attr("disabled",false);
                       $(".cron").removeClass("loader");
				    
				}
	            });
            });
    </script>';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>