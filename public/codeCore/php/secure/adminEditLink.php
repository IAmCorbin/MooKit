<?
$inputFilter = new Filters;

$link_id = $inputFilter->number($_POST['link_id']);
$name = $inputFilter->text($_POST['name']);
$href = $inputFilter->text($_POST['href']);
$desc = $inputFilter->text($_POST['desc'],false,true); //allow blank field
$weight = $inputFilter->number($_POST['weight']);
$access_level = $inputFilter->number($_POST['access_level']);
if($_POST['desc'] == '') $_POST['desc'] = NULL;
if(!isset($_POST['ajaxLink'])) $ajaxLink = '0'; else $ajaxLink = $_POST['ajaxLink'];
if(!isset($_POST['menuLink'])) $menuLink = '0'; else $menuLink = $_POST['menuLink'];
trigger_error($menuLink);

if($inputFilter->ERRORS()) {
	echo json_encode(array('status'=>"E_FILTER"));
	return;
}

$link = new Link($name,$href,$desc,$ajaxLink);
$link->update($link_id,$menuLink,$weight,$access_level);
//remove slashes from url
$href = stripslashes($href);
//return status and updated row information
echo json_encode(array('status'=>$link->status,'name'=>$name,'href'=>$href,'desc'=>$desc,'weight'=>$weight,'ajaxLink'=>$ajaxLink,'menuLink'=>$menuLink,'access_level'=>$access_level));
?>