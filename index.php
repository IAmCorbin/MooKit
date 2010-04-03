<?php
//INIT FUNCTION
require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php';
//Deny Direct Access to certain scripts
preg_match("/postUpdate|authUpdate\.php$/",$_GET['request'])? $s=true : $s = false;
INIT($s);

//If a valid file was requested return it and exit script  | list of blocked files in preg_match
if(is_readable($_GET['request']) && !preg_match("/\.s\.php$/", $_GET['request'])) {
	include $_GET['request'];
	exit();
}

switch($_GET['request']) {
	case 'blank/page':
		break;
	case '':
		//Create new application
		$Demo = new MooKit;

		//set title
		$Demo->main->title = "Demo MooKit Version 0.7 Application"; 	

		//grab sub templates
		//$Demo->main->navTpl = new Template('templates/nav.tpl.php');
			//create Main Menu
			$mainMenu = new Menu;
			$mainMenu->add('IAmCorbin.net','http://www.iamcorbin.net');
			  $mainMenu->addSub('Skip Intro','http://www.iamcorbin.net/?intro=1');
			  $mainMenu->addSub('The Desert','http://www.iamcorbin.net/desert');
			$mainMenu->add('MetalDisco.org','http://www.metaldisco.org');
			$mainMenu->add('Testing Ajax Links','');
			  $mainMenu->addSub('test1','test1','ajaxLink');
			  $mainMenu->addSub('test2','test2','ajaxLink');
			  $mainMenu->addSub('test3','test3','ajaxLink');
			//set mainmenu to template
			$Demo->main->Menu = $mainMenu;	
			
		//Login Form							
		$Demo->main->loginTpl = new Template('templates/loginForm.tpl.php'); 		/*add login form */		
		//Signup Form
		$Demo->main->signupTpl = new Template('templates/signupForm.tpl.php');	/* add signup form */	

		//Content Area
		$Demo->main->contentTpl = getAuthContent(FALSE);
		$Demo->main->contentTpl->userIP = $_SESSION['ip'];

		//OUTPUT
		$Demo->RUN();
		
		break;
	default:
		include '404.php';
		break;
}


?>