<?
function updateContent() {

	$contentTpl = new Template('templates/content.tpl.php');
	
	$DB = new DatabaseConnection;
	
	//Security Check
	$security = new Security;
	if(!$security->check()) { 
		//Unauthorized
		
		//Show Posts
		$contentTpl->postTpl = new Template('templates/post.tpl.php');
		//display most recent post
		$post = $DB->get_rows("SELECT `title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 3;");
		$contentTpl->postTpl->posts = $post;
	} else { 
		//Authorized
		
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
		$userInfo = $DB->get_row("SELECT * FROM `users` WHERE `alias`='".$_SESSION['alias']."' LIMIT 1;","assoc");
		$contentTpl->userInfo = $userInfo;
		
		//Post Editing
		$contentTpl->postEditTpl = new Template('templates/postEdit.tpl.php');
			$post = $DB->get_row("SELECT `post_id`,`title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 1;");
			$contentTpl->postEditTpl->postID = $post->post_id;
			$contentTpl->postEditTpl->postTitle = $post->title;
			$contentTpl->postEditTpl->postText = $post->html;
	}
	//return the finished template to JavaScript
	return $contentTpl;
}
?>