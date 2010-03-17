<?php
//~ if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 

require '../php/includes.php';

$inputFilter = new Filters;

//Validate User Input
$filteredInput['user'] = $inputFilter->text($_POST['user']);
$filteredInput['first'] = $inputFilter->text($_POST['first']);
$filteredInput['last'] = $inputFilter->text($_POST['last']);
$filteredInput['pass'] = $inputFilter->text($_POST['pass']);
$filteredInput['vpass'] = $inputFilter->text($_POST['vpass']);
$filteredInput['email'] = $inputFilter->email($_POST['email']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filtered input errors errors
	echo '{"status" : "ERROR_FILTER"}';
	return;
} else {
	//make sure passwords match
	if(($_POST['pass'] === $_POST['vpass'])){
		$user = new User;
		//Try and add new user
		try {
			switch($userStatus = $user->addNew($filteredInput)) {
				case 'added':
					echo json_encode(array('status'=>'ADDED'));
					return;
				case 'duplicate':
					throw new Exception('Duplicate User Exists');
					break;
				case 'passEncFail';
					throw new Exception('Password Encryption Failed');
					break;
				case false:
					throw new Exception('Error Adding User');
					break;
			}
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
		} catch(Exception $e) {
			echo json_encode(array('status'=>$e->getMessage()));
			return;
		}
	} else {
		echo json_encode(array('status'=>'ERROR_BADPASS'));
	}
}

//DEBUG STUFF
/*echo "<br />---------------------------------------------<br />FILTERED INPUTS: <br />";
foreach($filteredInput as $field => $input)
	echo $field.'=>'.$input.'<br />';

echo "POST Variables: <br />";
echo '<pre>';
var_export($_POST);
echo '</pre>';*/

//~ } //end if(defined('INSITE')
?>