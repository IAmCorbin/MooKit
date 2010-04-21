<?php
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	
	$DB = new DatabaseConnection;
	
	if(isset($_POST['alias'])) {
		$inputFilter = new Filters;
		$alias = $inputFilter->text($_POST['alias'],true);
		$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` WHERE `alias` LIKE '%$alias%' LIMIT 10;";
	} else {
	$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` LIMIT 10;";
	}
	$users = $DB->get_rows($query);
?>
	<div id="adminPanel">
		<form id="adminFindUsers" method="post" action="codeCore/php/secure/adminPanel.php">
			<input type="text" name="alias" size="20" value="<? if(isset($_POST['alias'])) echo $_POST['alias']; ?>" />
			<input type="submit" value="find users" />
		</form>
		<table class="users">
			<tr class="usersHead">
				<td>Alias</td>
				<td>First Name</td>
				<td>Last Name</td>
				<td>Email</td>
				<td>Access Level</td>
				<td>Title</td>
				<td>Delete</td>
			</tr>
<?
		foreach($users as $user) {
			$access_level = getHumanAccess($user->access_level);
			echo "<tr class=\"userTest\">".
					"<td name=$user->alias>$user->alias</td>".
					"<td>$user->nameFirst</td>".
					"<td>$user->nameLast</td>".
					"<td>$user->email</td>".
					"<td><span>$user->access_level</span><span class=\"adminAccessInc\">+</span><span class=\"adminAccessDec\">-</span></td>".
					"<td>$access_level</td>".
					'<td class="adminDeleteUser">X</td>'.
				"</tr>";
		}
?>
		</table>
	</div><!-- CLOSE ADMIN PANEL -->
<?
} else
	echo "Unauthorized";
?>