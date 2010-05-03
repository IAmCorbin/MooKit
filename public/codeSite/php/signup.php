<?php
//Add New User  					
$user =  new User(array('alias'=>$_POST['alias'],
				      'nameFirst'=>$_POST['nameFirst'],
				      'nameLast'=>$_POST['nameLast'],
				      'password'=>$_POST['password'],
				      'vpassword'=>$_POST['vpassword'],
				      'email'=>$_POST['email']),
			      TRUE, // New User Flag
			      NULL); // <- Send Callback Here : ,true, array('Email','test'));
//Send Status back to javascript
echo $user->json_status;
?>