<?php
require '../php/includes.php';

$inputFilter = new Filters;

//Validate User Input
$filteredInput['user'] = $inputFilter->text($_POST['user']);
$filteredInput['pass'] = $inputFilter->text($_POST['pass']);
//Check for Errors
if($erros = $inputFilter->ERRORS()) {
	//handle filter errors
	echo "SIZE OF \$ERRORS -> ".sizeof($errors).'<br />';
	foreach($errors as $error)
		echo $error."<br />";
} else {
	//user authentication
	$user = new User;
	if($user->authenticate($filteredInput['user'],$filteredInput['pass']))
		echo "LOGGED IN";
	else
		echo "INVALID USERNAME OR PASSWORD, PLEASE TRY AGAIN";
}




//DEBUG STUFF
echo '<br />----------------------------------<br /><pre>$_POST';
var_export($_POST);
echo '$_SESSION';
var_export($_SESSION);
echo '</pre>';
?>