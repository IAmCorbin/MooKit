<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	$alias = $_POST['alias'];
	$access_level = $_POST['access_level'];
	
	//filter to prevent against SQL injection
	$inputFilter = new Filters;
	$alias = $inputFilter->text($alias,true);
	
	//set new access level if below ADMIN ACCESS
	if($access_level & ACCESS_ADMIN) {
		//already an admin, can't reaise access any higher
		$status = 0;
	} else {
		if($access_level & ACCESS_CREATE) {
			//if user was a Creator, set to Admin
			$newAccess = $access_level | ACCESS_ADMIN;
			
		} else {
			if($access_level & ACCESS_BASIC) {
				//if user was Basic, set to Creator
				$newAccess = $access_level | ACCESS_CREATE;
			} else {
				if($access_level == ACCESS_NONE)
					//if user was unauthorized, set to BASIC ACCESS
					$newAccess = $access_level | ACCESS_BASIC;
			}
		}
		//connect to Database
		$DB = new DatabaseConnection;
		//update user's access level
		$query = "UPDATE `users` SET `access_level`='$newAccess' WHERE `alias`='$alias' LIMIT 1;";
		$status = $DB->delete($query);
	}
	echo json_encode(array('status'=>$status,'access'=>$newAccess,'title'=>getHumanAccess($newAccess)));
} else
	echo "Unauthorized";
?>