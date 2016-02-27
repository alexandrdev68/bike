<script data-bike="<?=IDENTJS?>">
$(document).ready(edbike_init);

function edbike_init(){
	$('div._edbikeAlert').hide();
	$('div._edbikeAlert button.close').click(function(){
		$('div._edbikeAlert').slideUp('fast');
	});

	$('div._editBikeModal').on('show', function(){
		bike.getBikeById(bike.currId, function(response){
			$('form._edBikeForm input[name="bModel"]').val(response.bike_info.model);
			$('form._edBikeForm input[name="bSerial"]').val(response.bike_info.serial_id);
			$('form._edBikeForm input[name="bNumber"]').val(response.bike_info.id);
			$('form._edBikeForm select[name="bPlace"]').val(response.bike_info.store_id);
			$('form._edBikeForm div._bikeFoto img').attr('src', response.bike_info.foto).show();
			$('#bikeCost').val((!!response.bike_info.properties.cost ? response.bike_info.properties.cost / 100 : ''));
		});
	})
	
	$('form._edBikeForm').submit(function(event){
		event.preventDefault();

		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'edit_bike'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					$('div._edbikeAlert span._messtext').text(response.message);
					$('div._edbikeAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._edBikeForm').clearForm();
					$('#load_edbike_foto').val('');
					$('div._edbikeAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._editBikeModal').delay('3000').modal('hide');
					if(response.uploaded_photo == 'yes') window.location.reload();
					bikes_fill();
				}else if(response.status == 'error'){
					$('div._edbikeAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('div._edbikeAlert span._messtext').text(response.message);
					$('div._edbikeAlert').addClass('alert-error').slideDown('fast');
				}else if(response.status == 'session_close'){
	            	bike.sessionStopped();
	            }
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}
</script>