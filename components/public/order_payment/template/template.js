var payment_window_vtemplate = new VTemplate({
	tmpName : 'payment_window'
});

function get_join_card_number(){
	var card_number_cuts = $('.credit_input');
	var card_number = '';
	for(var i = 0; i < card_number_cuts.length; i++){
		card_number += $(card_number_cuts[i]).val();
	}
	return card_number;
}

$(document).ready(function(event){
	$('.credit_input').groupinputs();
	
	payment_window_vtemplate.dateTimeWidget = new datetimepickerHandler({
		idFrom : 'ticketsDateFrom'
	});
	
	$('.credit_input').on('input propertychange', function(e) {
	    
	});
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
			payment_window_vtemplate.functions.hide_sms_field();
			$('button._submit_auth_button').hide();
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
			}else if(!!response.type && response.type == 'registration'){
				document.getElementById('operationType').value = 'auth';
				payment_window_vtemplate.ajaxRegisterClient.data.phone = response.phone;
				payment_window_vtemplate.eventFunctions.on_client_auth_submit();
				TEMPLATE.showNotice(response.message, 'info');
				$('div._register_fields').hide();
				
			}
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
			try{
				event.preventDefault();
			}catch(err){
				console.log(err);
			}
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

function datetimepickerHandler(params){
	params = params || {
		idFrom : 'datetimepicker2',
		idTo : null,
		dtpickerFrom :{
			format: 'yyyy-MM-dd hh:mm:ss',
			language: 'uk',
			pick12HourFormat: false
			},
		dtpickerTo : null
	};
	params.idFrom = params.idFrom || 'datetimepicker2';
	params.idTo = params.idTo || null;
	params.dtpickerFrom = params.dtpickerFrom || {
		format: 'yyyy-MM-dd hh:mm:ss',
		language: 'uk',
		pick12HourFormat: false
		};
	params.dtpickerTo = params.dtpickerTo || params.dtpickerFrom;
	this.dpParamsFrom = params.dtpickerFrom;
	this.dpParamsTo = params.dtpickerTo;
	this.dpIdFrom = params.idFrom;
	this.pickerFrom = null;
	this.pickerTo = null;
	this.dateFromElement = null;
	this.dateToElement = null;
	this.dpIdTo = params.idTo;
	this.date = {from : null, to : null};
	this.init =function(){
		var self = this;
		var dateDict = datetimepickerHandler.getDateArray(new Date());
		self.date.from = new Date(dateDict.Year, dateDict.Month - 1, dateDict.Day, 0,0,0);
		self.dateFromElement = $('#' + self.dpIdFrom);
		self.dateFromElement.datetimepicker(self.dpParamsFrom);
		self.pickerFrom = self.dateFromElement.data('datetimepicker');
		self.pickerFrom.setLocalDate(self.date.from);
		self.dateFromElement.on('changeDate', function(){
			self.date.from = self.pickerFrom.getLocalDate();
			var fromTime = self.date.from.getTime();
			var toTime = self.date.to.getTime();
			if(fromTime > toTime){
				var dt = datetimepickerHandler.getDateArray(self.date.from);
				self.date.to = new Date(dt.Year, dt.Month - 1, dt.Day, 23,59,59);
				self.pickerTo.setLocalDate(self.date.to);
			}
		});
		if(self.dpIdTo !== null && self.dpParamsTo !== null){
			self.dateToElement = $('#' + self.dpIdTo);
			self.dateToElement.datetimepicker(self.dpParamsTo);
			self.pickerTo = self.dateToElement.data('datetimepicker');
			self.date.to = new Date(dateDict.Year, dateDict.Month - 1, dateDict.Day, 23,59,59);
			self.pickerTo.setLocalDate(self.date.to);
			self.dateToElement.on('changeDate', function(){
				self.date.to = self.pickerTo.getLocalDate();
				if(self.date.to.getTime() < self.date.from.getTime()){
					var dt = datetimepickerHandler.getDateArray(self.date.to);
					self.date.from = new Date(dt.Year, dt.Month - 1, dt.Day, 0,0,0);
					self.pickerFrom.setLocalDate(self.date.from);
				}
			});
		}
	};
	this.getDateTimestamp = function(){
		return {from : this.date.from.getTime(), to : this.date.to.getTime()};
	};
	datetimepickerHandler.getDateArray = function(date){
		var tmpDict = {
				Year : date.getFullYear(), 
				Month : date.getMonth(),
				Day : date.getDate(),
				Hours : date.getHours(),
				Minutes : date.getMinutes(),
				Seconds : date.getSeconds(),
				timestamp : date.getTime()
				}
		tmpDict.Month++;
		tmpDict.Month = (tmpDict.Month < 10 ? '0' + tmpDict.Month : tmpDict.Month);
		tmpDict.Day = (tmpDict.Day < 10 ? '0' + tmpDict.Day : tmpDict.Day);
		tmpDict.Hours = (tmpDict.Hours < 10 ? '0' + tmpDict.Hours : tmpDict.Hours);
		tmpDict.Minutes = (tmpDict.Minutes < 10 ? '0' + tmpDict.Minutes : tmpDict.Minutes);
		tmpDict.Seconds = (tmpDict.Seconds < 10 ? '0' + tmpDict.Seconds : tmpDict.Seconds);
		tmpDict.dateTimeString = tmpDict.Year + '-' + 
									tmpDict.Month + '-' + 
									tmpDict.Day + ' ' + 
									tmpDict.Hours + ':' + 
									tmpDict.Minutes + ':' + 
									tmpDict.Seconds;
		return tmpDict;
	};
	
	this.init();
}