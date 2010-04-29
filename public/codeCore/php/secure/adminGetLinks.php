<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	//grab all sublinks by default
	if(!isset($_POST['notSubs'])) 
		$notSubs = false;
	else
		$notSubs = true;
	echo adminGetLinks($_POST['rType'],$_POST['name'],false,$notSubs);
} else
	echo "Unauthorized";
?>