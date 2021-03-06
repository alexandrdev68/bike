<link rel="stylesheet" type="text/css" href="js/jBox-0.3.2/Source/jBox.css?ver=<?=VERSION?>">
<script data-bike="<?=IDENTJS?>" src="js/jBox-0.3.2/Source/jBox.min.js?ver=<?=VERSION?>"></script>
<script data-bike="<?=IDENTJS?>" type="text/javascript">TEMPLATE.lang = $.parseJSON('<?=TEMP::$Lang['FORJS']?>');</script>
<link rel="stylesheet" type="text/css" href="<?=TEMP::$curr_temp_path?>/datetimepicker-master-new/jquery.datetimepicker.css?ver=<?=VERSION?>">
<script data-bike="<?=IDENTJS?>" src="<?=TEMP::$curr_temp_path?>/datetimepicker-master-new/build/jquery.datetimepicker.full.min.js?ver=<?=VERSION?>"></script>

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
						<li><a href="/public?place=in_store" data-value="<?=$value['id']?>"><?=TEMP::$Lang['all_stores_txt']?></a></li>
						<?foreach($_SESSION['STORES'] as $value):?><li><a href="/public?place=in_store&store_id=<?=$value['id']?>" data-value="<?=$value['id']?>"><?=$value['adress']?></a></li><?endforeach?>
					</ul>
			</li>
		<?endif?>
		</ul>
		
		<ul class="nav navbar-nav navbar-right">
			<li data-vtemplate_public_page="event=onLogin:logoutButtonShow,event=click:logoutButtonClick" <?if(USER::isClient()):?> <?else:?>class="hidden"<?endif?>><a href="#logout_btn"><?=TEMP::$Lang['exit_btn']?></a></li>
		</ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="row">
  <div class="col-md-12 col-md-offset-1">
	<ul  data-vtemplate_public_page="event=click:order_button_handler" class="thumbnails">
		<?foreach($arBikes as $num=>$bike):?>
		<?$thisIsLast = (fmod($num, 5) == 0);?>
		<?if($thisIsLast):?><div class="underline"></div><?endif?>
		<li class="col-md-2">
			<div class="thumbnail bikes_list_public">
			<?	$npos = strpos($bike['foto'],'.');
	        	$resizedFotopath = substr($bike['foto'], 0, $npos).'_resized_640.jpg';
			?>
				<div class="container-fluid bottomMargin10 minHeight70">
				<a class="bike_foto_magnific" href="upload/bikes/<?=$resizedFotopath?>">
				<img data-src="holder.js/300x200" class="col-md-14 col-md-offset-1 mfp-with-zoom" alt="no foto" src="upload/bikes/<?=$bike['foto']?>"></a>
				</div>
				<div class="visible-lg-block visible-sm-block visible-xs-block">
				<button data-value="<?=$bike['id']?>" data-img_src="<?=$resizedFotopath?>" type="button" class="btn btn-sm btn-success col-md-offset-2 col-lg-offset-3 col-xs-offset-5 bottomMargin10 topMargin30"><?=TEMP::$Lang['book_bike_txt']?></button>
				<h4 class="text-center text-info bottomMargin10"><?=$bike['model']?></h4>
				<p><strong><?=TEMP::$Lang['bike_number']?>:</strong> <?=$bike['id']?></p>
				<p><strong><?=TEMP::$Lang['store_adress']?>:</strong><br> <?=$bike['adress']?></p>
				</div>
				<div class="visible-md-block">
				<button type="button" data-value="<?=$bike['id']?>" data-img_src="<?=$resizedFotopath?>" class="btn btn-xs btn-success col-md-offset-2 col-lg-offset-3 col-xs-offset-5 bottomMargin10"><?=TEMP::$Lang['book_bike_txt']?></button>
				<h5 class="text-center text-info bottomMargin10"><?=$bike['model']?></h5>
				<p><small><strong><?=TEMP::$Lang['bike_number']?>:</strong> <?=$bike['id']?></small></p>
				<p><small><strong><?=TEMP::$Lang['store_adress']?>:</strong><br> <?=$bike['adress']?></small></p>
				</div>
			</div>
		</li>
		<?endforeach?>
	</ul>
  </div>
</div>
<?TEMP::component('public/order_payment', array())?>