<?
/**
  * Administrator - Get Links from Database
  * @package MooKit
  */
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	//grab all sublinks by default
	if(!isset($_POST['notSubs'])) 
		$notSubs = false;
	else
		$notSubs = true;
	switch($_POST['rType']) {
		case 'json':
			echo adminGetLinks("json",$_POST['name'],false,$notSubs);
			break;
		case 'rows':
			echo json_encode(array('status'=>'1','html'=>adminGetLinks("rows",$_POST['name'],false,$notSubs)));
			break;
	}
	
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>