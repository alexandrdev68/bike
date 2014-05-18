<script>
$(document).ready(payrent_init);

function pay_rent_handler(self){
	self.off('click');
	if(user.currId == null) return false;
	var currBlackElement = $('div._findList table._usListTable tr._blackList' + user.currId);
	if(currBlackElement !== undefined && $(currBlackElement).find('input[name="uRent"]').prop('checked')){
		$('div._payrentAlert span._messtext').text('<?=TEMP::$Lang['user_in_black_list']?>');
		$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp('fast');
		return false;
	}
	var print = $('input._print' + user.currId).prop('checked');
	var seat = $('input._seat' + user.currId).prop('checked');
	var rent_period = $('input._timecnt' + user.currId).val();
	var sendData = {'action' : 'go_rent',
        	'user_id' : user.currId,
        	'print' : print,
        	'seat' : seat,
        	'rent_period': rent_period, 
        	'bike_id': bike.currId}
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
        			window.open('/main/print', 'print');
        		}
                
            }else if(response.status == 'bad'){
            	$('div._payrentAlert strong').text(response.message);
            	$('div._payrentAlert strong').text("<?=TEMP::$Lang['warning']?>!");
				$('div._payrentAlert span._messtext').text(response.message);
				$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp('fast');
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

							$('tr._uInfo input').click(function(event){
								event.stopPropagation();
							});

							userData.num = 1;
							user.keypressflag = false;
							//setTimeout('user.keypressflag = false', 1000);
							var list = $('table._usListTable tr._uInfo td input[name="uRent"]');
							$(list[0]).attr('checked', true);
							user.currId = $(list[0]).data('userid');
							$(list).on('click', function(){
								user.currId = $(this).data('userid');
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

	$('form._payrentForm').submit(function(event){
		event.preventDefault();

		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'add_klient'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){

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
						}
					});

					$('button._uRentConfirm').fadeIn('fast');
					
					$('span._messtext').text(response.message);
					$('div._payrentAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._payrentForm').clearForm();
					$('div._payrentAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._payrentForm').delay('3000').fadeOut('slow', function(){
						$('div._findList').fadeIn('slow');
					});
					$('#load_user_foto').val('');
				}else if(response.status == 'error'){
					$('div._payrentAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('span._messtext').text(response.message);
					$('div._payrentAlert').addClass('alert-error').slideDown('fast').delay('3000').slideUp();
				}else if(response.status == 'session_close'){
		        	bike.sessionStopped();
		        }
				
			},
			error : function(response){
				console.log('bad');
			}
		});
	});

	$('button._uRentConfirm').on('click', function(){
		pay_rent_handler($(this));
	});

	$('div._payrentModal').on('hide', function(){
		$('div._payrentModal ._usListTable tr._uInfo').detach();
		$('#user_finder').val('');
	})

}
</script>