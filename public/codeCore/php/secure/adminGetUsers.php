<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo adminGetUsers($_POST['alias'],"rows");
} else 
	echo "Unauthorized";
?>