<?
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
	echo json_encode(array('status'=>'1','html'=>createGetPosts("rows", $_POST['title'])));
} else
	echo json_encode(array('status'=>"E_NOAUTH"));
?>