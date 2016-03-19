<?class Dbase {
    public $host = 'localhost';
    public $user;
    public $passw;
    public $base;
    static public $messages = array();
    
    static protected function addMess($message){
        self::$messages[] = $message;
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
    
    static protected function clearMess(){
        self::$messages = array();
    }
    
    
    
    public function m_connect(){
        $conn = mysql_connect($this->host, $this->user, $this->passw);
        if($conn === false){
            $this->addMess('mysql connection error');
            return false;
        }elseif(!mysql_select_db($this->base)){
            $this->addMess('cannot connect to database '.$this->base);
            return false;
        }else{
           $this->addMess('mysql connected');
           mysql_set_charset('utf8');
		   ob_clean();
           return true; 
        }
    }
    
    
    static protected function getData($sql){
    	$result = mysql_query($sql);
    	if($result !== false){
			while($arResult[] = mysql_fetch_assoc($result)){
				
			};
			array_pop($arResult);
			return $arResult;
    	}else{
    		//echo($sql);
            self::addMess('mysql request error: '.$sql);
    		return false;
    	}
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
	public function getArray($query, $assoc = true){
		$result = mysql_query($query);
		if($result === false) return false;
		while($arResult[] = ($assoc === true ? mysql_fetch_assoc($result) : mysql_fetch_row($result))){
			
		};
		array_pop($arResult);
		return count($arResult > 0) ? $arResult : false;
	}
    
	/**
	 * Возвращает количество рядов в таблице с переданным функции именем
	 * Пример: Dbase::getCountRowsOfTable('users');
	 * @var static public function
	 */
	static public function getCountRowsOfTable($tablename){
		$sql = 'SELECT COUNT(*) FROM `'.$tablename.'`';
		$res = mysql_query($sql);
		$rows = mysql_fetch_array($res);
		return $rows[0];
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
				$res = mysql_query($sql_p1.$sql_p2);
				$count =  mysql_affected_rows();
				if($count == 0) $error = 401;
				else $error = 0;
			}catch(Exception $e){
				$res = false;
				$mess = $e->getMessage();
			}
			
			return ($res === true && $error === 0) ? array('status'=>true) : array('status'=>false, 'message'=>'mySQL error#'.$error, 'sql'=>$sql_p1.$sql_p2, 'error'=>$error);
		};
	}
    
}?>