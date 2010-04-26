<?
$inputFilter = new Filters;

$name = $inputFilter->text($_POST['name']);
$href = $inputFilter->text($_POST['href']);
$desc = $inputFilter->text($_POST['desc'],false,false);
$weight = $inputFilter->number($_POST['weight']);
if(!isset($_POST['ajax'])) $ajax = '0';
if($_POST['desc'] == '') $_POST['desc'] = NULL;

if($inputFilter->ERRORS()) {
	echo json_encode(array('status'=>"E_FILTER"));
	return;
}

$link = new Link($name,$href,$desc,$ajax,NULL,TRUE,TRUE,$weight,$_POST['access_level']);
echo json_encode(array('status'=>$link->status));
?>