<?php
require_once('./smsclient.class.php');

//init class with your login/password
$sms = new SMSclient('', '', 'API_KEY');

/*
sendSMS(from (phone, name),to phone, message, timestamp when to send sms, wap-push link, flash flag (bool));
*/

//send on SMS and receive it's id for tracking
//message in UTF-8
$id = $sms->sendSMS('AlphaSMS', '380931234567', 'Текст сообщения на русском языка в UTF-8 любой длинны');

//send WAP-PUSH message
//$id = $sms->sendSMS('Alpha Name','0931234567', 'Самый классынй сайт!', time(), 'http://alphasms.com.ua/');

//send flash message at certain time
//$id = $sms->sendSMS('80501234567','80931234567', 'Flash message in english letters only!', strtotime('+1 minute'), '', 1);

//just for usage - text can be translierated to use less symbols in sms
//echo SMSclient::translit('Текст сообщения на русском языка в UTF-8 любой длинны');


//if no ID - then message is not sent and you should check errors
if($sms->hasErrors())
	die(var_dump($sms->getErrors()));
else
	var_dump($id);
	
//doing something interesting...
sleep(20);

//track message status after about 0,5 minute.
$res = $sms->receiveSMS($id);
var_dump($res);
var_dump($sms->getResponse());//full data

//get amount of money in account
var_dump($sms->getBalance());