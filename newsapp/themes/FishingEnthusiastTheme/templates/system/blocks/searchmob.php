<div class="box-inner">
<!-- Start: Search Box -->

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
<div id="header-search-mob">
	<form id="searchForm" method="post" action="<?= $C->SITE_URL ?>search">
			<div class="input-group">
			<input type="hidden" id="hserch-mob" name="serchtab" value="<?php echo $D->tab?>" />
			<input type="hidden" name="defaultval" value="<?php echo $D->tab?>" />


			<input type="text" class="form-control search-field-mob"  style="position:relative;" name="lookfor" value="<?=isset($D->search_string) ? htmlspecialchars($D->search_string):$this->page->lang('network_header_search_input_txt')?>" x-webkit-speech autocomplete="off" onwebkitspeechchange="STX.searchReplace();" data-watermark="<?= $this->page->lang('network_header_search_input_txt') ?>" placeholder="Search"  />
			<div class="input-group-btn">
				<button class="btn btn-default btn-xs" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
			

			<div class="searchselect dropdown search-drop search-drop-mob">

			<a href="" class="menu-btn"><?php echo $str;?></a> 
			<span class="glyphicon glyphicon-chevron-down" style="top:2px; color:grey"></span>
			<ul class="menu-options" style="line-height: 30px;">
				<li class="ctypes" data-type="posts"><a href="" data-type="posts"><?= $this->page->lang('hdr_search_posts') ?></a></li>
				<li class="ctypes" data-type="users"><a href="" data-type="users"><?= $this->page->lang('hdr_search_users') ?></a></li>
				<li class="ctypes" data-type="groups"><a href="" data-type="groups"><?= $this->page->lang('hdr_search_groups') ?></a></li>
				<li class="ctypes" data-type="location"><a href="" data-type="location"><?= $this->page->lang('hdr_search_location') ?></a></li>
			</ul>
			</div>	


	</form>
</div>
	
<!--/ End: Search Box -->



<!-- START : What is Buzzing -->
			         <div class="box-inner">
					 {%left_content%}
			<div class="col-md-12 box-footer">
			</div>
			
			</div>
<!--/ End: What is Buzzing -->

</div>

