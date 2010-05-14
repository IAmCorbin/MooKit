<?php
/**
  * Administration Panel
  * @package MooKit
  */
//require Administrator Access
if(Security::clearance() & ACCESS_ADMIN) {
?>
	<div id="adminPanel">
		<div id="adminPanelUsers">
			<div class="adminTitle">User Administration</div>
			<br />
			<form class="singleton" id="adminGetUsers" method="post" action="codeCore/php/secure/sharedGetUsers.php">
				<input type="text" name="alias" size="20" value="<? if(isset($_POST['alias'])) echo $_POST['alias']; ?>" />
				<input type="hidden" name="rType" value="rows" />
				<input type="submit" value="find users" />
			</form>
			<ul id="users_pagination" class="pagination"></ul>
			<table id="users">
				<thead>
					<th>ID<span class="sortArrow"></span></th>
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
		if(!isset($_POST['alias'])) $_POST['alias'] = '';
		echo sharedGetUsers("rows", $_POST['alias']);
		
?>				
				</tbody>
			</table>
		</div><!-- CLOSE ADMIN PANEL USERS -->
		<br />
		<div id="adminPanelMenu">
			<div class="adminTitle">Link Administration</div>
			<form class="singleton" id="adminGetLinks" method="post" action="codeCore/php/secure/adminGetLinks.php">
				<input type="text" name="name" size="20" value="<? if(isset($_POST['links'])) echo $_POST['links']; ?>" />
				<input type="hidden" name="rType" value="rows" />
				<input type="submit" value="find links" />
			</form>
			<ul id="links_pagination" class="pagination"></ul>
			<table id="links">
				<thead>
					<th>id</th>
					<th>name</th>
					<th>href</th>
					<th>description</th>
					<th>weight</th>
					<th>ajaxLink?</th>
					<th>menuLink?</th>
					<th>access level</th>
					<th>sublinks (right-click to remove)</th>
					<th class="nosort">Delete</th>
				</thead>
				<tbody>
					<?	echo adminGetLinks("rows"); ?>				
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
						<input name="weight" value="0" type="text" size="5" />
					</label>
					<label style="float: right;">
						<span>Ajax link?</span>
						<input name="ajaxLink" value="1" type="checkbox" />
					</label>
					<label style="float: right;">
						<span>Menu link?</span>
						<input name="menuLink" value="1" type="checkbox" />
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