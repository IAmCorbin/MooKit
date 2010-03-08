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
$scripts[] = '<script type="text/javascript" src="js/login.js"></script>'; //login form JavaScript
$scripts[] = '<script type="text/javascript" src="js/signup.js"></script>'; //signup form JavaScript
$scripts[] =  '<script type="text/javascript" src="js/debug.js"></script>'; //debug area JavaScript
$mainTpl->scripts = $scripts;

//set Styles
$styles = array();
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/nav.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/loginForm.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/signupForm.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/content.css.php" />';
$styles[] = '<link rel="stylesheet" type="text/css" href="templates/debug.css.php" />';
$mainTpl->styles = $styles;

//grab sub templates
$mainTpl->navTpl = new Template('../templates/nav.tpl.php'); 	
//set links
$mainTpl->navTpl->links = array(array('href'=>'http://www.iamcorbin.net',
							'name'=>'IAmCorbin.net'),
						array('href'=>'http://www.metaldisco.org',
							'name'=>'MetalDisco.org'));

																				
$mainTpl->loginTpl = new Template('../templates/loginForm.tpl.php'); 		/*add login form */		
$mainTpl->signupTpl = new Template('../templates/signupForm.tpl.php');	/* add signup form */	
$mainTpl->contentTpl = new Template('../templates/content.tpl.php');  		/* add content area */
$mainTpl->debugTpl = new Template('../templates/debug.tpl.php'); 		/* add debug area */		

//OUTPUT
echo $mainTpl;


?>