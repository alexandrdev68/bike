<script>
$(document).ready(action_init);

function action_init(){
	<?if(empty($_SESSION['ACTION_USER'])):?>
		$('#action_modal_window').modal('show');
	<?endif?>
}
</script>