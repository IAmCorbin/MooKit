<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	$alias = $_POST['alias'];
	$access_level = $_POST['access_level'];
	
	//filter to prevent against SQL injection
	$inputFilter = new Filters;
	$alias = $inputFilter->text($alias,true);
	
	//set new access level if current is above 0
	if($access_level > 0) {
		$newAccess = $access_level/2;
		//if level is 1, set to unauthorized (0)
		if($access_level == 1)
			$newAccess = 0;
		
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