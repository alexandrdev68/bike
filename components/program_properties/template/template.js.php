<script data-bike="<?=IDENTJS?>">
$(document).ready(pprop_init);


function pprop_init(){

	if(user.cookie.get('print_type') == 'html'){
		$('input._print_in_HTML').prop('checked', true);
	}else{
		$('input._print_in_HTML').prop('checked', false);
	}

	$('input._print_in_HTML').change(function(){
		if($(this).prop('checked')){
			if(user.cookie.get('print_type') == '') user.cookie.set('print_type', 'html', 100000);
		}else{
			if(user.cookie.get('print_type') == 'html') user.cookie.del('print_type');
		}
	});


}
</script>