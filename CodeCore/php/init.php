<?php
/** PHP Class Autoloading */
function __autoload($class_name) { 	require_once "CodeCore/Classes/php/".$class_name.".php"; }

//GLOBAL VARIABLES
define('DEBUG', false); //DEBUG FLAG
?>