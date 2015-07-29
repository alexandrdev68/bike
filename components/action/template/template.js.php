<script data-bike="<?=IDENTJS?>">
$(document).ready(action_init);

var leaders_list = new tableFromData({
	head : {name : "<?=TEMP::$Lang['pib_table']?>",
		time_start : "<?=TEMP::$Lang['txt_action_user_start_from']?>",
		scores : "<?=TEMP::$Lang['txt_leader_different']?>",
	},
	content : {
		scores : '<i>#$#</i>'
	},
	classes : 'table table-striped _actionLeadersList',
	counter : true
});

function fill_info_action(response){
	leaders_list.fill(response.leaders);
	$('div._leadrsContainer').html(leaders_list.table);
	$('._usersCountAll i').text(response.actions_count);
	$('._userPositionAction i').text(response.u_pos);
	$('._userDiffAction i').text(response.u_info.score);
}

var leaders_report = new serverRequest({
	url : '/',
	dataType : 'json',
	success : function(response){
		fill_info_action(response);
	}
});

function action_init(){
	<?if(empty($_SESSION['ACTION_USER'])):?>
		$('#action_modal_window').modal({
			backdrop : false,
			keyboard : false
		});
		$('#action_modal_window').modal('show');
	<?else:?>
		leaders_report.send({
			data : {action : 'find_action_user', sms_code : '<?=$_SESSION['ACTION_USER']?>'}
		});
	<?endif?>

	$('div._actionSMSCODE').hide();
	
	$('div._actionSMSCODE button').click(function(){
		$('div._actionSMSCODE').hide();
	});

	$('form[name="action_confirm"]').submit(function(event){
		event.preventDefault();
		$('._action_confirm_btn').trigger('click');
	});
	
	$('._action_confirm_btn').click(function(){
		var sms = document.querySelector('#smsCode').value;
		if(sms == '') return false;
		$('form[name="action_confirm"]').ajaxSubmit({
			type: 'post',
			dataType : 'json',
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					fill_info_action(response);
					$('#action_modal_window').modal('hide');
				}else if(response.status == 'bad'){
					$('div._actionSMSCODE ._messtext').text(response.message);
					$('div._actionSMSCODE').show();
				}
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}
</script>
