<?
function updateContent($secure=TRUE) {

	$contentTpl = new Template('templates/content.tpl.php');
	
	$DB = new DatabaseConnection;
	
	//Security Check
	$security = new Security;
	if(!$security->check()) { //Unauthorized
		$contentTpl->postTpl = new Template('templates/post.tpl.php');
		//display most recent post
		$post = $DB->query("SELECT `title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 3;","object");
		$contentTpl->postTpl->posts = $post;
		
		
	} else { //Authorized
		//Navigation
		$Menu = new Menu;
		$Menu->add('testing','','authAjaxLink');
		  $Menu->addSub('sublink test','','authAjaxLink');
		$Menu->add('Testing Secure Ajax Links','','authAjaxLink');
		  $Menu->addSub('test1secure','codeSite/php/test1.php','authAjaxLink');
		  $Menu->addSub('test2secure','codeSite/php/test2.php','authAjaxLink');
		  $Menu->addSub('test3secure','codeSite/php/test3.php','authAjaxLink');
		$contentTpl->Menu = $Menu;

		//User Info Table
		$userInfo = $DB->query("SELECT * FROM `users` WHERE `alias`='".$_SESSION['user']."' LIMIT 1;","assoc");
		$contentTpl->userInfo = $userInfo[0];
		
		//Post Editing
		$contentTpl->postEditTpl = new Template('templates/postEdit.tpl.php');
			$post = $DB->query("SELECT `post_id`,`title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 1;","object");
			$contentTpl->postEditTpl->postID = $post[0]->post_id;
			$contentTpl->postEditTpl->postTitle = $post[0]->title;
			$contentTpl->postEditTpl->postText = $post[0]->html;
	}
	//return the finished template to JavaScript
	return $contentTpl;
}
?>