<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	//make sure these keys exist and are not blank
	if(!array_keys_exist(array("name","href"),$_POST,FALSE,TRUE)) {
		echo json_encode(array('status'=>'E_MISSING_DATA'));
		return;
	}
	//if required keys passed, set any other missing to empty strings
	array_keys_exist(array("name","href","desc","weight","ajaxLink","menuLink","access_level"), $_POST, TRUE);
	//create the link object
	$link = new Link($_POST['name'],$_POST['href'],$_POST['desc'],$_POST['weight'],$_POST['ajaxLink'],$_POST['menuLink'],$_POST['access_level']);
	//attempt to add it to the database
	$link->update($_POST['link_id']);
	//return results
	echo json_encode(array('status'=>$link->status,'name'=>$link->name,'href'=>$link->href,'desc'=>$link->desc,'weight'=>$link->weight,'ajaxLink'=>$link->ajaxLink,'menuLink'=>$link->menuLink,'access_level'=>$link->access_level));
} else
	echo "Unauthorized";
?>