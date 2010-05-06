<?php
/**
 * Contains Filters Class
 * @package MooKit
 */
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
		$errors = array();
		if(!$htmLawed)
			$htmLawed = array('safe'=>1,
							'tidy'=>0,
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
	 * @param bool $stripWS 	Switch to optionally strip all whitespace
	 * @param bool $allowBlank    Switch to allow blank field
	 * @returns string
	 */
	public function text($user_text, $stripWS = false,$allowBlank=false) {
		//optional check for blank field
		if(!$allowBlank && $user_text == '') {
			$this->errors[] = 'Blank Field : '.$user_text;
			return $user_text;
		}
		//optionally remove whitespace
		if($stripWS && preg_match("/\ /",$user_text)) {
			$user_text = str_replace(" ","",$user_text);
			$this->errors[] = 'Whitespace Removed : '.$user_text;
		}
		//sanitize to remove invalid characters
		$text = filter_var($user_text, FILTER_SANITIZE_STRING);
		if($text !== $user_text ) {
			$this->errors[] = $user_text.'-> FILTER_SANITIZE_STRING -> '.$text;
		}
		//optional recheck for blank field
		if(!$allowBlank && $text == '') {
			$this->errors[] = 'Blank Field : '.$text;
		}
		return $text;			
	}
	/**
	 * Require All Alphanumeric or Underscore for characters
	 * 
	 * Remove all non-alphanumeric characters
	 * @param string $text
	 * @param bool $stripWS 	Passed to text() - Switch to optionally strip all whitespace
	 * @param bool $allowBlank    Passed to text() - Switch to allow blank field
	 * @returns string
	 */
	public function alphnum_($user_text, $stripWS=FALSE, $allowBlank=FALSE) {
		$user_text = $this->text($user_text, $stripWS, $allowBlank);
		if(!preg_match("/^([a-zA-Z0-9\_\ ]*)$/",$user_text)) {
			$text = preg_replace("/[^a-zA-Z1-9\_]/","",$user_text);
			$this->errors[] = 'Non-Alphanumberic Characters Removed : '.$text;
			return $text;
		} else {
			return $user_text;
		}
	}
	/**
	  * Require a valid number
	  * @param string $user_input
	  * @returns filtered input
	  */
	public function number($user_text) {
		if($user_text !== '') {
			if(!preg_match("/^\-?([0-9]+)$/",$user_text)) {
				$this->errors[sizeof($this->errors)] = 'Non-Numeric Characters Removed : '.$user_text;
				$text = preg_replace("/[^1-9\-]/","",$user_text);
				return $text;
			} else {
				return $user_text;
			}
		}
		else {
			$this->errors[sizeof($this->errors)] = 'Blank Field  : '.$user_text;
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
				$this->errors[] = 'Invalid URL : '.$user_ur;;
		}
		else {
			$this->errors[] = 'Invalid URL : '.$user_url;
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
	 * @param string $input the input to filter
	 * @param bool $allowBlank    Switch to allow blank field
	 * @return string
	 */
	 public function htmLawed($input,$allowBlank=TRUE) {
		if(!$allowBlank && $input == '')
			$this->errors[] = 'Blank Field : '.$input;
		//run through htmLawed
		$lawedText = htmLawed($input,$this->htmLawedConfig);
		//flag error if text was changed
		if($lawedText !== $input)
			$this->errors[] = $input.' -> htmLawed -> '.$input;
		return $lawedText;
	 }
	/**
	 * ERRORS
	 * 
	 * Returns the array of errors or null if none
	 * @returns array|NULL
	 */
	public function ERRORS() {
		if( sizeof($this->errors) > 0 ) {
			if(DEBUG) { echo "|-FILTER ERRORS-|"; var_dump($this->errors); }
			return $this->errors;
		} else {
			return NULL;
		}
	}

	//possible functions later
	//$filteredInput['state'] = $inputFilter->regEx('/^[a-z]{2}$/i',$_POST['state']);
	//$filteredInput['zip'] = $inputFilter->regEx('/^[0-9]{5}$|^[0-9]{5}\-[0-9]{4}$/',$_POST['zip']);
	
}
?>