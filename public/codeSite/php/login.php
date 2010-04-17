<?php 

//don't do anything if already logged in
//~ if($_SESSION['auth'] === 1) {
	//~ echo json_encode(array('status'=>"IN"));
	//~ return;
//~ }

//Validate User Input
$inputFilter = new Filters;
$filteredInput['alias'] = $inputFilter->text($_POST['alias'],true);
$filteredInput['password'] = $inputFilter->text($_POST['password']);

//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filter errors
	echo json_encode(array('status'=>'E_FILTERS','alias'=>$filteredInput['alias']));
	return;
} else {
	//no filter errors - user authentication
	$user = new User($filteredInput, false);
	//Send Status back to javascript
	echo $user->json_status;
}
?>