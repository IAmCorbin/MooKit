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
  
  <!--  
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. Look for COPYING. If not, see <http://www.gnu.org/licenses/>.
	-->
  
  */
//set default request if one is not sent
if(!isset($_GET['request']))
	$_GET['request'] = 'MAIN';
	
//Create new application -- this will handle the request and return allowed files
$Demo = new MooKit($_GET['request']);

//HANDLE REQUESTS
switch($_GET['request']) {
	case 'MAIN':
		//Initialize Application
		$Demo->INIT();
		
		//set title
		$Demo->main->title = "Demo MooKit Version 0.7 Application"; 	

		$Demo->main->userInfo = updateUserInfo();
		//SUB TEMPLATES
		
		//Menu
		$Demo->main->Menu = Menu::buildMain();
			
		//Login Form							
		$Demo->main->loginTpl = new Template('templates/loginForm.tpl.php');
		//Signup Form
		$Demo->main->signupTpl = new Template('templates/signupForm.tpl.php');

		//Content Area
		$contentTpl = new Template('templates/content.tpl.php');
		$DB = new DatabaseConnection;
		
		//Show Posts
		$contentTpl->postTpl = new Template('templates/post.tpl.php');
		//display most recent post
		$post = $DB->get_rows("SELECT `title`,`html` FROM `posts` ORDER BY `createTime` DESC LIMIT 3;");
		$contentTpl->postTpl->posts = $post;
		
		$Demo->main->contentTpl = $contentTpl;

		//OUTPUT
		$Demo->RUN();
		break;
	case 'blank/page':
		break;
	default:
		//If Page is not found, build an error page
		$Demo->INIT();
		$Demo->main->title = "Demo MooKit Application - 404 Error";
		$Demo->main->contentTpl = new Template('templates/404.tpl.php');
		$Demo->RUN(TRUE,FALSE); //Run Application - enable styles, disable JavaScripts
		break;
}
?>