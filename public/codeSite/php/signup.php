<?php
$inputFilter = new Filters;

//Validate User Input
$filteredInput['alias'] = $inputFilter->text($_POST['alias'], true); //also strip whitespace
$filteredInput['nameFirst'] = $inputFilter->text($_POST['nameFirst'],true); //also strip whitespace
$filteredInput['nameLast'] = $inputFilter->text($_POST['nameLast'],true); //also strip whitespace
$filteredInput['password'] = $inputFilter->text($_POST['password']);
$filteredInput['vpassword'] = $inputFilter->text($_POST['vpassword']);
$filteredInput['email'] = $inputFilter->email($_POST['email']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//Filter Error - Send back filteredInput so user can correct and resend
	echo json_encode(array('status'=>"ERROR_FILTER",'alias'=>$filteredInput['alias'],'nameFirst'=>$filteredInput['nameFirst'],'nameLast'=>$filteredInput['nameLast'],'email'=>$filteredInput['email']));
	return;
} else {
	//Add New User
	$user =  new User($filteredInput); // <- Send Callback Here : ,true, array('Email','test'));
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
	echo $user->json_status;
}
?>