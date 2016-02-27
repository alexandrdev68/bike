<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="http://veloolimp.com.ua/ru/"><?=TEMP::$Lang['txt_go_to_the_site']?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?if(@$_GET['place'] == 'in_store'):?>class="active"<?endif?>><a href="/public?place=in_store" ><?=TEMP::$Lang['bikes_in_store']?></a></li>
        <li <?if(@$_GET['place'] == 'in_rent'):?> class="active"<?endif?>><a href="/public?place=in_rent" ><?=TEMP::$Lang['bikes_in_rent']?></a></li>
        <?if(isset($_GET['place']) && $_GET['place'] == 'in_store'):?>
		<li class="divider-vertical"></li>
			<li class="dropdown _storeReportSelect">
				<a class="dropdown-toggle" data-toggle="dropdown" data-store_id="no" href="#"><span class="_storeReportText"><?=$store_title?></span> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="/public?place=in_store" data-value="<?=$value['id']?>">Всі пункти</a></li>
						<?foreach($_SESSION['STORES'] as $value):?><li><a href="/public?place=in_store&store_id=<?=$value['id']?>" data-value="<?=$value['id']?>"><?=$value['adress']?></a></li><?endforeach?>
					</ul>
			</li>
		<?endif?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="row">
  <div class="col-md-12 col-md-offset-1">
	<ul class="thumbnails">
		<?foreach($arBikes as $num=>$bike):?>
		<?$thisIsLast = (fmod($num, 5) == 0);?>
		<?if($thisIsLast):?><div class="underline"></div><?endif?>
		<li class="col-md-2">
			<div class="thumbnail bikes_list_public">
			<?	$npos = strpos($bike['foto'],'.');
	        	$resizedFotopath = substr($bike['foto'], 0, $npos).'_resized_640.jpg';
			?>
				<a class="bike_foto_magnific" href="upload/bikes/<?=$resizedFotopath?>">
				<img data-src="holder.js/300x200" class="col-md-10 col-md-offset-1" alt="no foto" src="upload/bikes/<?=$bike['foto']?>"></a>
				<h4 class="text-center"><?=$bike['model']?></h4>
				<p><strong><?=TEMP::$Lang['bike_number']?>:</strong> <?=$bike['id']?></p>
				<p><strong><?=TEMP::$Lang['store_adress']?>:</strong> <?=$bike['adress']?></p>
			</div>
		</li>
		<?endforeach?>
	</ul>
  </div>
</div>