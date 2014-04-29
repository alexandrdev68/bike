<script>
$(document).ready(action_init);

function action_init(){
	<?if(empty($_SESSION['ACTION_USER'])):?>
		$('#action_modal_window').modal({
			backdrop : false,
			keyboard : false
		});
		$('#action_modal_window').modal('show');
	<?endif?>

	$('._action_confirm_btn').click(function(){
		$('form[name="action_confirm"]').ajaxSubmit({
			type: 'post',
			dataType : 'json',
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					console.log(response);
				}
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}
</script>