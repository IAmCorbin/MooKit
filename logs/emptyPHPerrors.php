<?
//PHP Script to empty the php error log
$errorLogPath = "PHPerrors.xml";

if(is_readable($errorLogPath) && $_GET['pass'] == "blast") { 
		
		// Create a new DOMDocument object with formatting
		$errorLog = new DOMDocument('1.0');
		
		//Import error log file
		$errorLog->load($errorLogPath);
		
		//grab all errors
		$errors = $errorLog->documentElement->getElementsByTagName("error");
		
		//delete all errors
		$errorsLength = $errors->length-1;
		for($n = $errorsLength; $n >= 0; $n--) {
			$error = $errors->item($n);
			$errorLog->documentElement->removeChild($error);
		}

		// save the empty error log
		$errorLog->save($errorLogPath);
	} else { 
		echo "Error Accessing Error Log at ".$errorLogPath." or password was not passed with request ".' <a href="PHPerrors.xml">Go Back to PHPerrors.xml</a>';
		die();
	}
echo 'Error Log Cleared - <a href="PHPerrors.xml">Go Back to PHPerrors.xml</a>';
?>