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
			<div class="searchselect dropdown search-drop ">
			<a href="" class="menu-btn"><?php echo $str;?>&#9660;</a> 
			<ul class="menu-options">
				<li class="ctypes" data-type="posts"><a href="" data-type="posts">Buzzes</a></li>
				<?php  if(!empty($D->customuserlogged)){?>

				<li class="ctypes" data-type="users"><a href="" data-type="users">Accounts</a></li>
				<li class="ctypes" data-type="groups"><a href="" data-type="groups"><?= $this->page->lang('hdr_search_groups') ?></a></li>
				<li class="ctypes" data-type="location"><a href="" data-type="location">Location</a></li>
				<?php } ?>

			</ul>
			</div>	
	</form>
</div>
	
	