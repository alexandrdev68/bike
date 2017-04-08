<?php 
class Actions{
#---------------------------------------
	function registr_action_handler(){
		$id_user = Dbase::dataFilter(@$_POST['uLogin']);
		$user_level = (string)Dbase::dataFilter(@$_POST['uLevel']);
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		$phone = Dbase::dataFilter($_POST['uPhone']);
		$another_place = (isset($_POST['another_city']) ? Dbase::dataFilter($_POST['another_city']) : 'no');
		$war_veterane = (isset($_POST['war_veterane']) ? Dbase::dataFilter($_POST['war_veterane']) : 'no');
		
		//если регистрируем клиента - логин не нужен
		if($user_level == 4) $id_user = $phone;
		$pass = @$_POST['uPassword'];
		$repeat_pass = @$_POST['uConfirmPassword'];
		//проверка полей формы
		if(mb_strlen($id_user, 'utf-8') < 3){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_login']);
			return json_encode($response);
		}elseif(!isset($_POST['uLevel'])){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['user_level_not_set']);
			return json_encode($response);
		}elseif(!preg_match('/^[0-9]{12,12}$/', $phone) && @$_POST['uLevel'] == 4){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_phone']);
			return json_encode($response);
		}elseif(mb_strlen($pass, 'utf-8') < USER::$minpasswlen && @$_POST['uLevel'] != 4){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['password_to_small1'].USER::$minpasswlen.TEMP::$Lang['SYSTEM']['password_to_small2']);
			return json_encode($response);
		}elseif($pass !== $repeat_pass && @$_POST['uLevel'] != 4){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_confirm_passw']);
			return json_encode($response);
		}
		
		//загрузка и изменение размера фото
		if(isset($_FILES) && count($_FILES) > 0){
			foreach($_FILES as $index=>$foto){
				$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/", "klient_{$id_user}_{$index}.jpg", MAX_FOTO_SIZE, $index);
				if($res !== false && !is_array($res)){
					//если файл загрузился успешно изменяем размер фото
					$resized = Graph::imgResize(RES_IMGX, RES_IMGY, $res, false);
					$imagepath .= mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1).';';
					//print_r($imagepath);die();
					if($res === false){
						$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
						return json_encode($response);
					}
				}elseif(is_array($res)) return json_encode($res);
				else{
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
					return json_encode($response);
				}
			}//foreach
		}else $imagepath = '';
		
		//видаляємо лишній символ в кінці строки
		$imagepath = mb_substr($imagepath, 0, mb_strlen($imagepath) - 1);

		
		$arProperties = array('another_place'=>$another_place);
		//формирование допсвойств, если они заданы
		if(isset($_POST['resStore'])){
			$arProperties['store'] = $_POST['resStore'];
		}
		if($war_veterane == 'yes')
			$properties['war_veterane'] = $war_veterane;
		
		//добавление пользователя в БД
		$arFields = array('login'=>$id_user,
	  							'name'=>(string)$name_user,
    							'password'=>$pass,
	  							'patronymic'=>(string)$lastname_user,
	  							'surname'=>(string)$surname_user,
								'phone'=>$phone,
								'properties'=>json_encode($arProperties),
	  							'photo'=>$imagepath, 
	  							'user_level'=>(string)@$_POST['uLevel']);
		if(USER::add($arFields)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage());
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		return json_encode($response);
	}
#---------------------------------------

	function edit_user_handler(){
		$id_user = Dbase::dataFilter($_POST['uLogin']);
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		$id = Dbase::dataFilter($_POST['uId']);
		$phone = Dbase::dataFilter($_POST['uPhone']);
		$another_place = (isset($_POST['another_place']) ? Dbase::dataFilter($_POST['another_place']) : 'no');
		$arUser = USER::getFullInfo($id);
		$war_veterane = (isset($_POST['war_veterane']) ? Dbase::dataFilter($_POST['war_veterane']) : 'no');
		
		//розпарсуємо строку фото в масив для перевірки на видалення
		$arPhoto = explode(';', $arUser['photo']);
		$arImagepath = array();
		
		$imagepath = '';
		
		
		if(mb_strlen($id_user, 'utf-8') < 3){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_login']);
			return json_encode($response);
		}elseif(!isset($_POST['uLevel'])){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['user_level_not_set']);
			return json_encode($response);
		}
		
		//загрузка и изменение размера фото
		if(isset($_FILES) && count($_FILES) > 0){
			
			foreach($_FILES as $index=>$foto){
				$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/", "klient_{$id_user}_{$index}.jpg", MAX_FOTO_SIZE, $index);
				if($res !== false && !is_array($res)){
					//если файл загрузился успешно изменяем размер фото
					$resized = Graph::imgResize(RES_IMGX, RES_IMGY, $res, false);
					$arImagepath[] = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
					
				
					//print_r($imagepath);die();
					if($res === false){
						$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
						return json_encode($response);
					}
				}elseif(is_array($res)) return json_encode($res);
				else{
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
					return json_encode($response);
				}
			}//foreach
		}else $imagepath = '';
		
		if(count($arImagepath) >= count($arPhoto)){
			//if added extra foto or same as early then change path in DB field
			$imagepath = implode(';', $arImagepath);
		}else{
			// if count of photo less as early then do nothing
			$imagepath = '';
		}
		
		//формирование допсвойств, если они заданы
		if(isset($_POST['resStore']) || isset($_POST['uLivePlace'])){
			if($arUser === false) $response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request']);
			$arProperties = $arUser['properties'] == 'null' ? array() : $arUser['properties'];
			if(isset($_POST['resStore']) && $_POST['resStore'] != '') $arProperties['store'] = $_POST['resStore'];
			if(isset($_POST['uLivePlace']) && $_POST['uLivePlace'] != '') $arProperties["live_place"] = Dbase::dataFilter($_POST['uLivePlace']);
			if(isset($_POST['blackList']) && $_POST['blackList'] != '') $arProperties['blackList'] = Dbase::dataFilter($_POST['blackList']);
			else $arProperties['blackList'] = 'off';
		}
		$arProperties['another_place'] = $another_place;
		if($war_veterane == 'yes')
			$arProperties['war_veterane'] = $war_veterane;
		elseif($war_veterane == 'no' && isset($arProperties['war_veterane'])) {
			unset($arProperties['war_veterane']);
		}
		
		//print_r($arProperties); exit;
		
		//изменение данных пользователя в БД
		$arFields = array('login'=>$id_user,
	  							'name'=>(string)$name_user,
	  							'patronymic'=>(string)$lastname_user,
	  							'surname'=>(string)$surname_user,
								'properties'=>addslashes(json_encode($arProperties)),
	  							'photo'=>$imagepath,
								'phone'=>$phone, 
	  							'user_level'=>(string)@$_POST['uLevel']);
		//если пользователь не менял фото
		if($imagepath == '') unset($arFields['photo']);
		
		
		if(USER::change($arFields, $id, false)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage(), 'uploaded_photo'=>$imagepath == '' ? 'no' : 'yes');
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function edit_bike_handler(){
		$model = Dbase::dataFilter(@$_POST['bModel']);
		$serial = Dbase::dataFilter(@$_POST['bSerial']);
		$store_id = Dbase::dataFilter(@$_POST['bPlace']);
		$cost = str_replace(',', '.', Dbase::dataFilter(@$_POST['bCost']));
		$id = $_POST['bNumber'];
		if(mb_strlen($model, 'utf-8') < 2){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['small_bike_name']);
			return json_encode($response);
		}elseif(!preg_match('/^([0-9]{1,})$|^[0-9]{1,}\\.(?:[0-9]{1,2})$/', $cost)){
			$response = array('status'=>'error', 'message'=>"Невірно введено суму");
			return json_encode($response);
		}
		$arBike = BIKE::getInfo($id);
		
		$cost = (int)($cost * 100);
		
		//добавляем к другим свойствам, если есть
		$properties = array();
		if(!empty($arBike['properties'])){
			$properties = json_decode($arBike['properties'], true);
			$properties['cost'] = $cost;
		}else{
			$properties['cost'] = $cost;
		}
		//завантажуємо фото, якщо воно є
		if(!isset($_POST['foto'])){
			$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/", "bike_{$id}.jpg");
			if($res !== false && !is_array($res)){
				//если файл загрузился успешно изменяем размер фото
				$resized = Graph::imgResize(640, 400, $res, true);
				$resized = Graph::imgResize(150, 50, $res, false);
				$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
				//print_r($imagepath);die();
				if($res === false){
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
					return json_encode($response);
				}
			}elseif(is_array($res)) return json_encode($res);
			else{
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
				return json_encode($response);
			}
		}else $imagepath = '';
		
		$arFields = array('model'=>$model,
	  							'store_id'=>(string)$store_id,
	  							'serial_id'=>(string)$serial,
								'properties'=>json_encode(array('cost'=>$cost)),
	  							'foto'=>$imagepath);
		if($imagepath == '') unset($arFields['foto']);
		
		if(BIKE::change($arFields, $id, false)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage(), 'uploaded_photo'=>$imagepath == '' ? 'no' : 'yes');
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function login_action_handler(){
		$auth = USER::authorize(array('login'=>Dbase::dataFilter($_POST['uLogin']), 'password'=>$_POST['uPassw']));
		if($auth !== false){
			$response = array('status'=>'ok', 'user_level'=>$auth);
			//print_r($_SESSION['CURRUSER']); die();
			return json_encode($response);
		}else{
			$response = array('status'=>'bad', 'message'=>USER::lastMessage());
			return json_encode($response);
		}
	}
	
#---------------------------------------
	
	function verife_client_auth(){
		
		//verifying client authorise
		if(isset($_COOKIE['auth'])){
				
			if(isset($_SESSION['CURRUSER'])){
				ob_start();
				$token = hash('ripemd160', implode('', $_SESSION['CURRUSER']));
				ob_clean();
				//if client was authorised
				if($token == $_COOKIE['auth']){
						
					$response = array('status'=>'authorised', 'message'=>"client has been authorised early, authorisation doesn't needed");
					Dbase::writeLog('login client. '.$response['message']);
					return json_encode($response);
						
				}
			}
				
		}
		
	}
	
#----------------------------------------

	function login_client_handler(){
		
		$this->verife_client_auth();
		
		$phone = Dbase::dataFilter($_POST['phone']);
		$operation = Dbase::dataFilter($_POST['operation']);
		if(!preg_match('/^[0-9]{12,12}$/', $phone)){
			$response = array('status'=>'bad', 'message'=>'phone is not valid');
			Dbase::writeLog('login client. Phone is not valid: '.$phone);
			return json_encode($response);
		}
		if($operation == 'auth'){
			$_SESSION['sms_code'] = '';
			$arClientInfo = array();
			$arClientInfo = USER::getClientByPhone($phone);
			
			if($arClientInfo['status'] == 'ok'){
				TEMP::putInCash('clientInfo', $arClientInfo['find'][0]);
				if($arClientInfo['count'] == 1){
					$code = USER::smscodeGen();
					Dbase::writeLog('login client. sms code was generated');
					$response = array('status'=>'ok', 'type'=>'auth', 'message'=>TEMP::$Lang['SYSTEM']['sms_sent_mess']);
					$_SESSION['sms_code'] = $code;
					$text = 'your sms code: '.$code;
					//відправка смс
					TEMP::sendSMS_test($phone, $text);
				}elseif($arClientInfo['count'] > 1){
					Dbase::writeLog('login client. There are more than 1 clients by this phone: '.$phone);
					$response = array('status'=>'bad', 'type'=>'auth', 'message'=>'There are more than 1 clients by this phone');
				}elseif($arClientInfo['count'] == 0){
					TEMP::deleteFromCash('clientInfo');
					Dbase::writeLog('login client. Client by this phone is not found: '.$phone);
					$response = array('status'=>'bad', 'type'=>'auth', 'message'=>'Client by this phone is not found');
				}
			}else{
				$response = array('status'=>'bad', 'type'=>'auth', 'message'=>'server error');
				Dbase::writeLog('login client. error in get client by phone from DB');
			}
			
		}elseif($operation == 'registration'){
			//реєстрація користувача
			$_SESSION['sms_code'] = '';
			$firstname = Dbase::dataFilter($_POST['firstname']);
			$lastname = Dbase::dataFilter($_POST['lastname']);
			$secondname = Dbase::datafilter($_POST['secondname']);
			$arClientInfo = USER::getClientByPhone($phone);
			if($arClientInfo['status'] == 'ok'){
				if($arClientInfo['count'] == 0){
					//добавление пользователя в БД
					$properties = array(
							'from_payment_form'=>true
					);
					$imagepath = '';
					$arFields = array('login'=>(string)$phone,
							'name'=>(string)$firstname,
							'phone'=>(string)$phone,
							'patronymic'=>(string)$lastname,
							'surname'=>(string)$secondname,
							'properties'=>addslashes(json_encode ($properties)),
							'photo'=>$imagepath,
							'user_level'=>'4');
					//print_r($arFields); die();
					if(USER::add($arFields, false)){
						$response = array('status'=>'ok', 'type'=>'registration', 'phone'=>$phone, 'message'=>USER::lastMessage());
						Dbase::writeLog('new client was add. Phone: '.$phone.' name: '.$firstname);
					}else{
						$response = array('status'=>'error', 'type'=>'registration', 'message'=>USER::lastMessage());
						Dbase::writeLog('new client add error. Phone: '.$phone.' name: '.$firstname);
					}
				}elseif($arClientInfo['count'] > 0){
					$response = array('status'=>'error', 'type'=>'registration', 'message'=>TEMP::$Lang['more_than_one_user_txt']);
					Dbase::writeLog('for this phone client has registered. Phone: '.$phone.' name: '.$firstname);
				}
			}else{
				$response = array(
						'status'=>'bad',
						'message'=>'server error'
				);
				Dbase::writeLog('client registration. error while pass data from DB. phone: '.$phone);
			}
		}elseif($operation == 'smsconfirm'){
			//перевірка смс
			$_POST['smscode'] = Dbase::dataFilter($_POST['smscode']);
			if($_POST['smscode'] == $_SESSION['sms_code']){
				if(USER::client_authorize(TEMP::getFromCash('clientInfo')) === true){
					$response = array('status'=>'ok', 'type'=>'smsconfirm', 'message'=>TEMP::$Lang['auth_succesfull_txt']);
				};
			}else{
				$response = array('status'=>'bad', 'type'=>'smsconfirm', 'message'=>TEMP::$Lang['sms_code_wrong_txt']);
			}
			
			$_SESSION['sms_code'] = '';
		}
		
		
		return json_encode($response);
	}
#---------------------------------------
	function logout_handler(){
		USER::logout();
		$response = array('status'=>'ok');
		return json_encode($response);
	}
#---------------------------------------
	function get_users_list_handler(){
		if(USER::isAdmin()){
			$db = new Dbase();
			$rows = Dbase::getCountRowsOfTable('users');
			$curr_page = ceil(@$_POST['from_user_id'] / 100);
			$offset = @$_POST['from_user_id'];
			$pages = ceil($rows / 100);
			$len = 8;
			$arNav = array();
			if($pages > 1){
				$arNav = BIKE::build_nav($curr_page + 1, $pages, $len);
			}
			$sql = 'SELECT id, 
					name,
					login, 
					patronymic,
					surname,
					photo, 
					properties,
					email,
					phone,
					user_level FROM users LIMIT '.$offset.', 100';
			$arResult = $db->getArray($sql);
			foreach($arResult as $num=>$user){
				switch ($user['user_level']){
					case 552071 :
						$arResult[$num]['user_level'] = 'Administrator';
						break;
					case 1 :
						$arResult[$num]['user_level'] = 'Reseption';
						break;
					case 2 :
						$arResult[$num]['user_level'] = 'User';
						break;
					case 4 :
						$arResult[$num]['user_level'] = 'Klient';
						break;
				}
				$arResult[$num]['properties'] = json_decode($user['properties'], true);
			}
			$response = array('status'=>'ok', 'users_list'=>$arResult, 'nav'=>$arNav);
			return json_encode($response);
		}else{
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['you_dont_access']);
			return json_encode($response);
		}
	}
#---------------------------------------
	function get_bikes_list_store_handler(){
			$db = new Dbase();
			$from_id = Dbase::dataFilter(@$_POST['from_bike_id']);
			if(@$_POST['filter'] == 'in_store') $on_rent = 'no';
			elseif(@$_POST['filter'] == 'on_rent') $on_rent = 1;
			
			//если у текущего пользователя есть допсвойства
			if(mb_strlen($_SESSION['CURRUSER']['properties'], 'utf-8') > 0){
				$properties = json_decode($_SESSION['CURRUSER']['properties'], true);
			}
			
			$sqlSelect = 'SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`s`.`adress`';
			
			//если пользователю ограничен обзор велосипедов только одним пунктом
			if(isset($properties['store']) && !USER::isAdmin() && $on_rent == 'no'){
				$store_id = (int)$properties['store'];
				$sqlWhere = ' FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `b`.`store_id` = `s`.`id` WHERE `b`.`id` >= '.$from_id.' AND `b`.`on_rent` = "'.$on_rent.'" AND `b`.`store_id` = '.$store_id.' ORDER BY `b`.`id` LIMIT 100';
			}elseif($on_rent == 1){
				/*$sqlSelect = 'SELECT `b`.`id`, 
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
					`r`.`properties` AS `rent_prop`,
					`s`.`adress`,
					`u`.`name`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`phone`';
				$sqlWhere = 'LEFT OUTER JOIN `rent` `r` ON `b`.`id` = `r`.`bike_id` LEFT OUTER JOIN `users` `u` ON `u`.`id` = `r`.`klient_id` WHERE `b`.`id` >= '.$from_id.' AND `b`.`on_rent` = `r`.`id` ORDER BY `r`.`time_start` LIMIT 100';*/
				
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
					`u`.`properties` as user_properties,
					`u`.`phone`';
				
				$sqlWhere = " FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id` 
							LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` 
							LEFT OUTER JOIN `users` `u` ON `r`.`klient_id` = `u`.`id`
							 WHERE `r`.`time_end` = 0 AND `b`.`on_rent` != 'no' ORDER BY `r`.`time_start` LIMIT 100";
			}else{
				
				$sqlWhere = ' FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `b`.`store_id` = `s`.`id` WHERE `b`.`id` >= '.$from_id.' AND `b`.`on_rent` = "'.$on_rent.'" ORDER BY `b`.`id` LIMIT 100';
			}
			
			$sql = $sqlSelect.$sqlWhere;
			//echo $sql; die();
			$arResult = $db->getArray($sql);
			if($arResult === false){
				$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql);
				return json_encode($response);
			}
			//print_r($arResult); die();
			$now = time() * 1000;
			foreach($arResult as $num=>$bike){
				if($bike['foto'] != '') $arResult[$num]['foto'] = 'upload/bikes/'.$bike['foto'];
				if($on_rent == 1){
					$arResult[$num]['now'] = $now;
					$arResult[$num]['rent_prop'] = json_decode($bike['rent_prop'], true);
					$arResult[$num]['user_properties'] = json_decode($bike['user_properties'], true);
					$arResult[$num]['project_amount'] = BIKE::getRentAmount($bike['project_time']);
				}
			}
			$response = array('status'=>'ok', 'bikes_list'=>$arResult);
			return json_encode($response);
	}
#---------------------------------------
	function user_delete_handler(){
		if(USER::delete(@$_POST['uid']) === true){
			$response = array('status'=>'ok', 'mess'=>TEMP::$Lang['SYSTEM']['user_was_deleted']);

		}else $response = array('status'=>'bad', 'mess'=>USER::lastMessage());
		return json_encode($response);
	}
#---------------------------------------
	function bike_delete_handler(){
		if(BIKE::delete(@$_POST['bid']) === true){
			$response = array('status'=>'ok', 'mess'=>TEMP::$Lang['SYSTEM']['bike_was_deleted']);
		}else $response = array('status'=>'bad', 'mess'=>TEMP::$Lang['SYSTEM']['user_with_id2']);
		return json_encode($response);
	}
#---------------------------------------
	function add_bike_handler(){
		$model = Dbase::dataFilter(@$_POST['bModel']);
		$serial = Dbase::dataFilter(@$_POST['bSerial']);
		$number = Dbase::dataFilter(@$_POST['bNumber']);
		$store_id = Dbase::dataFilter(@$_POST['bPlace']);
		if(mb_strlen($model, 'utf-8') < 2){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['small_bike_name']);
			return json_encode($response);
		}elseif(!preg_match('/^[0-9]{1,}$/', $number)){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['number_uncorrect']);
			return json_encode($response);
		}elseif(BIKE::getInfo($number) !== false){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['bike_number_dublicat']);
			return json_encode($response);
		}
		//print_r(@$_FILES);die();
		//завантажуємо фото, якщо воно є
		if(!isset($_POST['foto'])){
			$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/", "bike_{$number}.jpg");
			if($res !== false && !is_array($res)){
				//если файл загрузился успешно изменяем размер фото
				$resized = Graph::imgResize(640, 400, $res, true);
				$resized = Graph::imgResize(150, 50, $res, false);
				$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
				//print_r($imagepath);die();
				if($res === false){
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
					return json_encode($response);
				}
			}elseif(is_array($res)) return json_encode($res);
			else{
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
				return json_encode($response);
			}
		}
		//записываем в БД новый велосипед
		$foto = isset($imagepath) ? $imagepath : '';
		$sql = "INSERT INTO `bikes` (`id`, `model`, `store_id`, `foto`, `serial_id`) VALUES ({$number}, '{$model}', {$store_id}, '{$foto}', '{$serial}')";
		if(Dbase::$PDOConnection->exec($sql) !==false){
			$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['bike_add_success']);
		}
		else {
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_add_bike']);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function find_user_handler(){
			if(USER::isClient())
				return json_encode(array('status'=>'bad', 'message'=>'you havn\'t access'));
			$db = new Dbase();
			$_POST['key'] = Dbase::dataFilter(@$_POST['key']);
			/*if(USER::isAdmin()){
				$sql_where = 'WHERE (u.login LIKE "%'.@$_POST['key'].
						'%" OR u.name LIKE "%'.@$_POST['key'].
						'%" OR u.patronymic LIKE "%'.@$_POST['key'].
						'%" OR u.surname LIKE "%'.@$_POST['key'].
						'%" OR u.phone LIKE "%'.@$_POST['key'].
						'%") AND u.user_level = 4 LIMIT 20';
			}else{
				$sql_where = 'WHERE (u.login LIKE "%'.@$_POST['key'].
						'%" OR u.phone LIKE "%'.@$_POST['key'].
						'%") AND u.user_level = 4 LIMIT 20';
			}*/
			
			$sql_where = 'WHERE (u.login LIKE "%'.@$_POST['key'].
						'%" OR u.name LIKE "%'.@$_POST['key'].
						'%" OR u.patronymic LIKE "%'.@$_POST['key'].
						'%" OR u.surname LIKE "%'.@$_POST['key'].
						'%" OR u.phone LIKE "%'.@$_POST['key'].
						'%") AND u.user_level = 4 LIMIT 20';
			
			//если действует акция
			if(BIKE_ACTION){
				$sql = 'SELECT `u`.`id`, 
					`u`.`name`,
					`u`.`login`, 
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`photo`, 
					`u`.`properties`,
					`u`.`phone`,
					`u`.`user_level`,
					`a`.`klient_id` as `action_klient`,
					`a`.`renttime_summ` as `action_time` FROM `users` `u` LEFT OUTER JOIN `action` `a` ON `u`.`id` = `a`.`klient_id` '.$sql_where;
			}else{
				$sql = 'SELECT `u`.`id`, 
					`u`.`name`,
					`u`.`login`, 
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`photo`, 
					`u`.`properties`,
					`u`.`phone`,
					`u`.`user_level`
					 FROM `users` `u` '.$sql_where;
			}
			
			$arResult = $db->getArray($sql);
			
			if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);
			foreach($arResult as $num=>$user){
				$arResult[$num]['properties'] = json_decode($user['properties'], true);
			}
			$response = array('status'=>'ok', 'find'=>$arResult);
			return json_encode($response);
	}
	
#---------------------------------------	
	function find_client_by_phone_handler(){
		
		$this->verife_client_auth();
		
		$db = new Dbase();
		$_POST['phone'] = Dbase::dataFilter(@$_POST['phone']);
		
		$sql_where = 'WHERE (u.phone ='.@$_POST['phone'].') AND u.user_level = 4 LIMIT 10';
		
		$sql = 'SELECT `u`.`id`,
					`u`.`name`,
					`u`.`login`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`properties`
					 FROM `users` `u` '.$sql_where;
		
		$arResult = $db->getArray($sql);
			
		if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);
		foreach($arResult as $num=>$user){
			$arResult[$num]['properties'] = json_decode($user['properties'], true);
		}
		$response = array('status'=>'ok', 'find'=>$arResult);
		return json_encode($response);
	}
	
#---------------------------------------

	function add_klient_handler(){
		$id_user = Dbase::dataFilter(@$_POST['uPhone']);
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		$live_place = Dbase::dataFilter($_POST['uLivePlace']);
		$another_place = (isset($_POST['another_city']) ? Dbase::dataFilter($_POST['another_city']) : 'no');
		$war_veterane = (isset($_POST['war_veterane']) ? Dbase::dataFilter($_POST['war_veterane']) : 'no');
		$imagepath = '';
		//echo json_encode($live_place); die();
		$phone = Dbase::dataFilter(@$_POST['uPhone']);
		//проверка полей формы
		if(mb_strlen($id_user, 'utf-8') < 3){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_login']);
			return json_encode($response);
		}elseif(!preg_match('/^[0-9]{12,12}$/', $phone)){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_phone']);
			return json_encode($response);
		}elseif(isset($_POST['ufoto'])){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['photo_missing']);
			return json_encode($response);
		}elseif(USER::getInfo($id_user) !== false){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['mess_phone_was_created1'].$id_user.TEMP::$Lang['SYSTEM']['mess_login_was_created2']);
			return json_encode($response);
		}
		
		//загрузка и изменение размера фото
		if(isset($_FILES) && count($_FILES) > 0){
			foreach($_FILES as $index=>$foto){
				$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/", "klient_{$id_user}_{$index}.jpg", MAX_FOTO_SIZE, $index);
				if($res !== false && !is_array($res)){
					//если файл загрузился успешно изменяем размер фото
					$resized = Graph::imgResize(RES_IMGX, RES_IMGY, $res, false);
					$imagepath .= mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1).';';
					//print_r($imagepath);die();
					if($res === false){
						$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
						return json_encode($response);
					}
				}elseif(is_array($res)) return json_encode($res);
				else{
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
					return json_encode($response);
				}
			}//foreach
		}else $imagepath = '';
		
		//видаляємо лишній символ в кінці строки
		$imagepath = mb_substr($imagepath, 0, mb_strlen($imagepath) - 1);
		
		//ініціюємо масив властивостей
		$properties = array('another_place'=>$another_place);
		if($live_place != ''){
			$properties['live_place'] = $live_place;
		}
		if($war_veterane == 'yes')
			$properties['war_veterane'] = $war_veterane;

		//добавление пользователя в БД
		$arFields = array('login'=>(string)$id_user,
	  							'name'=>(string)$name_user,
    							'phone'=>(string)$phone,
	  							'patronymic'=>(string)$lastname_user,
	  							'surname'=>(string)$surname_user,
	  							'properties'=>addslashes(json_encode ($properties)),
	  							'photo'=>$imagepath, 
	  							'user_level'=>'4');
		//print_r($arFields); die();
		if(USER::add($arFields, false)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage());
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function search_like_this_handler(){
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		
		//делаем поиск таких же клиентов, введенных ранее
		$sql = "SELECT `id`, `name`, `patronymic`, `surname`, `phone`, `properties` FROM `users` 
							WHERE `name` LIKE '%{$name_user}%' AND `patronymic` LIKE '%{$lastname_user}%'";
		
		$db = new Dbase();
		$arUsersLikeNew = $db->getArray($sql);
		if($arUsersLikeNew !== false){
			foreach($arUsersLikeNew as $num=>$user){
				$arUsersLikeNew[$num]['properties'] = json_decode($user['properties'], true);
			}
		}
		
		$response['status'] = 'ok';
		$response['users_likes_this'] = $arUsersLikeNew;
		//$response['sql'] = $sql;
		
		return json_encode($response);
	}
#---------------------------------------
	function go_rent_handler(){
		$bike_id = Dbase::dataFilter(@$_POST['bike_id']);
		$user_id = Dbase::dataFilter(@$_POST['user_id']);
		$rent_period = Dbase::dataFilter(@$_POST['rent_period']);
		$print_flag = @$_POST['print'] == 'true' ? true : false;
		$seat_flag = @$_POST['seat'] == 'true' ? true : false;
		$white_day = $rent_period == -10 ? true : false;
		$added = 0;
		$rent_period = $rent_period * 3600;
		
		if(isset($_POST['bike_action']) && $_POST['bike_action'] == 'true'){
			//print_r($_POST); exit;
			$db = new Dbase();
			
			//проверка на уникальность смс-ключа
			do{
				$sms_code = USER::passwGen(6);
				$sms_code = $sms_code[rand(0,3)];
				$sql = "SELECT `sms_code` FROM `actions` WHERE `sms_code` = '{$sms_code}'";
				$arRes = $db->getArray($sql);
			}while($arRes !== false);
			
			
			$arUser = USER::getFullInfo($_POST['user_id']);
			if($arUser === false){
				$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request']);
				return json_encode($response);
			}
			$sql = "SELECT `klient_id` FROM `actions` WHERE `klient_id` = {$_POST['user_id']}";
			$arRes = $db->getArray($sql);
			if($arRes == false){
				$arRes = DBase::setRecord(array('table'=>'action', 'fields'=>array(
		             													'klient_id'=>$_POST['user_id'],
		               													'time_start'=>time(),
		              													'amount_summ'=>0,
		               													'renttime_summ'=>0,
		               													'sms_code'=>$sms_code
	               												)));
	            if($arRes['status'] == true){
	            	$arRes = TEMP::sendSMS($arUser['phone'], TEMP::$Lang['txtsms_congratulation_action_start'].$sms_code , true);
	            	if($arRes['status'] === false){
	            		/*$response = array('status'=>'bad', 'message'=>$arRes['error']);
						return json_encode($response);*/
	            	}
	            }
			}
			
		}
		
		//инициализация дополнительных параметров
		$extra_data = array('added'=>0);
		if($seat_flag === true)
			$extra_data['added'] = BIKE::$added * 100;
		if($white_day === true){
			$date = new DateTime();
			$timestampNow = $date->getTimestamp();
			$date->setTime(20, 0);
			$timestampDayEnd = $date->getTimestamp();
			$rent_period = $timestampDayEnd - $timestampNow;
			$extra_data['white_day'] = BIKE::$whiteDayAmount;
		}
		
		$added_json = addslashes(json_encode($extra_data));
		
		if(BIKE::startRent($bike_id, $user_id, $rent_period, $added_json) === true){
			$response = array('status'=>'ok', 'print'=>'no', 'message'=>USER::lastMessage());

			if($print_flag === true){
				$_SESSION['PRINT']['type'] = 'contract';
				$_SESSION['PRINT']['info'] = USER::getFullInfo($user_id);
				$_SESSION['PRINT']['info']['bikes'] = BIKE::getKlientBikes($user_id);
				if(isset($_POST['bike_action']) && $_POST['bike_action'] != 'false') $_SESSION['PRINT']['action_ofert'] = true;
				else $_SESSION['PRINT']['action_ofert'] = false;
				$response['print'] = 'yes';
				//$response['print_data'] = $_SESSION['PRINT'];
			}
		}else{
			$response = array('status'=>'bad', 'message'=>USER::lastMessage());
		}

		return json_encode($response);
	}
#---------------------------------------	
	function stop_rent_handler(){
		$db = new Dbase();
		$bike_id = Dbase::dataFilter(@$_POST['bike_id']);
		$store_id = Dbase::dataFilter(@$_POST['store_id']);
		$user_id = Dbase::dataFilter(@$_POST['user_id']);
		$added = 0;
		
		if(BIKE_ACTION){
			//получаем полные данные о пользователе
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
						`b`.`store_id`,
						`a`.`klient_id` as `action_klient`,
						`a`.`renttime_summ` as `action_time`
						 FROM `users` `u` 
							LEFT OUTER JOIN `rent` `r` ON `r`.`klient_id` = `u`.`id` 
							AND `r`.`time_end` = 0 AND `r`.`bike_id` = '.$bike_id.'
							LEFT OUTER JOIN `bikes` `b` ON `b`.`on_rent` = `r`.`id` AND `b`.`id` = '.$bike_id.' 
							LEFT OUTER JOIN `action` `a` ON `a`.`klient_id` = `u`.`id` WHERE `u`.`id` = "'.$user_id.'" LIMIT 1';
		}else{
			//получаем полные данные о пользователе
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
						`b`.`store_id`
						 FROM `users` `u` 
							LEFT OUTER JOIN `rent` `r` ON `r`.`klient_id` = `u`.`id` 
							AND `r`.`time_end` = 0 AND `r`.`bike_id` = '.$bike_id.'
							LEFT OUTER JOIN `bikes` `b` ON `b`.`on_rent` = `r`.`id` AND `b`.`id` = '.$bike_id.' WHERE `u`.`id` = "'.$user_id.'" LIMIT 1';
		}
		
		//echo $sql; die();
		$arInfo = $db->getArray($sql);
		if(count($arInfo) > 0){
			$arInfo[0]['properties'] = json_decode($arInfo[0]['properties'], true);
			USER::$currUserProperties = $arInfo[0]['properties'];
			$arInfo[0]['rent_prop'] = @$arInfo[0]['rent_prop'] == null ? '' : json_decode($arInfo[0]['rent_prop'], true);
			USER::$currRentProperties = $arInfo[0]['rent_prop'];
		}
		$arInfo = $arInfo[0];
		
		//print_r($arInfo); exit;
		
		if(@$arInfo['rent_prop']['added'] > 0) $added += BIKE::$added * 100;
		
		
		unset($arInfo['foto']);
		unset($arInfo['time_end']);
		unset($arInfo['photo']);
		
		if(isset($arInfo['action_klient'])){
			$arStop = BIKE::stopRent($bike_id, $store_id, $arInfo['time_start'], $arInfo['project_time'], $added, $arInfo['action_klient']);
		}else 
			$arStop = BIKE::stopRent($bike_id, $store_id, $arInfo['time_start'], $arInfo['project_time'], $added);
		
		if($arStop !== false){
			$response = array('status'=>'ok', 'stopTime'=>$arStop['time_stop'], 'rent_amount'=>((int)$arStop['amount'] / 100), 'fullInfo'=>$arInfo, 'message'=>TEMP::$Lang['SYSTEM']['stop_rent_sucess']);

		}else $response = array('status'=>'bad', 'message'=>USER::lastMessage());
		
		
		return json_encode($response);
		
	}
#---------------------------------------	
	function get_bike_rents_for_date_handler(){
		
		$date = strtotime(Dbase::dataFilter($_POST['date']));
        $bike_id = Dbase::dataFilter($_POST['bike_id']);
                
                
		if($date == null){
			$date = strtotime(date('dd.mm.YYYY', time()));
		}
		
        $arBookings = Bike::getBikeBookingsByDate($bike_id, $date);
        
        if($arBookings !== false){
        	return json_encode(array('status'=>'ok', 'bookings'=> $arBookings));
        }else{
        	return json_encode(array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM'['error_get_bookings']]));
        }
		
	}
#---------------------------------------
	function get_user_info_handler(){
		$klient_id = Dbase::dataFilter(@$_POST['klient_id']);

		$arUser = USER::getFullInfo($klient_id);

		if($arUser === false){
			$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request']);
		}else{
			if($arUser['photo'] != ''){
				
				//формуємо шлях до фото та визначаємо чи є додаткові фото
				$arFoto = explode(';', $arUser['photo']);
				$arUser['extra_photo'] = array();
				
				foreach($arFoto as $index=>$name_photo){
					$arUser['extra_photo'][] = 'upload/klients/'.$name_photo;
					$ind = $index;
				}
				
				$arUser['photo'] = $arUser['extra_photo'][0];
				//$arUser['extra_photo'] = array_splice($arUser['extra_photo'], 1);
			}
			$arUser['now'] = time();
			//$arUser['properties'] = json_decode($arUser['properties']);
			$response = array('status'=>'ok', 'info'=>$arUser);
		} 
		
		return json_encode($response);
	}
#---------------------------------------
	function search_main_handler(){
		//поиск среди велосипедов на складе
		if(isset($_POST['#_bikesAllPage'])){
			$search = Dbase::dataFilter($_POST['#_bikesAllPage']);

			$db = new Dbase();
			$sql = 'SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`s`.`adress` FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` WHERE `b`.`id` >= 0 AND `b`.`on_rent` = \'no\' AND (`b`.`model` LIKE "%'.$search.'%" OR `b`.`serial_id` LIKE "%'.$search.'%" OR `s`.`adress` LIKE "%'.$search.'%") LIMIT 20';
			$arResult = $db->getArray($sql);
			if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);

			foreach($arResult as $num=>$bike){
				if($bike['foto'] != '') $arResult[$num]['foto'] = 'upload/bikes/'.$bike['foto'];
			}

		}elseif(isset($_POST['#_bikesRent'])){
			$search = Dbase::dataFilter($_POST['#_bikesRent']);

			$db = new Dbase();

			$sql = 'SELECT `b`.`id`, 
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
					`s`.`adress`,
					`u`.`name`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`phone` FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` LEFT OUTER JOIN `rent` `r` ON `b`.`id` = `r`.`bike_id` LEFT OUTER JOIN `users` `u` ON `u`.`id` = `r`.`klient_id` WHERE `b`.`id` >= 0 AND `b`.`on_rent` = `r`.`id` AND (`b`.`model` LIKE "%'.$search.'%" OR `b`.`serial_id` LIKE "%'.$search.'%" OR `s`.`adress` LIKE "%'.$search.'%") LIMIT 20';
			$arResult = $db->getArray($sql);
			if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);

			$now = time() * 1000;
			foreach($arResult as $num=>$bike){
				$arResult[$num]['now'] = $now;
			}

		}elseif(isset($_POST['#_usListPage'])){
			$search = Dbase::dataFilter($_POST['#_usListPage']);

			$db = new Dbase();
			//если действует акция
			if(BIKE_ACTION){
				$sql = 'SELECT `u`.`id`, 
					`u`.`name`,
					`u`.`login`, 
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`photo`, 
					`u`.`properties`,
					`u`.`phone`,
					`u`.`user_level`,
					`a`.`klient_id` as `action_klient`,
					`a`.`renttime_summ` as `action_time` FROM `users` `u` LEFT OUTER JOIN `action` `a` ON `u`.`id` = `a`.`klient_id` WHERE (login LIKE "%'.$search.'%" OR name LIKE "%'.$search.'%" OR patronymic LIKE "%'.$search.'%" OR surname LIKE "%'.$search.'%" OR phone LIKE "%'.$search.'%") LIMIT 20';
			}else{
				$sql = 'SELECT id, 
						name,
						login, 
						patronymic,
						surname,
						photo, 
						properties,
						phone,
						user_level FROM users WHERE (login LIKE "%'.$search.'%" OR name LIKE "%'.$search.'%" OR patronymic LIKE "%'.$search.'%" OR surname LIKE "%'.$search.'%" OR phone LIKE "%'.$search.'%") LIMIT 20';
			}
			$arResult = $db->getArray($sql);
			foreach($arResult as $num=>$user){
				switch ($user['user_level']){
					case 552071 :
						$arResult[$num]['user_level'] = 'Administrator';
						break;
					case 1 :
						$arResult[$num]['user_level'] = 'Reseption';
						break;
					case 2 :
						$arResult[$num]['user_level'] = 'User';
						break;
					case 4 :
						$arResult[$num]['user_level'] = 'Klient';
						break;
				}
			}

		}
		$response = array('status'=>'ok', 'find'=>$arResult);
		return json_encode($response);
	}
#---------------------------------------
	function get_stores_handler(){
		$response = array('status'=>'ok', 'stores'=>$_SESSION['STORES']);
		return json_encode($response);
	}
#---------------------------------------
	function accept_stores_handler(){
		
		foreach (@$_POST['accepted'] as $store){
			$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_changed_nothing']);
			$store['adress'] = Dbase::dataFilter($store['adress']);
			if($store['store_id'] != 'new'){
				$sql = 'UPDATE `store` SET `adress` = "'.$store['adress'].'" WHERE `id` = '.$store['store_id'];
			}else{
				$sql = 'INSERT INTO `store` (`adress`) VALUES ("'.$store['adress'].'")';
			}
			if($store['adress'] == '') continue;
			else{
				if(Dbase::$PDOConnection->exec($sql) !==false){
					$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_changed_success']);
				}
				else {
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_change_store']);
				}
			}
			
		}
		$db = new Dbase();
		$sql = "SELECT `id`, adress FROM `store`";
		$arRes = $db->getArray($sql);
		$_SESSION['STORES'] = $arRes;
		return json_encode($response);
	}
#---------------------------------------
	function delete_stores_handler(){
		foreach (@$_POST['deleted'] as $store){
			$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_changed_nothing']);
			if($store['store_id'] != 'new'){
				$sql = 'DELETE FROM `store` WHERE `id` = '.$store['store_id'];
				if(Dbase::$PDOConnection->exec($sql) !==false){
						$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_deleted_success']);
				}
				else {
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_delete_store']);
				}
			}
			
		}
		$db = new Dbase();
		$sql = "SELECT `id`, adress FROM `store`";
		$arRes = $db->getArray($sql);
		$_SESSION['STORES'] = $arRes;
		return json_encode($response);
	}
#---------------------------------------
	function get_bike_by_id_handler(){
		$arRes = BIKE::getInfo(Dbase::dataFilter($_POST['bike_id']));
		if(!empty($arRes['properties'])){
			$arRes['properties'] = json_decode($arRes['properties'], true);
		}
		if($arRes !== false){
			if($arRes['foto'] != '') $arRes['foto'] = 'upload/bikes/bike_'.$_POST['bike_id'].'_resized_640.jpg';
			$response = array('status'=>'ok', 'bike_info'=>$arRes);
		}else $response = array('status'=>'error', 'message'=>USER::lastMessage());
		return json_encode($response);
	}
	
#---------------------------------------
	
	function get_bike_by_id_public_handler(){
		$arRes = BIKE::getInfo(Dbase::dataFilter($_POST['bike_id']));
		if(!empty($arRes['properties'])){
			$arRes['properties'] = json_decode($arRes['properties'], true);
		}
		if ($arRes !== false) {
            if ($arRes['foto'] != '')
                $arRes['foto'] = 'upload/bikes/bike_' . $_POST['bike_id'] . '_resized_640.jpg';
            $arRes['store_address'] = BIKE::getStoresAdresses($arRes['store_id']);
            $response = array('status' => 'ok', 'bike_info' => $arRes);
        }else {
            $response = array('status' => 'error', 'message' => USER::lastMessage());
        }
        return json_encode($response);
	}
	
#---------------------------------------
	function day_report_handler(){
		$store_id = Dbase::dataFilter($_POST['store_id']);

		$now = time();
		$day = date('d', $now);
		$month = date('m', $now);
		$year = date('Y', $now);

		$from = strtotime($day.'-'.$month.'-'.$year.' 00:00:00');
		$to = strtotime($day.'-'.$month.'-'.$year.' 23:59:59');

		$arRents = BIKE::getRentsFromPeriod($from, $to, $store_id);
		//print_r($arRents); exit;
		
		$response = array('status'=>'ok', 'rents'=>$arRents);
		return json_encode($response);
	}
#---------------------------------------	
	function period_report_handler(){
		$store_id = Dbase::dataFilter($_POST['store_id']);
		$from = (int)Dbase::dataFilter($_POST['from']);
		$to = (int)Dbase::dataFilter($_POST['to']) + 86399;
		
		if($to - $from > 2764800){
			$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['period_to_big']);
		}else{
			$arRents = BIKE::getRentsFromPeriod($from, $to, $store_id);
			$response = array('status'=>'ok', 'rents'=>$arRents);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function cancel_rents_handler(){
		foreach (@$_POST['cancel'] as $rent){
			$sql = 'UPDATE `rent` SET `amount` = -1 WHERE `id` = '.$rent;
			if(Dbase::$PDOConnection->exec($sql) !==false){
					$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['rent_canceled_success']);
			}
			else {
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_cancel_rent'].$sql);
			}
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function recalc_fact_handler(){
		if(@$_POST['fact_time'] != '' && @$_POST['rent_id'] >= 0){
			$amount = BIKE::getRentAmount($_POST['fact_time']) * 100 + @$_POST['added'];
		
		
			$sql = 'UPDATE `rent` SET `amount` = '.$amount.' WHERE `id` = '.$_POST['rent_id'];
			
			if(Dbase::$PDOConnection->exec($sql) !==false){
					$response = array('status'=>'ok', 'message'=>'', 'amount'=>$amount);
			}
			else {
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_recalc_rent'].$sql);
			}
			
			
		}else $response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_recalc_rent']);
		
		return json_encode($response);
	}
#---------------------------------------
	function bike_report_handler(){
		$bike_id = Dbase::dataFilter($_POST['bike_id']);
		$arRes = BIKE::getBikeRents($bike_id);
		$response = array('model'=>$arRes[0]['model'], 'id'=>$arRes[0]['id'], 'serial_id'=>$arRes[0]['serial_id']);
		$amount = 0;
		$rent_time = 0;
		$project_time = 0;
		foreach($arRes as $rent){
			$amount += $rent['amount'];
			$rent_time += ($rent['time_end'] - $rent['time_start']);
			$project_time += $rent['project_time'];
		}
		$response['amount'] = number_format($amount / 100, 2,'.',' ');
		$response['amount_source'] = $amount;
		$response['rent_time'] = $rent_time;
		$response['project_time'] = $project_time;
		return json_encode(array('status'=>'ok', 'report'=>$response));
	}
#---------------------------------------
	function find_action_user_handler(){
		if($_SESSION['CODE_COUNT'] > 4){
			return json_encode(array('status'=>'bad', 'message'=>TEMP::$Lang['txt_counter_code_too_big']));
		}
		$sms_code = Dbase::dataFilter($_POST['sms_code']);
		$action_users_count = null;
		$user_position = null;
		$user_score = null;
		
		$db = new Dbase();
		
		$sql1 = "SELECT
				`a`.`klient_id`,
				`a`.`amount_summ` as `score`,
				`a`.`renttime_summ`,
				`a`.`time_start`,
				`u`.`id`,
				`u`.`name`,
				`u`.`patronymic`,
				`u`.`surname`,
				`u`.`user_level` FROM `action` `a` 
				LEFT OUTER JOIN `users` `u` ON `a`.`klient_id` = `u`.`id` 
				WHERE `a`.`sms_code` = '{$sms_code}'";
		
		$arRes = $db->getArray($sql1);
		if(!$arRes){
			$_SESSION['CODE_COUNT'] = $_SESSION['CODE_COUNT'] + 1;
			return json_encode(array('status'=>'bad', 'message'=>TEMP::$Lang['txt_sms_code_not_found']));
		}
		
		
		$_SESSION['ACTION_USER'] = $sms_code;
		
		$action_users_count = Dbase::getCountRowsOfTable('action');
		
		$sql2 = "SELECT `klient_id`,`time_start`, `sms_code`, `amount_summ` FROM `action` WHERE `amount_summ` >= {$arRes[0]['score']} ORDER BY `amount_summ` ASC";
		//$result = mysql_query($sql2);
		$arRes3 = $db->getArray($sql2);
		if(!$arRes3){
			return json_encode(array('status'=>'bad', 'message'=>'error: '.$sql2));
		}
		
		$temp = null;
		foreach($arRes3 as $num=>$item){
			if($item['amount_summ'] == $temp) unset($arRes3[$num]);
			$temp = $item['amount_summ'];
		}
		
		$user_position = count($arRes3);
				
			
		//$user_position = mysql_num_rows($result) + 1;
		
		$sql3 = "SELECT
				`a`.`klient_id`,
				`a`.`amount_summ` as `scores`,
				`a`.`renttime_summ`,
				`a`.`time_start`,
				`u`.`id`,
				`u`.`name`,
				`u`.`patronymic`,
				`u`.`surname`,
				`u`.`user_level` FROM `action` `a` 
				LEFT OUTER JOIN `users` `u` ON `a`.`klient_id` = `u`.`id` 
				ORDER BY `amount_summ` DESC LIMIT 20";
		$arRes1 = $db->getArray($sql3);
		if(!$arRes1){
			return json_encode(array('status'=>'bad', 'message'=>'error: '.$sql3));
		}
		$top_score = $arRes1[0]['scores'];
		foreach($arRes1 as $num=>$item){
			$arRes1[$num]['scores'] = ($arRes1[$num]['scores'] - $top_score) / 100;
			$arRes1[$num]['name'] .= ' '.$item['surname'].' '.$item['patronymic'];
			unset($arRes1[$num]['patronymic']);
			unset($arRes1[$num]['surname']);
			$arRes1[$num]['time_start'] = date('d.m.Y H:i', $item['time_start']);
		}
		
		$arRes[0]['score'] = ($arRes[0]['score'] - $top_score) / 100;
		
		return json_encode(array('status'=>"ok", 'u_pos'=>$user_position, 'actions_count'=>$action_users_count, 'u_info'=>$arRes[0], 'leaders'=>$arRes1, 'upper'=>$arRes3));
	}
#---------------------------------------
	function get_actions_list_handler(){
		if(!USER::isAdmin()) return json_encode(array('status'=>'bad', 'message'=>'permission denied'));
		$db = new Dbase();
		$rows = (int)Dbase::getCountRowsOfTable('action');
		$curr_page = ceil(@$_POST['from_user_offset'] / 100);
		$offset = @$_POST['from_user_offset'];
		$pages = ceil($rows / 100);
		$len = 8;
		$arNav = array();
		if($pages > 1){
			$arNav = BIKE::build_nav($curr_page + 1, $pages, $len);
		}
		
		
		$sql = "SELECT
				`a`.`klient_id`,
				`a`.`amount_summ` as `scores`,
				`a`.`renttime_summ`,
				`a`.`time_start`,
				`u`.`id`,
				`u`.`name`,
				`u`.`patronymic`,
				`u`.`surname`,
				`u`.`user_level` FROM `action` `a` 
				LEFT OUTER JOIN `users` `u` ON `a`.`klient_id` = `u`.`id` 
				ORDER BY `amount_summ` DESC LIMIT {$offset}, 100";
		$arRes = $db->getArray($sql);
		if(!$arRes){
			return json_encode(array('status'=>'bad', 'message'=>'error: '));
		}
		
		foreach($arRes as $num=>$item){
			$arRes[$num]['scores'] = $arRes[$num]['scores'] / 100;
			$arRes[$num]['name'] .= ' '.$item['surname'].' '.$item['patronymic'];
			unset($arRes[$num]['patronymic']);
			unset($arRes[$num]['surname']);
			$arRes[$num]['time_start'] = date('d.m.Y H:i', $item['time_start']);
		}
		
		return json_encode(array('status'=>"ok", 'actions_list'=>$arRes, 'nav'=>$arNav));
	}
#---------------------------------------
	function send_sms_handler(){
		$client_id = (int)Dbase::dataFilter($_POST['user_id']);
		$client_phone = Dbase::dataFilter($_POST['user_phone']);
		
		if($client_id == '' || $client_phone == ''){
			return json_encode(array('status'=>'bad', 'message'=>'bad dataset for request'));
		}
		
		$sql = "SELECT `sms_code` FROM `action` WHERE `klient_id` = {$client_id} LIMIT 1";
		
		$db = new Dbase();
		$arRes = $db->getArray($sql);
		if(!$arRes){
			return json_encode(array('status'=>'bad', 'message'=>'error: bad sql request'));
		}
		
		$text = TEMP::$Lang['txt_sms_double_send'].$arRes[0]['sms_code'];
		
		$arRet = TEMP::sendSMS($client_phone, $text, true);
		
		if($arRet['status'] === true){
			$arResponse = array('status'=>'ok', 'message'=>TEMP::$Lang['txt_sms_send_success']);
		}else{
			$arResponse = array('status'=>'bad', 'message'=>TEMP::$Lang['txt_sms_send_bad']);
		}
		
		return json_encode($arResponse);
	}

#---------------------------------------
	
	
#---------------------------------------

	function smsResseller_handler(){
		$text = Dbase::dataFilter($_POST['sms_text']);
		$translit = Dbase::dataFilter($_POST['translit']);
		$translit = ($translit == 'on' ? true : false);
		
		$arRequest = array('table'=>'``',
				'fields'=>'',
				'order_by'=>'phone',
				//'left_outer_join'=>'`transactions_log` `t` ON `p`.`ttn` = `t`.`uttn` AND `t`.`status` = 5',
				'sort'=>'DESC',
				'where'=>' `id` = '.implode(',', $_POST['users_id']),
		);
		
		$sql = "SELECT `id`, `name`, `patronymic`, `surname`, `phone` FROM `users` WHERE `id` in(".implode(',', $_POST['users_id']).")";
		
		$db = new Dbase();
		$arUsers = $db->getArray($sql);
		
		if(count($arUsers) > 0){
			foreach($arUsers as $user){
				//$arRet = TEMP::sendSMS_test($user['phone'], $text, $translit);
				$arRet = TEMP::sendSMS($user['phone'], $text, $translit);
				$arResponse[] = array('phone'=>$user['phone'], 'sms_status'=>$arRet, 'uid'=>$user['id']);
			}
		}
		
		
		return json_encode(array('status'=>0, 'result'=>$arResponse));
	}
	
#---------------------------------------

	function smsUsersSelect_handler(){
		$filter = Dbase::dataFilter($_POST['filter']);
		$type = Dbase::dataFilter($_POST['type']);
		$page = Dbase::dataFilter($_POST['page']);
		$porcion = 1000;
		
		switch($filter){
			case 'all_not_action':
				$db = new Dbase();
				if($type == 'count'){
					$sql = 'SELECT COUNT(`u`.`id`) FROM bike.users `u` 
							LEFT JOIN `action` `a` ON `a`.`klient_id` = `u`.`id` 
							WHERE `a`.`klient_id` IS NULL;';
					$arRes = $db->getArray($sql);
					$arRes = $arRes[0]['COUNT(`u`.`id`)'];
					return json_encode(array('status'=>0, 
							'sms_count'=>$arRes, 
							'pages'=>(int)ceil($arRes / $porcion), 
							'porcion'=>$porcion, 
							'type'=>$type));
				}elseif($type == 'get'){
					$sql = 'SELECT `u`.`id` FROM bike.users `u` 
							LEFT JOIN `action` `a` ON `a`.`klient_id` = `u`.`id` 
							WHERE `a`.`klient_id` IS NULL LIMIT '.($page * $porcion).', '.$porcion.';';
					$arRes = $db->getArray($sql, false);
					/*array_walk($arRes, function(&$ar, $key){
						$ar = (int)$ar[0];
					});*/
					if(count($arRes) > 0){
						return json_encode(array('status'=>0,
								'users'=>$arRes,
								'type'=>$type));
					}
				}
				break;
		}
		
		
		
	}
	
#---------------------------------------
}
?>