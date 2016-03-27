var payment_window_vtemplate = new VTemplate({
	tmpName : 'payment_window'
});

payment_window_vtemplate.ajaxFindClient = new serverRequest({
	url : '/',
	dataType : 'json',
	data : {action : 'find_client_by_phone'},
	success : function(response){
		if(response.find.length > 0){
			var render = {user : response.find[0]};
			payment_window_vtemplate.render(render);
			$('button._submit_auth_button').show();
			document.getElementById('operationType').value = 'auth';
			$('div._register_fields').hide();
			TEMPLATE.showNotice(TEMPLATE.lang.js_msg_user_found, 'info');
		}else{
			$('div._register_fields').show();
			TEMPLATE.showNotice(TEMPLATE.lang.js_msg_input_user_data, 'info');
			document.getElementById('operationType').value = 'registration';
		}
		
		
	},
	error : function(response){
		console.log('bad');
	}
});

payment_window_vtemplate.ajaxRegisterClient = new serverRequest({
	url : '/',
	dataType : 'json',
	data : {action : 'login_client'},
	success : function(response){
		if(response.status == 'ok'){
			payment_window_vtemplate.functions.show_sms_field();
			$('#InputSMSCode').focus();
			if(!!response.type && response.type == 'smsconfirm'){
				$(document).trigger('onLogin');
				TEMPLATE.showNotice(response.message, 'info');
				payment_window_vtemplate.functions.hide_sms_field();
			}else if(!!response.type && response.type == 'auth'){
				TEMPLATE.showNotice(response.message, 'info');
				document.getElementById('operationType').value = 'smsconfirm';
			}
			console.log('login_action ok');
		}else if(response.status == 'bad'){
			payment_window_vtemplate.functions.hide_sms_field();
			TEMPLATE.showNotice(response.message, 'error');
			TEMPLATE.showNotice(TEMPLATE.lang.js_msg_try_again, 'info');
			document.getElementById('operationType').value = 'auth';
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
			
			
			
			
		},
		on_client_auth_submit : function(event){
			event.preventDefault();
			clearInterval(payment_window_vtemplate.scan_res);
			with(payment_window_vtemplate){
				scan_started = false;
				ajaxRegisterClient.data.operation = document.getElementById('operationType').value;
				if(ajaxRegisterClient.data.operation == 'auth'){
					ajaxRegisterClient.data.phone = document.getElementById('InputPhone').value;
				}else if(ajaxRegisterClient.data.operation == 'registration'){
					ajaxRegisterClient.data.phone = document.getElementById('InputPhone').value;
					ajaxRegisterClient.data.firstname = document.getElementById('InputFirstname').value;
					ajaxRegisterClient.data.secondname = document.getElementById('InputSecondname').value;
					ajaxRegisterClient.data.lastname = document.getElementById('InputLastname').value;
				}else if(ajaxRegisterClient.data.operation == 'smsconfirm'){
					ajaxRegisterClient.data.smscode = document.getElementById('InputSMSCode').value;
					if(ajaxRegisterClient.data.smscode == '')
						return false;
				}
				ajaxRegisterClient.send();
			}
			
			
		},
		on_keypress_phone_verife : function(event){
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
			payment_window_vtemplate.button_visible = false;
			payment_window_vtemplate.scan_res = setInterval(function(){
				var input_elements = document.querySelectorAll('form._client_auth_form input._toscan');
				var confirm = false;
				for(var i = 0; i < input_elements.length; i++){
					var val = input_elements[i].value;
					if(val.length > 1){
						confirm = true;
					}else{
						confirm = false;
						break;
					}
						
				}
				if(confirm){
					$('button._submit_auth_button').show();
					payment_window_vtemplate.button_visible = true;
				}else{
					if(payment_window_vtemplate.button_visible){
						$('button._submit_auth_button').hide();
						payment_window_vtemplate.button_visible = false;
					}
				}
			}, 1000);
		},
		scan_authorize : function(){
			with(payment_window_vtemplate){
				if(auth_scan_started){
					return false;
				}
				auth_scan_started = true;
				
				auth_scan_res = setInterval(function(){
					
				}, 1000);
			}
			
			
			
		},
		hide_sms_field : function(){
			var elem = document.querySelector('div._smscode');
			document.querySelector('#InputSMSCode').value = '';
			$(elem).hide();
		},
		show_sms_field : function(){
			var elem = document.querySelector('div._smscode');
			document.querySelector('#InputSMSCode').value = '';
			$(elem).show();
		}
}