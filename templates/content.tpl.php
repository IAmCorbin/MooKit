<?php require_once('../php/includes.php'); ?>
	<!-- CONTENT AREA -->
		<div class="authArea">
			<? if($_SESSION['auth'] == 1) { ?>
				<div class="authAreaContent">
					<?//var_dump($userInfo);?>
					<? $_SESSION['DB']->displayResults($userInfo); ?><br /><!-- display a formatted table of the cooresponding user data -->
					
					<?= $cssTpl ?>
					
					<div id="postArea">
						<?= $postTpl ?>
					</div>
					
				</div>
			<? } ?>	
		</div>
	<!-- END CONTENT AREA -->
