<?
/**
  * Administrator - Delete a Link
  * @package MooKit
  */
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo Link::delete($_POST['link_id']);
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>