<script>
$(document).ready(addbike_init);

function addbike_init(){
	$('div._addbikeAlert').hide();
	$('div._addbikeAlert button.close').click(function(){
		$('div._addbikeAlert').slideUp('fast');
	});
	$('form._addBikeForm').submit(function(event){
		event.preventDefault();

		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'add_bike'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					$('div._addbikeAlert span._messtext').text(response.message);
					$('div._addbikeAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._addBikeForm').clearForm();
					$('#load_bike_foto').val('');
					$('div._addbikeAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._addBikeForm').delay('3000').fadeOut('fast');
				}else if(response.status == 'error'){
					$('div._addbikeAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('span._messtext').text(response.message);
					$('div._addbikeAlert').addClass('alert-error').slideDown('fast');
				}
				
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}
</script>