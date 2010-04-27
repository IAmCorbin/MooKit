<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	//grab all sublinks by default
	if(!isset($_POST['notSubs'])) 
		$notSubs = false;
	else
		$notSubs = true;
	echo adminGetLinks($_POST['name'],false,$_POST['rType'],$notSubs);
} else
	echo "Unauthorized";
?>