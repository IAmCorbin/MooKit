<?php
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
	
	$DB = new DatabaseConnection;
	
	if(isset($_POST['alias'])) {
		$inputFilter = new Filters;
		$alias = $inputFilter->text($_POST['alias'],true);
		$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` WHERE `alias` LIKE '%$alias%' LIMIT 20;";
	} else {
	$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` LIMIT 10;";
	}
	$users = $DB->get_rows($query);
?>

	<div id="adminPanel">
		<div id="adminPanelUsers">
			<div class="adminTitle">User Administration</div>
			<form id="adminFindUsers" method="post" action="codeCore/php/secure/adminPanel.php">
				<input type="text" name="alias" size="20" value="<? if(isset($_POST['alias'])) echo $_POST['alias']; ?>" />
				<input type="submit" value="find users" />
			</form>
			<ul id="users_pagination"></ul>
			<table id="users">
				<thead>
					<th>Alias<span class="sortArrow"></span></th>
					<th>First Name<span class="sortArrow"></span></th>
					<th>Last Name<span class="sortArrow"></span></th>
					<th>Email<span class="sortArrow"></span></th>
					<th>Access Level<span class="sortArrow"></span></th>
					<th>Title<span class="sortArrow"></span></th>
					<th class="nosort">Delete</th>
				</thead>
				<tbody>
<?
			foreach($users as $user) {
				$access_level = getHumanAccess($user->access_level);
				echo "<tr>".
						"<td name=$user->alias>$user->alias</td>".
						"<td>$user->nameFirst</td>".
						"<td>$user->nameLast</td>".
						"<td>$user->email</td>".
						"<td><span class=\"adminAccessDec\">-</span>&nbsp;&nbsp;&nbsp;<span>$user->access_level</span><span class=\"adminAccessInc\">+</span></td>".
						"<td>$access_level</td>".
						'<td class="adminDeleteUser">X</td>'.
					"</tr>";
			}
?>				
				</tbody>
			</table>
		</div><!-- CLOSE ADMIN PANEL USERS -->
		<br />
		<div id="adminPanelMenu">
			<div class="adminTitle">Menu Administration</div>
			<? 
				$mainMenu = new MainMenu;
				var_dump($mainMenu->menu);
			?>
			<form id="adminAddLink" method="post" action="codeCore/php/secure/adminAddLink.php">
				<label>Link Name<input name="name" type="text" size="20" /></label><br />
				<label>href<input name="href" type="text" size="40" /></label><br />
				<label>description (optional)<input name="desc" type="text" size="20" /></label><br />
				<label>ajax link?<input name="ajax" value="1" type="checkbox" /></label><br />
				<label>weight<input name="weight" type="text" size="5" /></label><br />
				<input type="submit" value="add" />
			</form>
		</div>
	</div><!-- CLOSE ADMIN PANEL -->
<?
} else
	echo "Unauthorized";
?>