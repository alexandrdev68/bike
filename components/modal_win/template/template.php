<div class="modal hide fade _modalWin">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class="_modalHeader"></h3>
  </div>
  <div class="modal-body">
    <p class="_modalText"></p>
    <div class="_storeSelect disabled">
    	<label class="control-label"><?=TEMP::$Lang['store_adress_if_undefined']?>:</label>
    	<select required name="bPlace"><option></option>
			<?foreach($_SESSION['STORES'] as $value):?><option value="<?=$value['id']?>"><?=$value['adress']?></option><?endforeach;?>
		</select>
    </div>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn"><?=TEMP::$Lang['exit_btn']?></a>
    <button class="btn btn-primary _confirmModal"><?=TEMP::$Lang['yes_btn']?></button>
  </div>
</div>