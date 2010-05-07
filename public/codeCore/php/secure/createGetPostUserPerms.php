<?
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	echo Post::getUserPerms($_POST['post_id'], $_POST['rType']);
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>