<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	$inputFilter = new Filters;

	$name = $inputFilter->text($_POST['name']);
	$href = $inputFilter->text($_POST['href']);
	$desc = $inputFilter->text($_POST['desc'],false,true); //allow blank field
	$weight = $inputFilter->number($_POST['weight']);
	if(!isset($_POST['ajaxLink'])) $ajaxLink = '0'; else $ajaxLink = $_POST['ajaxLink'];
	if(!isset($_POST['menuLink'])) $menuLink = '0'; else $menuLink = $_POST['menuLink'];
	if($_POST['desc'] == '') $_POST['desc'] = NULL;

	if($inputFilter->ERRORS()) {
		echo json_encode(array('status'=>"E_FILTER"));
		return;
	}

	$link = new Link($name,$href,$desc,$ajaxLink,NULL);
	$link->insert($menuLink,$weight,$_POST['access_level']);
	echo json_encode(array('status'=>$link->status,'name'=>$name,'href'=>$href,'desc'=>$desc,'weight'=>$weight,'ajaxLink'=>$ajaxLink,'menuLink'=>$menuLink,'access_level'=>$access_level));
} else
	echo "Unauthorized";
?>