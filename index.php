<?php
/**
  * Demo MooKit Application 
  *
  * This is a demo application that is being used to build the MooKit itself
  *
  * .htaccess is redirecting all application requests to /index.php?request="The/Request"
  *	This causes this index.php file to become the gatekeeper for the application, 
  *	it will serve files or build the application based on the request, you can setup whatever type of user requests you want to allow here
  *		ex: allow selection by date, yyyy/mm/dd, or the regEx -  ^[0-9]{4}/[0-1][0-9]/[0-3][0-9]$
  *
  * codeCore/php/init.php - Included via php's auto_prepend_file - set in .htaccess
  *
  */
//Create new application
$Demo = new MooKit($_GET['request']);

//HANDLE REQUESTS
switch($_GET['request']) {
	case '':
		//Initialize Application
		$Demo->INIT();
		
		//set title
		$Demo->main->title = "Demo MooKit Version 0.7 Application"; 	

		//SUB TEMPLATES
		
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
		$Demo->main->loginTpl = new Template('templates/loginForm.tpl.php');
		//Signup Form
		$Demo->main->signupTpl = new Template('templates/signupForm.tpl.php');

		//Content Area
		$Demo->main->contentTpl = updateContent(FALSE);
		$Demo->main->contentTpl->userIP = $_SESSION['ip'];

		//OUTPUT
		$Demo->RUN();
		break;
	case 'blank/page':
		break;
	default:
		//If Page is not found throw an error page
		$Demo->INIT();
		$Demo->main->title = "Demo MooKit Application - 404 Error";
		//$Demo->main->Menu = new Menu;
		$Demo->main->contentTpl = new Template('templates/404.tpl.php');//'<p>Invalid Page Requested, Please <a href="http://10.10.10.100/MooKit/">Visit the Main Site</a></p>';
		$Demo->RUN(TRUE,FALSE); //Run Application - disable JavaScripts
		break;
}
?>