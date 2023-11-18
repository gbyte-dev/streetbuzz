
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
          <a  data-target="#eveone" role="tab" data-toggle="tab" >
               All Events
          </a>
      </li>
      <li><a   data-target="#evetwo"  eveonerole="tab" data-toggle="tab" class="myeven">
          My Events
          </a>
      </li>
      <li>
          <a  data-target="#evethree"  role="tab" data-toggle="tab" class="acc">
              Accepted Invites
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

      <div class="tab-pane fade active in" id="eveone" >

	  <div><a  id="aleve" title="Calendar View" class="btn btn-primary calander-view"><span>Calendar View</span></a></div>
	<a data-target="#eveone1" role="tab" data-toggle="tab" class="btn btn-primary calander-view">
        List View
          </a>
     
    <div id="allevents">
	  {%allevents%}
	  </div>


      </div>

      <div class="tab-pane fade" id="evetwo">
	   <div><a  id="myevents" title="Calendar View" class="btn btn-primary calander-view"><span>Calendar View</span></a></div>
   
		   <div id="myeven">{%myevents%}</div>
      </div>
      <div class="tab-pane fade" id="evethree">
	  	   <div><a  id="acceptevents" title="Calendar View" class="btn btn-primary calander-view"><span>Calendar View</span></a></div>

	  <div id="accept">{%acceptevents%}</div>
           

      </div>
    </div>
	
	
	<!-- Sub tab panes -->
  <!-- <div class="tab-content">
      <div class="tab-pane fade active in" id="subeveone">
          <p>All contents here</p>
      </div>
      <div class="tab-pane fade" id="subevetwo">
          <p>People you follow contents here</p>
      </div>
      <div class="tab-pane fade" id="subevethree">
          <p>Groups you have joined contents here</p>
      </div>
    </div> -->
    
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
