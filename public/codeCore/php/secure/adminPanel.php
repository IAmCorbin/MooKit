<?php
//require Administrator Access
if(Security::clearance() == 4) {
	
	$DB = new DatabaseConnection;
	
	$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users`";
	$users = $DB->get_rows($query);
?>
	<table class="users">
		<tr class="usersHead">
			<td>Alias</td>
			<td>First Name</td>
			<td>Last Name</td>
			<td>Email</td>
			<td>Access Level</td>
			<td>Delete</td>
		</tr>
<?
	foreach($users as $user) {
		echo "<tr class=\"userTest\">".
				"<td name=$user->alias>$user->alias</td>".
				"<td>$user->nameFirst</td>".
				"<td>$user->nameLast</td>".
				"<td>$user->email</td>".
				"<td>$user->access_level <span class=\"adminAccessInc\">+</span><span class=\"adminAccessDec\">-</span></td>".
				'<td class="adminDeleteUser">X</td>'.
			"</tr>";
	}
?>
	</table>
<?
} else
	return "Unauthorized";
?>