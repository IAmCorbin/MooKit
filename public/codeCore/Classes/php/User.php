<?php
/**
 * contains User Class
 * @package MooKit
 */
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
	/** @var mysqli $mysqli		mysqli database object */
	var $DB;
	/** @var string $status		stores the status (success/failure) of user manipulation */
	var $status;
	/** @var int $user_id		users's id */
	var $user_id;
	/** @var string $nameFirst	user's first name */
	var $nameFirst;
	/** @var string $namelast	user's last name */
	var $nameLast;
	/** @var string $alias		user's username */
	var $alias;
	/** @var string $password	user's password */
	var $password;
	/** @var string $email		user's email */
	var $email;
	/** @var string $registered	date and time user registered */
	var $registered;
	/** @var string $lastLogin	date and time user last logged in */
	var $lastLogin;
	/** @var string $ip_address	user's ip address */
	var $ip_address;
	/** @var string $access_level	user's permissions */
	var $access_level;
	
	/** 
	  * Constructor 
	  *@param array $filteredInput - array filled with filtered user input
	  *@param bool $newUser - switch to create a new user or retrieve an existing one
	  */
	function __construct($filteredInput, $newUser = true) {
		//check for valid passed data
		$this->status = json_encode(array('status'=>"alias = ".addslashes($filteredInput['alias']))); return;
		if(!isset($filteredInput['alias']) || $filteredInput['alias'] == ' ') {
			$this->status =  json_encode(array('status'=>'ERROR_MISSING_DATA'));
		}
		//check for matching passwords first
		if($filteredInput['password'] === $filteredInput['vpassword']) {
			//establish database connection
			$this->DB = new DatabaseConnection;
			
			if($newUser) {
				//attempt to add this user
				switch($userStatus = $this->addNew($filteredInput)) {
					case 'added':
						$this->status = json_encode(array('status'=>'ADDED'));
						return;
					case 'duplicate':
						$this->status =  json_encode(array('status'=>'ERROR_DUPLICATE'));
						return;
					case 'passEncFail';
						$this->status =  json_encode(array('status'=>'ERROR_ADDING'));
						return;
					case false:
						$this->status =  json_encode(array('status'=>'ERROR_ADDING'));
						return;
				}
			} else {
				//attemp to retrieve this user
			}			
		} else
			$this->status =  json_encode(array('status'=>'ERROR_BADPASS'));
		
	}
	/**
	 * Add a new user to the database
	 *@param array $filteredInput - array filled with filtered user input
	 *@return bool
	 */
	public function addNew($filteredInput) {
		//check database for duplicate username
		$query = "SELECT `alias` FROM `users` WHERE `alias`='".$filteredInput['alias']."' LIMIT 1;";
		$user = $this->DB->query($query,"object");
		//return if username is already found, no duplicates allowed
		if(is_object($user[0])) if($user[0]->alias) return 'duplicate';
		
		//generate encrypted password
		$regTime = date('Y-m-d H:i:s');
		if($encPass = $this->encryptPassword($filteredInput['alias'],$filteredInput['password'],$regTime)) {
			//add new user to database
			$this->DB->query(
				'INSERT INTO `users`(`alias`,`nameFirst`,`nameLast`,`password`,`email`,`registered`,`ip_address`) 
				VALUES(\''.$filteredInput['alias'].'\',\''.$filteredInput['nameFirst'].'\',\''.$filteredInput['nameLast'].'\',\''.$encPass.'\',\''.$filteredInput['email'].'\',\''.$regTime.'\', INET_ATON(\''.$_SERVER['REMOTE_ADDR'].'\'));',null);
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
			$query = "SELECT `user_id`,`alias` FROM $tbl WHERE `alias`='$user' AND `password`='$encPass';";// LIMIT 1;";
			$results = $this->DB->query($query,'object');
			if($results[0]->user_id) {
				//set authenticated session variables
				$_SESSION['auth'] = 1;
				$_SESSION['user'] = $user; //username
				$_SESSION['user_id'] = $results[0]->user_id;
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
	  * Retrieve a user's information from the database and store to object variables
	  * @param array $filteredInput - array of filtered user input
	  */
	public function retrieve($filteredInput) {
		
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
	/** 
	  * Set Authorized Session Variables
	  */
	public function AUTH() {
	
	}
	/**
	 * Removed Authorized Session Variables
	 */
	public function NOAUTH() {
		unset($_SESSION['auth']); //remove authentication
		unset($_SESSION['user']); //unset username
		unset($_SESSION['user_id']); //unset user_id
		unset($_SESSION['ip']); //unset ip address
	}
}
?>