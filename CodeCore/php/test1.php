<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'MooKit/CodeCore/php/includes.php'; INIT();


$DB = new DatabaseConnection;

$users = $DB->query("SELECT * FROM `users`","object");

foreach($users as $user)
	echo $user->nameFirst;
?>

<span style="font-size:25px; color: #FAA;">TEST 1!!!!</span>
