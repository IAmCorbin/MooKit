<?php
if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 
/**
 *Class to encapsulate php 5.2 filter_var for simple form validation
 *
 * @author Corbin Tarrant
 * @copyright Febuary 19th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
Class Filters {
	/** @var array $errors array of thrown filter errors */
	var $errors;
	/** @var array $htmLawedConfig htmLawed configuration settings */
	var $htmLawedConfig;
	/**
	 * Constructor
	 *
	 * Initialize the $errors array to 'none'
	 * @param array $htmLawed htmLawed configuration settings
	 */
	public function __construct($htmLawed = null) {
		$errors[0] = 'none';
		if(!$htmLawed)
			$htmLawed = array('safe'=>1,
							'tidy'=>1,
							'deny_attribute'=>'* -href -target -style -class',
							'schemes'=>'style: *; href: *; target: *');
		$this->htmLawedConfig = $htmLawed;
	}
	/**
	 * Filter an Email
	 * 
	 * Filter a user entered email address
	 * @param string $user_email
	 * @returns string
	 */
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
	/**
	 * Filter text
	 * 
	 * Filter user entered text
	 * @param string $user_text
	 * @returns string
	 */
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
	/**
	 * Filter a URL
	 * 
	 * Filter a user entered URL
	 * @param string $user_url
	 * @returns string
	 */
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
	/**
	 * Filter against a regEx
	 * 
	 * Filter user entered input against a provided regEx
	 * @param regEx $regEx a valid regular expression
	 * @param string $subject the string to test against
	 * @returns string
	 */
	public function regEx($regEx, $subject) {
		if(preg_match($regEx, $subject))
			return $subject;
		else
			$this->errors[sizeof($this->errors)] = 'RegEx Failed';
	}
	/**
	 * Filter input with htmLawed
	 * @link http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/
	 * @params string $input the input to filter
	 * @returns string
	 */
	 public function htmLawed($input) {
		//REMOVE MAGIC QUOTES
		if(get_magic_quotes_gpc())
			$input = stripslashes($input);
		return htmLawed($input,$this->htmLawedConfig);
		
	 }
	/**
	 * ERRORS
	 * 
	 * Returns the array of errors or null if none
	 * @returns array|NULL
	 */
	public function ERRORS() {
		if( sizeof($this->errors) > 0 ) {
			return $this->errors;
		} else {
			//hidden element to flag PHP Validation_ read from javascript
			//echo '<div id="PHPVALIDATED" style="display:none;"></div>';
			return NULL;
		}
	}

	//possible functions later
	//$filteredInput['state'] = $inputFilter->regEx('/^[a-z]{2}$/i',$_POST['state']);
	//$filteredInput['zip'] = $inputFilter->regEx('/^[0-9]{5}$|^[0-9]{5}\-[0-9]{4}$/',$_POST['zip']);
	
}

} //end if(defined('INSITE')
?>