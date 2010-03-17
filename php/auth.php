<?
require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php';

function getAuthContent() {
	
	$contentTpl = new Template('../templates/content.tpl.php');
	
	//Security Check
	$security = new Security;
	if(!$security->check()) {
		$contentTpl->userInfo = null;
		$contentTpl->cssTpl = null;
		$contentTpl->postTpl = null;
	} else { //Authorized
		$DB = new DatabaseConnection;
		//User Info Table
		$contentTpl->userInfo = $DB->query("SELECT * FROM `users` WHERE `alias`='".$_SESSION['user']."' LIMIT 1;","mysql");
		//userCSS
		$contentTpl->cssTpl = new Template('../templates/userCSS.tpl.php');
		//Post
		$contentTpl->postTpl = new Template('../templates/postEdit.tpl.php');
			$post = $DB->query("SELECT * FROM `posts` WHERE `user_id`=1;","object");
			$contentTpl->postTpl->postTitle = $post[0]->title;
			$contentTpl->postTpl->postText = $post[0]->html;
	}
	return $contentTpl;
}
?>