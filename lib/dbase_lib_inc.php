<?class Dbase {
    public $host = 'localhost';
    public $user;
    public $passw;
    public $base;
    static public $PDOConnection;
    static public $messages = array();
    
    static protected function addMess($message, $arData = array()){
        $now = date('Y-m-d H:i:s', time());
        $request = (isset($arData['request']) ? $arData['request'] : '');
        $response = (isset($arData['response']) ? $arData['response'] : '');
        $operation = (isset($arData['operation']) ? $arData['operation'] : '');
        $status = (isset($arData['status']) ? $arData['status'] : '');
        self::setRecord(array('table'=>'engine_logs', 'fields'=>array(
        												'log_date'=>$now,
        												'operation'=>$operation,
        												'request'=>$request,
        												'response'=>$response,
        												'status'=>$status,
        												'message'=>$message												
        
        										)));
        self::$messages[] = $message;
    }
    
    static protected function clearMess(){
        self::$messages = array();
    }
    
    /*
     * Записывает логи в файл из переменной DBase::$messages
     * либо записівает в лог строку, переданную в качестве параметра
     * Dbase::writeLog('some log string');
     */
    static public function writeLog($arLogFields = null){
    
    	$filename = $_SERVER['DOCUMENT_ROOT'].'/data/logs/oper_'.date('Y-m-d').'.log';
    	$date = date('Y-m-d H:i:s', time());
    
    	if($arLogFields === null)
    		$logString = $date.' : '.Dbase::$messages[count(Dbase::$messages) - 1];
    		else $logString = $date.' : '.json_encode($arLogFields);
    
    		$logString .= chr(10).chr(13);
    		file_put_contents($filename, $logString, FILE_APPEND);
    
    }
    
    public function m_connect(){
		try{
		    $DbaseParams = 'mysql:host='.$this->host.';dbname='.$this->base;
			self::$PDOConnection = new PDO($DbaseParams, $this->user, $this->passw);
			self::$PDOConnection->exec('SET CHARACTER SET utf8');
		}catch (PDOException $e) {
			//если не подключились к БД - выход их программы
			if(!is_object(self::$PDOConnection)){
				ob_clean();
				echo json_encode(array('status'=>'bad', 'message'=>'can\'t connect to mySQL'));
				exit;
			};
			$this->addMess($e->getMessage());
		    return false;
		}
    	
    	return true;
    }
    
    static public function get_data($arParam = array('table'=>'', 'fields'=>'', 'where'=>'', 'order_by'=>'', 'limit'=>'10', 'sort'=>'ASC')){
		$arData = array();
		$query = 'SELECT '.(@$arParam['fields'] == '' || !isset($arParam['fields']) ? '*' : $arParam['fields']).' FROM '
							.$arParam['table'].(isset($arParam['left_outer_join']) ? ' LEFT OUTER JOIN '.$arParam['left_outer_join'] : '')
							.(isset($arParam['where']) ? ' WHERE '.$arParam['where'] : '')
							.(empty($arParam['order_by']) ? '' : ' ORDER BY '.$arParam['order_by'])
							.' '.@$arParam['sort']
							.(isset($arParam['limit']) ? ' LIMIT '.$arParam['limit'] : '');
		try{
			if(!is_object(self::$PDOConnection)) throw new PDOException('don\'t connect to database');
			$arRes = self::$PDOConnection->query($query, 2);
			if(count($arRes) < 1 || $arRes == '') return array('status'=>0, 'data'=>array(), 'message'=>'query: '.$query.' OK.');
			foreach($arRes as $row) {
			  $arData[] = $row;
			}
		}catch(PDOException $e){
			return array('status'=>101, 'data'=>false, 'message'=>(DEBUG_MODE ? 'query: '.$query.' return error: '.$e->getMessage() : 'mysql error'));
		}
		
		return array('status'=>0, 'data'=>$arData, 'message'=>(DEBUG_MODE ? 'query: '.$query.' OK.' : 'query OK'));
    }
    
    static protected function getData($sql){
    	$arResult = array();
    	try{
			foreach(self::$PDOConnection->query($sql, 2) as $row) {
		        $arResult[] = $row;
		    }
		    return $arResult;
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage());
			return false;
		}
    }
    
    /**
	 * Удаляет записи из базы данных и возвращает массив результата
	 * Пример: Dbase::deleteRecord(array('table'=>'mist_express', 'where'=>'`id`=23'));
	 * @var public static function
	 */
    static public function deleteRecord($arParam = array('table'=>'', 'where'=>'')){
    	$query = "DELETE FROM `".$arParam['table']."` WHERE ".$arParam['where'];
		try{
			if(!is_object(self::$PDOConnection)) throw new PDOException('don\'t connect to database');
			$count = self::$PDOConnection->exec($query);
			if($count < 1) return array('status'=>0, 'count'=>$count, 'message'=>'query: '.$query.' was deleted 0 rows.');
		}catch(PDOException $e){
			return array('status'=>101, 'count'=>false, 'message'=>(DEBUG_MODE ? 'query: '.$query.' return error: '.$e->getMessage() : 'mysql error'));
		}
		
		return array('status'=>0, 'count'=>$count, 'message'=>(DEBUG_MODE ? 'query: '.$query.' OK.' : 'query OK'));
    }
    
    
    /**
	 * Фильтрует данные переданные через поля ввода на предмет тэгов и пробелов, числа преобразует к целому
	 * Пример: Dbase->dataFilter('12345'[, 'i']) по умолчанию работает со строками;
	 * @var public function
	 */
    static public function dataFilter($data, $i='s'){
		if($i=='s' && gettype($data) != 'integer'){
			return trim(stripslashes(strip_tags($data)));
		} elseif($i=='i' || gettype($data) == 'integer'){
			return (int)$data;
		}
			
	}
	
	/**
	 * Делает выборку из базы данных и возвращает ассоциативный массив или false если нет ни одно значения
	 * Пример: Dbase->getArray('SELECT * FROM myDB');
	 * @var public function
	 */
	public function getArray($query){
		$arData = array();
		try{
			$arResult = self::$PDOConnection->query($query, 2);
			if(count($arResult) == 0 || $arResult === false)
				return false;
			foreach($arResult as $row) {
			  $arData[] = $row;
			}
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage(), array('sql'=>$query));
		}
		return count($arData > 0) ? $arData : false;
	}
    
	/**
	 * Возвращает количество рядов в таблице с переданным функции именем
	 * Пример: Dbase::getCountRowsOfTable('users');
	 * @var static public function
	 */
	static public function getCountRowsOfTable($tablename){
		$sql = 'SELECT COUNT(*) FROM `'.$tablename.'`';
		
		try{
			foreach(self::$PDOConnection->query($sql, 2) as $row) {
		        $arResult[] = $row;
		    }
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage());
		}

        if(isset($arResult[0]['COUNT(*)']))
            $rows = (int)$arResult[0]['COUNT(*)'];
        else $rows = (int)$arResult[0];

		return $rows;
	}
	
	
	/**
	 * Добавляет в заданную таблицу запись с указанными ячейками
	 * Пример: DBase::setRecord(array('table'=>'gm_log_operation', 'fields'=>array(
     *          													'dt_created'=>date('Y-m-d h:i:s', time()),
     *          													'operation'=>'login ',
     *         													'status'=>$err_no,
     *          													'input_data'=>'login: '.$ldap_login,
     *          													'response_str'=>$this->db->add_slash($err_text)
     *          												)));;
	 * @var static public function
	 */
	static public function setRecord($arParams, $filtr = true){
		if(isset($arParams['table']) && count($arParams['fields']) > 0){
			$sql_p1 = 'INSERT INTO `'.$arParams['table'].'` (';
		//формирование sql запроса
			$sql_p2 = ") VALUES (";
			$count = count($arParams['fields']);
			$i = 1;
			foreach($arParams['fields'] as $index=>$field){
					$sql_p1 .= $index;
					$sql_p2 .= (gettype($field) == 'integer' ? "" : "'").($filtr === true ? self::dataFilter($field) : $field).(gettype($field) == 'integer' ? "" : "'");
					if($i < $count){
						$sql_p2 .=', ';
						$sql_p1 .=', '; 
					}
					else $sql_p2 .= ')';
						
				$i++;
			}
			
			$res = true;
			try{
				$count = self::$PDOConnection->exec($sql_p1.$sql_p2);
				if($count == 0) $error = self::$PDOConnection->errorCode();
				else $error = 0;
			}catch(Exception $e){
				$res = false;
				$mess = $e->getMessage();
			}
			
			return ($res === true && $error === 0) ? array('status'=>true, 'sql'=>(DEBUG_MODE ? $sql_p1.$sql_p2 : 'this is production')) : array('status'=>false, 'message'=>'mySQL error#'.$error, 'sql'=>(DEBUG_MODE ? $sql_p1.$sql_p2 : 'this is production'), 'error'=>$error);
		};
	}
    
/**
	 * Изменяет ячейки в таблице в строке по искомому ключу
	 * Пример: 
	 * @var static public function
	 */
	static public function updateRecord($arParams, $filtr = true){
		if(isset($arParams['table']) && count($arParams['fields']) > 0 && isset($arParams['where'])){
			$sql_p1 = 'UPDATE `'.$arParams['table'].'` SET ';
		//формирование sql запроса
			$sql_p2 = " WHERE ";
			$count = count($arParams['fields']);
			$i = 1;
			foreach($arParams['fields'] as $index=>$field){
					$sql_p1 .= '`'.$index.'`=';
					$sql_p1 .= (gettype($field) == 'integer' ? "" : "'").($filtr === true ? self::dataFilter($field) : $field).(gettype($field) == 'integer' ? "" : "'");
					if($i < $count){
						$sql_p1 .=', '; 
					}
					else $sql_p2 .= $arParams['where'];
						
				$i++;
			}
			//echo $sql_p1.$sql_p2; exit;
			$res = true;
			try{
				$count = self::$PDOConnection->exec($sql_p1.$sql_p2);
				if($count == 0) $error = self::$PDOConnection->errorCode();
				else $error = 0;
			}catch(Exception $e){
				$res = false;
				$mess = $e->getMessage();
			}
			
			return ($res === true && $error === 0) ? array('status'=>true) : array('status'=>false, 'message'=>'mySQL error#'.$error, 'sql'=>(DEBUG_MODE ? $sql_p1.$sql_p2 : 'this is production'), 'error'=>$mess);
		};
	}
	
}?>