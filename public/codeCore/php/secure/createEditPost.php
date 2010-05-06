<?php
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	$post = new Post(array('post_id'=>$_POST['post_id'],
					     'title'=>$_POST['title'],
					     'html'=>$_POST['html']), FALSE); //update existing post
	echo $post->json_status;
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>