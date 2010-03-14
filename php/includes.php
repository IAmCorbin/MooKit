<?php
chdir($_SERVER['DOCUMENT_ROOT'].'/MooKit/phpClasses');

define('DEBUG', false); //DEBUG FLAG

require_once 'htmLawed1.1.9.1.php';
require_once  'Filters.php';
require_once  'Database.php';
require_once  'User.php';
require_once  'Template.php';
session_start();
session_regenerate_id();	
?>