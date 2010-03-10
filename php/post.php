<?php
require_once '../php/includes.php';

$inputFilter = new Filters;

//Validate User Input
$filteredInput['title'] = $inputFilter->text($_POST['title']);
$filteredInput['text'] = $inputFilter->text($_POST['text']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filter errors
	//echo "SIZE OF \$ERRORS -> ".sizeof($errors).'<br />';
	//foreach($errors as $error)
	//	echo $error."<br />";
	return false;
} else {
	//NO ERRORS
	$config = array('safe'=>1,'tidy'=>1,'deny_attribute'=>'* -style');
	$spec = '-*;';
	$title = htmLawed($_POST['title'],$config,$spec);
	$text = htmLawed($_POST['text'],$config,$spec);
	if($_SESSION['DB']->query("UPDATE `posts` SET title='".$title."', text='".$text."' WHERE `ID`=1;")) {
		$json = json_encode(array('title'=>$title,'text'=>$text));
		echo $json;
	} else
		return false; //error with query
	
}

?>