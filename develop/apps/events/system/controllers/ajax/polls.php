<div class="col-md-12">
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
          <a href="#eveone" role="tab" data-toggle="tab">
               All Polls
          </a>
      </li>
      <li><a href="#evetwo" role="tab" data-toggle="tab" class="myeven">
          My Polls
          </a>
      </li>
      <li>
          <a href="#evethree" role="tab" data-toggle="tab" class="acc">
             My Responses
          </a>
      </li>
    </ul>
	
	<!-- Sub tabs -->
	<!--<ul class="nav nav-tabs sub-tabs" role="tablist">
      <li class="active">
          <a href="#subeveone" role="tab" data-toggle="tab">
               All
          </a>
      </li>
      <li><a href="#subevetwo" role="tab" data-toggle="tab">
          People you follow
          </a>
      </li>
      <li>
          <a href="#subevethree" role="tab" data-toggle="tab">
              Groups you have joined
          </a>
      </li>
    </ul>-->
	
    
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane fade active in" id="eveone">
	  
    <div id="allevents">
	  {%poll_all_html%}
	  </div>
	  <input type="hidden" id="all-show-count" value="10">																																																														
	  <div class="show-more-container">
	  <a id="all-show"class="show-more"><span>Show more</span></a>
	  </div>


      </div>
      <div class="tab-pane fade" id="evetwo">

   
		   <div id="myeven">{%poll_my_html%}</div>
		    <input type="hidden" id="my-show-count" value="10">
		  <div class="show-more-container">

	  <a   id="my-show"class="show-more"><span>Show more</span></a>
	  </div>
      </div>
      <div class="tab-pane fade" id="evethree">


	  <div id="accept">{%poll_myresponse_html%}</div>
	   <input type="hidden" id="accept-show-count" value="10">
	   	  <div class="show-more-container">

	  <a   id="accept-show"class="show-more"><span>Show more</span></a>
	  </div>
           

      </div>
    </div>
	
	
	
    
</div>




<style>

/* Tab Navigation */
.nav-tabs {
    margin: 0;
    padding: 0;
    border: 0;    
}
.nav-tabs > li > a {
	color: #0099d3;
	font-weight:bold;
    border-radius: 0;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
border:0;
border-color: #FFF;
color: #66757F;
}
.nav-tabs > li > a:hover {
	background: none;
	border-color: #FFF;
}
 
 
 
 /*Sub Tabs*/
 .sub-tabs > li > a {
    background: #FFF;
	color: #0099d3;
	font-weight:normal;
    border-radius: 0;
}


/* Tab Content */
.tab-content > .tab-pane  {
    border-radius: 0;
    text-align: left;
    padding: 20px;
}

</style>
