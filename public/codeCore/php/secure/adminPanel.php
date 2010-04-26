<?php
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
?>
	<div id="adminPanel">
		<div id="adminPanelUsers">
			<div class="adminTitle">User Administration</div>
			<br />
			<form class="singleton" id="adminFindUsers" method="post" action="codeCore/php/secure/adminPanel.php">
				<input type="text" name="alias" size="20" value="<? if(isset($_POST['alias'])) echo $_POST['alias']; ?>" />
				<input type="submit" value="find users" />
			</form>
			<ul id="users_pagination" class="pagination"></ul>
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
			$DB = new DatabaseConnection;
			
			if(isset($_POST['alias'])) {
				$inputFilter = new Filters;
				$alias = $inputFilter->text($_POST['alias'],true);
				$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` WHERE `alias` LIKE '%$alias%' LIMIT 20;";
			} else {
			$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` LIMIT 10;";
			}
			
			$users = $DB->get_rows($query);

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
			<form class="singleton" id="adminFindLinks" method="post" action="codeCore/php/secure/adminGetLinks.php">
				<input type="text" name="name" size="20" value="<? if(isset($_POST['links'])) echo $_POST['links']; ?>" />
				<input type="submit" value="find links" />
			</form>
			<ul id="links_pagination" class="pagination"></ul>
			<table id="links">
				<thead>
					<th>name</th>
					<th>href</th>
					<th>description</th>
					<th>weight</th>
					<th>ajax?</th>
					<th>mainMenu?</th>
					<th>access level</th>
					<th class="nosort">Delete</th>
				</thead>
				<tbody>
<?
			//grab all existing links and sublinks from the database
			$links = Link::getAll(true);
			
			$lastLink_id = null;
				

			foreach($links as $link) {
				//avoid double display of links
				if($link->link_id != $lastLink_id) {
					$access_level = getHumanAccess($link->access_level);
					echo "<tr>".
							"<td>$link->name</td>".
							"<td>$link->href</td>".
							"<td>$link->desc</td>".
							"<td>$link->weight</td>".
							"<td>$link->ajaxLink</td>".
							"<td>$link->mainMenu</td>".
							"<td>$link->access_level</td>".
							'<td class="adminDeleteLink">X</td>'.
						"</tr>";
				}
				$lastLink_id=$link->link_id;
			}
?>				
				</tbody>
			</table>
			
				<form id="adminAddLink" method="post" action="codeCore/php/secure/adminAddLink.php">
					<h1>Add a Link</h1>
					<label>
						<span>Link Name</span>
						<input name="name" type="text" size="20" />
					</label>
					<label>
						<span>href</span>
						<input name="href" type="text" size="40" />
					</label>
					<label>
						<span>Description</span>
						<input name="desc" type="text" size="20" /><- Optional: 
					</label>
					<label style="float: left;">
						<span>Weight</span>
						<input name="weight" type="text" size="5" />
					</label>
					<label style="float: right;">
						<span>Ajax link?</span>
						<input name="ajax" value="1" type="checkbox" />
					</label>
					<label style="float: right;">
						<span>Menu link?</span>
						<input name="ajax" value="1" type="checkbox" />
					</label>
					<label style="float: right;">
						<span>Access Level</span>
						<select name="access_level">
							<option value="<?echo ACCESS_NONE;?>">NONE</option>
							<option value="<?echo ACCESS_BASIC;?>">BASIC</option>
							<option value="<?echo ACCESS_CREATE;?>">CREATE</option>
							<option value="<?echo ACCESS_ADMIN;?>">ADMIN</option>
						</select>
					</label>
					<label style="clear: both;" >
						<input type="submit" value="add" />
					</label>
				</form> 
		</div>
	</div><!-- CLOSE ADMIN PANEL -->
<?
} else
	echo "Unauthorized";
?>