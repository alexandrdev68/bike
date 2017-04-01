<?php

if(isset($_POST['action'])){
	if(!isset($_SESSION['CURRUSER']) && @$_POST['action'] !== 'login_action' && @$_POST['action'] !== 'find_action_user'){
		echo json_encode(array('status'=>'session_close'));
		exit;
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/php_interface/db_init_inc.php');
	$temp = new TEMP((defined("CURR_TEMPLATE") ? CURR_TEMPLATE : DEFAULT_TEMPLATE));
	TEMP::component('localization', array('language'=>isset($_SESSION['user_lang']) ? $_SESSION['user_lang'] : 'ua'), false);
	$actions = new Actions();
	$method = $_POST['action'].'_handler';
	if(method_exists($actions, $method)){
		echo $actions->$method();
		exit;
	}else{
		echo json_encode(array('status'=>'bad', 'err'=>'action not exists'));
		exit;
	}
}
?>