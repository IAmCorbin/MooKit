<?
/**
  * Administrator - Add a new Link
  * @package MooKit
  */
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	$link = new Link(array('name'=>$_POST['name'],
					   'href'=>$_POST['href'],
					   'desc'=>$_POST['desc'],
					   'weight'=>$_POST['weight'],
					   'ajaxLink'=>$_POST['ajaxLink'],
					   'menuLink'=>$_POST['menuLink'],
					   'access_level'=>$_POST['access_level']));
	//attempt to add it to the database
	$link->insert();
	//return results
	echo $link->json_status;
} else
	echo "Unauthorized";
?>