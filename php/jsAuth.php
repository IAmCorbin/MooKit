<?
require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/php/includes.php';
$security = new Security;
if($security->check())
	echo getAuthContent();
?>