<?php
/**
  * contains Security Class
  * @package MooKit
  */
/**
 * Security Class
 *
 * a class for checking user session credentials
 * 
 * @author Corbin Tarrant
 * @copyright March 15th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
 class Security {
	/** @var bool $status 	security status */
	var $status;
	/**
	 * check for secure session 
	 *@returns bool
	 */
	function __construct() {
		//default to unauthorized
		if(!isset($_SESSION['auth'])) $_SESSION['auth'] = 0;
		//check for authorization, ip address and valid user
		if(  $_SESSION['auth'] === 1 && $this->checkIP() && $this->checkUser() )
			$this->status = true;
		else
			$this->status =  false;
	}
	/** Return Security Status */
	public function check() {
		return $this->status;
	}
	/** Validate IP */
	public function checkIP() {
		//die("session_ip : ".$_SESSION['ip']." | server[remote_addr] : ".$_SERVER['REMOTE_ADDR']);
		return ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR'] ? true : false);
	}
	/** Validate User */
	public function checkUser() {
		return isset($_SESSION['alias']);
	}
 }
?>