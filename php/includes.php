<?php
chdir($_SERVER['DOCUMENT_ROOT'].'/MooKit/phpClasses');

define('DEBUG', false); //DEBUG FLAG
define('INSITE',true); //EXTRA SCRIPT SECURITY, disallow direct script access

//Classes
require_once 'htmLawed1.1.9.1.php';
require_once 'Security.php';
require_once  'Filters.php';
require_once  'Database.php';
require_once  'Template.php';
require_once  'User.php';
require_once 'Post.php';
//Functions
require_once '../php/auth.php';
require_once '../php/functions.php';
session_start();
session_regenerate_id();	
?>