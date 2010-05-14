<?php 
/**
  * Test Script
  * @package MooKit
  */
$DB = new DB_MySQLi;

$users = $DB->get_rows("SELECT * FROM `users`");

echo '<div style="text-align: center; width: 100%;">';
foreach($users as $user)
	echo $user->nameFirst." | ";
echo "</div>";
?>

<span style="font-size:25px; color: #FAA;">TEST 1!!!!</span>
