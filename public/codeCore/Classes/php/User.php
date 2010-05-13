<?php
/**
 * contains User Class
 * @package MooKit
 */
/**
  * User Class
  *
  * User manipulation class ( Add / Edit / Delete / Retrieve )
  * 
  * @author Corbin Tarrant
  * @copyright Febuary 20th, 2010
  * @link http://www.IAmCorbin.net
  * @package MooKit
  */
class User {
	/** @var 		DB_MySQLi 	$DB			database object */
	var $DB = NULL;
	/** @var 		string 		$json_status	stores the status (success/error) of user manipulation, and any other data to be sent back to javascript */
	var $json_status = NULL;
	/** @var 			int 		$user_id		users's id */
	var $user_id = NULL;
	/** @var 			string 	$nameFirst	user's first name */
	var $nameFirst = NULL;
	/** @var 			string 	$namelast	user's last name */
	var $nameLast = NULL;
	/** @var 			string 	$alias		user's alias */
	var $alias = NULL;
	/** @var 			string 	$email		user's email */
	var $email = NULL;
	/** @var 			string 	$registered	date and time user registered */
	var $registered = NULL;
	/** @var 			string 	$lastLogin	date and time user last logged in */
	var $lastLogin = NULL;
	/** @var 			string 	$ip_address	user's ip address */
	var $ip_address = NULL;
	/** @var 			int	 	$access_level	user's access_level */
	var $access_level = NULL;
	
	/** 
	  * Constructor 
	  *
	  * Filters input and adds or authenticates a user with the database
	  *
	  *@param 	array 	$userInput 		array filled with filtered user input : if creating a new user pass keys{ alias, nameFirst, nameLast, password, vpassword, email }, if authenticating an existing user pass keys{ alias, password, vpassword }
	  *@param 	bool 	$newUser 		switch to create a new user or retrieve an existing one
	  *@param 	function 	$newUserCallback 	function that will be called if a new user is successfully added
	  */
	function __construct($userInput, $newUser = TRUE, $newUserCallback = NULL) {
		//make sure $userInput is an array
		if(!is_array($userInput)) {
			$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
			return;
		}
		//establish database connection
		$this->DB = new DB_MySQLi;
		
		if($newUser) {		
			//check for valid passed data
			if(!array_keys_exist(array('alias','nameFirst','nameLast','password','email'),$userInput)) {
				$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
				return;
			}
			//filter input
			$inputFilter = new Filters;
			//Validate User Input
			$filteredInput['alias'] = $inputFilter->text($userInput['alias'], true); //also strip whitespace
			$filteredInput['alias'] = $inputFilter->alphnum_($filteredInput['alias']); //only allow alphanumeric or underscore for alias
			$filteredInput['nameFirst'] = $inputFilter->text($userInput['nameFirst'],true); //also strip whitespace
			$filteredInput['nameLast'] = $inputFilter->text($userInput['nameLast'],true); //also strip whitespace
			$filteredInput['password'] = $inputFilter->text($userInput['password']);
			$filteredInput['vpassword'] = $inputFilter->text($userInput['vpassword']);
			$filteredInput['email'] = $inputFilter->email($userInput['email']);

			//Check for matching passwords
			if($filteredInput['password'] != $filteredInput['vpassword']) {
				$this->json_status = json_encode(array('status'=>"E_BADPASS",'alias'=>$filteredInput['alias'],'nameFirst'=>$filteredInput['nameFirst'],'nameLast'=>$filteredInput['nameLast'],'email'=>$filteredInput['email']));
				return;
			}
			//Check for Filter Errors
			if($errors = $inputFilter->ERRORS()) {
				//Filter Error - Send back filteredInput so user can correct and resend
				$this->json_status =  json_encode(array('status'=>"E_FILTERS",'alias'=>$filteredInput['alias'],'nameFirst'=>$filteredInput['nameFirst'],'nameLast'=>$filteredInput['nameLast'],'email'=>$filteredInput['email']));	
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
			if(!array_keys_exist(array('alias','password'),$userInput)) {
				$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
				return;
			}
			//filter input
			$inputFilter = new Filters;
			//Validate User Input
			$filteredInput['alias'] = $inputFilter->text($userInput['alias'], true); //also strip whitespace
			$filteredInput['alias'] = $inputFilter->alphnum_($filteredInput['alias']); //only allow alphanumeric or underscore for alias
			$filteredInput['password'] = $inputFilter->text($userInput['password']);

			//Check for Filter Errors
			if($errors = $inputFilter->ERRORS()) {
				//Filter Error - Send back filteredInput so user can correct and resend
				$this->json_status =  json_encode(array('status'=>"E_FILTERS",'alias'=>$filteredInput['alias']));	
				return;
			}
			
			//get user's registration time
			$user = $this->DB->get_row("SELECT `registered` FROM `users` WHERE `alias`=? LIMIT 1;",
									's',array($filteredInput['alias']));
			if(is_object($user) && isset($user->registered)) {
				//store user's registration DATETIME
				$this->regTime = $user->registered;
			} else { //user was not found
				$this->json_status =  json_encode(array('status'=>'E_NOAUTH','alias'=>$filteredInput['alias']));
				return false;			
			}
			//encrypt sent password
			$encPass = $this->encryptPassword($filteredInput['password'],$this->regTime);
			
			//attempt to retrieve this user
			if($this->retrieve($filteredInput['alias'],$encPass)) {
				//set authenticated session variables
				$this->AUTH();
				//update last login time in database to now
				$this->updateLastLogin();
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
	 * @param 	string 	$alias 		new user's alias
	 * @param 	string 	$password 	new user's password
	 * @param 	string 	$nameFirst 	new user's first name
	 * @param 	string 	$nameLast 	new user's last name
	 * @param 	string 	$email 		new user's email address
	 * 
	 */
	public function addNew($alias,$password,$nameFirst,$nameLast,$email) {
		//check database for duplicate username
		$user = $this->DB->get_row("SELECT `alias` FROM `users` WHERE `alias`=? OR `email`=? LIMIT 1;",
						    'ss', array($alias,$email));
		if(is_object($user) && isset($user->alias)) {
			$this->json_status =  json_encode(array('status'=>'E_DUPLICATE','alias'=>$alias,'nameFirst'=>$nameFirst,'nameLast'=>$nameLast,'email'=>$email));
			return false;
		}
		
		//set user's registration time as the current time
		$regTime = date('Y-m-d H:i:s');
		//get user's IP
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		//generate encrypted password
		$encPass = $this->encryptPassword($password,$regTime); 
		//add new user to database
		if($this->DB->insert("INSERT INTO `users`(`alias`,`nameFirst`,`nameLast`,`password`,`email`,`registered`,`ip_address`) 
						 VALUES(?,?,?,?,?,?,INET_ATON(?));",
						 'sssssss',array($alias,$nameFirst,$nameLast,$encPass,$email,$regTime,$ip_address))) {
			$this->json_status = json_encode(array('status'=>'1'));
			return true;
		} else {
			$this->json_status =  json_encode(array('status'=>'E_INSERT'));
			return false;
		}
	}
	/**
	  * Retrieve a user's information from the database and store to object variables
	  * @param 	string 	$alias 	user's alias
	  * @param 	string 	$pass 	user's encrypted password
	  */
	public function retrieve($alias, $encPass) {
		//grab user data from database
		$user = $this->DB->get_row("SELECT `user_id`,`alias`,`nameFirst`,`nameLast`,`email`,`lastLogin`,INET_NTOA(ip_address) as ip_address,`access_level` 
							      FROM `users` WHERE `alias`=? AND `password`=?;",
							      'ss',array($alias,$encPass));
		if(!is_object($user)) {
			$this->json_status =  json_encode(array('status'=>'E_NOAUTH'));
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
		$this->json_status = json_encode(array('status'=>'1'));
		return true;
	}
	/**
	  * Update user's lastLogin datetime
	  * @return bool
	  */
	public function updateLastLogin() {
		
		if($this->DB->update("UPDATE `users` SET `lastLogin`='".date('Y-m-d H:i:s')."' WHERE `alias`=?;",
						  's',array($this->alias)))
			return true;
		else
			return false;
	}
	/**
	   * SELECT users from the database WHERE LIKE $alias
	   * @param 	string	$alias	The aliases to search for  ( LIKE '%$alias%' )
	   * @param 	string 	$rType 	the return type for the users
	   * @returns 	mixed	the requested database return type
	   */
	public static function get($alias, $rType="object") {
		$DB = new DB_MySQLi;
		if(isset($alias)) {
			$inputFilter = new Filters;
			$alias = $inputFilter->text($alias,true);
			//set return columns based on access level
			$access_level = Security::clearance();
			if($access_level & ACCESS_ADMIN)
				$columns = "`user_id`, `alias`,`nameFirst`,`nameLast`,`email`,`access_level`";
			else if($access_level & ACCESS_CREATE)
				$columns = "`user_id`, `alias`, `nameFirst`,`nameLast`";
			$results = $DB->get_rows("SELECT $columns FROM `users` WHERE `alias` LIKE CONCAT('%',?,'%') LIMIT 20;",
						's',array($alias), $rType);
						
		} else {
			$results = $DB->get_rows("SELECT $columns FROM `users` LIMIT 20;",NULL,NULL,$rType);
		}
		return $results;
	}
	/**
	  * Delete a user from the Database
	  * @param 	int	$user_id		The user to delete
	  * @return 	status
	  */
	public static function delete($user_id) {
		//check for valid input and return error if not valid
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		if($inputFilter->ERRORS()) return json_encode(array('status'=>"E_FILTER"));
		//establish database connection
		$DB = new DB_MySQLi;
		//delete user
		$DB->delete("DELETE FROM `users` WHERE `user_id`=?;",
				      'i',array($user_id));
		//close the database connection
		$DB->close();
		return json_encode(array('status'=>$DB->STATUS));
	}
	/**
	  * Increase the access level of a user
	  * @param	int	$user_id		The user to increase access for
	  * @return 	json_status with updated access level and title
	  */
	public static function accessInc($user_id) {			
		$status = "1";
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		if($inputFilter->ERRORS()) return json_encode(array('status'=>"E_FILTERS"));
		//connect to Database
		$DB = new DB_MySQLi;
		//get current access_level
		if(!$user = $DB->get_row("SELECT `access_level` FROM `users` WHERE `user_id`=?;",
						     'i',array($user_id)))
			return json_encode(array('status'=>"E_ID"));
		$access_level = $user->access_level;
		//set new access level if below ADMIN ACCESS
		if($access_level & ACCESS_ADMIN) {
			//already an admin, can't reaise access any higher
			return json_encode(array('status'=>'0'));
		} else {
			if($access_level & ACCESS_CREATE) {
				//if user was a Creator, set to Admin
				$newAccess = $access_level | ACCESS_ADMIN;
				
			} else {
				if($access_level & ACCESS_BASIC) {
					//if user was Basic, set to Creator
					$newAccess = $access_level | ACCESS_CREATE;
				} else {
					if($access_level == ACCESS_NONE)
						//if user was unauthorized, set to BASIC ACCESS
						$newAccess = $access_level | ACCESS_BASIC;
				}
			}
			//update user's access level
			$DB->update("UPDATE `users` SET `access_level`=? WHERE `user_id`=?;",
					       'ii',array($newAccess,$user_id));
		}
		return json_encode(array('status'=>$status,'access'=>$newAccess,'title'=>getHumanAccess($newAccess)));
	}
	/**
	  * Decrease the access level of a user
	  * @param	int	$user_id		The user to increase access for
	  * @return 	json_status with updated access level and title
	  */
	public static function accessDec($user_id) {			
		$status = "1";
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		if($inputFilter->ERRORS()) return json_encode(array('status'=>"E_FILTERS"));
		//connect to Database
		$DB = new DB_MySQLi;
		//get current access_level
		if(!$user = $DB->get_row("SELECT `access_level` FROM `users` WHERE `user_id`=?;",
							  'i',array($user_id)))
			return json_encode(array('status'=>"E_ID"));
		$access_level = $user->access_level;
		
		//set new access level if current is above 0
		if($access_level == ACCESS_NONE) {
			return json_encode(array('status'=>'0'));
		} else {
			if($access_level & ACCESS_ADMIN) {
				$newAccess = $access_level ^= ACCESS_ADMIN;
			} else {
				if($access_level & ACCESS_CREATE) {
					$newAccess = $access_level ^= ACCESS_CREATE;
				} else {
					if($access_level == ACCESS_BASIC)
						$newAccess = $access_level ^= ACCESS_BASIC;
				}
			}	
			//update user's access level
			$status = $DB->update("UPDATE `users` SET `access_level`=? WHERE `user_id`=?;",
							       'ii',array($newAccess,$user_id));		
		}
		echo json_encode(array('status'=>$status,'access'=>$newAccess,'title'=>getHumanAccess($newAccess)));
	}
	/**
	 * Encrypt a password
	 *@param 	string 	$pass		password to encrypt
	 *@param 	string 	$regTime		the user's registration time
	 *@return 	string 	encrypted password
	 */
	public function encryptPassword($pass,$regTime) { 
		//create salt from the sha1 of the user's registration time
		$salt = sha1($regTime);
		//md5 encrypt the salt+password, then sha1 that whole thing and return the encrypted password
		return sha1(md5($salt.$pass));
	}
	/** Set Authorized Session Variables */
	public function AUTH() {
		$_SESSION['auth'] = 1;
		$_SESSION['alias'] = $this->alias;
		$_SESSION['user_id'] = $this->user_id;
		$_SESSION['ip'] = $this->ip_address;
		$_SESSION['access_level'] = $this->access_level;
	}
	/** Removed Authorized Session Variables */
	public static function NOAUTH() {
		unset($_SESSION['auth']); //remove authentication
		unset($_SESSION['alias']); //unset username
		unset($_SESSION['user_id']); //unset user_id
		unset($_SESSION['ip']); //unset ip address
		unset($_SESSION['access_level']); //unset access_level
	}
}
?>