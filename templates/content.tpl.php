<?php 
require_once('../php/includes.php'); 

$DB = new DatabaseConnection(null, null, null, null, FALSE); //create a dummy DatabaseConnection object so we can use the displayResults function

?>
<?= $userIP ?>
	<!-- CONTENT AREA -->
		<div class="publicArea">
		
		</div>		
		<? if($_SESSION['auth'] == 1) { ?>
		<div class="secureArea">
		
				<div class="secureContent">
					<span id="logout" class="button">LOGOUT</span>
					<? $DB->displayResults($userInfo); ?><br /><!-- display a formatted table of the cooresponding user data -->
					
					<?= $cssTpl ?>
					
					<div id="postArea">
						<?= $postTpl ?>
					</div>
					
				</div>
			
		</div>
		<? } ?>	
	<!-- END CONTENT AREA -->
