<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/MooKit/CodeCore/php/includes.php'; INIT(false);
?>

<?= $userIP ?>
	<!-- CONTENT AREA -->
		<? if($_SESSION['auth'] == 1) { ?>
		<div class="secureArea">
			<div id="secureMenu">
				<? $Menu->output('div','span','secureLink','secureSubLink'); ?>
				<span id="logout" class="button">LOGOUT</span>
			</div>
			<div id="secureContent">
				<? 
				foreach($userInfo as $field=>$value) {
					echo '<span class="userData">'.$field."--".$value."</span>";
				}?><br /><!-- display user data -->
				
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
