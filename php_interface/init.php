<?ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(0);
define("VERSION", 110);
define("SMS_LOGIN", "380673666811");
define("SMS_PASSW", "swimmer");
define("DEFAULT_TEMPLATE", "bike");
date_default_timezone_set('Europe/Kiev');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/main_lib_inc.php');
require_once('db_init_inc.php');

//print_r($db->messages);

$temp = new TEMP((defined("CURR_TEMPLATE") ? CURR_TEMPLATE : DEFAULT_TEMPLATE));

ob_clean();
?>