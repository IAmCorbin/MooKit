<?
/**
  * Creator - Get Posts
  * @package MooKit
  */
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	if(!isset($_POST['title'])) $_POST['title'] = NULL;
	if(!isset($_POST['post_id'])) $_POST['post_id'] = NULL;
	if($_POST['rType'] === "json")
		echo createGetPosts($_POST['rType'],$_POST['title'],$_POST['post_id']);
	else
		echo json_encode(array('status'=>'1','html'=>createGetPosts($_POST['rType'],$_POST['title'],$_POST['post_id'])));
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>