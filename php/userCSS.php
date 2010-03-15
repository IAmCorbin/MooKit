<?php
require_once '../php/includes.php';

//Security Check
$security = new Security;
if(!$security->check()) 
	echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>';
else { //Authorized

	$inputFilter = new Filters;

	$userCSS = $inputFilter->htmLawed($_POST['css']);

	//send filtered css back to javascript
	echo json_encode(array('css'=>$userCSS));
	
	
}
?>