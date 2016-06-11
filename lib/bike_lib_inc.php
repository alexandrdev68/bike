<?php
class BIKE extends USER{
	
	static public $firstHourAmount = 40;
	static public $dayAmount = 200;
	static public $nextHourAmount = 20;
	static public $secondHourAmount = 30;
	static public $added = 20;
	static public $timeBuffer = 15;

/**
	 * Удаляет велосипед с указанным id
	 * Возвращает true операция прошла успешно
	 * Пример: BIKE::delete(30);
	 * @var static function
	 */
    static public function delete($bike_id){
    	$arBinfo = self::getInfo($bike_id);
    	if($arBinfo['on_rent'] != 'no'){
    		self::addMess(TEMP::$Lang['SYSTEM']['bike_in_rent']);
    		return false;
    	}elseif($arBinfo['foto'] != ''){
    		$delFile = self::delFile("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/{$arBinfo['foto']}");
    		$delFile = self::delFile("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/bike_{$arBinfo['id']}_resized_640.jpg");
    	}
    	
    	$sql = 'DELETE FROM bikes WHERE id = '.(is_numeric($bike_id) ? $bike_id : 0);
    	$result = mysql_query($sql);
    	if($result !== false){
			self::addMess(TEMP::$Lang['SYSTEM']['bike_was_deleted']);
			return true;
    	}else{
    		self::addMess(TEMP::$Lang['SYSTEM']['bike_with_id1'].$user_id.TEMP::$Lang['SYSTEM']['user_with_id2'].$sql);
    		return false;
    	}
    }
    
/**
	 * Возвращает данные о велосипеде по его id или false если велосипеда не существует.
	 * Пример: print_r(BIKE::getInfo('20202'));
	 * @var static function
	 */
	static public function getInfo($id){
		$sql = 'SELECT id, 
						model, 
						store_id,
						properties,
						foto,
						on_rent,
						serial_id FROM bikes WHERE id = "'.$id.'" LIMIT 1';
		$arRes = self::getData($sql);
		return count($arRes) > 0 ? $arRes[0] : false;
	}
	
	static public function getStoresAdresses($store_id = null){
		if(!isset($_SESSION['STORES'])){
			$db = new Dbase();
			$sql = "SELECT `id`, adress FROM `store`";
			$arRes = $db->getArray($sql);
			$_SESSION['STORES'] = $arRes;
		}
		if($store_id === null)
			return $_SESSION['STORES'];
		else{
			foreach($_SESSION['STORES'] as $store){
				if($store['id'] == $store_id)
					return $store['adress'];
			}
		}
		return false;
	}
	
	/**
	 *  Стартует отчет времени проката велосипеда с переданным id для пользователя с переданным id
	 *  также записывает в базу количество времени проката, переданное в часах
	 *  возвращает true в случае если операция прошла успешно
	 *  Пример:BIKE::startRent(12, 10, 1, 0); где $added - дополнительная одноразовая плата за услугу
	 */
	static public function startRent($bike_id, $user_id, $time, $added){
		//проверяем нет ли в прокате этого велосипеда или на пользователе не числится велосипед
		//$sql = "SELECT `bike_id`, `klient_id` FROM `rent` WHERE (`bike_id` = {$bike_id} OR `klient_id` = {$user_id}) AND `time_end` = 0";
		$sql1 = "SELECT `bike_id`, `klient_id` FROM `rent` WHERE `bike_id` = {$bike_id} AND `time_end` = 0";
		$result1 = mysql_query($sql1);
		$sql2 = "SELECT `bike_id`, `klient_id` FROM `rent` WHERE `klient_id` = {$user_id} AND `time_end` = 0";
		$result2 = mysql_query($sql2);
		
		$rows1 = mysql_num_rows($result1);
		
		$rows2 = mysql_num_rows($result2);
		
		
		//echo($sql); die();
		if($rows1 > 0){
			self::addMess(TEMP::$Lang['SYSTEM']['bike_in_rent']);
    		return false;
		}elseif($rows2 >= 3){
			self::addMess(TEMP::$Lang['SYSTEM']['user_in_rent']);
    		return false;
		}
		
		//получаем данные о велосипеде для записи номера пункта проката в информацию о прокате
		$bikeInfo = self::getInfo($bike_id);
		if($bikeInfo !== false){
			$store_id = $bikeInfo['store_id'];
		}else{
			$store_id = NULL;
		}
		
		$time_start = time();
		//переводим часы в секунды
		$time = $time * 3600;
		
		$added *= 100;
		$added_json = addslashes(json_encode(array('added'=>$added)));
		
		$sql1 = "INSERT INTO `rent` (`bike_id`, `klient_id`, `time_start`, `store_start`, `project_time`, `properties`) 
									VALUES ({$bike_id}, {$user_id}, {$time_start}, {$store_id}, {$time}, '{$added_json}')";
		$result1 = mysql_query($sql1);
		$last_id = mysql_insert_id();
		$sql2 = "UPDATE `bikes` SET `on_rent` = '{$last_id}' WHERE `id` = {$bike_id}";
    	$result2 = mysql_query($sql2);
    	if($result1 !== false && $result2 !== false){
			self::addMess(TEMP::$Lang['SYSTEM']['rent_was_started']);
			return true;
    	}else{
    		self::addMess(TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql1.' or '.$sql2);
    		return false;
    	}
	}
	
	
	/**
	 *  Останавливает отчет времени проката велосипеда с переданным id велосипеда и пункта приема
	 *  возвращает метку времени останова проката, в случае если операция прошла успешно или false
	 *  Пример:BIKE::stopRent(12, 2, 1020910921, 3600);
	 */
	static public function stopRent($bike_id, $store_id, $time_start, $project_time, $added, $action_client = null){
		$currTime = time();
		$discount = 0;
		$rent_period = $currTime - $time_start;
		
		/*if($rent_period > $project_time){
			$amount += self::$nextHourAmount * floor(($rent_period - $project_time - self::$timeBuffer * 60) / 3600) + self::$nextHourAmount; 
		}
		*/
		//смотрим или был заказ на сутки, если да, считаем по суточному тарифу
		if((fmod($project_time, 86400) == 0) && ($rent_period < $project_time)){
			$amount = self::getRentAmount($project_time);
		}else{
			$amount = self::getRentAmount($rent_period);
		}
		
		//формуємо знижку
		if(isset(USER::$currUserProperties['war_veterane']) && USER::$currUserProperties['war_veterane'] == 'yes'){
			$discount += BIKE::$firstHourAmount;
		}
		
		$amount = ($amount * 100) + (int)$added;
		
		//підраховуємо суму зі знижкою
		if($amount >= $discount){
			$amount = $amount - ($discount * 100);
		}

		$sql = "UPDATE `rent` SET `time_end` = {$currTime}, `amount` = {$amount}, `store_finish` = {$store_id} WHERE `bike_id`= {$bike_id} AND `time_end` = 0";
		$sql2 = "UPDATE `bikes` SET `on_rent` = 'no', `store_id` = {$store_id} WHERE `id` = {$bike_id}";
		
		$result = mysql_query($sql);
		$result2 = mysql_query($sql2);
		
		if($result === false || $result2 === false){
			self::addMess(TEMP::$Lang['SYSTEM']['error_stop_rent'].$sql.'   '.$sql2);
			return false;
		}
		
		//под акцию
		if($action_client !== null){
			$sql3 = "UPDATE `action` SET `renttime_summ` = `renttime_summ` + {$rent_period}, `amount_summ` = `amount_summ` + {$amount} WHERE `klient_id` = {$action_client}";
			$result3 = mysql_query($sql3);
			if($result3 === false){
				self::addMess(TEMP::$Lang['SYSTEM']['error_stop_rent'].$sql3);
				return false;
			}
		}
		
		return array('time_stop'=>$currTime, 'amount'=>$amount);
	}

	/** Возвращает разницу между датами в виде ассоциативного
	* массива. В качестве параметра указываются метки времени.
	*	Пример: self::getTimeBetween(0, 125543235);
	*/
	static public function getTimeBetween($date1 , $date2){
		date_default_timezone_set('Europe/London');
		$datetime1 = new DateTime(date('d-m-Y H:i:s', $date1));
		$datetime2 = new DateTime(date('d-m-Y H:i:s', $date2));
		$interval = $datetime1->diff($datetime2); 
		$arRet['days'] = $interval->format('%d');
		$arRet['hours'] = $interval->format('%H');
		$arRet['minutes'] = $interval->format('%i');
		$arRet['seconds'] = $interval->format('%s');
		return $arRet;
	}
	
/**
	 * Изменяет данные велосипеда c id  переданным в качестве параметра (3-й параметр отключает/включает фильтр данных).
	 * Пример: BIKE::change(array('model'=>'aist',
	 * 							'serial_id'=>3,
	 * 							), 14, false);
	 * @var static function
	 */
	static public function change($arFields, $id, $filtr = true){
	//формирование sql запроса
		$sql = "UPDATE `bikes` SET ";
		$count = count($arFields);
		$i = 1;
		foreach($arFields as $index=>$field){
			if($field != ''|| $field != NULL){
				$sql .= $index.' = '.(gettype($field) == 'integer' ? "" : "'").($filtr === true ? self::dataFilter($field) : $field).(gettype($field) == 'integer' ? "" : "'");
				if($i < $count){
					$sql .=', '; 
				}	
			}
			$i++;
		}
		$sql .= " WHERE id = ".$id;
		if(mysql_query($sql) !==false){
			self::addMess(TEMP::$Lang['SYSTEM']['store_changed_success']);
			return true;
		}
		else {
			self::addMess(TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql);
			return false;
		}
		
	}
	
	
	/** Вычисляет стоимость аренды. В качестве параметра передается
	 *  количество времени аренды в секундах.
	 */
	static public function getRentAmount($rent_seconds){
	
		$arDiff = self::getTimeBetween(0, $rent_seconds);
		
		$amount = 0;
	
		$days = $arDiff['days'];
		$hours = $arDiff['hours'];
		$minutes = $arDiff['minutes'];
	
		if($minutes > self::$timeBuffer){
			$hours++;
		}
		if($days > 0){
			$amount += self::$dayAmount * $days;
			$amount += self::$nextHourAmount * $hours;
		}elseif($hours > 0){
			$amount += ($hours == 1 ? self::$firstHourAmount : 0);
			$amount += ($hours == 2 ? self:: $secondHourAmount  + self::$firstHourAmount: 0);
			$amount += ($hours >= 3 ? (self::$nextHourAmount * ($hours - 2))  + self:: $secondHourAmount + self::$firstHourAmount: 0);
		}
		
	
		return $amount;
	}
	
        /**
         * вертає наявність броні на велосипед з переданим id
         * @param type $id
         * @param type $date_tstmp
         */
	static public function getBikeBookingsByDate($id, $date_tstmp){
		$sqlSelect = 'SELECT
					`r`.`id`,
					`r`.`bike_id`,
					`r`.`time_start`,
					`r`.`project_time`,
					`r`.`klient_id`,
					`r`.`properties` AS `rent_prop`,
					`b`.`id`,
					`b`.`model`,
					`b`.`store_id`,
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`s`.`adress`,
					`u`.`name`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`phone`';
		
		$sqlWhere = " FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id`
							LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id`
							LEFT OUTER JOIN `users` `u` ON `r`.`klient_id` = `u`.`id`
							 WHERE `r`.`time_end` = 0 AND `r`.`bike_id` = {$id} ORDER BY `r`.`time_start` LIMIT 100";
		
		$sql = $sqlSelect.$sqlWhere;
		
		$db = new Dbase();
		
		$arBooking = $db->getArray($sql);
		
		if($arBooking === false){
			Dbase::writeMessage('(getBikeBookingsByDate) трапилась помилка під час отримання інформації про бронювання на велосипед: '.$id);
			return false;
		}else{
			return $arBooking;
		}
		
	}

	/**
	 * Возвращает склоненное слово в соответствии с массивом склонений
	 */
	static public function declension($value, $arDeclens){	//$value-число; $arDeclens - массив склонений типа [значение]=>['склонение']
											//[0]=>стандартное склонение, под другими значениями принимаються исключения (числа от 10 до 20 выводятся как стандарт)
		if($value>20){
			$ak = fmod($value, 10);
		}else{
			$ak = fmod($value, 100);
		};
		foreach($arDeclens as $index=>$decl){
			if($ak == $index){
				return $decl;
				break;
			};
		};
		return $arDeclens[0];
	}

	/** Возвращает информацию про закрытые прокаты на пункте (если без привязки к пункту - не передавать) за период времени переданный в функцию
	 *  (передаются метки времени в формате юникс)
	 */
	static public function getRentsFromPeriod($date_from, $dato_to, $store = 'no'){
		$sql = "SELECT `r`.`time_start`,
						`r`.`id` AS `rent_id`,
						`r`.`time_end`,
						`r`.`store_start`,
						`r`.`store_finish`,
						`r`.`project_time`,
						`r`.`amount`,
						`b`.`model`,
						`b`.`id`,
						`b`.`serial_id`,
						`b`.`store_id`,
						`u`.`name`,
						`u`.`surname`,
						`u`.`patronymic` FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id` 
														LEFT OUTER JOIN `users` `u` ON `u`.`id` = `r`.`klient_id` 
														WHERE `r`.`time_end` >= {$date_from} AND `r`.`time_end` <= {$dato_to}  AND `r`.`time_end` <> 0"
														.($store == 'no' ? '' : " AND ((`r`.`store_start` = {$store} && `r`.`store_finish` <> '') 
														|| `r`.`store_finish` = {$store})")
														." AND `r`.`amount` <> -1 ORDER BY `r`.`time_end` LIMIT 5000";
		//echo $sql; exit;
		$arRents = self::getData($sql);
		//print_r($arRents); exit;
		foreach($arRents as $index=>$rent){
			$arRents[$index]['real_time'] = $rent['time_end'] - $rent['time_start'];
			if($rent['store_start'] != $rent['store_finish']){
				$project_amount = BIKE::getRentAmount($rent['project_time']) * 100;
				$arRents[$index]['project_amount'] = $project_amount;
				$real_amount = (int)$rent['amount'];
				if((int)$real_amount > (int)$project_amount){
					$diff_amount = $real_amount - $project_amount;
					$arRents[$index]['amount'] = $arRents[$index]['amount'] - $diff_amount;
					$arDiffStoreRent[0] = $rent;
					$arDiffStoreRent[0]['amount'] = $diff_amount;
					if($store !== 'no' && $arDiffStoreRent[0]['store_finish'] == $store){
						unset($arRents[$index]);
						$arRents = TEMP::insert_in_array($arRents, $index, $arDiffStoreRent);
					}
				}elseif((int)$real_amount < (int)$project_amount){
					$diff_amount = $real_amount - $project_amount;
					$arRents[$index]['amount'] = $project_amount;
					$arDiffStoreRent[0] = $rent;
					$arDiffStoreRent[0]['amount'] = $diff_amount;
					if($store !== 'no' && $arDiffStoreRent[0]['store_finish'] == $store){
						unset($arRents[$index]);
						$arRents = TEMP::insert_in_array($arRents, $index, $arDiffStoreRent);
					}
				}
			}
		}
		return $arRents;
	}
	
	
	/** Возвращает информацию про все прокаты велосипеда с переданным id
	 *  (передаются метки времени в формате юникс)
	 */
	static public function getBikeRents($bike_id){
		$sql = "SELECT `r`.`time_start`,
						`r`.`id` AS `rent_id`,
						`r`.`time_end`,
						`r`.`project_time`,
						`r`.`amount`,
						`b`.`model`,
						`b`.`id`,
						`b`.`serial_id` FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id` 
														WHERE `b`.`id` = {$bike_id} AND `r`.`amount` <> -1 ORDER BY `b`.`id` LIMIT 10000";

		$arRes = self::getData($sql);
		return $arRes;
	}

	/** Возвращает информацию про велосипеды, которые числятся на клиенте с id, переданным в качестве пар-ра
	 *  
	 */
	static public function getKlientBikes($id_klient){
		$sql = "SELECT `b`.`id` AS `bike_id`,
						`b`.`model`,
						`b`.`serial_id`,
						`b`.`properties` AS `bike_prop`,
						`r`.`project_time`,
						`r`.`properties` AS `rent_prop`,
						`r`.`id` AS `rent_id` FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id` WHERE `r`.`klient_id` = {$id_klient} AND `r`.`time_end` = 0";

		$arRes = self::getData($sql);
		foreach($arRes as $num=>$bike){
			$arRes[$num]['rent_prop'] = json_decode($bike['rent_prop'], true);
			$arRes[$num]['bike_prop'] = json_decode($bike['bike_prop'], true);
		}

		return count($arRes) == 0 ? false : $arRes;
	}
	
	
/*Функция возвращает навигационную цепочку
	входные параметры: $curr_page - текущая страница; $pages - всего страниц; $len - длина вывода цепочки (>=6)
	возвращает индексный массив, где значениями являются номера страниц и:-
	"curr" - означает, что страница является текущей;
	"pred" - кнопка "Предыдущая страница";
	"next"- кнопка "Следующая страница";
	"all" - кнопка "Вывести все";
	"first" - обычно выводится как троеточие вначале цепочки;
	"last" - обычно выводится как троеточие в конце цепочки.
	
	индекс "current" - номер текущей страницы
	*/
	static public function build_nav($curr_page, $pages, $len){
	   //длинна цепочки не может быть меньше 6
	   if($len < 6) return false;
	   if($pages <= $len){
	       $index = 1;
	       if($curr_page > 1){
	       $result[$index] = '<';
	       $index++;
	       }
	       for($c = 1; $c <= $pages; $c++){
	           if($c == $curr_page){
	               $result[$index] = 'curr';
	               $index++;
	           }else{
	               $result[$index] = $c;
	               $index++;
	           }
	       }
	       if($curr_page < $pages){
	           $result[$index] = '>';
	           $index++;
	       }
	       //$index++; $result[$index] = "all";
	   }elseif($pages > $len){
	       $index = 1;
	       $val = 1;
	       if($curr_page > 1){
	           $result[$index] = '<';
	           $index++;
	       }
	       if($curr_page == (int)$val){
	           $result[$index] = "curr";
	           $index++; $val++;
	       }
	       $result[$index] = $val;
	       $index++; $val++;
	       if($curr_page <= ceil(($len-1)/2)){
	           //текущая страница в первой половине видимости
	           for($c = 1; $c<=($len-2); $c++){
	               if($curr_page == (int)$val){
	                   $result[$index] = "curr";
	                   $index++; $val++;
	               }
	               $result[$index] = $val;
	               $index++; $val++;
	           }
	           $result[$index] = "...";
	           $index++;
	           $result[$index] = $pages; $index++;
	           if($curr_page < $pages)    $result[$index] = ">";
	           //$index++; //$result[$index] = "all";
	           $result['current'] = $curr_page; return $result;
	           break;
	       }else{
	           //текущая страница за пределами видимости
	           $result[$index] = "...";
	           $index++;
	           $val = $pages-$len+1;
	           if(($curr_page+ceil(($len-1)/2)) > $pages){
	               for($c = $val; $c< $pages; $c++){
	                   if($curr_page == (int)$val){
	                       $result[$index] = "curr";
	                       $index++; $val++;
	                   }
	                   $result[$index] = $val;
	                   $index++; $val++;
	                   if($curr_page < $pages)    $result[$index] = ">"; else $result[$index] = "curr";
	               }
	           }else{
	           $val = round($curr_page - ($len-4)/2);
	           for($c = 1; $c<=($len-4); $c++){
	                   if($curr_page == (int)$val){
	                       $result[$index] = "curr";
	                       $index++; $val++;
	                   }
	                   $result[$index] = $val;
	                   $index++; $val++;
	               }
	           $result[$index] = "...";
	           $index++;
	           $result[$index] = $pages; $index++;
	           if($curr_page < $pages)    $result[$index] = ">";
	           }
	       }
	       }
	   //$index++;
	   //$result[$index] = 'all';
	   $result['current'] = $curr_page;
	   return $result;
	}
	
	//обрезает слишком большие номера страниц
	static public function navChainFilter($arChain){
		foreach ($arChain as $num=>$chain){
			if(is_numeric($chain) && $chain > 1000){
				$arChain[$num] = substr($chain, 0, 2).'...';
			}
		}
		return $arChain;
	}
}
?>