<?php
	//Create Post Object
	$post = new Post($_POST['title'],$_POST['text'],date('Y-m-d H:i:s'));
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
			if($DB->insert("INSERT INTO `posts` SET `user_id`='".$_SESSION['user_id']."', `title`='".$titleMysql."', `html`='".$htmlMysql."',  createTime`='".$this->createTime."', `modTime`='".$this->createTime."';") {
				//SUCCESS
				echo json_encode(array('status'=>'OK','title'=>stripslashes($post->title),'html'=>stripslashes($post->html));
			} else {
				//QUERY FAILURE
				echo json_encode(array('status'=>'ERROR_QUERY'));
			}
		} else
			echo json_encode(array('status'=>'ERROR_ID'));
	}
?>