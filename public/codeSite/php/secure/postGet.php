<?php
//open connection	
$DB = new DatabaseConnection;
//get post and echo the json return
if($post = $DB->get_row("SELECT `post_id`,`title`,`html` FROM `posts` WHERE `post_id`='".$_POST['post_id']."';","json"))
	echo $post;
else
	echo "Error Retrieving Desired Post...Sorry, please try again later or contact the administrator";
?>