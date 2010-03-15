<?php
require_once '../php/includes.php';

//Security Check
$security = new Security;
if(!$security->check()) 
	echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>';
else { //Authorized

	//Filter User Input
	$inputFilter = new Filters;
	$filteredInput['title'] = $inputFilter->text($_POST['title']);
	$title = $inputFilter->htmLawed($filteredInput['title']);
	$text = $inputFilter->htmLawed($_POST['text']);

	//Check for Errors
	if($errors = $inputFilter->ERRORS()) {
		//handle filter errors
		echo json_encode(array('status'=>'ERROR_FILTER'));
	} else {
		//NO ERRORS
		$titleEscaped = mysql_real_escape_string($title);
		$textEscaped = mysql_real_escape_string($text);
		
		//UPDATE POST IN Database
		$DB = new DatabaseConnection;
		if($DB->query("UPDATE `posts` SET title='".$titleEscaped."', text='".$textEscaped."' WHERE `ID`=1;",null)) {
			//SUCCESS
			$json = json_encode(array('status'=>'OK','titlePost'=>$_POST['title'],'titleFilter'=>$filteredInput['title'],'titleLawed'=>$title,'titleEscaped'=>$titleEscaped,'textPost'=>$_POST['text'],'textLawed'=>$text,'textEscaped'=>$textEscaped));
			echo $json;
		} else {
			//FAILURE
			echo json_encode(array('status'=>'ERROR_QUERY'));
		}
	}
	
}
?>