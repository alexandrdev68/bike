<?php
	if(@$_GET['test'] == 'yes'){
		for($time = 0; $time <= 420000; $time += 600){
			$arPeriod = BIKE::getTimeBetween(0, $time);
			$period = $arPeriod['days'].' d. '.$arPeriod['hours'].' h. '.$arPeriod['minutes'].' m.';
			$amount = 10 * floor(($time - 15 * 60) / 3600) + 10; 
			echo 'period.: '.$period.' amount.:'.BIKE::getRentAmount($time).'<br>';
		}
		exit;
	}
	
	$db = new Dbase();

	
	$arBikesInStore = array();
	$arBikesOnRent = array();
	$store_title = TEMP::$Lang['store_address'];

	if(isset($_GET['store_id'])){
		$store_id = $_GET['store_id'];
		foreach($_SESSION['STORES'] as $value){
			if($value['id'] == $store_id) $store_title = $value['adress'];
		}
	}else $store_id = '';
	
	$sql1 = "SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`s`.`adress` FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` 
								WHERE `b`.`id` >= 0 AND `b`.`on_rent` = 'no' ".
								($store_id === '' ? '' : "AND `s`.`id` = {$store_id}")." ORDER BY `store_id` LIMIT 300";

	$sql2 = "SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`r`.`id`,
					`r`.`bike_id`,
					`r`.`time_start`,
					`r`.`project_time`,
					`r`.`klient_id`,
					`s`.`adress` FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` LEFT OUTER JOIN `rent` `r` ON `b`.`id` = `r`.`bike_id` 
								WHERE `b`.`id` >= 0 AND `b`.`on_rent` = `r`.`id` LIMIT 100";
	
	if(!empty($_GET['place'])){
		if($_GET['place'] == 'in_store'){
			//echo $sql1;
			$arBikes = $db->getArray($sql1);
		}elseif($_GET['place'] == 'in_rent'){
			$arBikes = $db->getArray($sql2);
		}
	}
	
	


	//print_r($arBikesInStore);


	

	$arDay = Array(
					0=>TEMP::$Lang['days'],
					1=>TEMP::$Lang['day'],
					2=>TEMP::$Lang['day1'],
					3=>TEMP::$Lang['day1'],
					4=>TEMP::$Lang['day1'],
					
				);

	$arHours = Array(
					0=>TEMP::$Lang['hours'],
					1=>TEMP::$Lang['hours1'],
					2=>TEMP::$Lang['hours2'],
					3=>TEMP::$Lang['hours2'],
					4=>TEMP::$Lang['hours2'],
					
				);

	$arMinutes = Array(
					0=>TEMP::$Lang['minutes'],
					1=>TEMP::$Lang['minutes2'],
					2=>TEMP::$Lang['minutes1'],
					3=>TEMP::$Lang['minutes1'],
					4=>TEMP::$Lang['minutes1'],
					
				);


	//print_r($arBikesOnRent);
?>