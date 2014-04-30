<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<base href="/">
	<title>bike</title>
	<link rel="stylesheet" href="<?=TEMP::$styles_dir?>/style.css?ver=<?=VERSION?>">
	<link rel="stylesheet" href="<?=TEMP::$curr_temp_path?>/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=TEMP::$curr_temp_path?>/datepicker/css/datepicker.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="<?=TEMP::$js_dir?>/jquery-1.9.1.min.js"></script>
	<script src="<?=TEMP::$js_dir?>/main.js?ver=<?=VERSION?>"></script>
	<meta name="bike" content="application for bike manage">
	<!--[if lt IE 9]>
		   <script>
			  document.createElement('header');
			  document.createElement('nav');
			  document.createElement('section');
			  document.createElement('article');
			  document.createElement('aside');
			  document.createElement('footer');
		   </script>
		<![endif]-->
</head>
<body id="action">
<div class="row">
	<div class="col-md-10 col-md-offset-1 actionBackColorWhite contentMinHeight topMargin20">
		<div class="col-md-4 topMargin10"><a class="logo" href="http://veloolimp.com.ua/"></a></div>
		<div class="col-md-4 col-md-offset-4 topMargin10">
			<div class="panel panel-default">
			  <div class="panel-body">
			    <ul>
				    <li class="_usersCountAll"><b><?=TEMP::$Lang['txt_pryjnalo_uchast']?>:</b> <i></i></li>
				    <li class="_userPositionAction"><b><?=TEMP::$Lang['txt_your_position_in_action']?>:</b> <i></i></li>
				    <li class="_userDiffAction"><b><?=TEMP::$Lang['txt_your_diff_from_leader']?>:</b> <i></i></li>
			    </ul>
			  </div>
			</div>
		</div>
	