<?php
//This file should use php's auto_prepend_file ability to load before the main site index.php
/** PHP Class Autoloading */
function __autoload($class_name) { 	require_once "codeCore/Classes/php/".$class_name.".php"; }

//GLOBAL VARIABLES
define('DEBUG', false); //DEBUG FLAG
?>
