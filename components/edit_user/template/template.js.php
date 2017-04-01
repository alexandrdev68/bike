<script data-bike="<?=IDENTJS?>">
$(document).ready(eduser_init);

function eduser_init(){
	$('div._eduserAlert').hide();
	$('div._eduserAlert button.close').click(function(){
		$('div._eduserAlert').slideUp('fast');
	});

	var editUser = new VTemplate({
		tmpName : 'edituser',
		functions : {
			user_level_set : function(level){
				editUser.workElement.value = level;
				if(level == 4) $('form._edUserForm select[name="resStore"]').attr('disabled', true);
				else $('form._edUserForm select[name="resStore"]').attr('disabled', false);
			},
			store_set : function(properties){
				editUser.workElement.value = (properties === null ? '' : properties.store === undefined ? '' : properties.store);
			},
			blacklist_set : function(properties){
				$(editUser.workElement).prop('checked', properties === null ? false : properties.blackList == 'on' ? true : false);
			},
			live_place_set : function(properties){
				$(editUser.workElement).val(properties === null ? '' : !!properties.live_place ? properties.live_place : '');
			},
			another_city_set : function(properties){
				$(editUser.workElement).prop('checked', properties === null ? false : properties.another_place == 'yes' ? true : false);
			},
			war_veterane_set : function(properties){
				$(editUser.workElement).prop('checked', properties === null ? false : properties.war_veterane == 'yes' ? true : false);
			}
		}
	});

	
	$('div._editUserModal').on('show', function(){
		user.getById(user.currId, function(response){
			response.info.id = user.currId;
			editUser.render(response.info);
		});
	})
	
	$('div._editUserModal').on('hide', function(){
		$('div._edUserFoto img').attr('src', '');
	});
	
	$('form._edUserForm').submit(function(event){
		event.preventDefault();
		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'edit_user'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					$('div._eduserAlert span._messtext').text(response.message);
					$('div._eduserAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._edUserForm').clearForm();
					$('#edit_user_foto').val('');
					$('div._eduserAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._editUserModal').delay('3000').modal('hide');
					//if(response.uploaded_photo == 'yes') users_fill(user.navChain.length > 0 ? user.navChain.curr : 0);//window.location.reload();
					users_fill(user.navChain.current !== undefined ? (user.navChain.current - 1) * 100 : 0);
				}else if(response.status == 'error'){
					$('div._eduserAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('div._eduserAlert span._messtext').text(response.message);
					$('div._eduserAlert').addClass('alert-error').slideDown('fast');
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