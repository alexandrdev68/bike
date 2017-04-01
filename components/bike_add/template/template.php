<div class="row-fluid disabled _addBikeForm">
	<div class="span8 offset1">
		<h4 id="myModalLabel1"><?=TEMP::$Lang['add_bike_btn']?></h4>
		<div class="alert _addbikeAlert alert-error alert-block fade in">
			<button class="close"  type="button">×</button>
			<strong><?=TEMP::$Lang['warning']?>!</strong>
			<span class="_messtext"></span>
		</div>
		    <form class="form-inline _addBikeForm">
			    <div class="control-group">
				    <div class="controls">
				    	<input required name="bModel" type="text" placeholder="<?=TEMP::$Lang['model_col']?>">
				    	<input required name="bSerial" type="text" placeholder="<?=TEMP::$Lang['serial_id']?>">
				    	<input required name="bNumber" type="text" placeholder="<?=TEMP::$Lang['bike_number']?>">
				    </div>
			    </div>
			    <div class="control-group">
			    	<label class="control-label"><?=TEMP::$Lang['store_adress']?></label>
			    	<select required name="bPlace">
						<?foreach($arRes as $value):?><option value="<?=$value['id']?>"><?=$value['adress']?></option><?endforeach;?>
					</select>
				</div>
				<div class="control-group">
					<label class="control-label" for="load_bike_foto"><?=TEMP::$Lang['load_photo']?></label>
					<input id="load_bike_foto" name="foto" type="file" value="">
				</div>

			    <div class="control-group">
				    <div class="controls">
					    <button type="submit" class="btn"><?=TEMP::$Lang['add_bike']?></button>
				    </div>
			    </div>
		    </form>
	</div>
</div>