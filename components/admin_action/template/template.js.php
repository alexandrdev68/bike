<script data-bike="<?=IDENTJS?>">
$(document).ready(admin_action_init);

var actions_list = new tableFromData({
	head : {name : "<?=TEMP::$Lang['pib_table']?>",
		klient_id : '',
		time_start : "<?=TEMP::$Lang['txt_action_user_start_from']?>",
		scores : "<?=TEMP::$Lang['txt_leader_scores']?>"
	},
	content : {
		scores : '<i>#$#</i>',
		klient_id : '<span class="_href _actionsUserInfo" data-klient_id="#$#"><i class="icon-eye-open"></i></span>'
	},
	classes : 'table table-striped _actionsList',
	counter : true
});

var actions_curr_page = 1;

function fill_info_action(response){
	actions_list.rowNum = (actions_curr_page - 1) * 100;
	actions_list.fill(response.actions_list);
	$('div._actContnr').html(actions_list.table);
	$('span._actionsUserInfo').click(function(){
		user.showInfo($(this).data('klient_id'));
	});
	bike.buildNavChain({
		target : '._actionsNavChain',
		chain : response.nav,
		onPageChange : function(page){
			actions_curr_page = page;
			actions_report.send({
				data : {action : 'get_actions_list', from_user_offset : (page - 1) * 100}
			});
		}
	});
}

var actions_report = new serverRequest({
	url : '/',
	dataType : 'json',
	success : function(response){
		fill_info_action(response);
	}
});


function admin_action_init(){
	
}
</script>
