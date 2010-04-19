<?
/**
  * Variable function used to display updated Menu on user status change ( logged in/out )
  * @returns the content template object
  */
function updateMenu() {

	$DB = new DatabaseConnection;
	
	if(Security::clearance()) {
		//Authorized
		$mainMenu = new Menu;
		$mainMenu->add('IAmCorbin.net','http://www.iamcorbin.net');
		  $mainMenu->addSub('Skip Intro','http://www.iamcorbin.net/?intro=1');
		  $mainMenu->addSub('The Desert','http://www.iamcorbin.net/desert');
		$mainMenu->add('MetalDisco.org','http://www.metaldisco.org');
		$mainMenu->add('Testing Ajax Links','');
		  $mainMenu->addSub('test1','test1','ajaxLink');
		  $mainMenu->addSub('test2','test2','ajaxLink');
		  $mainMenu->addSub('test3','test3','ajaxLink');
		$mainMenu->add('LogOut','logout','ajaxLink');
	} else{
		//Unauthorized
		$mainMenu = new Menu;
		$mainMenu->add('IAmCorbin.net','http://www.iamcorbin.net');
		  $mainMenu->addSub('Skip Intro','http://www.iamcorbin.net/?intro=1');
		  $mainMenu->addSub('The Desert','http://www.iamcorbin.net/desert');
		  $mainMenu->addSub('pssst','secret','ajaxLink');
	}
	return $mainMenu;
}


/**
  * Variable function used to display updated content on user status change ( logged in/out )
  * @returns the content template object
  */
function updateContent() {

	$contentTpl = new Template('templates/content.tpl.php');
	
	$DB = new DatabaseConnection;
	
	//Security Check
	if(Security::clearance()) { 
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
	} else { 
		//Unauthorized
		
		//Show Posts
		$contentTpl->postTpl = new Template('templates/post.tpl.php');
		//display most recent post
		$post = $DB->get_rows("SELECT `title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 3;");
		$contentTpl->postTpl->posts = $post;
	}
	//return the finished template to JavaScript
	return $contentTpl;
}
?>