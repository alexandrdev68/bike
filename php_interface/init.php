<?ini_set('display_errors', 1);
error_reporting(E_ERROR);
session_start(0);
define("VERSION", 140);
define("SMS_LOGIN", "380673666811");
define("SMS_PASSW", "swimmer");
define("SMS_API_KEY", "4f1c76b4ae78754d1bfa5cbaaad31522e99a1d63");
define("DEFAULT_TEMPLATE", "bike");
define("BIKE_ACTION", true);
define('IDENTJS', "1op09");

date_default_timezone_set('Europe/Kiev');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/main_lib_inc.php');
require_once('db_init_inc.php');

//print_r($db->messages);

$temp = new TEMP((defined("CURR_TEMPLATE") ? CURR_TEMPLATE : DEFAULT_TEMPLATE));

ob_clean();
?>
