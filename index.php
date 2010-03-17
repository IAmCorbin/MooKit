<?php
require_once 'php/includes.php';

//create a new database connection
$DB = new DatabaseConnection;

//grab main template
$main = new Template('../templates/main.tpl.php',false,true);
$main->title = "MooKit Version 1"; 	//set title

//set JavaScripts
$scripts = array();
$scripts[] =  '<script type="text/javascript" src="js/debug.js"></script>'; //debug area
if($_SESSION['auth'] === 1) {
	$scripts[] =  '<script type="text/javascript" src="js/mainAuth.js"></script>'; //post
	$scripts[] =  '<script type="text/javascript" src="js/post.js"></script>'; //post
	$scripts[] =  '<script type="text/javascript" src="js/userCSS.js"></script>'; //post
} else {
	$scripts[] = '<script type="text/javascript" src="js/login.js"></script>'; //login form
	$scripts[] = '<script type="text/javascript" src="js/signup.js"></script>'; //signup form
}
$main->scripts = $scripts;

//set Styles
$styles = array();
$styles[] = '<link rel="stylesheet" type="text/css" href="style/nav.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="style/content.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="style/debug.css.php" />';
if($_SESSION['auth'] === 1) {
	$styles[] = '<link rel="stylesheet" type="text/css" href="style/post.css.php" />';
} else {	
	$styles[] = '<link rel="stylesheet" type="text/css" href="style/loginForm.css.php" />';
	$styles[] = '<link rel="stylesheet" type="text/css" href="style/signupForm.css.php" />';
}
$main->styles = $styles;

//grab sub templates
$main->navTpl = new Template('../templates/nav.tpl.php');
	//links
	$main->navTpl->links = array(array('href'=>'http://www.iamcorbin.net',
							'name'=>'IAmCorbin.net',
							'sublinks'=>array(array('href'=>'http://www.iamcorbin.net/?intro=1',
										    'name'=>'Skip Intro'),
										    array('href'=>'http://www.iamcorbin.net/desert',
											    'name'=>'The Desert'))),
						array('href'=>'http://www.metaldisco.org',
							'name'=>'MetalDisco.org',
							'sublinks'=>null));

//Login Form							
$main->loginTpl = new Template('../templates/loginForm.tpl.php'); 		/*add login form */		
//Signup Form
$main->signupTpl = new Template('../templates/signupForm.tpl.php');	/* add signup form */	

//Content Area
$main->contentTpl = getAuthContent();
$main->contentTpl->userIP = $_SESSION['ip'];
	
//debug
$main->debugTpl = new Template('../templates/debug.tpl.php'); 		/* add debug area */		

//OUTPUT
echo $main;


?>