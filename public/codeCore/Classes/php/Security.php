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
		return ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR'] ? true : false);
	}
	/** Validate User */
	public function checkUser() {
		return isset($_SESSION['user']);
	}
 }
?>