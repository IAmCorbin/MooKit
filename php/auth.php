<?
function getAuthContent($secure=TRUE) {
	require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php'; INIT($secure);
	
	$contentTpl = new Template('../templates/content.tpl.php');
	
	$DB = new DatabaseConnection;
	
	//Security Check
	$security = new Security;
	if(!$security->check()) {
		
		//$contentTpl->userInfo = null;
		//$contentTpl->cssTpl = null;
		$contentTpl->postTpl = new Template('../templates/post.tpl.php');
		//display most recent post
		$post = $DB->query("SELECT `title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 3;","object");
		$contentTpl->postTpl->posts = $post;
		
		
	} else { //Authorized
		//Navigation
		$contentTpl->navTpl = new Template('../templates/nav.tpl.php');
		$contentTpl->navTpl->links = array(	array('href'=>'',
										'name'=>'testing',
										'ajax'=>'authAjaxLink',
										'sublinks'=>array( array(	'href'=>'',
															'name'=>'sublink test',
															'ajax'=>'authAjaxLink'))),
									array('href'=>'',
										'name'=>'Testing Secure Ajax Links',
										'ajax'=>'authAjaxLink',
										'sublinks'=>array(	array('href'=>'php/test1.php',
															'ajax'=>'authAjaxLink',
															'name'=>'test1secure'),
														array('href'=>'php/test2.php',
															'ajax'=>'authAjaxLink',
															'name'=>'Test2secure'),
														array('href'=>'php/test3.php',
															'ajax'=>'authAjaxLink',
															'name'=>'Test3secure'))));
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