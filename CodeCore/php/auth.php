<?
function getAuthContent($secure=TRUE) {
	require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php'; INIT($secure);
	
	$contentTpl = new Template('templates/content.tpl.php');
	
	$DB = new DatabaseConnection;
	
	//Security Check
	$security = new Security;
	if(!$security->check()) {
		
		//$contentTpl->userInfo = null;
		//$contentTpl->cssTpl = null;
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
		  $Menu->addSub('test1secure','CodeCore/php/test1.php','authAjaxLink');
		  $Menu->addSub('test2secure','CodeCore/php/test2.php','authAjaxLink');
		  $Menu->addSub('test3secure','CodeCore/php/test3.php','authAjaxLink');
		$contentTpl->Menu = $Menu;
		
		//~ $contentTpl->navTpl->links = array(	array('href'=>'',
										//~ 'name'=>'testing',
										//~ 'ajax'=>'authAjaxLink',
										//~ 'sublinks'=>array( array(	'href'=>'',
															//~ 'name'=>'sublink test',
															//~ 'ajax'=>'authAjaxLink'))),
									//~ array('href'=>'',
										//~ 'name'=>'Testing Secure Ajax Links',
										//~ 'ajax'=>'authAjaxLink',
										//~ 'sublinks'=>array(	array('href'=>'CodeCore/php/test1.php',
															//~ 'ajax'=>'authAjaxLink',
															//~ 'name'=>'test1secure'),
														//~ array('href'=>'CodeCore/php/test2.php',
															//~ 'ajax'=>'authAjaxLink',
															//~ 'name'=>'Test2secure'),
														//~ array('href'=>'CodeCore/php/test3.php',
															//~ 'ajax'=>'authAjaxLink',
															//~ 'name'=>'Test3secure'))));
		//User Info Table
		$userInfo = $DB->query("SELECT * FROM `users` WHERE `alias`='".$_SESSION['user']."' LIMIT 1;","assoc");
		$contentTpl->userInfo = $userInfo[0];
		//userCSS
		$contentTpl->cssTpl = new Template('templates/userCSS.tpl.php');
		//Post
		$contentTpl->postEditTpl = new Template('templates/postEdit.tpl.php');
			$post = $DB->query("SELECT `post_id`,`title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 1;","object");
			$contentTpl->postEditTpl->postID = $post[0]->post_id;
			$contentTpl->postEditTpl->postTitle = $post[0]->title;
			$contentTpl->postEditTpl->postText = $post[0]->html;
	}
	return $contentTpl;
}
?>