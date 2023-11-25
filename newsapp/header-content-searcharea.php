<?php 
			if($D->tab =='posts'){
				$str ='Buzzes';
			}elseif($D->tab =='users'){
				$str ='Accounts';
			}elseif($D->tab =='groups'){
				$str ='Groups';
				
			}elseif($D->tab =='location'){
				$str ='Location';
				
			}else{
				$str ='Buzzes';
				
			}
			?>
<div id="header-search">
	<form id="searchForm" method="post" action="<?= $C->SITE_URL ?>search">
			<div class="input-group">
			<input type="hidden" id="hserch" name="serchtab" value="<?php echo $D->tab?>" />
			<input type="hidden" name="defaultval" value="<?php echo $D->tab?>" />
			<input type="text" class="form-control search-field"  style="position:relative;" name="lookfor" class="form-control search-field" value="<?=isset($D->search_string) ? htmlspecialchars($D->search_string):$this->page->lang('network_header_search_input_txt')?>" x-webkit-speech autocomplete="off" onwebkitspeechchange="STX.searchReplace();" data-watermark="<?= $this->page->lang('network_header_search_input_txt') ?>" placeholder="Search..." />
			<div class="input-group-btn">
				<button class="btn btn-default btn-xs" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
			
				
				<?php  if(!empty($D->customuserlogged)){?>
		<div class="searchselect dropdown search-drop ">
			<a href="" class="menu-btn"><?php echo $str;?>&#9660;</a> 
			<ul class="menu-options">
				
                <li class="ctypes" data-type="posts"><a href="" data-type="posts">Buzzes</a></li>
				<li class="ctypes" data-type="users"><a href="" data-type="users">Accounts</a></li>
				<li class="ctypes" data-type="groups"><a href="" data-type="groups"><?= $this->page->lang('hdr_search_groups') ?></a></li>
				<li class="ctypes" data-type="location"><a href="" data-type="location">Location</a></li>
						</ul>
		</div>	

				<?php }else{ ?>
				
        <div class="searchselect dropdown search-drop ">
			<a href="" class="menu-btn"><?php echo $str;?>&#9660;</a> 
			<ul class="menu-options">
				
                <li class="ctypes" data-type="posts"><a href="" data-type="posts">Buzzes</a></li>
				<li class="ctypes" data-type="users"><a href="" data-type="users">Accounts</a></li>
				<li class="ctypes" data-type="groups"><a href="" data-type="groups"><?= $this->page->lang('hdr_search_groups') ?></a></li>
				<li class="ctypes" data-type="location"><a href="" data-type="location">Location</a></li>
                		</ul>
		</div>	
	        	<?php  
				$url= $_SERVER['REQUEST_URI'];  
                if (strpos($url, 'search') !== false) {
				 ?>

	<script type="<?php echo $C->SITE_URL ; ?>"> </script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/jquery.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/jquery-ui.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/plugins/jquery.address.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/plugins/jquery.ajaxupload.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/plugins/jquery.hoverintent.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/plugins/jquery.colorbox.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/plugins/jquery.jcarousel.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/common.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/services.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/htmlarea.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/attachments.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/activities.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/comments.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/users.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/administration.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/notifications.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/groups.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/dialogs.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/htmlarea_asset.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/cookie.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>static/js/push.min.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/flatui-radio.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/bootstrap-select.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/run.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/icon-font-ie7.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/bootstrap-switch.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/flatui-checkbox.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/bootstrap.min.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/html5shiv.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/parallax.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/scrollup/static/js/scrollup.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/stickymessage/static/js/stickymessage.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/postreshare/static/js/postreshares.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/specialgroups/static/js/specialgroups.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/spamprotector/static/js/spamprotector.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/poll/static/js/poll.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/events/static/js/common.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/groupscarousel/static/js/images?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/groupscarousel/static/js/MetroJs.min.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/groupscarousel/static/js/MetroJs.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/userscarousel/static/js/images?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/userscarousel/static/js/MetroJs.min.js?v=3.6.0"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/userscarousel/static/js/MetroJs.js?v=3.6.0"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDArYN-93IBn3EBtCMXBoSoznKr3F1wPxE&libraries=places&v=2.exp"></script>
	
	
	<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>themes/FishingEnthusiastTheme/js/parallax.js"></script>
	<script src="<?php echo $C->SITE_URL ; ?>static/js/textareaeditor.js?v=3.6.0"></script>
                <?php } } ?>
	
	</form>
</div>	
	