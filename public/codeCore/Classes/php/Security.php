<?php
/**
  * contains Security Class
  * @package MooKit
  */
/**
 * Security Class
 *
 * a static class for checking user session credentials
 * 
 * @author Corbin Tarrant
 * @copyright March 15th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
 class Security {
	/** Static Security Check **/
	public static function clearance() {
		if(!isset($_SESSION['auth']))
			return false;
		//check for authorization, ip address and valid user
		if(  $_SESSION['auth'] === 1 && Security::checkIP() && Security::checkUser() )
			return $_SESSION['access_level'];
		else
			return  false;
	}
	/** Validate IP */
	public static function checkIP() {
		return ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR'] ? true : false);
	}
	/** Validate User */
	public static function checkUser() {
		return isset($_SESSION['alias']);
	}
 }
?>