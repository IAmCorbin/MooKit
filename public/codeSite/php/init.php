<?php
//*************
//Site Settings
//*************

//set the default timezone
date_default_timezone_set('America/Detroit');
//GLOBAL VARIABLES
define('DEBUG', true); //DEBUG FLAG
//This is the name of your main folder
define('NAMESPACE','MooKit');
//define root directory
define('ROOT_DIR','/home/corbin/skillWebDevelopment/LAMP_devSite/MooKit/public/');
//define the error log directory
//    make sure you have also created the files according to LOG_INFO in that folder
define('ERROR_LOG_DIR','/home/corbin/skillWebDevelopment/LAMP_devSite/MooKit/logs/');
//set the expiration time for php errors - WARNING - this can cause site to run slow if lots of errors are present
//    this should be a negative number to indicate a time prior to now
//    any errors older than this time will be removed
//    examples: '-7 days' | '-1 month' | '- 2 days 60 minutes'
//define('PHP_ERROR_EXPIRE','-7 days');


?>