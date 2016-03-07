var payment_window_vtemplate = new VTemplate({
	tmpName : 'payment_window'
});

payment_window_vtemplate.ajaxFindClient = new serverRequest({
	url : '/',
	dataType : 'json',
	data : {action : 'find_client_by_phone'},
	success : function(response){
		console.log(response);
		if(response.find.length > 0){
			var render = {user : response.find[0]};
			payment_window_vtemplate.render(render);
		}
		
		
	},
	error : function(response){
		console.log('bad');
	}
});

payment_window_vtemplate.scan_started = false;

payment_window_vtemplate.eventFunctions = {
		payment_okhandler : function(event){
			console.log('okHandler');
		},
		on_user_data_confirm_check : function(event){
			payment_window_vtemplate.removeEvent(event.target, 'change', payment_window_vtemplate.eventFunctions.on_user_data_confirm_check)
			document.querySelector('button._submit_auth_button').click();
			
			
			
		},
		on_client_auth_submit : function(event){
			event.preventDefault();
			console.log('some submit request');
		},
		on_keypress_phone_verife : function(event){
			console.log(event.target.value);
			if(event.target.value.length == 12){
				payment_window_vtemplate.ajaxFindClient.data.phone = event.target.value;
				payment_window_vtemplate.ajaxFindClient.send();
			}else
				return false;
		}
}
payment_window_vtemplate.functions = {
		scan_inputs : function(){
			if(payment_window_vtemplate.scan_started){
				return false;
			}
			payment_window_vtemplate.scan_started = true;
			payment_window_vtemplate.scan_res = setInterval(function(){
				var input_elements = document.querySelectorAll('form._client_auth_form input[type="text"]');
				var confirm = 0;
				for(var i = 0; i < input_elements.length; i++){
					var val = input_elements[i].value;
					if(val.length > 1)
						confirm++;
				}
				if(confirm ===4){
					clearInterval(payment_window_vtemplate.scan_res);
					payment_window_vtemplate.scan_started = false;
					$('input._confirm_checkbox').prop('disabled', false);
				}
			}, 1000);
		}
}