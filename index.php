<?php
require_once 'php/includes.php';

//create a new database connection
new DatabaseConnection('localhost','test','test','test');
//if session auth is not set, set it to 0
isset($_SESSION['auth'])? 0: $_SESSION['auth'] = 0;

//grab main template
$mainTpl = new Template('../templates/main.tpl.php',false,true);	
$mainTpl->title = "MooKit Version 1"; 	//set title

//grab sub templates
$mainTpl->navTpl = new Template('../templates/nav.tpl.php'); 	
//set links
$mainTpl->navTpl->links = array(array('href'=>'http://www.iamcorbin.net',
							'name'=>'IAmCorbin.net'),
						array('href'=>'http://www.metaldisco.org',
							'name'=>'MetalDisco.org'));

																				$scripts = array(); //initialize scripts array
$mainTpl->loginTpl = new Template('../templates/loginForm.tpl.php'); 		/*add login form */		$scripts[] = '<script type="text/javascript" src="js/login.js"></script>'; //login form JavaScript

$mainTpl->signupTpl = new Template('../templates/signupForm.tpl.php');	/* add signup form */	$scripts[] = '<script type="text/javascript" src="js/signup.js"></script>'; //signup form JavaScript

$mainTpl->contentTpl = new Template('../templates/content.tpl.php');  		/* add content area */

$mainTpl->debugTpl = new Template('../templates/debug.tpl.php'); 		/* add debug area */		$scripts[] =  '<script type="text/javascript" src="js/debug.js"></script>'; //debug area JavaScript

//set scripts
$mainTpl->scripts = $scripts;
//OUTPUT
echo $mainTpl;


?>