<?
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	echo json_encode(array('status'=>'1','html'=>adminGetUsers("rows", $_POST['alias'])));
} else 
	echo json_encode(array('status'=>"E_NOAUTH"));
?>