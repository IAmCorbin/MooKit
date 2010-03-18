<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php'; INIT(false);



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
?>