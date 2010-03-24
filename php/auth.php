<?
function getAuthContent($secure=TRUE) {
	require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php'; INIT($secure);
	
	$contentTpl = new Template('../templates/content.tpl.php');
	
	$DB = new DatabaseConnection;
	
	//Security Check
	$security = new Security;
	if(!$security->check()) {
		$contentTpl->userInfo = null;
		$contentTpl->cssTpl = null;
		$contentTpl->postTpl = null;
		$contentTpl->postTpl = new Template('../templates/post.tpl.php');
		//display most recent post
		$post = $DB->query("SELECT `title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 3;","object");
		$contentTpl->postTpl->posts = $post;
		
		
	} else { //Authorized
		
		//User Info Table
		$contentTpl->userInfo = $DB->query("SELECT * FROM `users` WHERE `alias`='".$_SESSION['user']."' LIMIT 1;","mysql");
		//userCSS
		$contentTpl->cssTpl = new Template('../templates/userCSS.tpl.php');
		//Post
		$contentTpl->postEditTpl = new Template('../templates/postEdit.tpl.php');
			$post = $DB->query("SELECT `post_id`,`title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 1;","object");
			$contentTpl->postEditTpl->postID = $post[0]->post_id;
			$contentTpl->postEditTpl->postTitle = $post[0]->title;
			$contentTpl->postEditTpl->postText = $post[0]->html;
	}
	return $contentTpl;
}
?>