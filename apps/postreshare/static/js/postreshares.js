var Postreshare = function () {

    // --- declare private methods --- //
	var plugin = 'postreshare'
	
	function reshareSuccess(response, context) {
		if( response.status == 'ERROR' ){
			commandFail(response, context);
			return;
		}
		$(context).parents('.reshare-list').replaceWith(response.html);
	}
	
	function showResharesSuccess(response, context) {
		if( response.status == 'ERROR' ){
			commandFail(response, context);
			return;
		}
		Dialogs.alert(response.html, "Show");
	}

	function unreshareSuccess(response, context) {
		if( response.status == 'ERROR' ){
			commandFail(response, context);
			return;
		}
		$(context).parents('.reshare-list').replaceWith(response.html);
	}
	
	function commandFail(response, context) {
        STX.showMessage(response.message, "error");
		//console.log(error);
    };
    
    
    // --- declare public methods --- //
    return {

    	reshare: function(el, value, event) {
    		var args = {
					module: 'postreshare',
					action: 'reshare',
					data: value
				}
			Services.invoke(args, reshareSuccess, commandFail, el);
        	
        },
        
        unreshare: function(el, value, event) {
        	var args = {
					module: 'postreshare',
					action: 'unreshare',
					data:  value
				}
			Services.invoke(args, unreshareSuccess, commandFail, el);
        },
        
        showreshares: function(el, value, event) {
        	var args = {
					module: 'postreshare',
					action: 'showreshares',
					data:  value
				}
			Services.invoke(args, showResharesSuccess, commandFail, el);
        }

    }
} ();