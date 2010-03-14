<?php
//if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 

require_once '../php/includes.php';

$inputFilter = new Filters;

$userCSS = $inputFilter->htmLawed($_POST['css']);

//send filtered css back to javascript
echo json_encode(array('css'=>$userCSS));


//} //end if(defined('INSITE')
?>