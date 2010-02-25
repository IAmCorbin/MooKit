<?php
/***********************
/Class to encapsulate php 5.2 filter_var for simple form validation
/Febuary 19th, 2010
/
/Code by Corbin
/http://www.IAmCorbin.net
/************************/
Class Filters {

	private $errors;
	
	public function __construct() {
		$errors[0] = 'none';
	}

	public function email($user_email) {
		//if not blank
		if($user_email !== '') {
			//sanitize to remove invalid characters
			$email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
			//validate
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
				return $email;
			else
				$this->errors[sizeof($this->errors)] = 'Invalid Email';
		}
		else {
			$this->errors[sizeof($this->errors)] = 'Invalid Email';
		}
	}
	
	public function text($user_text) {
		if($user_text !== '') {
			//sanitize to remove invalid characters
			$text = filter_var($user_text, FILTER_SANITIZE_STRING);
			if($text !== '')
				return $text;
			else 
				$this->errors[sizeof($this->errors)] = 'Blank Field';
		}
		else {
			$this->errors[sizeof($this->errors)] = 'Blank Field';
		}
	}
	
	public function url($user_url) {
		if($user_url !== '') {
			//sanitize to remove invalid characters
			$url = filter_var($user_url, FILTER_SANITIZE_URL);
			//validate
			if(filter_var($url, FILTER_VALIDATE_URL))
				return $url;
			else
				$this->errors[sizeof($this->errors)] = 'Invalid URL';
		}
		else {
			$this->errors[sizeof($this->errors)] = 'Invalid URL';
		}
	}
	
	public function regEx($regEx, $subject) {
		if(preg_match($regEx, $subject))
			return $subject;
		else
			$this->errors[sizeof($this->errors)] = 'RegEx Failed';
	}
	
	public function ERRORS() {
		if( sizeof($this->errors) > 0 ) {
			return $this->errors;
		} else {
			//hidden element to flag PHP Validation_ read from javascript
			echo '<div id="PHPVALIDATED" style="display:none;"></div>';
			return NULL;
		}
	}

	//possible functions later
	//$filteredInput['state'] = $inputFilter->regEx('/^[a-z]{2}$/i',$_POST['state']);
	//$filteredInput['zip'] = $inputFilter->regEx('/^[0-9]{5}$|^[0-9]{5}\-[0-9]{4}$/',$_POST['zip']);
	
}

?>