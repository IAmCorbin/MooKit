<?
/**
  * Administrator - Increase User Access
  * @package MooKit
  */
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo User::accessInc($_POST['user_id']);
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>