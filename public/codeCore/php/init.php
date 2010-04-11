<?php
//This file should use php's auto_prepend_file ability to load before the main site index.php
/** PHP Class Autoloading */
function __autoload($class_name) { 	require_once "codeCore/Classes/php/".$class_name.".php"; }

//set the default timezone
date_default_timezone_set('America/Detroit');

//GLOBAL VARIABLES
define('DEBUG', false); //DEBUG FLAG
define('NAMESPACE','MooKit');
//define error directory
define('ERROR_LOG_DIR','/home/corbin/skillWebDevelopment/LAMP_devSite/MooKit/logs/');
//set the expiration time for php errors
define('PHP_ERROR_EXPIRE','-7 days');
?>
