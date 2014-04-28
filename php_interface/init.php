<?ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(0);
define("VERSION", 109);
define("SMS_LOGIN", "380673666811");
define("SMS_PASSW", "swimmer")
date_default_timezone_set('Europe/Kiev');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/main_lib_inc.php');
require_once('db_init_inc.php');

//print_r($db->messages);

$temp = new TEMP();

ob_clean();
?>