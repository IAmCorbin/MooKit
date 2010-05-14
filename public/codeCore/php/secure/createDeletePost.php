<?
/**
  * Creator - Delete a Post
  * @package MooKit
  */
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	echo Post::delete($_POST['post_id']);
} else {
	echo json_encode(array('status'=>"E_NOAUTH"));
}
?>