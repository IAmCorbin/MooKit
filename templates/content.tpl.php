<?php require_once('../php/includes.php'); ?>
<!-- CONTENT AREA -->
	<div class="authArea">
		<? if($_SESSION['auth'] == 1) { ?>
			<div class="authAreaContent">
				<p>YOU HAVE BEEN AUTHORIZED</p><br />
				<?var_dump($userInfo);?>
				<? $_SESSION['DB']->displayResults($userInfo); ?><br />
				<?= $postTpl ?>
			</div>
		<? } ?>	
	</div>
<!-- END CONTENT AREA -->