<div class="modal hide fade _editBikeModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=TEMP::$Lang['edit_bike_btn']?></h3>
    <div class="alert _edbikeAlert alert-error alert-block fade in">
		<button class="close"  type="button">×</button>
		<strong><?=TEMP::$Lang['warning']?>!</strong>
		<span class="_messtext"></span>
	</div>
  </div>
  <div class="modal-body">
    <form class=" _edBikeForm">
	    <div class="_bikeFoto user_foto">
	      <img src="" alt="no foto" width="380" height="285" class="img-polaroid">
	    
	    </div>
	    <div class="control-group">
		    <div class="controls">
		    	<label for="bikeModel" class="control-label offset1">Модель:</label>
		    	<input id="bikeModel" class="offset1" required name="bModel" type="text" placeholder="<?=TEMP::$Lang['model_col']?>"><br>
		    	<label for="bikeSerial" class="control-label offset1">Серійний номер:</label>
		    	<input id="bikeSerial" class="offset1" required name="bSerial" type="text" placeholder="<?=TEMP::$Lang['serial_id']?>">
		    	<input name="bNumber" type="hidden">
		    </div>
	    </div>
	    <div class="control-group">
		    <div class="controls">
		    	<label for="bikeCost" class="control-label offset1">Вартість велосипеду (грн.):</label>
		    	<input id="bikeCost" class="offset1" required name="bCost" type="text" placeholder="Вартість">
		    </div>
	    </div>
	    <div class="control-group">
	    	<label class="control-label offset1"><?=TEMP::$Lang['store_adress']?></label>
	    	<select required name="bPlace" class="offset1">
				<?foreach($arRes as $value):?><option value="<?=$value['id']?>"><?=$value['adress']?></option><?endforeach;?>
			</select>
		</div>
		<div class="control-group">
			<label class="control-label" for="load_edbike_foto"><?=TEMP::$Lang['load_photo']?></label>
			<input id="load_edbike_foto" name="foto" type="file" value="">
		</div>

	    <div class="control-group">
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