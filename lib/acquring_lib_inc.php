<?php
class ACQURING extends Dbase{
	
	static public $url = GM_API_URI;//for production 'https://api.globalmoney.ua';
	//static public $url = 'https://sandbox.globalmoney.ua/gmws';//for development
	static public $operation = '/acquiring/';
	static public $type = 'POST';
	static public $need_auth = false;
	static public $login;
	static public $password;
	static public $request_fields = '{}';
	static public $response;
	static public $cRes;
	static public $error = array();
	
	
	static public function set_errors(){
		self::$error = array(
	    'email_format'   => "Введённый email содержит ошибки, или это не email",
	    'empty_required' => 'Это поле является обязательным',
	    'required_bars_empty'=> 'параметр отсутсвует',
	    'wallet_invalid' => 'Неверный идентификатор кошелька. Проверьте правильность ввода.',
	    'wallet_empty' => 'Введите идентификатор кошелька',
	    
	    'amount_empty' => 'Введите сумму пополнения',
	    'amount_negative' => 'Сумма пополнения не может быть отрицательной',
			  
	    'code_0'=>'Операція пройшла успішно',
		'code_2'=>'Платіж знаходиться в процесі обробки',
		'code_3'=>'Платіж успішно оброблено',
		'code_6'=>'Недостатньо коштів на гаманці',
		'code_056'=>'Не можу отримати serviceId',
	    'code_401' => 'Внутренняя ошибка сервера, попробуйте повторить позже',
	    'code_402' => 'Аутентифікація не пройдена, перевірте правильність введення логіна, пароля',
	    'code_403' => 'Запрос неполон или неверен',
	    'code_405' => 'Specified wallet or user is blocked by administrator ',
	    
	    'code_410' => 'Такой кошелёк уже есть',
	    'code_411' => 'Кошелёк не найден',
	    'code_412' => 'Кошелёк заблокирован',

	    'code_413' => 'Кошелёк назначения не найден',
	    'code_414' => 'На кошельке плательщика недостаточно денег',
	    'code_415' => 'Операционный лимит на кошельке плательщика исчерпан',

	    'code_416'=> 'Комиссия недействительна',
	    'code_417'=> 'Кошелёк получателя не может принять столько денег из-за ограничений кошелька',
	    'code_418'=> 'Referenced transaction not found',
	    'code_419'=> 'Limit values exceed wallet amount limitations',
	    'code_420'=> 'Такой email уже зарегистрирован',
	    'code_421'=> 'Неверный email',
	    'code_422'=> 'Такой телефон уже зарегистрирован',
	    'code_423'=> 'Неправильный номер телефона',
	    'code_424'=> 'Неверный пароль',
	    'code_425'=> 'Неправильное имя',
	    'code_426'=> 'Неправильная фамилия',
	    'code_427'=> 'Неправильное отчество',
	    'code_428'=> 'Pin-код неверен',
	    'code_429'=> 'Неправильный ввод адреса',
	    'code_430'=> 'Даты для получения истории транзакций неверны',
	    'code_431'=> 'Границы получения истории транзакций неверны',
	    'code_432'=> 'Неверная сумма транзакции',
	    'code_433'=> 'Кошельки назначения и источника совпадают',
	    'code_434'=> 'Нельзя удалить непустой кошелёк',
	    'code_435'=> 'Дата окончания действия неверна',
	    'code_436'=> 'Код пополнения не найден',
	    'code_437'=> 'Неправильная сумма кода пополнения',
	    'code_438'=> "Указанная в ссылке транзакция содержит сумму, которая не соответствует сумме текуцей транзации",
	    'code_439'=> 'Банковский счет не найден в информации о кошельке',
	    'code_440'=> 'Тикет для вывода денег не найден',
	    'code_441'=> "Транзакция не может быть отменена",
	    'code_442'=> 'Идентификатор кошелька не удовлетворяет требованиям для цифрового кода кошелька, не является действительным номером телефона или адресом электронной почты.',
	    'code_443'=> 'данные субъекта т.е. пользователя были уже кем-то изменены',
	    'code_444'=>'Кошелек не поддерживает запрошенный вид операции, например, кошелек агента и продавца не поддреживают операцию трансфера',
	    'code_445'=>'Запрошенный пользователь не найден',
	    'code_446'=>'Недопустимый формат логина пользователя',
	    'code_447'=>'Пользователь с таким логином уже существует',
	    'code_448'=>'Недопустимый формат пользовательского email',
	    'code_449'=>'Пользователь с таким email уже зарегистрирован',
	    'code_450'=>'Неизвестная роль пользователя',
	    'code_451'=>'Транзитный кошелек не найден',
	    'code_452'=>'К кошельку не прилагается электронная почта или телефон, поэтому не возможно привязать карту',
	    'code_453'=>'Токен восстановления не найден',
	    'code_454'=>'Токен восстановления истек',
	    'code_455'=>'Неправильный формат номера карты',
	    'code_456'=>'Номер карты не действителен',
	    'code_457'=>'Номер карты уже использован',
	    'code_458'=>'Номер карты не зарегистрирован во внешней системе',
	    'code_459'=>'Карта заблокирована',
	    
	    'code_460'=>'Минимальная сумма для отправки смс указана неверно',
	    'code_465'=>'Неверный ЕДРПОУ ',//EDRPOU field is invalid
	    'code_466'=>'Название предприятия указано неверно',//Appointment is invalid
	    'code_467'=>'Слишком длинный комментарий',//Comment length is too big
	    'code_468'=>'Тикет уже закрыт',//Ticket has bee already closed
	    'code_469'=>'Указан неверный тип тикета',//Invalid ticket type specified
	    'code_470'=>'МФО банка недействительно',
	    'code_471'=>'Банковский счет недействителен',
	    'code_472'=>'Название банка недействительно',
	    'code_475'=>'Неверное имя приложения',
	    'code_476'=>'Неверное описание приложения',
	    'code_477'=>'Неверный URL приложения',
	    'code_478'=>'Неверный URL перенаправления приложения',
	    'code_479'=>'Неверный URL логотипа приложения',
	    'code_480'=>'Неверный контакт приложения',
	    'code_481'=>'Указанное приложение не найдено',
	    'code_482'=>'Неверные права для приложения',
	    'code_483'=>'Временный токен не найден',
	    'code_484'=>'Неизвестный флаг кошелька',//Unknown wallet flag
	    'code_485'=>'Неверное имя компании',//Company name is invalid
	    'code_486'=>'Неверный адрес компании',//Company address is not valid
	    'code_487'=>'Комиссия компании указано неверно',
	    'code_488'=>'Номер контракта компании не является действительным',//Company contract number is not valid
	    'code_489'=>'Неверное контактное лицо компании',//Company contact name is not valid
	    'code_490'=>'Неверное контактное лицо компании',//Company contact middle name is not valid
	    'code_491'=>'Неверное контактное лицо компании',//Company contact last name is not valid
	    'code_492'=>'Неверный номер телефона контактного лица компании',//Company contact phone is not valid
	    'code_493'=>'Неверный емейл контактного лица компании',//Company contact email is not valid
	    'code_494'=>'Неверная должность контактного лица компании',//Company contact position is not valid
	    'code_495'=>'Код проверки e-mail/телефона не найден',//Email/phone validation code not found
	    'code_496'=>'Дополнительное поле тикета недействительно',//Ticket additional field is invalid
	    'code_497'=>'Срок действия виртуальной карты недействителен',//Virtual card validity period is wrong
	    'code_498'=>'Кредитная карта не найдена',//Credit card is not found
	    'code_499'=>'Нет доступной виртуальной карты',//No virtual credit card available in cards pool
	    'code_500'=>'Месячный лимит кошелька был достигнут',//Wallet monthly limit has been reached
	    'code_501'=>'Контактный телефон компании недействителен',//Company contact phone is not valid
	    'code_502'=>'Контактный e-mail компании недействителен',//Company contact email is not valid
	    'code_511'=>'Неправильный номер Cvv для данной карты',//Cvc or Cvc2 number is wrong for specified card
	    'code_513'=>'Авторизация отклонена',
	    'code_514'=>'Credit card is expired',
	    'code_518'=>'Thirdparty service is not available',
	    'code_521'=>'Specified account is not found',
	    'code_524'=>'Payment failed of unknown reason',
	  );
	}
	
	static public function validate($value, $type){
	  switch($type){
	    case 'email':
	      return preg_match('/^[a-zA-Z0-9][-._a-zA-Z0-9]+@(?:[-a-zA-Z0-9]+\.)+[a-zA-Z]{2,6}$/', $value);
	      break;
	    case 'card':
	      return preg_match('/^[0-9]{14,19}$/', $value);
	      break;
	    case 'cvv':
	      return preg_match('/^[0-9]{3,3}$/', $value);
	      break;
	    case 'owner':
	      return preg_match('/^[A-Za-z\-\ ]{3,}$/', $value);
	      break;
	    case 'expiry':
	      return preg_match('/^[0-9]{4,4}$/', $value);
	      break;
	    case 'wallet_id':
	      return preg_match('/^[0-9]{14,14}$/', $value);
	      break;
	    case 'phone':
	      return preg_match('/^[0-9]{12,13}$/', $value);
	      break;
	    case 'summ':
	    	return preg_match('/^([0-9]{1,})$|^[0-9]{1,}\\.(?:[0-9]{1,2})$/', $value);
	    	break;
	  }
	}
	
	static public function write_log($arLogFields){
		
		$filename = $_SERVER['DOCUMENT_ROOT'].'/data/oper_'.date('Y-W').'.log';
		$date = date('Y-m-d H:i:s', time());
		//$res = json_decode(self::$response, true);
		$logString = $date.' params: '.json_encode($arLogFields);
		/*if($res['status'] == 0){
			$logString .= ' oper_status: good';
		}else{
			$res['message'] = self::get_error_text($res['status']);
			$logString .= ' oper_status: bad '.$res['message'];
		}*/
		$logString .= chr(10).chr(13);
		file_put_contents($filename, $logString, FILE_APPEND);
		
	}
	
	static public function send($arFields = array()){
		self::$request_fields = count($arFields) == 0 ? '' : json_encode($arFields);
		
		//echo self::$request_fields.'    '.self::$login.' : '.self::$password.'     '.self::$url.self::$operation; exit;
		
		
		self::init();
		
		self::set_errors();
		
		self::$response = curl_exec(self::$cRes);
	}
	
	static public function init(){
		 self::$cRes = curl_init(self::$url.self::$operation);
	     if (self::$need_auth)
	     {
	          curl_setopt(self::$cRes, CURLOPT_USERPWD, self::$login.':'.sha1(self::$password));
	     }
	     
	     curl_setopt(self::$cRes, CURLOPT_POST, self::$type == 'POST');
	     if (!in_array(self::$type, array('POST', 'GET')))
	     {
	          curl_setopt(self::$cRes, CURLOPT_CUSTOMREQUEST, self::$type);
	     }
	     curl_setopt(self::$cRes, CURLOPT_RETURNTRANSFER, true);
	     curl_setopt(self::$cRes, CURLOPT_FAILONERROR, true);
	     curl_setopt(self::$cRes, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	     if (!empty(self::$request_fields))
	     {
	          curl_setopt(self::$cRes, CURLOPT_POSTFIELDS, self::$request_fields);
	     }
	     else
	     {
	          if (self::$type == 'POST')
	          {
	               curl_setopt($c, CURLOPT_POSTFIELDS, '{}');
	          }
	     }
	}
	
	static public function get_error_text($code){
		return isset(self::$error['code_'.$code]) ? self::$error['code_'.$code] : 'unknown error #'.$code;
	}
	
	static public function comment_filter($data){
     	$filter = array( '<', '>', '&', '#', '=', '"');
     	$replace = array('', '', '', '', '', '`');
     	return str_replace($filter, $replace, trim(stripslashes(strip_tags($data))));
     }
}
?>