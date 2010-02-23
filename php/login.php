<?php
require '../php/includes.php';

$inputFilter = new Filters;

//Validate User Input
$filteredInput['user'] = $inputFilter->text($_POST['user']);
$filteredInput['pass'] = $inputFilter->text($_POST['pass']);
if($erros = $inputFilter->ERRORS()) {
	//handle filter errors
	echo "SIZE OF \$ERRORS -> ".sizeof($errors).'<br />';
	foreach($errors as $error)
		echo $error."<br />";
} else {
	echo "NO FILTER ERRORS!<br />";
	//user authentication
	$user = new User;
	$user->authenticate($filteredInput['user'],$filteredInput['pass']);
}


//mail('IAmCorbin@Gmail.com','test','test');
//DEBUG STUFF
echo '<br />----------------------------------<br /><pre>$_POST';
var_export($_POST);
echo '$_SESSION';
var_export($_SESSION);
echo '</pre>';
?>