<?php
//This file should use php's auto_prepend_file ability to load before the main site index.php
/** PHP Class Autoloading */
function __autoload($class_name) { 	require_once "codeCore/Classes/php/".$class_name.".php"; }

//set the default timezone
date_default_timezone_set('America/Detroit');

//GLOBAL VARIABLES
define('DEBUG', true); //DEBUG FLAG
define('NAMESPACE','MooKit');
//define MooKit Directory
define('ROOT_DIR','/home/corbin/skillWebDevelopment/LAMP_devSite/MooKit/public/');
//define error directory
define('ERROR_LOG_DIR','/home/corbin/skillWebDevelopment/LAMP_devSite/MooKit/logs/');
//set the expiration time for php errors
define('PHP_ERROR_EXPIRE','-7 days');

// Set Error Reporting Level
error_reporting(E_ALL);

/** REMOVE EVIL MAGIC QUOTES **/
if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}
?>
