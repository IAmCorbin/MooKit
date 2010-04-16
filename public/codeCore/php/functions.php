<?php
/**
 * Check for Magic Quotes and Strip if on and return mysql_real_escape_string
 */
function magicMySQL($DB,$var) {
	return mysqli_real_escape_string($DB,$var);
}

/** Error Handling and Logging **/
function ErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    //Error Log filename
      $errorLogPath = ERROR_LOG_DIR."PHPerrors.xml";
    //how long to keep errors 
      defined('PHP_ERROR_EXPIRE')? $errorExpireTime = strtotime(PHP_ERROR_EXPIRE) : $errorExpireTime = NULL;
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
		if($errorExpireTime) {
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
			ob_start();
			var_export($vars);
			$vars = ob_get_contents();
			ob_clean(); //cleanup ob
			$err->addChild('vartrace', $vars);
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
		// SEND AN EMAIL TO ADMINISTRATOR IF ERROR LOGGING IS BROKEN
	}
    
	// !!!
	// Add Administrator Email notification depending on error type
	// !!!
    //if ($errno == E_USER_ERROR) {
	//mail("phpdev@example.com", "Critical User Error", $err);
    //}
}
set_error_handler("ErrorHandler");


 function array_keys_exist($keyArray, $array) {
	foreach($keyArray as $key) {
		if(!array_key_exists($key, $array))
			return false;
	}
 
	return true;
}
?>