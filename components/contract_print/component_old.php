<?php
if(!isset($_SESSION['CURRUSER'])) header('Location: /');

error_reporting(E_ERROR);
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/PHPExcel/PHPExcel.php');
//require_once $_SERVER['DOCUMENT_ROOT'].'/lib/PHPExcel/PHPExcel/IOFactory.php';

if($_SESSION['PRINT']['type'] == 'contract'){
	//получаем таблицу
	
	$objPHPExcel = PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/upload/contract/contract.xlsx');
	//$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$locale = 'ru';
	$validLocale = PHPExcel_Settings::setLocale($locale);

	$date = date('d.m.Y', $_SESSION['PRINT']['info']['time_start']);
	$time = date('H:i', $_SESSION['PRINT']['info']['time_start']);

	/*$center = array(
				'alignment'=>array(
				'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
			)
		);
	$objWorksheet->getStyle('B6:E8')->applyFromArray($center);*/
	
	//print_r($_SESSION['PRINT']['info']['bikes']); exit;
	
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
		$objWorksheet->getCell('E'.$cell)->setValue($amount.' ');
		$c++;
	}
	$objWorksheet->getCell('E9')->setValue($summ.' ');
	
	$objWorksheet->getCell('C26')->setValue($_SESSION['PRINT']['info']['patronymic'].' '.$_SESSION['PRINT']['info']['name'].' '.$_SESSION['PRINT']['info']['surname']);
	$objWorksheet->getCell('C28')->setValue($date);
	$objWorksheet->getCell('C29')->setValue($time);
	$objWorksheet->getCell('C30')->setValue($_SESSION['PRINT']['info']['phone'].' ');

	
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

?>