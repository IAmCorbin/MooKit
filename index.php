<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php'; INIT(false);

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


?>