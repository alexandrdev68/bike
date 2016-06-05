<script data-bike="<?=IDENTJS?>">
$(document).ready(payrent_init);

function pay_rent_handler(self){
	self.off('click');
	if(user.currId == null) return false;
	if(!!user.currIdProperties){
		if(!!user.currIdProperties.another_place){
			if(user.currIdProperties.another_place == 'yes'){
				$('div._payrentAlert').addClass('alert-error').slideDown('fast');
				$('div._payrentAlert span._messtext').text('<?=TEMP::$Lang['user_from_another_city']?>');
				user.currIdProperties.another_place = 'confirmed';
				$('button._uRentConfirm').on('click', function(){
	        		pay_rent_handler($(this));
	        	});
	        	return false;
			}
		}
	}
	var currBlackElement = $('div._findList table._usListTable tr._blackList' + user.currId);
	if(currBlackElement !== undefined && $(currBlackElement).find('input[name="uRent"]').prop('checked')){
		$('div._payrentAlert span._messtext').text('<?=TEMP::$Lang['user_in_black_list']?>');
		$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp('fast');
		return false;
	}
	var print = $('input._print' + user.currId).prop('checked');
	var seat = $('input._seat' + user.currId).prop('checked');
	var rent_period = $('input._timecnt' + user.currId).val();
	var war_veterane = $('input._war_veterane' + user.currId).prop('checked');
	var sendData = {'action' : 'go_rent',
        	'user_id' : user.currId,
        	'print' : print,
        	'seat' : seat,
        	'rent_period': rent_period, 
        	'bike_id': bike.currId,
        	'war_veterane' : war_veterane
        	}
	<?if(BIKE_ACTION):?>
		if(document.querySelector('input._action' + user.currId) !== null){
			sendData.bike_action = $('input._action' + user.currId).prop('checked');
		}
	<?endif?>
	$('div._findShadow, div._findLoader').show();
	$.ajax({
        url: window.location,
        type:"POST",
        data: sendData,
        dataType: 'json',
        success: function(response) {
        	$('div._findShadow, div._findLoader').hide();
        	if(response.status == 'ok'){
        		$('span._messtext').text(response.message);
				$('div._payrentAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
        		$('div._payrentAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp('fast', function(){
	        		$('div._payrentModal').modal('hide');
	        		$('button._uRentConfirm').on('click', function(){
		        		pay_rent_handler($(this));
		        	});
	        		var bikeList = $('table._bkListTable tr');
	        		bike.findInList(bike.currId, '_bkListTable', 'bikeid', function(del_num){
	                	$(bikeList[del_num]).fadeOut('slow', function(){
		                	$(bikeList[del_num]).detach();
		                });
	                }, '._payInRent');
	        	});
        		
        		if(response.print == 'yes'){
        			var params = "menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes";
        			var print_win = window.open("/main/print", "_blank", params);
        		}
                
            }else if(response.status == 'bad'){
            	$('div._payrentAlert strong').text(response.message);
            	$('div._payrentAlert strong').text("<?=TEMP::$Lang['warning']?>!");
				$('div._payrentAlert span._messtext').text(response.message);
				$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp('fast');
				$('button._uRentConfirm').on('click', function(){
	        		pay_rent_handler($(this));
	        	});
            }else if(response.status == 'session_close'){
	        	bike.sessionStopped();
	        }
        },
        error: function(response){
        	
        }
	});
}

function payrent_init(){
	var usList;
	user.keypressflag = false;

	$('input._userAutocomplete').keyup(function(event){
		var q = document.querySelector('input._userAutocomplete').value;
		if(q.length < 3) return false;
		if(event.keyCode == 13){
			
		};

		if(user.keypressedInterval < user.interval)	user.keypressedInterval = user.interval - 1;
			
		if(user.keypressedInterval == user.interval){
			user.keyIntevalId = setInterval(function(){
				if(user.keypressedInterval == 0){
					clearInterval(user.keyIntevalId);
					user.keypressedInterval = user.interval;
					user.findLoader('show');
					q = document.querySelector('input._userAutocomplete').value;
					user.find({
						word : q,
						maxLength : 3,
						onFind : function(list){
							//console.log(list);
							user.list = list;
							//process(list);
							userData.num = 1;
							user.findLoader('hide');
							$('div._payrentModal ._usListTable tr._uInfo').detach();
							for(var l in list){
								usList = new userData(list[l], 'no');
								$('div._payrentModal ._usListTable').append(usList.html);
							}

							$('tr._uInfo').click(function(){
								var user_id = $(this).find('td:last-child i._delUsr').data('userid') === null ? $(this).find('input[name="uRent"]').data('userid') : $(this).find('td:last-child i._delUsr').data('userid');
								
								user.showInfo(user_id);
							});
							$('input[name="uRent"]').click(function(event){
								event.stopPropagation();
								
							});

							$('tr._uInfo input, tr._uInfo button').click(function(event){
								event.stopPropagation();
							});

							$('button._sendSMSBtn').on('click', function(event){
								send_sms.send({
									data : {action : 'send_sms', user_id : $(this).data('userid'), user_phone : $(this).data('userphone')}
								});
							});

							userData.num = 1;
							user.keypressflag = false;
							//setTimeout('user.keypressflag = false', 1000);
							var list = $('table._usListTable tr._uInfo td input[name="uRent"]');
							$(list[0]).attr('checked', true);
							//console.log(list);
							user.currId = $(list[0]).data('userid');
							user.currIdProperties = user.list[0].properties;
							$(list).on('click', function(){
								user.currId = $(this).data('userid');
								for(var num in user.list){
									if(user.list[num].id == user.currId){
										user.currIdProperties = user.list[num].properties;
									}
								}
							});
						}
					});
					return false;
				}else{
					user.keypressedInterval--;
					return false;
				}
			}, 1);
		};
		
	}).focusin(function(){
		$('div._payrentForm').fadeOut('slow', function(){
			$('div._findList').fadeIn('slow');
		});
		$('button._uRentConfirm').fadeIn('fast');
	});;

	$('button._addKlientBtn').click(function(){
		$('div._findList').fadeOut('slow', function(){
			$('div._payrentForm').fadeIn('slow');
		});
		$('button._uRentConfirm').fadeOut('fast');
	});

	var usersLikeThis_table = new tableFromData({
		head : {fullName : "<?=TEMP::$Lang['pib_table']?>",
			phone : "<?=TEMP::$Lang['input_phone']?>",
			blackList : ''
		},
		content : {
			phone : '<i>#$#</i>',
			blackList : '<span class="text-center" data-blacklist="#$#"><i class="icon-thumbs-down"></i></span>'
		},
		classes : 'table table-striped _usersLikeThisList',
		counter : true
	});

	function formAddSubmit(){
		$('form._payrentForm').ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'add_klient'},
			url: window.location,
			success: function(response) {
				$('form._payrentForm button').prop('disabled', false);
				if(response.status == 'ok'){
					user.addUserConfirm = false;
					user.find({
						word : $('form._payrentForm input[name="uPhone"]').val(),
						maxLength : 3,
						onFind : function(list){
							//console.log(list);
							//process(list);
							$('div._payrentModal ._usListTable tr._uInfo').detach();
							for(var l in list){
								usList = new userData(list[l], 'no');
								$('div._payrentModal ._usListTable').append(usList.html);
							}
							userData.num = 1;
							$('table._usListTable tr._uInfo td input[name="uRent"]').attr('checked', true);
							user.currId = list[0].id;
							user.currIdProperties = list[0].properties;
						}
					});

					$('button._uRentConfirm').fadeIn('fast');
					
					$('span._messtext').text(response.message);
					$('div._payrentAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._payrentForm').clearForm();
					$('div._payrentAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._payrentForm').delay('3000').fadeOut('slow', function(){
						$('div._findList').fadeIn('slow');
						$('div._users_like_this_container').empty();
					});
					$('#load_user_foto').val('');
				}else if(response.status == 'error'){
					$('div._payrentAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('span._messtext').text(response.message);
					$('div._users_like_this_container').empty();
					$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp();
				}else if(response.status == 'session_close'){
		        	bike.sessionStopped();
		        }
				
			},
			error : function(response){
				console.log('bad');
			}
		});
	}
	
	$('form._payrentForm').submit(function(event){
		event.preventDefault();
		$('form._payrentForm button').prop('disabled', true);
		if(!user.addUserConfirm){
			var actionSend = new serverRequest({
				data : {action : 'search_like_this', 
					uFirstname : $('form._payrentForm input[name="uFirstname"]').val(),
					uLastname : $('form._payrentForm input[name="uLastname"]').val(),
					uPatronymic : $('form._payrentForm input[name="uPatronymic"]').val()
				},
				success : function(response){
					$('form._payrentForm button').prop('disabled', false);
					if(response.status == 'ok'){
						if(response.users_likes_this.length > 0){
							for(var num in response.users_likes_this){
								response.users_likes_this[num]['fullName'] = response.users_likes_this[num]['name'] + ' ' + 
																				response.users_likes_this[num]['surname'] + ' ' + 
																				response.users_likes_this[num]['patronymic'];
								if(response.users_likes_this[num]['properties'] == null) response.users_likes_this[num]['properties'] = {};
								response.users_likes_this[num]['blackList'] = (!!response.users_likes_this[num]['properties']['blackList'] && response.users_likes_this[num]['properties']['blackList'] == 'on' ? 'yes' : 'no');
							}
							usersLikeThis_table.fill(response.users_likes_this);
							var html = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>';
							    html += '<strong></strong><?=TEMP::$Lang["txt_user_like_this"]?></div>';
							$('div._users_like_this_container').append(html);
							$('div._users_like_this_container').append(usersLikeThis_table.table);
							user.addUserConfirm = true;
							return false;
						}else{
							formAddSubmit();
						}
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
				}
			});

			actionSend.send();
		}else{
			formAddSubmit();
		}
		
		
		

		
	});

	$('button._uRentConfirm').on('click', function(){
		pay_rent_handler($(this));
	});

	$('div._payrentModal').on('hide', function(){
		$('div._payrentModal ._usListTable tr._uInfo').detach();
		$('div._users_like_this_container').empty();
		$('#user_finder').val('');
	});

	var send_sms = new serverRequest({
		url : '/',
		dataType : 'json',
		success : function(response){
			if(response.status == 'ok'){
				$('div._payrentAlert strong').text(response.message);
				$('div._payrentAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
				$('div._payrentAlert span._messtext').text(response.message);
				$('div._payrentAlert').removeClass('alert-error').slideDown('fast').delay('3000').slideUp('fast');
			}else if(response.status == 'bad'){
				$('div._payrentAlert strong').text(response.message);
            	$('div._payrentAlert strong').text("<?=TEMP::$Lang['warning']?>!");
				$('div._payrentAlert span._messtext').text(response.message);
				$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp('fast');
			}
		}
	});

}
</script>