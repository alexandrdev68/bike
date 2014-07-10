<?class TEMP {
	/**
	 * название текущего шаблона (совпадает с названием папки в которой хранится шаблон)
	 * @var string
	 */
	static $current;
    static $header_path;
    static $index_path;
    static $footer_path;
    static $styles_dir;
    static $js_dir;
    static $curr_temp_path;
    static $curr_lang;
    static $Lang = array();
    static $sms = null;
    static $used_magnific_popup = false;
	
    function __construct($template){
		self::$current = $template;
    	self::$curr_temp_path = 'templates/'.self::$current;
    	self::$header_path = self::$curr_temp_path.'/header.php';
        self::$index_path = self::$curr_temp_path.'/index.php';
        self::$footer_path = self::$curr_temp_path.'/footer.php';
        self::$styles_dir = self::$curr_temp_path."/css";
        self::$js_dir = self::$curr_temp_path.'/js';
        self::$curr_lang = 'ua';
        
    }
    
    /**
	 * вставляет компонент с именем, совпадающим с названием папки в каталоге components
	 * в качестве входного параметра можно передававать массив
	 * Если в качестве 3-го параметра передать false будет грузится только component.php
	 * Пример: TEMP::component('myComponent', array('name'=>'Jon'));
	 * @var function
	 */
    static public function component($name, $arPar, $template = true){
            include($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/component.php');
            if($template){
            	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/template/template.js.php')) include($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/template/template.js.php');
            	include($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/template/template.php');
            }     
    }
    
	
    
    static public function sendSMS($phone, $text, $translit = false){
    	//return false;
    	$arParams = array();
    	$arRet = array();
    	if(!is_object(self::$sms)){
    		spl_autoload_unregister('class_autoload');
			require_once($_SERVER['DOCUMENT_ROOT'].'/lib/alphasms-client-api/smsclient.class.php');
			self::$sms = new SMSclient(SMS_LOGIN, SMS_PASSW, SMS_API_KEY);
			spl_autoload_register('class_autoload');
    	}
    	
		if($translit) $text = SMSclient::translit($text);
		$id = self::$sms->sendSMS('Olimpia', $phone, $text);
		
		if(self::$sms->hasErrors()){
			//echo 'login: '.SMS_LOGIN.' passw: '.SMS_PASSW.'<br>';
			$arRet = array('status'=>false, 'error'=>self::$sms->getErrors());
			$arParams = array('table'=>'sms_log', 'fields'=>array(
	             													'sms_id'=>'null',
	               													'sms_time'=>time(),
	              													'phone'=>$phone,
	               													'sms_text'=>$text,
																	'sms_status'=>401,
																	'sms_error'=>$arRet['error']
               												));
		}else{
			$arParams = array('table'=>'sms_log', 'fields'=>array(
	             													'sms_id'=>$id,
	               													'sms_time'=>time(),
	              													'phone'=>$phone,
	               													'sms_text'=>$text,
																	'sms_status'=>1
               												));
			$arRet = array('status'=>true);
		}
		$arRes = DBase::setRecord($arParams);
		return $arRet;
    }
    
    
    //вставляет внутрь массива другой массив со смещением ключей
	//$array - массив куда надо вставить, $pos - номер позиции, $value - вставляемое значение
    static public function insert_in_array($array, $pos, $value){
         array_splice($array, $pos, 0, $value);
         return $array;
    }
    
}?>