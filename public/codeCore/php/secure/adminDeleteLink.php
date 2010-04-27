<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo Link::delete($_POST['link_id']);
} else
	echo "Unauthorized";
?>