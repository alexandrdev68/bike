<div class="navbar">
	<div class="navbar-inner">
	<a class="brand" href="http://veloolimp.com.ua/ru/"><?=TEMP::$Lang['txt_go_to_the_site']?></a>
	<ul class="nav">
		<li <?if(@$_GET['place'] == 'in_store'):?>class="active"<?endif?>><a href="/public?place=in_store" ><?=TEMP::$Lang['bikes_in_store']?></a></li>
		<li <?if(@$_GET['place'] == 'in_rent'):?> class="active"<?endif?>><a href="/public?place=in_rent" ><?=TEMP::$Lang['bikes_in_rent']?></a></li>
		<?if(isset($_GET['place']) && $_GET['place'] == 'in_store'):?>
		<li class="divider-vertical"></li>
			<li class="dropdown _storeReportSelect">
				<a class="dropdown-toggle" data-toggle="dropdown" data-store_id="no" href="#"><span class="_storeReportText"><?=$store_title?></span> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?foreach($_SESSION['STORES'] as $value):?><li><a href="/public?place=in_store&store_id=<?=$value['id']?>" data-value="<?=$value['id']?>"><?=$value['adress']?></a></li><?endforeach?>
					</ul>
			</li>
		<?endif?>
	</ul>
	</div>
</div>
<div class="row-fluid">
  <div class="span10 offset1">
	<ul class="thumbnails">
		<?foreach($arBikes as $num=>$bike):?>
		<li class="span2">
			<div class="thumbnail bikes_list_public">
				<img data-src="holder.js/300x200" alt="no foto" src="upload/bikes/<?=$bike['foto']?>">
				<h4 class="text-center"><?=$bike['model']?></h4>
				<p><strong><?=TEMP::$Lang['bike_number']?>:</strong> <?=$bike['id']?></p>
				<p><strong><?=TEMP::$Lang['store_adress']?>:</strong> <?=$bike['adress']?></p>
			</div>
		</li>
		<?if(fmod($num, 4) == 0 && $num != 0):?><div class="underline"></div><?endif?>
		<?endforeach?>
	</ul>
  </div>
</div>