<?
define("CURR_TEMPLATE", "public_new");
require_once($_SERVER['DOCUMENT_ROOT'].'/php_interface/init.php');
TEMP::$index_path = 'public/public.php';
TEMP::$used_magnific_popup = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/index.php');?>