<?
define("CURR_TEMPLATE", "action");
require_once($_SERVER['DOCUMENT_ROOT'].'/php_interface/init.php');
TEMP::$index_path = 'action/action.php';
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/index.php');?>