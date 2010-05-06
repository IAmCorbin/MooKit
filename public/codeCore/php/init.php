<?php
//This file should use php's auto_prepend_file ability to load before the main site index.php
/** PHP Class Autoloading */
function __autoload($class_name) { 	require_once "codeCore/Classes/php/".$class_name.".php"; }

//Require Site Settings
require_once 'codeSite/php/init.php';

//PERMISSIONS
//define access levels
define('ACCESS_ADMIN',4);
define('ACCESS_CREATE',2);
define('ACCESS_BASIC',1);
define('ACCESS_NONE',0);
//post access
define('ACCESS_READ',4);
define('ACCESS_WRITE',2);
define('ACCESS_DENY',1);

// Set Error Reporting Level
error_reporting(E_ALL);
// Set Error Logging Location
ini_set('error_log',ERROR_LOG_DIR.'phpErrors.log');

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
