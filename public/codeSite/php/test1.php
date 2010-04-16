<?php 
$DB = new DatabaseConnection;

$users = $DB->get_rows("SELECT * FROM `users`","object");

echo '<div style="text-align: center; width: 100%;">';
foreach($users as $user)
	echo $user->nameFirst." | ";
echo "</div>";
?>

<span style="font-size:25px; color: #FAA;">TEST 1!!!!</span>
