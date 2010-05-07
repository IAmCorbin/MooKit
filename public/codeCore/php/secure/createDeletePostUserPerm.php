<?php
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	echo Post::deleteUserPerm($_POST['user_id'],$_POST['post_id'],$_POST['rType']);
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>