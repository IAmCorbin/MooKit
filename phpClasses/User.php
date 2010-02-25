<?php
/**
 * class User
 *
 * a class for adding or authenticating users against a users table in a database
 * 
 * Febuary 20th, 2010
 * Code by Corbin
 * http://www.IAmCorbin.net
 */
class User {
	/**
	 * Add a new user to the database
	 *@param: $filteredInput - array filled with filtered user input
	 *@return: true if inserted, false if failed
	 */
	public function addNew($filteredInput) {
		//check database for duplicate username
		$query = "SELECT `alias` FROM `users` WHERE `alias`='".$filteredInput['user']."' LIMIT 1;";
		$results = $_SESSION['DB']->query($query);
		if(mysql_num_rows($results) > 0) //return false is username is already found
			return false;
		//generate encrypted password
		$regTime = date('Y-m-d H:i:s');
		if($encPass = $this->encryptPassword($filteredInput['user'],$filteredInput['pass'],$regTime)) {
			//add new user to database
			echo "ENCODED PASSWORD => ".$encPass."<br />";
			$_SESSION['DB']->query(
				'INSERT INTO `users`(`alias`,`nameFirst`,`nameLast`,`password`,`email`,`registered`) 
				VALUES(\''.$filteredInput['user'].'\',\''.$filteredInput['first'].'\',\''.$filteredInput['last'].'\',\''.$encPass.'\',\''.$filteredInput['email'].'\',\''.$regTime.'\');');
			return true;
		} else
			return false;
	}
	/**
	 * Authenticates a user against database - on authorization it will set $_SESSION['auth'] = 1
	 *@param: $user - username to test
	 *@param: $pass - password to test
	 *@param: $tbl - the database table to test against
	 *@return: true if authenticated, false if failed
	 */
	public function authenticate($user,$pass, $tbl='users') {
		//if authentication if set, unset it
		isset($_SESSION['auth'])? $_SESSION['auth']=0  : 0;
		//if a valid password is returned (requires the username to be in the database)
		if($encPass = $this->encryptPassword($user,$pass)) {
			echo 'encoded password: '.$encPass;
			//check user
			$query = "SELECT * FROM $tbl WHERE `alias`='$user' AND `password`='$encPass' LIMIT 1;";
			$results = $_SESSION['DB']->query($query);
			if(mysql_num_rows($results)>0) {
				$_SESSION['auth'] = 1;
				$_SESSION['user'] = $user;
				//hidden element to flag successful login read from JavaScript
				echo '<div id="LOGGEDIN" style="display:none;"></div>';
				return true;
			}
		} else {
			echo 'nopassword returned<br />';
			$this->NOAUTH();
			return false;
		}
		$this->NOAUTH();
		return false;
	}
	/**
	 * NO AUTHORIZATION
	 * REMOVE AUTHORIZATION FLAG AND USERNAME
	 */
	public function NOAUTH() {
		$_SESSION['auth'] = 0;
		unset($_SESSION['user']);
		//flag logged out for javascript
		echo '<div id="LOGGEDOUT" style="display:none;"></div>';
	}
	/**
	 * Encrypt a password
	 *@param: $user - username 
	 *@param: $pass - password to encrypt
	 *@param: $regTime - the user's registration time, used to pass in if this is a new user and we are encrypting the password for the first time
	 *@return string - encrypted password or false
	 */
	public function encryptPassword($user,$pass,$regTime=NULL) {
		//addNew will pass a new regTime, so don't look for a non-existant one in the database
		if(!$regTime) {
			//get user registration time
			$query = "SELECT `registered` FROM `users` WHERE `alias`='$user' LIMIT 1;";
			$results = $_SESSION['DB']->query($query);
			if(mysql_num_rows($results) > 0) {//store user's registration DATETIME
				$regTime = mysql_fetch_row($results);
				$regTime = $regTime[0];
			} else //return NULL if no valid user alias was found in the database
				return false;
		}
		echo 'REGTIME = '.$regTime.'<br />';
		//create salt from the sha1 of the user's registration time
		$salt = sha1($regTime);
		//md5 encrypt the salt+password, then sha1 that whole thing and return the encrypted password
		return sha1(md5($salt.$pass));
	}
	
}

?>