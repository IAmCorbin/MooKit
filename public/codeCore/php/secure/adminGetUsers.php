<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo adminGetUsers("rows", $_POST['alias']);
} else 
	echo "Unauthorized";
?>