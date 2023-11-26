var Spamprotector = function () {

    // --- declare private methods --- //
	var plugin = 'spamprotector'
	
	function markSuccess(response, context) {
       var user =$("#actuserid").val();
		if(user ==''){
		   var actpollid =$("#actpollid").val();

			var title='Sign up';
		   	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:actpollid,title:title},
					url:siteurl+"sighnuppopup",

					success:function(msg){

						 $('#replaypopup-'+actpollid).html('');
						 $('#replaypopup-'+actpollid).html(msg);
						 $('#replaypopup-'+actpollid).modal('show'); 
						
						
					}
				});

			return false;
        }
		if(response.status!='ERROR'){
			$(context).parents('.like-list').replaceWith(response.html);
		}else{
			STX.showMessage(response.message, "error");
			//STX.showMessage(response.message, "error");
		}
		
	}
	
	function commandFail(response, context) {
		STX.showMessage(response.message, "error");
		//console.log(error);
    };
    
    
    // --- declare public methods --- //
    return {

    	mark: function(el, value, event) {
    		var args = {
					module: 'spamprotector',
					action: 'mark',
					data: value
				}
			Services.invoke(args, markSuccess, commandFail, el);
        	
        },
        
        unmark: function(el, value, event) {
    		var args = {
					module: 'spamprotector',
					action: 'unmark',
					data: value
				}
			Services.invoke(args, markSuccess, commandFail, el);
        	
        },

    }
} ();