<?
/**
  * Administrator - Delete a Sublink
  * @package MooKit
  */
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo Link::deleteSub($_POST['link_id'],$_POST['sublink_id']);
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>