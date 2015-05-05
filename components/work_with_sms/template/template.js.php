<script>
$(document).ready(work_with_sms_init);

var SMSSender = {
		selectedUsers : [],
		ajax : null,
		resselerFinish : false,
		countSMS : 0,
		countSendedSMS : 0,
		pagesNum : 0,
		lastPosition : 0,
		inArray : function(arraySource, find){
			if(typeof(arraySource) != 'object') return false;
			if(typeof(find) == 'object'){
				for(var num = 0; num < arraySource.length; num++){
					for(var fnum = 0; fnum < find.length; fnum++){
						if(arraySource[num] === find[fnum]) return num;
					}
				}
				return -1;
			}else if(typeof(find) == 'string' || typeof(find) == 'number'){
				for(var num = 0; num < arraySource.length; num++){
					if(arraySource[num] === find) return num;
				}
				return -1;
			}else return false;
		},
		toType : function(type, value){
		switch(type){
		case 'string' :
			return String(value);
			break;
		case 'integer' :
			return parseInt(value);
			break;
		case 'float' :
			return parseFloat(value);
			break;
		case 'strToArray':
			return value.split(',');
			break;
		case 'strToArrayFloat':
			var tmp = value.split(',');
			for(var num = 0; num < tmp.length; num++){
				tmp[num] = SMSSender.toType('float', tmp[num]);
			}
			return tmp;
			break;
		default:
			return value;
		}
	},
	sendSelected : function(){
			SMSSender.ajax.data['sms_text'] = $('div._sendSMSResselerModal textarea[name="sms_text"]').val();
			if(SMSSender.ajax.data.sms_text == '' || SMSSender.ajax.data.sms_text.length < 4)
				return false;
			else if(SMSSender.selectedUsers.length == 0){
				return false;
			}
			SMSSender.ajax.data['translit'] = ($('div._sendSMSResselerModal input[name="translit"]').prop('checked') ? 'on' : '');
			var users_id_porcion = [];
			var counter = 0;
			while(users_id_porcion.length <= 20 && counter < SMSSender.selectedUsers.length){
				if(SMSSender.selectedUsers[SMSSender.lastPosition] !== undefined){
					users_id_porcion.push(SMSSender.selectedUsers[SMSSender.lastPosition]);
				}
				SMSSender.lastPosition++;
				counter++;
				if(counter >= SMSSender.selectedUsers.length || users_id_porcion.length == 0)
					SMSSender.resselerFinish = true;
			};
			SMSSender.countSendedSMS += users_id_porcion.length;
			SMSSender.ajax.data['users_id'] = users_id_porcion;
			SMSSender.ajax.send();
		},
		selectUsersviaAjax : function(filter){
			SMSSender.ajaxSelect.data['filter'] = filter;
			SMSSender.ajaxSelect.data['type'] = 'count';
			SMSSender.ajaxSelect.send();
			
		}
		
};

SMSSender.ajax = new serverRequest({
	url : '/',
	dataType : 'json',
	data : {action : 'smsResseller'},
	success : function(response){
		for(var v = 0; v < response.result.length; v++){
			if(response.result[v]['sms_status']['status'] === true){
				//for(var s = 0; s < SMSSender.ajax.data.users_id.length; s++){
				//console.log(SMSSender.inArray(SMSSender.selectedUsers, response.result[v]['uid']));
				delete(SMSSender.selectedUsers[SMSSender.inArray(SMSSender.selectedUsers, SMSSender.toType('integer', response.result[v]['uid']))]);
				//}
			}
			
		}
		
		var percent = SMSSender.toType('integer', SMSSender.countSendedSMS / SMSSender.countSMS * 100);
		$('div._sms_progressbar div').width(percent + '%').text(percent + '%');
		if(!SMSSender.resselerFinish){
			SMSSender.sendSelected();
		}else{
			setTimeout(function(){
				$('div._sms_progressbar').fadeOut('slow');
			}, 1000);
			SMSSender.countSendedSMS = 0;
			
			users_fill_in_sms((user.navChain.current - 1) * 100);
		}
	},
	error : function(response){
		console.log('bad');
	}
});

SMSSender.ajaxSelect = new serverRequest({
	url : '/',
	dataType : 'json',
	data : {action : 'smsUsersSelect'},
	success : function(response){
		if(response.type == 'count'){
			SMSSender.pagesNum = response.pages;
			SMSSender.ajaxSelect.data['type'] = 'get';
			SMSSender.ajaxSelect.data['page'] = SMSSender.pagesNum - 1;
			SMSSender.ajaxSelect.send();
		}else if(response.type == 'get'){
			for(var us = 0; us < response.users.length; us++){
				SMSSender.selectedUsers.push(response.users[us]);
			}
			SMSSender.pagesNum--;
			if(SMSSender.pagesNum > 0){
				SMSSender.ajaxSelect.data['page'] = SMSSender.pagesNum - 1;
				SMSSender.ajaxSelect.data['type'] = 'get';
				SMSSender.ajaxSelect.send();
			}else{
				users_fill_in_sms((user.navChain.current - 1) * 100);
			}
			
		}
		
	},
	error : function(response){
		console.log('bad');
	}
});

function build_navchain_in_sms(chain){
	bike.buildNavChain({
		target : 'div._usersSMSNavChain',
		chain : chain,
		onPageChange : function(page){
			users_fill_in_sms((page - 1) * 100);
		}
	});
}

function users_fill_in_sms(offset){
	offset = offset || 0;
	$('#_usSMSListPage table._usSMSListTable tr._uInfo').detach();
	user.getUsersList({
		from_user_id : offset,
		onListResponse : function(){
			if(user.navChain[1] !== undefined){
				$('div._usersSMSPaging').show();
				build_navchain_in_sms(user.navChain);
			}
			userData.num = offset + 1;
			for(var us in user.currentList){
				var usRow = new userData(user.currentList[us]);
				var num_us_selected = SMSSender.inArray(SMSSender.selectedUsers, SMSSender.toType('integer', user.currentList[us]['id']));
				var checked = (num_us_selected == -1 ? '' : 'checked');
				$('#_usSMSListPage table._usSMSListTable').append(usRow.html);
				$('#_usSMSListPage table._usSMSListTable tr:last-child td:last-child')
				.html('<input type="checkbox" ' + checked + 
						' data-user_id="' + user.currentList[us]['id'] + '">');

			};
			

			$(document).scrollTop(user.currentCoordinates);
			//userData.num = offset + 1;
			userEventInit();

			bike.rentList = {};

			$('#_usSMSListPage table._usSMSListTable tr td:last-child input[type="checkbox"]').
			on('change', function(){
				var user_id = $(this).data('user_id');
				if($(this).prop('checked')){
					SMSSender.selectedUsers.push(user_id);
				}else{
					
					var num = SMSSender.inArray(SMSSender.selectedUsers, user_id);
					if(num != -1)
						delete SMSSender.selectedUsers[num];
				}
			});
		}
	});
}

function work_with_sms_init(){
	$('button._smsManage').click(function(){
		$('div._viewPort span div.disabled').hide();
		$('div._smsManage').fadeIn('fast');
		users_fill_in_sms(0);
	});

	$('button._sendsmsbtn').on('click', function(){
		SMSSender.countSMS = 0;
		user.currentCoordinates = $(document).scrollTop();
		for(var n = 0; n < SMSSender.selectedUsers.length; n++){
			if(!!SMSSender.selectedUsers[n])
				SMSSender.countSMS++;
		}
		$('div._sendSMSResselerModal span._smsCount').text(SMSSender.countSMS);
		$('div._sendSMSResselerModal').modal('show');

	});

	$('a._closeSMSModal').on('click', function(event){
		event.preventDefault();
		$('div._sendSMSResselerModal').modal('hide');
	});

	$('a._sendSMSResselerProcess').on('click', function(event){
		event.preventDefault();
		SMSSender.resselerFinish = false;
		SMSSender.lastPosition = 0;
		SMSSender.sendSelected();
		$('a._closeSMSModal').trigger('click');
		$('div._sms_progressbar div').width(0 + '%');
		$('div._sms_progressbar').show();
	});

	$('div._selectallsmsbtn a[href="#all_not_action"]').on('click', function(event){
		event.preventDefault();
		user.currentCoordinates = $(document).scrollTop();
		SMSSender.selectedUsers = [];
		SMSSender.countSMS = 0;
		SMSSender.selectUsersviaAjax('all_not_action');
	});

}
</script>