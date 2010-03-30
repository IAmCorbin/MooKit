<?
require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php';
//decode sent json
if(get_magic_quotes_gpc()) $_POST['json'] = stripslashes($_POST['json']);
$json = json_decode($_POST['json']);

if($json->secure == '1') {
	INIT(true);
	//set secure styles
	$styles = array();
	foreach(new DirectoryIterator('style/secure') as $style)
		//make sure file is .css or .css.php
			if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style))
				array_push($styles,'style/secure/'.$style);
	//set secure scripts
	$scripts = array();
	foreach(new DirectoryIterator('CodeCore/js/secure') as $script)
		//make sure file is .js
			if( preg_match("/\.js$/",$script))
				array_push($scripts,'CodeCore/js/secure/'.$script);
} else {
	echo "HIT!";
	INIT(false,"WTF");
	echo "AFTER!";
}
//send authContent template and stylesheets to JavaScript
echo json_encode(array("html"=>getAuthContent()->run(),"styles"=>$styles,"scripts"=>$scripts));
?>