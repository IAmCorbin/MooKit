<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo Link::insertSub($_POST['link_id'],$_POST['sublink_id']);
} else
	echo "Unauthorized";
?>