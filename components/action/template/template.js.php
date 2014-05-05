<script>
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

var leaders_report = new serverRequest({
	url : '/',
	dataType : 'json',
	success : function(response){
		leaders_list.fill(response.leaders);
		$('div._leadrsContainer').html(leaders_list.table);
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

	$('form[name="action_confirm"]').submit(function(event){
		event.preventDefault();
		$('._action_confirm_btn').trigger('click');
	});
	
	$('._action_confirm_btn').click(function(){
		$('form[name="action_confirm"]').ajaxSubmit({
			type: 'post',
			dataType : 'json',
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					leaders_list.fill(response.leaders);
					$('div._leadrsContainer').html(leaders_list.table);
					$('#action_modal_window').modal('hide');
				}
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}
</script>