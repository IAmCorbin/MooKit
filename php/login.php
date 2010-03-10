<?php
require_once '../php/includes.php';

//don't do anything if already logged in
$inputFilter = new Filters;

if($_SESSION['user'] === $_POST['user']) {
	echo '{"status" : "IN" }';
	return;
}
//Validate User Input
$filteredInput['user'] = $inputFilter->text($_POST['user']);
$filteredInput['pass'] = $inputFilter->text($_POST['pass']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filter errors
	echo "SIZE OF \$ERRORS -> ".sizeof($errors).'<br />';
	foreach($errors as $error)
		echo $error."<br />";
} else {
	//user authentication
	$user = new User;
	if($user->authenticate($filteredInput['user'],$filteredInput['pass']))
		echo '{ "status" : "LOGGEDIN" }';
	else
		echo '{ "status" : "LOGGEDOUT" }';
}



//DEBUG STUFF
/*echo '<br />----------------------------------<br /><pre>$_POST';
var_export($_POST);
echo '$_SESSION';
var_export($_SESSION);
echo '</pre>';*/
?>