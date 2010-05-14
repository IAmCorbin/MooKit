<?php
/**
  * Shared - Get User Information from Database
  * @package MooKit
  */
//require Admin or Create Access
$access_level = Security::clearance();
if(($access_level & ACCESS_CREATE)||($access_level & ACCESS_ADMIN)) {
	if(!isset($_POST['alias'])) $_POST['title'] = NULL;
	if($_POST['rType'] === "json")
		echo sharedGetUsers($_POST['rType'],$_POST['alias']);
	else
		echo json_encode(array('status'=>'1','html'=>sharedGetUsers($_POST['rType'],$_POST['alias'])));
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>