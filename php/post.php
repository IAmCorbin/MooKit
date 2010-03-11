<?php
require_once '../php/includes.php';

$inputFilter = new Filters;

//Filter User Input
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
	
	if($_SESSION['DB']->query("UPDATE `posts` SET title='".$titleEscaped."', text='".$textEscaped."' WHERE `ID`=1;")) {
		$json = json_encode(array('status'=>'OK','titlePost'=>$_POST['title'],'titleFilter'=>$filteredInput['title'],'titleLawed'=>$title,'titleEscaped'=>$titleEscaped,'textPost'=>$_POST['text'],'textLawed'=>$text,'textEscaped'=>$textEscaped));
		echo $json;
	} else {
		echo json_encode(array('status'=>'ERROR_QUERY'));
	}
	
}

?>