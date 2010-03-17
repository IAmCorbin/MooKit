<?php
require_once '../php/includes.php';

//Security Check
$security = new Security;
if(!$security->check()) 
	echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>';
else { //Authorized

	//Create Post Object
	$post = new Post($_POST['title'],$_POST['text'],null,date('Y-m-d H:i:s'));
	//if no errors
	if($post->errorStatus()) //FILTER FAILTURE
		echo json_encode(array('status'=>'ERROR_FILTER'));
	else { //NO ERRORS
		//prepare vars for query
		$post->titleMysql = magicStripper($post->title);
		$post->htmlMysql = magicStripper($post->html);
		//UPDATE POST IN Database
		$DB = new DatabaseConnection;
		if($DB->query("UPDATE `posts` SET title='".$post->titleMysql."', html='".$post->htmlMysql."' WHERE `user_id`=1;",null)) {
			//SUCCESS
			echo json_encode(array('status'=>'OK','title'=>stripslashes($post->title),'html'=>stripslashes($post->html),'titleMysql'=>$post->titleMysql,'htmlMysql'=>$post->htmlMysql));//,'titlePost'=>$_POST['title'],'titleFilter'=>$filteredInput['title'],'titleLawed'=>$title,'titleEscaped'=>$titleEscaped,'textPost'=>$_POST['text'],'textLawed'=>$text,'textEscaped'=>$textEscaped));
		} else {
			//QUERY FAILURE
			echo json_encode(array('status'=>'ERROR_QUERY'));
		}
	}

}
?>