<?php
//chdir('/home/corbin/skillWebDevelopment/LAMP_devSite/MooKit/phpClasses');
chdir($_SERVER['DOCUMENT_ROOT'].'/MooKit/phpClasses');
require_once  'Filters.php';
require_once  'Database.php';
require_once  'User.php';
require_once  'Template.php';
require_once 'htmLawed1.1.9.1.php';
session_start();
session_regenerate_id();	
?>