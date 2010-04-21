<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	$alias = $_POST['alias'];
	$access_level = $_POST['access_level'];
	
	//filter to prevent against SQL injection
	$inputFilter = new Filters;
	$alias = $inputFilter->text($alias,true);
	
	//set new access level if below ADMIN ACCESS
	if($access_level < ACCESS_ADMIN) {
		$newAccess = $access_level*2;
		//if user was unauthorized, set to BASIC ACCESS
		if($access_level == 0)
			$newAccess = 1;
		
		//connect to Database
		$DB = new DatabaseConnection;
		//update user's access level
		$query = "UPDATE `users` SET `access_level`='$newAccess' WHERE `alias`='$alias' LIMIT 1;";
		$status = $DB->delete($query);
	} else {
		$status = 0;
	}
	echo json_encode(array('status'=>$status,'access'=>$newAccess,'title'=>getHumanAccess($newAccess)));
} else
	echo "Unauthorized";
?>