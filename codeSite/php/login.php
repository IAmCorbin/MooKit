<?php 

//don't do anything if already logged in
if($_SESSION['auth'] === 1) {
	echo json_encode(array('status'=>"IN"));
	return;
}

//Validate User Input
$inputFilter = new Filters;
$filteredInput['user'] = $inputFilter->text($_POST['user']);
$filteredInput['pass'] = $inputFilter->text($_POST['pass']);

//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filter errors
	echo json_encode(array('status'=>'ERROR_FILTER'));
	return;
} else {
	//no filter errors - user authentication
	$user = new User;
	if($user->authenticate($filteredInput['user'],$filteredInput['pass']))
		echo json_encode(array('status'=>"LOGGEDIN"));
	else
		echo json_encode(array('status'=>"LOGGEDOUT"));
}
?>