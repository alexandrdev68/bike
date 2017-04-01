<?php
$db = new Dbase();
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/php_interface/local_init.php')) include($_SERVER['DOCUMENT_ROOT'].'/php_interface/local_init.php');
else{
	$db->user = 'veloolim_root';
	$db->passw = 'Veloolimp_Root';
	$db->base = 'veloolim_bike';
	$db->m_connect();
}
?>
