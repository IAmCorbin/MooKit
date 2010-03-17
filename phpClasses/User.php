<?php
if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 
/**
 * User Class
 *
 * a class for adding or authenticating users against a users table in a database
 * 
 * @author Corbin Tarrant
 * @copyright Febuary 20th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
class User {
	/** @var mysqli $mysqli	mysqli database object */
	var $DB;
	/** Constructor */
	function __construct() {
		$this->DB = new DatabaseConnection;
	}
	/**
	 * Add a new user to the database
	 *@param array $filteredInput - array filled with filtered user input
	 *@return bool
	 */
	public function addNew($filteredInput) {
		//check database for duplicate username
		$query = "SELECT `alias` FROM `users` WHERE `alias`='".$filteredInput['user']."' LIMIT 1;";
		//return if username is already found, no duplicates allowed
		if($this->DB->query($query,"assoc") > 0) return 'duplicate';
		
		//generate encrypted password
		$regTime = date('Y-m-d H:i:s');
		if($encPass = $this->encryptPassword($filteredInput['user'],$filteredInput['pass'],$regTime)) {
			//add new user to database
			$this->DB->query(
				'INSERT INTO `users`(`alias`,`nameFirst`,`nameLast`,`password`,`email`,`registered`) 
				VALUES(\''.$filteredInput['user'].'\',\''.$filteredInput['first'].'\',\''.$filteredInput['last'].'\',\''.$encPass.'\',\''.$filteredInput['email'].'\',\''.$regTime.'\');',null);
			return 'added';
		} else
			return 'passEncFail';
	}
	/**
	 * Authenticates a user against database - on authorization it will set $_SESSION['auth'] = 1
	 *@param string $user - username to test
	 *@param string $pass - password to test
	 *@param string $tbl - the database table to test against
	 *@return bool
	 */
	public function authenticate($user,$pass, $tbl='users') {
		//if authentication if set, unset it
		isset($_SESSION['auth'])? $_SESSION['auth']=0  : 0;
		//if a valid password is returned (requires the username to be in the database)
		if($encPass = $this->encryptPassword($user,$pass)) {
			//check user
			$query = "SELECT * FROM $tbl WHERE `alias`='$user' AND `password`='$encPass';";// LIMIT 1;";
			$results = $this->DB->query($query,'assoc');
			if($results) {
				//set authenticated session variables
				$_SESSION['auth'] = 1;
				$_SESSION['user'] = $user; //username
				$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];//ip
				return true;
			}
		} else {
			$this->NOAUTH();
			return false;
		}
		$this->NOAUTH();
		return false;
	}
	/**
	 * NO AUTHORIZATION
	 *
	 * REMOVE AUTHORIZATION FLAG AND USERNAME
	 */
	public function NOAUTH() {
		unset($_SESSION['auth']); //remove authentication
		unset($_SESSION['user']); //unset username
		unset($_SESSION['ip']); //unset ip address
	}
	/**
	 * Encrypt a password
	 *@param string $user	username 
	 *@param string $pass	password to encrypt
	 *@param string $regTime	the user's registration time, used to pass in if this is a new user and we are encrypting the password for the first time
	 *@return string 	encrypted password or false
	 */
	public function encryptPassword($user,$pass,$regTime=NULL) { 
		//addNew will pass a new regTime, so don't look for a non-existant one in the database
		if(!$regTime) {
			//get user registration time
			$query = "SELECT `registered` FROM `users` WHERE `alias`='$user' LIMIT 1;";
			if($results = $this->DB->query($query,'object')) {
				//store user's registration DATETIME
				$regTime = $results[0]->registered;
			} else //return NULL if no valid user alias was found in the database
				return false;
		}
		//create salt from the sha1 of the user's registration time
		$salt = sha1($regTime);
		//md5 encrypt the salt+password, then sha1 that whole thing and return the encrypted password
		return sha1(md5($salt.$pass));
	}
	
}

} //end if(defined('INSITE')
?>