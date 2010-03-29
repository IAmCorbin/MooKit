<?php
require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php'; INIT();

	$inputFilter = new Filters;

	$userCSS = $inputFilter->htmLawed($_POST['css']);

	//send filtered css back to javascript
	echo json_encode(array('css'=>$userCSS));
?>