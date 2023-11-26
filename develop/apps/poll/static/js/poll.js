// Poll namespace
var Poll = function () {

    // --- declare private methods --- //	
	function _init() {
		appBackClick();
		appResultsClick();
		appVoteSubmitClick();
		appQuestionClick();
		appUserAnswerClick();
		
		adminDeleteClick();
		adminQuestionClick();
		adminNewAnswerClick();
    }
	
	// app
	function appUserAnswerClick() {
		$('.poll-app ul').click( function(event) {
			var target = $(event.target);
			if (target.is("input[type=radio]")){
				if (target.val() == '0') 
					target.next().attr('disabled', false);
				else
					$(this).find('#user_answer').attr('disabled', true);
			}
		});
	}
	
	function appQuestionClick() {			
		$('.poll-app .poll-question-wrap > a').each( function(index) {
			$(this).click( function(event) {				
				event.preventDefault();			
			});	
		});
		
		$('.poll-app .poll-question-wrap').each( function(index) {			
			$(this).click( function(event) {				
				var icon = $(this).find('.icon');
				if (icon.hasClass('icon-down'))
					icon.removeClass('icon-down').addClass('icon-right');
				else
					icon.removeClass('icon-right').addClass('icon-down');
				$(this).next().toggleClass( "display-none" );
			});	
		});
	}
	
	function appBackClick() {
		$('.poll-app a[data-name=poll-btn-back]').click( function(event) {
			event.preventDefault();
			var div = $('div[data-poll-id=' + $(this).attr('data-id') + ']');
			div.find('div[data-name=poll-results-wrap]').toggleClass( "display-none" );
			div.find('div[data-name=poll-votes-wrap]').toggleClass( "display-none" );
		});
	}
	
	function appVoteSubmitClick() {		
		$('.poll-app form').submit(function( event ) {
			var div = $('div[data-poll-id=' + $(this).attr('data-id') + ']');
			var errorMsg = '';			
			var poll_answer_id = div.find('input[name=poll_answer_id]:checked');
			var user_answer = div.find('#user_answer');
			
			div.find('div[data-name=poll-votes-error]').remove();
			
			if (user_answer.val() === undefined && poll_answer_id.val() === undefined) {
				errorMsg += 'Please select an answer.';				
			}
			
			if ((user_answer.val() !== undefined && poll_answer_id.val() === undefined) || 
				(user_answer.val() !== undefined && poll_answer_id.val() == '0' && user_answer.val() == '')) {
				errorMsg += 'Please select an answer or enter your own.';
			}
			
			if (errorMsg != '') {				
				event.preventDefault();
				var msg = "<div class='system-message error' data-name='poll-votes-error'><ul class='poll_error'>";
				msg += "<li>" + errorMsg + "</li>";
				msg += "</ul></div>";
				div.find('.message').html(msg);
			}
		});
	}
	
	function appResultsClick() {
		$('.poll-app a[data-name=poll-btn-results]').click( function(event) {
			event.preventDefault();
			var div = $('div[data-poll-id=' + $(this).attr('data-id') + ']');
			div.find('div[data-name=poll-results-wrap]').toggleClass( "display-none" );
			div.find('div[data-name=poll-votes-wrap]').toggleClass( "display-none" );			
			div.find('div[data-name=poll-votes-error]').remove();
		});
	}
	
	// admin
	function adminNewAnswerClick() {
		$('.poll-admin a[data-name=add-new-answer]').click( function(event) {
			event.preventDefault();
			var newAnswerNumber = $('.answers > div').length + 1;
			var div = "<div class='form-group'>" +
							"<label>Answer " + newAnswerNumber + ":</label>" +
							"<input type='text' name='answer[]' value='' class='form-control' >" +
						"</div>";
			$('.answers').append(div);
		});
	}
	
	function adminDeleteClick() {
		$('.poll-admin a[data-name=poll-delete]').each( function(index) {
			$(this).click( function(event) {
				event.preventDefault();
				var href = $(this).attr("href");				
				var callback = function(){
					window.location.href = href;
				}
				Dialogs.confirmYesNo('Are you sure you want to delete this poll?', callback);
			});			
		});
	}
	
	function adminQuestionClick() {
		$('.poll-admin div[data-name=poll-question] > a').each( function(index) {
			$(this).click( function(event) {
				event.preventDefault();			
			});	
		});
		
		$('.poll-admin div[data-name=poll-question]').each( function(index) {			
			$(this).click( function(event) {
				var icon = $(this).find('.icon');
				if (icon.hasClass('icon-down'))
					icon.removeClass('icon-down').addClass('icon-right');
				else
					icon.removeClass('icon-right').addClass('icon-down');				
				$(this).next().toggleClass( "display-none" );
			});	
		});
	}
    
    // --- declare public methods --- //
    return { 
    	init: _init
    }
} ();

$(document).ready(function() {
	Poll.init();
});