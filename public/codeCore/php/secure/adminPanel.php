<?php
//require Administrator Access
if(Security::clearance() == 4) {
	
	$DB = new DatabaseConnection;
	
	$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users`";
	$users = $DB->get_rows($query);

	foreach($users as $user) {
		echo "<div class=\"userTest\">".$user->alias.$user->nameFirst.$user->nameLast.$user->email.$user->access_level."</div>";
	}
	
} else
	return "Unauthorized";
?>