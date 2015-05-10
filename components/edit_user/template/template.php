<div class="modal hide fade _editUserModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=TEMP::$Lang['edit_user_btn']?></h3>
    <div class="alert _eduserAlert alert-error alert-block fade in">
		<button class="close"  type="button">×</button>
		<strong><?=TEMP::$Lang['warning']?>!</strong>
		<span class="_messtext"></span>
	</div>
  </div>
  <div class="modal-body">
    <div class="_edUserFoto user_foto">
      <img data-vtemplate_edituser="src=photo" src="" alt="no foto" width="380" height="285" class="img-polaroid">
    </div>
    <form class="_edUserForm">
	    <div class="control-group">
		    <div class="controls">
		    	<label>Login:</label><input required name="uLogin" type="text" id="editLogin" placeholder="Login" data-vtemplate_edituser="value=login">
		    </div>
	    </div>
	    <div class="control-group">
		    <div class="controls">
		    	<label class="span2"><?=TEMP::$Lang['input_firstName']?>: <input data-vtemplate_edituser="value=name" name="uFirstname" type="text" id="editName" placeholder="<?=TEMP::$Lang['input_firstName']?>"></label>
		    	<label class="span2 offset1"><?=TEMP::$Lang['input_patronymic']?>: <input data-vtemplate_edituser="value=surname" name="uLastname" type="text" id="editFirst" placeholder="<?=TEMP::$Lang['input_patronymic']?>"></label>
		    	<label class="span2 offset1"><?=TEMP::$Lang['input_lastName']?>: <input data-vtemplate_edituser="value=patronymic" name="uPatronymic" type="text" id="editPatronymic" placeholder="<?=TEMP::$Lang['input_lastName']?>"></label>
		     </div>
		     <div class="controls">
		    	<label class="span2 offset"><?=TEMP::$Lang['input_phone']?>: <input data-vtemplate_edituser="value=phone" name="uPhone" type="text" placeholder="<?=TEMP::$Lang['input_phone']?>"></label>
		    	<label class="span2 offset1"><?=TEMP::$Lang['input_live_place']?>: <input data-vtemplate_edituser="function=live_place_set:properties" name="uLivePlace" type="text" placeholder="<?=TEMP::$Lang['input_live_place']?>"></label>
		    </div>
		    	<input data-vtemplate_edituser="value=id" type="hidden" name="uId" value="">
	    
	    
		    <div class="controls span">
		    	<label class="control-label span2" for="editLevel"><?=TEMP::$Lang['input_level']?>:
		    	<select data-vtemplate_edituser="function=user_level_set:user_level" required name="uLevel" id="editLevel">
					<option value="552071">admin</option>
					<option value="1">reception</option>
					<option value="2">user</option>
					<option selected value="4">klient</option>
				</select>
				</label>
				<label class="control-label span2 offset1" for="editselectStore"><?=TEMP::$Lang['select_store']?>:
				<select data-vtemplate_edituser="function=store_set:properties" name="resStore" id="editselectStore" disabled>
					<option></option>
					<?foreach($arRes as $store):?><option value="<?=$store['id']?>"><?=$store['adress']?></option><?endforeach?>
				</select>
				</label>
		    </div>
		    <div class="controls span left_margin0">
		    	<label class="control-label span2" for="editBlackList"><span class="text-warning"><?=TEMP::$Lang['add_black_list']?>:</span>
					<input data-vtemplate_edituser="function=blacklist_set:properties" id="editBlackList" type="checkbox" name="blackList">
				</label>
				<label class="control-label span2 offset1"><?=TEMP::$Lang['from_another_city']?>: 
					<input data-vtemplate_edituser="function=another_city_set:properties" value="yes" type="checkbox" name="another_place">
				</label>
		    </div>
	    </div>
	    <div class="control-group span left_margin0">
	    	<div class="controls">
				<label class="control-label span2" for="edit_user_foto"><?=TEMP::$Lang['load_photo']?></label>
				<input id="edit_user_foto" name="foto" type="file" value="">
			</div>
	    </div>
	    <div class="control-group span10">
		    <div class="controls text-center">
			    <button type="submit" class="btn btn-primary btn-large"><?=TEMP::$Lang['accept_btn']?></button>
		    </div>
	    </div>
    </form>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn"><?=TEMP::$Lang['exit_btn']?></a>
  </div>
</div>