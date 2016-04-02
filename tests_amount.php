<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/bike_lib_inc.php';

$rent_time = 3600; 

for($i = $rent_time; $i < 864001; $i += 3600){
	echo 'rent time: '.($rent_time / 120).' amount: '.BIKE::getRentAmount($rent_time).'<br>';
}
?>