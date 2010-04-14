<?php
$inputFilter = new Filters;

//Validate User Input
$filteredInput['alias'] = $inputFilter->text($_POST['user']);
$filteredInput['nameFirst'] = $inputFilter->text($_POST['first']);
$filteredInput['nameLast'] = $inputFilter->text($_POST['last']);
$filteredInput['password'] = $inputFilter->text($_POST['pass']);
$filteredInput['vpassword'] = $inputFilter->text($_POST['vpass']);
$filteredInput['email'] = $inputFilter->email($_POST['email']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filtered input errors errors
	echo '{"status" : "ERROR_FILTER"}';
	return;
} else {
	//Add New User
	$status =  new User($filteredInput);
	echo $status->status;

		//send user an email
			//~ $to = $filteredInput['email'];
			//~ $subject = "User Login Test - Account Created";
			//~ $message = 	"Thanks for signing up\n
						 //~ \n
						 //~ You signed up providing this information:\n
						 //~ Username: ".$filteredInput['user']."\n
						 //~ First Name: ".$filteredInput['first']."\n
						 //~ Last Name: ".$filteredInput['last']."\n
						 //~ Email: ".$filteredInput['email']."\n
						 //~ Registration Date and Time: ".$regTime."\n\n
						 //~ See ya Soon!\n\n
						 //~ ~The Management";
			//sendEmail($to,$subject,$message); CREATE FUNCTION
			//if(!mail($to,$subject,$message)) 
				//throw new Exception('Error Sending Email');
			//User Added
	
}
?>