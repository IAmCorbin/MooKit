<?
/**
  * Script to trigger an error from javascript
  * @package MooKit
  **/
//decode sent json
$json = json_decode($_POST['json']);

//log error
trigger_error($json->error,E_USER_WARNING);
?>