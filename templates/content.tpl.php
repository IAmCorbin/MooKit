<?php 
require_once('../php/includes.php'); 

$DB = new DatabaseConnection(null, null, null, null, FALSE); //create a dummy DatabaseConnection object so we can use the displayResults function

?>
<?= $userIP ?>
	<!-- CONTENT AREA -->
		<? if($_SESSION['auth'] == 1) { ?>
		<div class="secureArea">
			<div id="secureMenu">
				<?= $navTpl ?>
				<span id="logout" class="button">LOGOUT</span>
			</div>
			<div id="secureContent">
				<? $DB->displayResults($userInfo); ?><br /><!-- display a formatted table of the cooresponding user data -->
				
				<?= $cssTpl ?>
				
				<div id="postArea">
					<?= $postEditTpl ?>
				</div>
			</div>
			<div id="LOGGEDIN"></div> <!-- JavaScript Flag -->
		</div>
		<? } else { ?>
		<div class="publicArea">
			<?= $postTpl ?>
		</div>
		<? } ?>
	<!-- END CONTENT AREA -->
