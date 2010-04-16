<?
//PHP Script to empty the php error log
$errorLogPath = $_POST['errorLog'];

if(is_readable($errorLogPath) && $_POST['trim'] !== "") { 
	// Create a new DOMDocument object with formatting
	$errorLog = new DOMDocument('1.0');
	
	//Import error log file
	$errorLog->load($errorLogPath);
	
	//grab all errors
	$errors = $errorLog->documentElement->getElementsByTagName("error");
	
	$errorExpireTime = strtotime($_POST['trim']);
	//trim errors
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
	
	// save the empty error log
	$errorLog->save($errorLogPath);
} else { 
	echo "Error Accessing Error Log at ".$errorLogPath." or trim amount not passed with request ".' <a href="'.$_POST['errorLog'].'">Go Back to '.$_POST['errorLog'].'</a>';
	die();
}
echo 'Error Log Cleared - <a href="'.$_POST['errorLog'].'">Go Back to '.$_POST['errorLog'].'</a>';
?>