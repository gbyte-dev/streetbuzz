
<style>
    .signin-page{
        padding-top:8%;
    }
    
    .row {
 margin-right: 0px !important;
  margin-left: 0px !important; 
}
   
    @media only screen and (max-width: 700px) {
  .signin-page {
     
     margin:10%;
  }
  .headerme{
    display: none;  
  }
}

</style>
<div class="top80">
{%coverimage%}
 <div class="container-fluid">
  <div class="col-lg-3 col-md-3 left_column_handler">
 
  <div id="left-area" class="sidebar left_column_handler hidden-xs hidden-sm z-indexed">

 
    <div class="col-lg-12 col-md-12 side-bar-area" style="padding:0">
		{%left_content_placeholder%}
		<?php 
			 		
					$url = $_SERVER["REQUEST_URI"];
					if (strpos($url, 'search') !== false) { ?>
					<div class="box_tags">
											{%left_content%}
											</div>

					<?php }else{ ?>
						{%left_content%}
					<?php } ?>
					
  
	
		<!-- {%left_content_bottom%} -->

	</div>
	</div>
	</div>
	
	
	 <div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			{%main_content_top_placeholder%}
			{%subheader_placeholder%}
		</div>
		<div id="center-container">
			{%main_content_placeholder%}
			{%main_content%}
			{%main_content_bottom%}
		</div>
	</div>
	</div>

	



  <div class="col-md-3 left_column_handler">
 
  <div id="left-area" class="sidebar left_column_handler hidden-xs hidden-sm z-indexed">

 
    <div class="col-md-12 side-bar-area" style="padding:0">
		{%right_content_placeholder%}
		<!--{%right_content%}-->
		<!--{%right_content_bottom%}-->

	</div>
	</div>
	</div>
	


	</div>
	</div>
	
		
	<div class="clear"></div>
	
