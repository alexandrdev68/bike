<script>
$(document).ready(admin_action_init);

var actions_list = new tableFromData({
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
