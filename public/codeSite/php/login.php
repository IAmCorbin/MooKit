<?php 

//don't do anything if already logged in
//~ if($_SESSION['auth'] === 1) {
	//~ echo json_encode(array('status'=>"IN"));
	//~ return;
//~ }

//Authuenticate User
$user =  new User(array('alias'=>$_POST['alias'],
				      'password'=>$_POST['password']),
			      FALSE); // Not a new user, just authenticate
//Send Status back to javascript
echo $user->json_status;
?>