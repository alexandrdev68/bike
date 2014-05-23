<?php

if(!isset($_SESSION['CURRUSER'])) header('Location: /');
error_reporting(E_ALL);

//для не админа возвращаем HTML
if(!USER::isAdmin()){
	
	TEMP::component('HTML_print', $_SESSION['PRINT']);
	
}else{
	spl_autoload_unregister('class_autoload');
	require_once($_SERVER['DOCUMENT_ROOT'].'/lib/PHPExcel/PHPExcel.php');
	//require_once $_SERVER['DOCUMENT_ROOT'].'/lib/PHPExcel/PHPExcel/IOFactory.php';
	spl_autoload_register('class_autoload');
	
	//print_r($_SESSION); exit;
	
	if($_SESSION['PRINT']['type'] == 'contract'){
		//получаем таблицу
		
		$objPHPExcel = PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/upload/contract/contract_ext.xlsx');
		//$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$locale = 'ru';
		$validLocale = PHPExcel_Settings::setLocale($locale);
	
		$date = date('d.m.Y', $_SESSION['PRINT']['info']['time_start']);
		$time = date('H:i', $_SESSION['PRINT']['info']['time_start']);
		$time_end = date('H:i d.m.Y', $_SESSION['PRINT']['info']['time_start'] + $_SESSION['PRINT']['info']['project_time']);
	
		/*$center = array(
					'alignment'=>array(
					'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
				)
			);
		$objWorksheet->getStyle('B6:E8')->applyFromArray($center);*/
		
		//print_r($_SESSION['PRINT']); exit;
		
		//записываем в ячейки таблицы значения
		$c = 0;
		$summ = 0;
		foreach($_SESSION['PRINT']['info']['bikes'] as $bike){
			$cell = 6 + $c;
			$amount = BIKE::getRentAmount($bike['project_time']);
			if(@$bike['rent_prop']['added'] > 0) $amount = $amount + ($bike['rent_prop']['added'] / 100);
			$summ += $amount;
			$objWorksheet->getCell('B'.$cell)->setValue($bike['bike_id'].' ');
			$objWorksheet->getCell('C'.$cell)->setValue($bike['model']);
			$objWorksheet->getCell('D'.$cell)->setValue($bike['serial_id'].' ');
			if(!empty($bike['bike_prop']) && isset($bike['bike_prop']['cost']))	$objWorksheet->getCell('E'.$cell)->setValue(($bike['bike_prop']['cost'] / 100).' ');
			$objWorksheet->getCell('F'.$cell)->setValue($amount.' ');
			$c++;
		}
		$objWorksheet->getCell('F9')->setValue($summ.' ');
		
		$objWorksheet->getCell('B29')->setValue('Я, '.$_SESSION['PRINT']['info']['patronymic'].' '.$_SESSION['PRINT']['info']['name'].' '.$_SESSION['PRINT']['info']['surname']);
		$objWorksheet->getCell('B30')->setValue('Адреса проживання/прописки:   '.@$_SESSION['PRINT']['info']['properties']['live_place']);
		$objWorksheet->getCell('B32')->setValue('дата '.$date.',     час: з '.$time.' до '.$time_end.',     тел. '.$_SESSION['PRINT']['info']['phone'].' ');
		
		if(BIKE_ACTION && $_SESSION['PRINT']['action_ofert']){
			$objWorksheet->getCell('B35')->setValue('Даю згоду для участі в акції "Велосипед за 1 грн.":___________________');
		}
	
		
		// redirect output to client browser
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/upload/contract/contract_1'.$_SESSION['PRINT']['info']['login'].'.xlsx');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="contract.xls"');
		header('Cache-Control: max-age=0');
		//выводим в браузер таблицу с бланком
		$objWriter->save('php://output');
		
	
		//print_r($_SESSION['PRINT']);
	
	
	}
}




?>
