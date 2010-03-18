<?php
require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php'; INIT();
	//Create Post Object
	$post = new Post($_POST['title'],$_POST['text'],null,date('Y-m-d H:i:s'));
	//if no errors
	if($post->errorStatus()) //FILTER FAILTURE
		echo json_encode(array('status'=>'ERROR_FILTER'));
	else { //NO ERRORS
		//prepare vars for query
		$titleMysql = magicMySQL($post->title);
		$htmlMysql = magicMySQL($post->html);
		//UPDATE POST IN Database
		$DB = new DatabaseConnection;
		if($_POST['post_id']) { //do not update if not a valid post number;
			if($DB->query("UPDATE `posts` SET title='".$titleMysql."', html='".$htmlMysql."', modTime=NOW() WHERE `post_id`='".$_POST['post_id']."';",null)) {
				//SUCCESS
				echo json_encode(array('status'=>'OK','title'=>stripslashes($post->title),'html'=>stripslashes($post->html)));
			} else {
				//QUERY FAILURE
				echo json_encode(array('status'=>'ERROR_QUERY'));
			}
		} else
			echo json_encode(array('status'=>'ERROR_ID'));
	}
?>