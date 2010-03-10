<?php
require_once 'php/includes.php';

//create a new database connection
new DatabaseConnection;
//if session auth is not set, set it to 0
isset($_SESSION['auth'])? 0: $_SESSION['auth'] = 0;

//grab main template
$mainTpl = new Template('../templates/main.tpl.php',false,true);	
$mainTpl->title = "MooKit Version 1"; 	//set title

//set JavaScripts
$scripts = array();
$scripts[] = '<script type="text/javascript" src="js/login.js"></script>'; //login form
$scripts[] = '<script type="text/javascript" src="js/signup.js"></script>'; //signup form
$scripts[] =  '<script type="text/javascript" src="js/debug.js"></script>'; //debug area
if($_SESSION['auth'] === 1)
	$scripts[] =  '<script type="text/javascript" src="js/post.js"></script>'; //post
$mainTpl->scripts = $scripts;

//set Styles
$styles = array();
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/nav.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/loginForm.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/signupForm.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/content.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/debug.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/post.css.php" />';
$mainTpl->styles = $styles;

//grab sub templates
$mainTpl->navTpl = new Template('../templates/nav.tpl.php'); 	
	//links
	$mainTpl->navTpl->links = array(array('href'=>'http://www.iamcorbin.net',
							'name'=>'IAmCorbin.net'),
						array('href'=>'http://www.metaldisco.org',
							'name'=>'MetalDisco.org'));

//Login Form																				
$mainTpl->loginTpl = new Template('../templates/loginForm.tpl.php'); 		/*add login form */		
//Signup Form
$mainTpl->signupTpl = new Template('../templates/signupForm.tpl.php');	/* add signup form */	
//Content Area
$mainTpl->contentTpl = new Template('../templates/content.tpl.php');  		/* add content area */
	//User Info Table
	$mainTpl->contentTpl->userInfo = $_SESSION['DB']->query("SELECT * FROM `users` WHERE `alias`='".$_SESSION['user']."' LIMIT 1;","mysql");
		//Post
		$mainTpl->contentTpl->postTpl = new Template('../templates/post.tpl.php');
		$post = $_SESSION['DB']->query("SELECT * FROM `posts`","object");
		$mainTpl->contentTpl->postTpl->postTitle = $post[0]->title;
		$mainTpl->contentTpl->postTpl->postText = $post[0]->text;

$mainTpl->debugTpl = new Template('../templates/debug.tpl.php'); 		/* add debug area */		

//OUTPUT
echo $mainTpl;


?>