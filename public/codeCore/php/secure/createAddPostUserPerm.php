<?php
/**
  * Creator - Add a New Post User Permission
  * @package MooKit
  */
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	echo Post::addUserPerm($_POST['user_id'],$_POST['post_id'],$_POST['access_level'],$_POST['rType']);
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>