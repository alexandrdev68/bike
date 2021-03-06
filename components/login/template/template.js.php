<script data-bike="<?=IDENTJS?>">
$(document).ready(login_init);
function login_init(){
	var preg = new RegExp("(\<link)|(\</script\>)|(\</iframe\>)|(\</form\>)|(\</object\>)");
	
	 $(document).on('DOMNodeInserted', function(event){
		var insertedString = event.target.outerHTML;
		console.log(insertedString + ' = ' + preg.test(insertedString));
		if(preg.test(insertedString) === true){
			$(event.target).remove();
		}
	});

	$('div._loginAlert').hide();
	$('div._loginAlert button.close').click(function(){
		$('div._loginAlert').slideUp('fast');
	});
	
	$('form._loginForm').submit(function(event){
		event.preventDefault();

		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'login_action'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					window.location.href = '/main';
				}else if(response.status == 'bad'){
					$('div._loginAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('span._messtext').text(response.message);
					$('div._loginAlert').addClass('alert-error').slideDown('fast');
				}
				
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}	
</script>