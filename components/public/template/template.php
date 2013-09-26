<div class="row-fluid">
  <div class="span6  _bikesInStore border_block">
  	<div class="span12 pull-left"><p><h3 class="text-info text-center"><?=TEMP::$Lang['bikes_in_store']?></h3></p></div>
	<ul class="thumbnails">
		<?foreach($arBikesInStore as $num=>$bike):?>
		<li class="span4">
			<div class="thumbnail bikes_list_public">
				<img data-src="holder.js/300x200" alt="no foto" src="upload/bikes/<?=$bike['foto']?>">
				<h4 class="text-center"><?=$bike['model']?></h4>
				<p><strong><?=TEMP::$Lang['bike_number']?>:</strong> <?=$bike['id']?></p>
				<p><strong><?=TEMP::$Lang['store_adress']?>:</strong> <?=$bike['adress']?></p>
			</div>
		</li>
		<?if(fmod($num, 2) == 0 && $num != 0):?><div class="underline"></div><?endif?>
		<?endforeach?>
	</ul>
  </div>
  <div class="span6 _bikesOnRent border_block">
  	<div class="span12 pull-left"><p><h3 class="text-info text-center"><?=TEMP::$Lang['bikes_in_rent']?></h3></p></div>
  	<?foreach($arBikesOnRent as $num=>$bike):?>
		<?$arDiff = BIKE::getTimeBetween(0, $bike['project_time'])?>
		<?$arNow = BIKE::getTimeBetween($bike['time_start'], time())?>
		<li class="span4">
			<div class="thumbnail bikes_list_public_rent">
				<img data-src="holder.js/300x200" alt="no foto" src="upload/bikes/<?=$bike['foto']?>">
				<h4 class="text-center"><?=$bike['model']?></h4>
				<p><strong><?=TEMP::$Lang['bike_number']?>:</strong> <?=$bike['bike_id']?></p>
				<p><strong><?=TEMP::$Lang['payment_time']?>:</strong></p>
				<p><?=$arDiff['days']?> <?=BIKE::declension($arDiff['days'], $arDay)?> <?=$arDiff['hours']?> <?=BIKE::declension($arDiff['hours'], $arHours)?></p>
				<p><strong><?=TEMP::$Lang['on_rent_text']?>:</strong></p>
				<p><?=$arNow['days']?> <?=BIKE::declension($arNow['days'], $arDay)?> <?=$arNow['hours']?> <?=BIKE::declension($arNow['hours'], $arHours)?> <?=$arNow['minutes']?> <?=BIKE::declension((int)$arNow['minutes'], $arMinutes)?></p>
			</div>
		</li>
		<?if(fmod($num + 1, 2) == 0):?><div class="underline"></div><?endif?>
	<?endforeach?>
  </div>
</div>