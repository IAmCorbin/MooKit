<?
require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php'; INIT();

$styles = array();
foreach(new DirectoryIterator('style/secure') as $style)
	//make sure file is .css or .css.php
		if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style))
			array_push($styles,'style/secure/'.$style);
$scripts = array();
foreach(new DirectoryIterator('CodeCore/js/secure') as $script)
	//make sure file is .js
		if( preg_match("/\.js$/",$script))
			array_push($scripts,'CodeCore/js/secure/'.$script);
//send authContent template and stylesheets to JavaScript
echo json_encode(array('html'=>getAuthContent()->run(),'styles'=>$styles,'scripts'=>$scripts));
?>