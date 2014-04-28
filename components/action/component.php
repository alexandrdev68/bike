<?php
spl_autoload_unregister('class_autoload');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/alphasms-client-api/smsclient.class.php');
$sms = new SMSclient(SMS_LOGIN, SMS_PASSW, 'API_KEY');
spl_autoload_register('class_autoload');

/*$id = $sms->sendSMS('AlphaSMS', '380959289006', 'Текст сообщения на русском языка в UTF-8 любой длинны');

if($sms->hasErrors()){
	//echo 'login: '.SMS_LOGIN.' passw: '.SMS_PASSW.'<br>';
	die(var_dump($sms->getErrors()));
}else
	var_dump($id);
*/
?>