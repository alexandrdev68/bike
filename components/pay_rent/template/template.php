<div class="modal hide fade _payrentModal disabled" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<div class="row-fluid">
			<div class="span7 offset0">
				<label class="control-label" for="user_finder"><?=TEMP::$Lang['user_finder']?>:
					<input id="user_finder"
					class="_userAutocomplete input-medium search-query"
					autocomplete="off" type="text" data-provide="typeahead"> </label>
			</div>
			<div class="span5">
				<button type="button" class="btn _addKlientBtn"><?=TEMP::$Lang['add_klient']?></button>
			</div>
		</div>
	</div>
	<div
		class="alert _payrentAlert alert-error alert-block fade in disabled">
		<button class="close" type="button">×</button>
		<strong><?=TEMP::$Lang['warning']?>!</strong> <span class="_messtext"></span>
	</div>
	<div class="modal-body">
		<div class="control-group _findList">
			<div class="shadow _findShadow"></div>
			<div class="loader _findLoader"></div>
			<table class="table table-striped _usListTable">
				<tr>
					<th>№</th>
						<?if(USER::isAdmin()):?><th>UID</th><?endif?>
						<?if(USER::isAdmin()):?><th>Login</th><?endif?>
						<th><?=TEMP::$Lang['pib_table']?></th>
					<th><?=TEMP::$Lang['input_phone']?></th>
					<th><?=TEMP::$Lang['added']?></th>
					<th><?=TEMP::$Lang['time_count']?></th>
					<th></th>
				</tr>
			</table>
		</div>
		<div class="control-group _payrentForm disabled">
			<form class="form-inline _payrentForm">
				<div class="control-group">
					<!--<input required name="uLogin" type="text" placeholder="Login">-->
					<div class="controls">
						<input required name="uPhone" type="text"
							placeholder="<?=TEMP::$Lang['input_phone']?>"> <input
							name="uFirstname" type="text"
							placeholder="<?=TEMP::$Lang['input_firstName']?>"> <input
							name="uLastname" type="text"
							placeholder="<?=TEMP::$Lang['input_patronymic']?>">
					</div>
					<div class="controls top_margin10">
						<input name="uPatronymic" type="text"
							placeholder="<?=TEMP::$Lang['input_lastName']?>"> <input
							name="uLivePlace" type="text"
							placeholder="<?=TEMP::$Lang['input_live_place']?>"> <label><?=TEMP::$Lang['from_another_city']?>
					    <input type="checkbox" name="another_city" value="yes"> </label>
						<label><?=TEMP::$Lang['war_veterane']?>
					    <input type="checkbox" name="war_veterane" value="yes"> </label>
					</div>
					<div class="controls span">
						<label class="control-label span2" for="extend_info_add">Додаткова
							інформація про клієнта</label>
						<textarea
							data-vtemplate_edituser="function=extend_info_set:properties"
							name="extend_info" id="extend_info_add"></textarea>
					</div>
					<div class="underline"></div>
					<div class="controls span top_margin10">
						<label class="control-label" for="load_user_foto"><?=TEMP::$Lang['load_photo']?></label>
						<input id="load_user_foto" name="foto" type="file" value="">
					</div>
					<div class="underline"></div>
					<div class="controls span top_margin10">
						<label class="control-label" for="load_extra_foto"><?=TEMP::$Lang['load_extra_photo']?></label>
						<input id="load_extra_foto" name="foto_extra" type="file" value="">
					</div>
				</div>
				<div class="underline"></div>
				<div class="controls span text-center">
					<button type="submit" class="btn btn-large btn-primary"><?=TEMP::$Lang['add_btn']?></button>
				</div>
			</form>
			<div class="_users_like_this_container"></div>
		</div>
	</div>
	<div class="modal-footer">

		<button class="btn pull-left _uRentConfirm btn-primary"><?=TEMP::$Lang['come_rent']?></button>

		<button class="btn pull-right" data-dismiss="modal" aria-hidden="true">Close</button>

	</div>
</div>