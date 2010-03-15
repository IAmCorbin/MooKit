<?php
//~ if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 
require_once '../php/includes.php';

//don't do anything if already logged in
if($_SESSION['auth'] === 1) {
	echo json_encode(array('status'=>"IN"));
	return;
}
$inputFilter = new Filters;
//Validate User Input
$filteredInput['user'] = $inputFilter->text($_POST['user']);
$filteredInput['pass'] = $inputFilter->text($_POST['pass']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filter errors
	echo json_encode(array('status'=>'ERROR_FILTER'));
	if(DEBUG) { echo "SIZE OF \$ERRORS -> ".sizeof($errors).'<br />'; foreach($errors as $error) echo $error."<br />"; }
	return;
} else {
	//user authentication
	$user = new User;
	if($user->authenticate($filteredInput['user'],$filteredInput['pass']))
		echo json_encode(array('status'=>"LOGGEDIN"));
	else
		echo json_encode(array('status'=>"LOGGEDOUT"));
}



//DEBUG STUFF
/*echo '<br />----------------------------------<br /><pre>$_POST';
var_export($_POST);
echo '$_SESSION';
var_export($_SESSION);
echo '</pre>';*/

//~ } //end if(defined('INSITE')
?>