<?
//decode sent json
$json = json_decode($_POST['json']);

if($json->secure == '1') {
	//set secure styles
	$styles = array();
	foreach(new DirectoryIterator('style/secure') as $style)
		//make sure file is .css or .css.php
			if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style))
				array_push($styles,'style/secure/'.$style);
	//set secure scripts
	$scripts = array();
	//codeCore
	foreach(new DirectoryIterator('codeCore/js/secure') as $script)
		//make sure file is .js
			if( preg_match("/\.js$/",$script))
				array_push($scripts,'codeCore/js/secure/'.$script);
	//codeSite
	foreach(new DirectoryIterator('codeSite/js/secure') as $script)
		//make sure file is .js
			if( preg_match("/\.js$/",$script))
				array_push($scripts,'codeSite/js/secure/'.$script);
} else {
	$styles = array();
	$scripts = array();
}
//send authContent template and stylesheets to JavaScript
echo json_encode(array("html"=>updateContent()->run(),"styles"=>$styles,"scripts"=>$scripts));
?>