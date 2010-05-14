<?
/**
  * Creator - Add a New Post
  * @package MooKit
  */
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	$post = new Post(array('title'=>$_POST['title'],
					     'html'=>$_POST['html']));
	echo $post->json_status;
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>