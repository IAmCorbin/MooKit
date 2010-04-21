<?php
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	$alias = $_POST['alias'];
	
	$inputFilter = new Filters;
	$alias = $inputFilter->text($alias,true);
	
	$DB = new DatabaseConnection;
	
	$query = "DELETE FROM `users` WHERE `alias`='$alias'	LIMIT 1;";
	$status = $DB->delete($query);
	echo json_encode(array('status'=>$status));
} else
	echo "Unauthorized";
?>