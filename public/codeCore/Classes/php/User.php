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
	/** @var DatabaseConnection $DB	database object */
	var $DB = NULL;
	/** @var string $json_status	stores the status (success/failure) of user manipulation, to be sent back to javascript */
	var $json_status = NULL;
	/** @var int $user_id		users's id */
	var $user_id = NULL;
	/** @var string $nameFirst	user's first name */
	var $nameFirst = NULL;
	/** @var string $namelast	user's last name */
	var $nameLast = NULL;
	/** @var string $alias		user's username */
	var $alias = NULL;
	/** @var string $email		user's email */
	var $email = NULL;
	/** @var string $registered	date and time user registered */
	var $registered = NULL;
	/** @var string $lastLogin	date and time user last logged in */
	var $lastLogin = NULL;
	/** @var string $ip_address	user's ip address */
	var $ip_address = NULL;
	/** @var string $access_level	user's permissions */
	var $access_level = NULL;
	
	/** 
	  * Constructor 
	  *@param array $filteredInput - array filled with filtered user input
	  *@param bool $newUser - switch to create a new user or retrieve an existing one
	  *@param function $newUserCallback - function that will be called if a new user is successfully added
	  */
	function __construct($filteredInput, $newUser = true, $newUserCallback = null) {
		//make sure $filteredInput is an array
		if(!is_array($filteredInput)) {
			$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
			return;
		}
		//establish database connection
		$this->DB = new DatabaseConnection;
		
		if($newUser) {
			//check for valid passed data
			if(!array_keys_exist(array('alias','nameFirst','nameLast','password','email'),$filteredInput)) {
				$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
				return;
			}
			//attempt to add this user
			if($userStatus = $this->addNew($filteredInput['alias'],$filteredInput['password'],$filteredInput['nameFirst'],$filteredInput['nameLast'],$filteredInput['email'])) {
					//Fire New User Callback if it was passed
					if(is_callable($newUserCallback)) {
						//echo "is_callable : true";
						call_user_func($newUserCallback);
					}
			}
		} else {
			//check for valid passed data
			if(!array_keys_exist(array('alias','password'),$filteredInput)) {
				$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
				return;
			}
			
			//get user's registration time
			$query = "SELECT `registered` FROM `users` WHERE `alias`='".$filteredInput['alias']."' LIMIT 1;";
			$user = $this->DB->get_row($query);
			if(is_object($user) && isset($user->registered)) {
				//store user's registration DATETIME
				$this->regTime = $user->registered;
			} else { //user was not found
				$this->json_status =  json_encode(array('status'=>'E_NOAUTH','alias'=>$filteredInput['alias']));
				return false;			
			}
			//encrypt sent password
			$encPass = $this->encryptPassword($filteredInput['alias'],$filteredInput['password'],$this->regTime);
			
			//attempt to retrieve this user
			if($this->retrieve($filteredInput['alias'],$encPass)) {
				//set authenticated session variables
				$this->AUTH();
				return true;
			} else {
				$this->NOAUTH();
				$this->json_status =  json_encode(array('status'=>'E_NOAUTH'));
				return false;
			}
		}
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
	public function addNew($alias,$password,$nameFirst,$nameLast,$email) {
		//check database for duplicate username
		$query = "SELECT `alias` FROM `users` WHERE `alias`='$alias' OR `email`='$email' LIMIT 1;";
		$user = $this->DB->get_row($query);
		if(is_object($user) && isset($user->alias)) {
			$this->json_status =  json_encode(array('status'=>'E_DUPLICATE','alias'=>$alias,'nameFirst'=>$nameFirst,'nameLast'=>$nameLast,'email'=>$email));
			return false;
		}
		
		//set user's registration time as the current time
		$regTime = date('Y-m-d H:i:s');
		//get user's IP
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		//generate encrypted password
		$encPass = $this->encryptPassword($alias,$password,$regTime); 
		//add new user to database
		if($this->DB->insert(
			"INSERT INTO `users`(`alias`,`nameFirst`,`nameLast`,`password`,`email`,`registered`,`ip_address`) 
			VALUES('$alias','$nameFirst','$nameLast','$encPass','$email','$regTime',INET_ATON('$ip_address'));") == 1) {
			$this->json_status = json_encode(array('status'=>'OK'));
			return true;
		} else {
			$this->json_status =  json_encode(array('status'=>'E_INSERT'));
			return false;
		}
	}
	/**
	  * Retrieve a user's information from the database and store to object variables
	  * @param string $alias - user's alias
	  * @param string $pass - user's encrypted password
	  */
	public function retrieve($alias, $encPass) {
		//grab user data from database
		$query = "SELECT `user_id`,`alias`,`nameFirst`,`nameLast`,`email`,`lastLogin`,INET_NTOA('ip_address'),`access_level` FROM `users` WHERE `alias`='$alias' AND `password`='$encPass';";
		$user = $this->DB->get_row($query);
		if(!is_object($user)) {
			$this->json_status =  json_encode(array('status'=>'E_NO_ACCESS'));
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
		$this->json_status = json_encode(array('status'=>'OK'));
		return true;
	}
	/**
	 * Encrypt a password
	 *@param string $user		username 
	 *@param string $pass		password to encrypt
	 *@param string $regTime	the user's registration time
	 *@return string 			encrypted password
	 */
	public function encryptPassword($user,$pass,$regTime) { 
		//create salt from the sha1 of the user's registration time
		$salt = sha1($regTime);
		//md5 encrypt the salt+password, then sha1 that whole thing and return the encrypted password
		return sha1(md5($salt.$pass));
	}
	/** 
	  * Set Authorized Session Variables
	  */
	public function AUTH() {
		$_SESSION['auth'] = 1;
		$_SESSION['alias'] = $this->alias;
		$_SESSION['user_id'] = $this->user_id;
		$_SESSION['ip'] = $this->ip_address;
	}
	/**
	 * Removed Authorized Session Variables
	 */
	public static function NOAUTH() {
		unset($_SESSION['auth']); //remove authentication
		unset($_SESSION['alias']); //unset username
		unset($_SESSION['user_id']); //unset user_id
		unset($_SESSION['ip']); //unset ip address
	}
}
?>