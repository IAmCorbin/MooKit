<?php
require_once '../php/includes.php';

$inputFilter = new Filters;

//Validate User Input
$filteredInput['title'] = $inputFilter->text($_POST['title']);
//Check for Errors
if($errors = $inputFilter->ERRORS()) {
	//handle filter errors
	//echo "SIZE OF \$ERRORS -> ".sizeof($errors).'<br />';
	//foreach($errors as $error)
	//	echo $error."<br />";
	return false;
} else {
	//NO ERRORS
	$config = array('safe'=>1,
				'tidy'=>1,
				'deny_attribute'=>'* -href -target -style',
				'schemes'=>'style: *; href: *; target: *');
	$title = $filteredInput['title'];
	$text = $_POST['text'];
	if(get_magic_quotes_gpc()) {
		$title = stripslashes($title);
		$text = stripslashes($text);
	}
	$title = htmLawed($title,$config);
	$text = htmLawed($text,$config);
	
	
	if($_SESSION['DB']->query("UPDATE `posts` SET title='".$title."', text='".$text."' WHERE `ID`=1;")) {
		$json = json_encode(array('title'=>$title,'text'=>$text));
		echo $json;
	} else
		return false; //error with query
	
}

?>