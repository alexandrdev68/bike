<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/dbase_lib_inc.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/user_lib_inc.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/bike_lib_inc.php';

$rent_time = 3600; 

for($i = $rent_time; $i < 864001; $i += 245){
	$arDiff = BIKE::getTimeBetween(0, $i); 
		$days = $arDiff['days'];
		$hours = $arDiff['hours'];
		$minutes = $arDiff['minutes'];
		$seconds = $arDiff['seconds'];
		echo 'rent time: '.$days.'days, '.$hours.' hours, '.$minutes.' minutes, '.$seconds.' seconds. '
			.' amount: '.(BIKE::getRentAmount($i)).'hrn. <br>';
	
}
?>