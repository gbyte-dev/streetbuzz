<script>
		var k=0;
		$('#calendar1').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			viewRender: function (view) {
				var h;
				if (view.name == "month") {
					h = NaN;
				}
				else {
					h = 2500;  // high enough to avoid scrollbars
				}

				$('#calendar1').fullCalendar('option', 'contentHeight', h);
			},
			eventClick: function(data1, event, view) {
				var content = 'Invalid Key';
				$.ajax({
					url:siteurl + 'services/events/vieweventmytabs',
					type: 'POST',
					data: {
						event: data1.id,
						group : $('#group_id').val(),
						username : $('#username').val()
					},
					success: function(response) {
						content = response.data.html;
						$("#content1").html(content);
						/*$.colorbox({
							width: '700px',
							html: content,
							open: true
						});*/
					}
				});
			},
			eventAfterAllRender: function(view){
				group_val = $('#group_id').val();
				if(!group_val){
					eventTextColor : '#000 !important';
				}				
			},
			eventMouseover : function(data, event, view) {
				if (view.name != "month") {
					var content = '<div class="row_tip"><h2><b>'+data.title+'</b></h2></div>'+
					'<div class="row_tip"><label>Type</label>: <span>'+data.event_type+'</span> </div>'+
					'<div class="row_tip"><label>Description</label>: <span>'+data.event_description+'</span> </div>'+
					'<div class="row_tip"><label>Address</label>: <span>'+data.address+'</span> </div>'+
					'<div class="row_tip"><label>Start Time</label>: <span>'+data.start_disp+'</span> </div>'+
					'<div class="row_tip"><label>End Time</label>: <span>'+data.end_disp+'</span> </div>';
					//$(this).css("top", (event.pageY-150));
					$(this).tooltipster({contentAsHTML: true});
					$(this).tooltipster('content', content);
					$(this).tooltipster('reposition', event);
					$(this).tooltipster('show', event);
				}
			},	
			eventResizeStart: false,
			editable: false,
			selectable: false,
			selectHelper: true,
			select: function(start, end) {
				var group_id=$('#group_id').val();
				if(group_id){
					window.location.href = siteurl+"plugin/events/add_event/start:"+start.unix()+"/end:"+end.unix()+"/group:"+group_id;
				}else{
					window.location.href = siteurl+"plugin/events/add_event/start:"+start.unix()+"/end:"+end.unix();
				}
			},
			events: function(start, end, timezone, callback) {
				$.ajax({
					type: 'post',
					url:siteurl + 'services/events/getevents',
					data: {
						// our hypothetical feed requires UNIX timestamps
						group : $('#group_id').val(),
						username : $('#username').val(),
						start: start.unix(),
						end: end.unix(),
						tab: 'myevent',
                                                dashboard: true,
					},
					success: function(response) {
						var events = [];
						$(response.data.events).each(function() {
							events.push({
								id: $(this).attr('id'),
								title: $(this).attr('title'),
								backgroundColor: $(this).attr('backgroundColor'),
								className: $(this).attr('className'),
								textColor: $(this).attr('textColor'),
								event_type: $(this).attr('event_type'),
								event_name: $(this).attr('event_name'),
								event_description: $(this).attr('event_description'),
								address: $(this).attr('address'),
								start_disp: $(this).attr('start_disp'),
								end_disp: $(this).attr('end_disp'),
								start: $(this).attr('start'),
								end: $(this).attr('end') // will be parsed
							});
						});
						callback(events);
					}
				});
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			},
			eventResize: function(event) {
			   var start = event.start.unix();
			   var end = event.end.unix();
			   $.ajax({
					url:siteurl + 'services/events/updateevents',
					data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
					type: "POST",
					success: function(json) {
						alert("Updated Successfully");
					}
			   });
			},
			 droppable: false,
			eventDrop: function(event, delta) {
			var start = event.start.unix();
			   var end = event.end.unix();
				$.ajax({
					url:siteurl + 'services/events/updateevents',
					data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
					type: "POST",
					success: function(response) {
						if(response.data.events=='success'){
							alert('Event Saved');
						}else{
							alert('Not saved.');
						}
					}
				});	
			}
		});
	
</script>
<div id="content1" class="col-xs-12 content table " ></div>

<div id='calendar1'></div>
<div id="check_data"></div>
