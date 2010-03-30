<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php'; INIT(false);

//Create new application
$Demo = new MooKit;

//set title
$Demo->main->title = "Demo MooKit Version 0.7 Application"; 	

//set public JavaScripts
$Demo->addScript('CodeCore/js/login.js');
$Demo->addScript('CodeCore/js/signup.js');
//set authenticated JavaScripts
	$Demo->addScript('CodeCore/js/auth.js','secure');
	$Demo->addScript('CodeCore/js/postEdit.js','secure');
	$Demo->addScript('CodeCore/js/userCSS.js','secure');

//grab sub templates
$Demo->main->navTpl = new Template('templates/nav.tpl.php');
	//links
	$Demo->main->navTpl->links = array( array('href'=>'http://www.iamcorbin.net',
								'name'=>'IAmCorbin.net',
								'sublinks'=>array(array('href'=>'http://www.iamcorbin.net/?intro=1',
										    'name'=>'Skip Intro'),
										    array('href'=>'http://www.iamcorbin.net/desert',
											    'name'=>'The Desert'))),
							array('href'=>'http://www.metaldisco.org',
								'name'=>'MetalDisco.org',
								'sublinks'=>null),
							array('href'=>'',
								'name'=>'Testing Ajax Links',
								'sublinks'=>array(	array('href'=>'test1',
													'ajax'=>'ajaxLink',
													'name'=>'test1'),
												array('href'=>'test2',
													'ajax'=>'ajaxLink',
													'name'=>'Test2'),
												array('href'=>'test3',
													'ajax'=>'ajaxLink',
													'name'=>'Test3'))),
								
							
							
					);

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