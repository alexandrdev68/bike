<?class USER extends Dbase {
	
	static public $arUserFields = array(
								'name', 
								'patronymic',
								'surname',
								'login',
								'password',
								'photo',
								'user_level',
								'properties',
								'email',
								'phone'
								);
	static public $minpasswlen = 6;
	static public $maxpasswlen = 45;
	static public $currUserProperties = array();
	
	/**
	 * Возвращает данные о пользователе по его login или false если пользователя не существует.
	 * Пример: print_r(USER::getInfo('20202'));
	 * @var static function
	 */
	static public function getInfo($login){
		$sql = 'SELECT id, 
						name, 
						patronymic,
						surname,
						login,
						password,
						photo,
						user_level,
						properties,
						email,
						phone FROM users WHERE login = "'.$login.'" LIMIT 1';
		$arRes = self::getData($sql);
		return count($arRes) > 0 ? $arRes[0] : false;
	}
	
	static public function getClientByPhone($phone){
		$phone = Dbase::dataFilter($phone);
		
		
		$db = new Dbase();
		
		$sql_where = 'WHERE (u.phone ='.$phone.') AND u.user_level = 4 LIMIT 10';
		
		$sql = 'SELECT `u`.`id`,
					`u`.`name`,
					`u`.`login`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`properties`
					 FROM `users` `u` '.$sql_where;
		
		$arResult = $db->getArray($sql);
			
		if($arResult === false){
			Dbase::writeLog($sql);
			return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);
		} 
		foreach($arResult as $num=>$user){
			$arResult[$num]['properties'] = json_decode($user['properties'], true);
		}
		$response = array('status'=>'ok', 'find'=>$arResult, 'count'=>count($arResult));
		return $response;
	}


	/**
	 * Возвращает полные данные о пользователе (включая и данные о велосипеде, если таковой числиться за ним)
	 * по его id в базе или false если пользователя не существует.
	 * Пример: print_r(USER::getFullInfo(8));
	 * @var static function
	 */
	static public function getFullInfo($id){
		if(BIKE_ACTION){
			$sql = 'SELECT 	`u`.`name`, 
						`u`.`patronymic`,
						`u`.`surname`,
						`u`.`login`,
						`u`.`photo`,
						`u`.`properties`,
						`u`.`email`,
						`u`.`phone`,
						`u`.`user_level`,
						`r`.`id` AS `rent_id`,
						`r`.`bike_id`,
						`r`.`time_start`,
						`r`.`time_end`,
						`r`.`project_time`,
						`r`.`properties` AS `rent_prop`,
						`b`.`model`,
						`b`.`serial_id`,
						`b`.`foto`,
						`a`.`klient_id` as `action_klient`,
						`a`.`renttime_summ` as `action_time`
						 FROM `users` `u` 
							LEFT OUTER JOIN `rent` `r` ON `r`.`klient_id` = `u`.`id` 
							AND `r`.`time_end` = 0
							LEFT OUTER JOIN `bikes` `b` ON `b`.`on_rent` = `r`.`id` 
							LEFT OUTER JOIN `action` `a` ON `u`.`id` = `a`.`klient_id` WHERE `u`.`id` = "'.$id.'" LIMIT 1';
		}else{
			$sql = 'SELECT 	`u`.`name`, 
						`u`.`patronymic`,
						`u`.`surname`,
						`u`.`login`,
						`u`.`photo`,
						`u`.`properties`,
						`u`.`email`,
						`u`.`phone`,
						`u`.`user_level`,
						`r`.`id` AS `rent_id`,
						`r`.`bike_id`,
						`r`.`time_start`,
						`r`.`time_end`,
						`r`.`project_time`,
						`r`.`properties` AS `rent_prop`,
						`b`.`model`,
						`b`.`serial_id`,
						`b`.`foto`
						 FROM `users` `u` 
							LEFT OUTER JOIN `rent` `r` ON `r`.`klient_id` = `u`.`id` 
							AND `r`.`time_end` = 0
							LEFT OUTER JOIN `bikes` `b` ON `b`.`on_rent` = `r`.`id` WHERE `u`.`id` = "'.$id.'" LIMIT 1';
		}
		
		//echo $sql; die();
		$arRes = self::getData($sql);
		if(count($arRes) > 0){
			$arRes[0]['properties'] = json_decode($arRes[0]['properties'], true);
			$arRes[0]['rent_prop'] = @$arRes[0]['rent_prop'] == null ? '' : json_decode($arRes[0]['rent_prop'], true);
		}
		return count($arRes) > 0 ? $arRes[0] : false;
	}


	/**
	 * Возвращает уровень пользователя по его login или false если пользователя не существует.
	 * Пример: USER::getLevel('20202'));
	 * @var static function
	 */
	static public function getLevel($login){
		$sql = 'SELECT user_level FROM users WHERE login = "'.$login.'" LIMIT 1';
		$arRes = self::getData($sql);
		return count($arRes) > 0 ? $arRes[0]['user_level'] : false;
	}

	
	/**
	 * Добавляет нового пользователя.
	 * Пример: USER::add(array('login'=>1029384756102938,
	 * 							'phone'=>'380677777777',
	 * 							'name'=>'Jon',
	 * 							'patronymic'=>'Jones',
	 * 							'surname'=>'Jonson',
	 * 							'photo'=>'/img/uuuu001.jpg', 
	 * 							'user_level'=>3));
	 * @var static function
	 */
	static public function add($arFields, $filtr = true){
		
		if(mb_strlen($arFields['login'], 'utf-8') < 3){
			self::addMess(TEMP::$Lang['SYSTEM']['wrong_login']);
			self::writeLog();
			return false;
		}
		if(!isset($arFields['user_level'])){
			self::addMess(TEMP::$Lang['SYSTEM']['user_level_not_set']);
			self::writeLog();
			return false;
		}elseif((mb_strlen(@$arFields['password'], 'utf-8') < self::$minpasswlen || mb_strlen($arFields['password'], 'utf-8') > self::$maxpasswlen) && $arFields['user_level'] != 4){
			self::addMess(TEMP::$Lang['SYSTEM']['password_to_small1'].self::$minpasswlen.TEMP::$Lang['SYSTEM']['password_to_small2']);
			self::writeLog();
			return false;
		}elseif((mb_strlen(@$arFields['password'], 'utf-8') >= self::$minpasswlen || mb_strlen(@$arFields['password'], 'utf-8') <= self::$maxpasswlen) && $arFields['user_level'] != 4){
			if(CRYPT_SHA512 == 1){
				$arFields['password'] = crypt($arFields['password'], '$6$rounds=5000$'.self::getHash().'$');
				
			}
			else self::addMess(TEMP::$Lang['SYSTEM']['encrypt_system_invalid']);
		}
		
		//проверка существует ли пользователь с таким же именем
		if(self::getInfo($arFields['login']) !== false){
			self::addMess(TEMP::$Lang['SYSTEM']['mess_login_was_created1'].$arFields['login'].TEMP::$Lang['SYSTEM']['mess_login_was_created2']);
			self::writeLog();
			return false;
		}
		
		
		//формирование sql запроса
		$sql_p1 = "INSERT INTO users (";
		$sql_p2 = ") VALUES (";
		$count = count($arFields);
		$i = 1;
		foreach($arFields as $index=>$field){
			if(in_array($index, self::$arUserFields) && ($field != ''|| $field != NULL)){
				$sql_p1 .= $index;
				$sql_p2 .= (gettype($field) == 'integer' ? "" : "'").($filtr === true ? self::dataFilter($field) : $field).(gettype($field) == 'integer' ? "" : "'");
				if($i < $count){
					$sql_p2 .=', ';
					$sql_p1 .=', '; 
				}
				else $sql_p2 .= ')';
					
			}
			$i++;
		}
		//echo $sql_p1.$sql_p2; die();
		if(mysql_query($sql_p1.$sql_p2) !==false){
			self::addMess(TEMP::$Lang['SYSTEM']['mess_user_added']);
			self::writeLog();
			return true;
		}
		else {
			self::addMess(TEMP::$Lang['SYSTEM']['mess_user_not_added'].$sql_p1.$sql_p2);
			self::writeLog();
			return false;
		}
	}
	
	/**
	 * Изменяет данные пользователя c id  переданным в качестве параметра (3-й параметр отключает/включает фильтр данных).
	 * Пример: USER::change(array('login'=>1029384756102938,
	 * 							'properties=>json_encode(array('store'=>3, 'live_place'=>'vul. Ak.Uschenka')),
	 * 							'name'=>'Jon',
	 * 							'patronymic'=>'Jones',
	 * 							'surname'=>'Jonson',
	 * 							'photo'=>'/img/uuuu001.jpg', 
	 * 							'user_level'=>3), 14, false);
	 * @var static function
	 */
	static public function change($arFields, $id, $filtr = true){
		if(mb_strlen($arFields['login'], 'utf-8') < 3){
			self::addMess(TEMP::$Lang['SYSTEM']['wrong_login']);
			self::writeLog();
			return false;
		}
		if(!isset($arFields['user_level'])){
			self::addMess(TEMP::$Lang['SYSTEM']['user_level_not_set']);
			self::writeLog();
			return false;
		}
		
	//формирование sql запроса
		$sql = "UPDATE users SET ";
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
			self::writeLog();
			return true;
		}
		else {
			self::addMess(TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql);
			self::writeLog();
			return false;
		}
		
	}
	
	static protected function getHash(){
		$hash = '';
		for($i=0;$i<16;$i++){
		  $j = mt_rand(0,53);
		  if($j<26)$hash .= chr(rand(65,90));
		  else if($j<52)$hash .= chr(rand(97,122));
		  else if($j<53)$hash .= '.';
		  else $hash .= '/';
   		}
   		return $hash;
	}
	
	/**
	 * Авторизирует ранее зарегистрированного пользователя, если авторизация
	 * прошла неуспешно возвращает false и в Dbase::$messages добавляет сообщение об ошибке.
	 * Если авторизация прошла успешно возвращает уровень пользователя и записывает данные о пользователе в сессию
	 * Пример: USER::authorize(array('uuid'=>1029384756102938', 'password'=>'mypassw'));
	 * @var static function
	 */
	static public function authorize($arFields){
		$userinfo = self::getInfo($arFields['login']);
		if(isset($_SESSION['CURRUSER'])) unset($_SESSION['CURRUSER']);
		if(mb_strlen($arFields['login'], 'utf-8') < 3){
				self::addMess(TEMP::$Lang['SYSTEM']['wrong_login']);
				self::writeLog();
				return false;
			}elseif(strlen($arFields['password']) < self::$minpasswlen){
				self::addMess(TEMP::$Lang['SYSTEM']['password_to_small1'].self::$minpasswlen.TEMP::$Lang['SYSTEM']['password_to_small2']);
				self::writeLog();
				return false;
			}elseif (!$userinfo){
				self::addMess(TEMP::$Lang['SYSTEM']['mess_login_was_created1'].$arFields['login'].TEMP::$Lang['SYSTEM']['not_found']);
				self::writeLog();
				return false;
			}elseif(crypt($arFields['password'], $userinfo['password']) == $userinfo['password']){
				$_SESSION['CURRUSER'] = $userinfo;
				self::addMess(TEMP::$Lang['SYSTEM']['mess_login_was_created1'].$arFields['login'].TEMP::$Lang['SYSTEM']['authorize_success']);
				self::writeLog();
				//print_r($_SESSION['CURRUSER']);
				return $userinfo['user_level'];
			}elseif(crypt($arFields['password'], $userinfo['password']) != $userinfo['password']){
				self::addMess(TEMP::$Lang['SYSTEM']['wrong_passw']);
				self::writeLog();
				return false;
			}
	}
	
	static public function client_authorize($arClientData){
		if(count($arClientData) == 0){
			Dbase::writeLog('client data is not set (client_authorize)');
			return false;
		}
		$userinfo = self::getFullInfo($arClientData['id']);
		$_SESSION['CURRUSER'] = $userinfo;
		Dbase::writeLog('client with phone'.$_SESSION['CURRUSER']['phone'].' has been authorize');
		return true;
	}
	
	static public function lastMessage(){
		$c = count(self::$messages)-1;
		return self::$messages[$c];
	}
	
	static public function logout(){
		if(isset($_SESSION['CURRUSER'])){
			unset($_SESSION['CURRUSER']);
		}else{
			Dbase::writeLog('logout impossible, becouse didn\'t login');
		}
	}
	
    /**
	 * Удаляет файл по пути $path и добавляет сообщение в массив $messages
	 * Возвращает true если файл успешно удален
	 * Пример: USER::delFile('upload/temp/photo.jpg');
	 * @var function
	 */
    static public function delFile($path){
    	
    	if(file_exists($path) == false){
    		self::addMess(TEMP::$Lang['SYSTEM']['file_not_exists'].$path);
    		self::writeLog();
    		return false;
    	}
    	if(unlink($path) === true){
    		self::addMess(TEMP::$Lang['SYSTEM']['file_was_deleted'].$path);
    		self::writeLog();
    		return true;
    	}else{
    		self::addMess(TEMP::$Lang['SYSTEM']['file_wrong_delete'].$path);
    		self::writeLog();
    		return false;
    	}
    }

    /**
	 * Удаляет пользователя с указанным id (не uuid)
	 * Возвращает true операция прошла успешно
	 * Пример: USER::delete(30);
	 * @var static function
	 */
    static public function delete($user_id){
    	$arUinfo = self::getFullInfo($user_id);
    	if($arUinfo['bike_id'] != ''){
    		self::addMess(TEMP::$Lang['SYSTEM']['bike_on_this_user']);
    		return false;
    	}elseif($arUinfo['photo'] != ''){
    		$delFile = self::delFile("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/{$arUinfo['photo']}");
    	}
    	
    	$sql = 'DELETE FROM users WHERE id = '.(is_numeric($user_id) ? $user_id : 0);
    	$result = mysql_query($sql);
    	if($result !== false){
			self::addMess(TEMP::$Lang['SYSTEM']['user_was_deleted']);
			return true;
    	}else{
    		self::addMess(TEMP::$Lang['SYSTEM']['user_with_id1'].$user_id.TEMP::$Lang['SYSTEM']['user_with_id2'].$sql);
    		return false;
    	}
    }

    /**
	 * Возвращает true если текущий пользователь admin
	 * Пример: USER::isAdmin();
	 * @var static function
	 */
    static public function isAdmin(){
    	if(@$_SESSION['CURRUSER']['user_level'] == 552071 || @$_SESSION['CURRUSER']['user_level'] == 552081) return true;
    	else return false;
    }
    
    static public function isSuperAdmin(){
    	if(@$_SESSION['CURRUSER']['user_level'] == 552081) return true;
    	else return false;
    }
    
    static public function isAuthorize(){
    	if(isset($_SESSION['CURRUSER']['user_level'])){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    static public function isClient(){
    	if(@$_SESSION['CURRUSER']['user_level'] == 4) return true;
    	else return false;
    }
    
    //функция генерации пароля возвращает 4 варианта пароля в индексном массиве
	static public function passwGen($count){
       $tmpArr = Array();
       for($i=1; $i<=$count*2; $i++){
               switch(rand(0,2)){
                       case 0:$val = rand(48, 57);
                       break;
                       case 1:$val = rand(65, 90);
                       break;
                       case 2:$val = rand(97, 122);
                       break;
               };
               $tmpArr[$i] = chr($val);
       };
       for($i=1; $i<=$count; $i++){
               @$tmpArr['string'] .= $tmpArr[rand(1,$count)];
               @$tmpArr['string1'] .= $tmpArr[($i*2)];
               @$tmpArr['string2'] .= $tmpArr[($i+$count)];
               @$tmpArr['string3'] .= $tmpArr[$i];

       };
       $res[] = $tmpArr['string'];$res[] = $tmpArr['string1'];$res[] = $tmpArr['string2'];$res[] = $tmpArr['string3'];
       return $res;
	}
	
	static public function smscodeGen($count = 8){
		$code = '';
		if($count > 20)
			return false;
		for($i = 0; $i < $count; $i++){
			$code .= rand(0, 9);
		}
		return $code;
	}
}?>