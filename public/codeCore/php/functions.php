<?php
/**
 * Check for Magic Quotes and Strip if on and return mysql_real_escape_string
 */
function magicMySQL($DB,$var) {
	if(get_magic_quotes_gpc()) $var = stripslashes($var);
	return mysqli_real_escape_string($DB,$var);
}
// we will do our own error handling
error_reporting(0);

/** Error Handling and Logging **/
function ErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    //Error Log filename
      $errorLogPath = ERROR_LOG_DIR."PHPerrors.xml";
    //how long to keep errors 
      defined('PHP_ERROR_EXPIRE')? $errorExpireTime = strtotime(PHP_ERROR_EXPIRE) : $errorExpireTime = strtotime("-7 days");
    // define an assoc array of error string
    // in reality the only entries we should
    // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
    // E_USER_WARNING and E_USER_NOTICE
      $errortype = array (
		  E_ERROR              => 'Error',
		  E_WARNING            => 'Warning',
		  E_PARSE              => 'Parsing Error',
		  E_NOTICE             => 'Notice',
		  E_CORE_ERROR         => 'Core Error',
		  E_CORE_WARNING       => 'Core Warning',
		  E_COMPILE_ERROR      => 'Compile Error',
		  E_COMPILE_WARNING    => 'Compile Warning',
		  E_USER_ERROR         => 'User Error',
		  E_USER_WARNING       => 'User Warning',
		  E_USER_NOTICE        => 'User Notice',
		  E_STRICT             => 'Runtime Notice',
		  E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
		  );
    // set of errors for which a var trace will be saved
      $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    
	if(is_readable($errorLogPath)) { 
		// Create a new DOMDocument object with formatting
		$errorLog = new DOMDocument('1.0');
		$errorLog->formatOutput = true;
		$errorLog->preserveWhiteSpace = false;
		//Import error log file
		$errorLog->load($errorLogPath);
		//grab all errors
		$errors = $errorLog->documentElement->getElementsByTagName("error");
		
		
		//check time and remove old errors		
		$errorsLength = $errors->length-1;
		for($n = $errorsLength; $n >= 0; $n--) {
			$error = $errors->item($n);
			//get error time
			$errorTime = $error->getElementsByTagName("datetime");
			//convert time
			$errorTime = strtotime($errorTime->item(0)->nodeValue);
			//compare and remove if expired
			if( $errorTime < $errorExpireTime)
				$errorLog->documentElement->removeChild($error);
		}

		//Create the new Error XML
		$err = new SimpleXMLElement("<error></error>");
		$err->addChild('datetime',date('d M Y H:i T'));
		$err->addChild('num',$errno);
		$err->addChild('type',$errortype[$errno]);
		$err->addChild('msg',$errmsg);
		$err->addChild('scriptname',$filename);
		$err->addChild('linenum',$linenum);
		if (in_array($errno, $user_errors)) {
			$err->addChild('vartrace', wddx_serialize_value($vars, "Variables"));
		}
		//convert the simpleXML to a DOMElement 
		$Err = dom_import_simplexml($err);
		//import the node, and all its children, to the document
		$Err = $errorLog->importNode($Err,true);
		// And then append it to the "<root>" node
		$errorLog->documentElement->appendChild($Err);
		
		// save the modified error log
		$errorLog->save($errorLogPath);
	} else { 
		echo "Error Accessing Error Log at ".$errorLogPath." | Please make sure this file is avaiable and writable by the web server";
		die();
	}
    
	// !!!
	// Add Administrator Email notification depending on error type
	// !!!
    //if ($errno == E_USER_ERROR) {
	//mail("phpdev@example.com", "Critical User Error", $err);
    //}
}
set_error_handler("ErrorHandler");
?>