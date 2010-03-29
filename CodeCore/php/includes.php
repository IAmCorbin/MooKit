<?php
/**
 * Encapsulates includes and provides a secure flag do a security check before continuing
 *@param bool $secure
 */
function INIT($secure=TRUE) {
	//GLOBAL DEFINITIONS
	define('DEBUG', false); //DEBUG FLAG
	define('INSITE',true); //EXTRA SCRIPT SECURITY, disallow direct script access
	//change directory
	chdir($_SERVER['DOCUMENT_ROOT'].'/MooKit');
	//Classes
	require_once 'CodeCore/Classes/php/htmLawed1.1.9.1.php';
	require_once 'CodeCore/Classes/php/Security.php';
	require_once  'CodeCore/Classes/php/Filters.php';
	require_once  'CodeCore/Classes/php/Database.php';
	require_once  'CodeCore/Classes/php/Template.php';
	require_once  'CodeCore/Classes/php/User.php';
	require_once 'CodeCore/Classes/php/Post.php';
	//Functions
	require_once 'CodeCore/php/auth.php';
	require_once 'CodeCore/php/functions.php';
	session_start();
	session_regenerate_id();	
	
	$_SESSION['SYSNAME'] = 'MooKit';
	
		if($secure) {
			//Security Check
			$security = new Security;
			if(!$security->check())  {
			echo 'Not Authorized. Please Visit <a href="http://10.10.10.100/MooKit">The Main Site</a>';
			die;
		}
	}
}
?>