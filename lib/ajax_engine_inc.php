<?php
$arExclusions = array(
		'login_action',
		'find_action_user',
		'get_bike_by_id_public',
		'find_client_by_phone',
		'login_client'
);

if(isset($_POST['action'])){
	if(!isset($_SESSION['CURRUSER']) && !in_array(@$_POST['action'], $arExclusions)){
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