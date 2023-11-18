var Specialgroups = function () {

    // --- declare private methods --- //
	var plugin = 'specialgroups'
	
	function removegroupSuccess(response, context) {
		$(context).parent().parent().remove();
		Dialogs.alert(response.html, "Done");
	}
	
	function commandFail(response, context) {
        STX.showMessage(response.message, "error");
		//console.log(error);
    };
    
    
    // --- declare public methods --- //
    return {

    	removespecialgroup: function(el, value, event) {
    		var args = {
					module: 'specialgroups',
					action: 'removespecialgroup',
					data: value
				}
			Services.invoke(args, removegroupSuccess, commandFail, el);
        	
        }

    }
} ();