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
	/** @var string $json_status	stores the status (success/failure) of user manipulation, to be sent back to javascript */
	var $json_status;
	/** @var int $user_id		users's id */
	var $user_id;
	/** @var string $nameFirst	user's first name */
	var $nameFirst;
	/** @var string $namelast	user's last name */
	var $nameLast;
	/** @var string $alias		user's username */
	var $alias;
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
	  *@param function $newUserCallback - function that will be called if a new user is successfully added
	  */
	function __construct($filteredInput, $newUser = true, $newUserCallback = null) {
		//check for valid passed data
		//if(!array_key_exists('alias',$filteredInput) || )
		//check for matching passwords first
		if($filteredInput['password'] === $filteredInput['vpassword']) {
			//establish database connection
			$this->DB = new DatabaseConnection;
			
			if($newUser) {
				//attempt to add this user
				switch($userStatus = $this->addNew($filteredInput)) {
					case 'added':
						$this->json_status = json_encode(array('status'=>'ADDED'));
						//Fire New User Callback
						if(is_callable($newUserCallback)) {
							//echo "is_callable : true";
							call_user_func($newUserCallback);
						}
						return;
					case 'duplicate':
						$this->json_status =  json_encode(array('status'=>'ERROR_DUPLICATE'));
						return;
					case 'passEncFail';
						$this->json_status =  json_encode(array('status'=>'ERROR_ADDING'));
						return;
					case false:
						$this->json_status =  json_encode(array('status'=>'ERROR_ADDING'));
						return;
				}
			} else {
				//attemp to retrieve this user
			}			
		} else
			$this->json_status =  json_encode(array('status'=>'ERROR_BADPASS'));
		
	}
	/**
	 * Add a new user to the database
	 *@param array $filteredInput - array filled with filtered user input
	 *@param string $alias - new user's alias
	 *@param string $password - new user's password
	 *@param string $nameFirst - new user's first name
	 *@param string $nameLast - new user's last name
	 *@param string $email - new user's email address
	 */
	public function addNew($filteredInput) {
		//check database for duplicate username
		$query = "SELECT `alias` FROM `users` WHERE `alias`='".$filteredInput['alias']."' OR `email`='".$filteredInput['email']."' LIMIT 1;";
		$user = $this->DB->get_row($query);
		//return if username is already found, no duplicates allowed
		if(is_object($user[0])) if($user->alias) return 'duplicate';
		
		//generate encrypted password
		$regTime = date('Y-m-d H:i:s');
		if($encPass = $this->encryptPassword($filteredInput['alias'],$filteredInput['password'],$regTime)) {
			//add new user to database
			$this->DB->insert(
				'INSERT INTO `users`(`alias`,`nameFirst`,`nameLast`,`password`,`email`,`registered`,`ip_address`) 
				VALUES(\''.$filteredInput['alias'].'\',\''.$filteredInput['nameFirst'].'\',\''.$filteredInput['nameLast'].'\',\''.$encPass.'\',\''.$filteredInput['email'].'\',\''.$regTime.'\', INET_ATON(\''.$_SERVER['REMOTE_ADDR'].'\'));');
			return 'added';
		} else
			return 'passEncFail';
	}
	/**
	 * Authenticates a user against database - on authorization it will set $_SESSION['auth'] = 1
	 *@param string $user - username to test
	 *@param string $pass - password to test
	 *@return bool
	 */
	public function authenticate($user,$pass) {
		//if authentication is set, unset it
		isset($_SESSION['auth'])? $_SESSION['auth']=0  : 0;
		//if a valid password is returned (requires the username to be in the database)
		if($encPass = $this->encryptPassword($user,$pass)) {
			//check user
			if($this->retrieve($user,$pass)) {
				//set authenticated session variables
				$_SESSION['auth'] = 1;
				$_SESSION['user'] = $this->alias; //username
				$_SESSION['user_id'] = $this->user_id;
				$_SESSION['ip'] = $this->ip_address;//ip
				return true;
			}
		} else {
			User::NOAUTH();
			return false;
		}
		User::NOAUTH();
		return false;
	}
	/**
	  * Retrieve a user's information from the database and store to object variables
	  * @param string $alias - user's alias
	  * @param string $pass - user's encrypted password
	  */
	public function retrieve($alias, $encPass) {
		//grab user data from database
		$query = "SELECT `user_id`,`alias`,`nameFirst`,`nameLast`,`email`,lastLogin FROM `users` WHERE `alias`='$alias' AND `password`='$encPass';";// LIMIT 1;";
		$user = $this->DB->get_row($query);
		
		if(!is_object($user)) {
			//throw an error if no user was found
			trigger_error("Error Retrieving User");
			return false;
		}

		//set User data
		$this->user_id = $user->user_id;
		$this->alias = $user->alias;
		$this->nameFirst = $user->nameFirst;
		$this->nameLast = $user->nameLast;
		$this->email = $user->email;
		$this->lastLogin = $user->lastLogin;
		$this->ip_address = $user->ip_address;
		$this->access_level = $user->access_level;
		//success
		return true;
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
			if($result = $this->DB->get_row($query)) {
				//store user's registration DATETIME
				$regTime = $result->registered;
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
	public static function NOAUTH() {
		unset($_SESSION['auth']); //remove authentication
		unset($_SESSION['user']); //unset username
		unset($_SESSION['user_id']); //unset user_id
		unset($_SESSION['ip']); //unset ip address
	}
}
?>