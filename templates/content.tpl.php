<style type="text/css">
	.authArea {  position: absolute; width: 75%; height: 60%; left: 12%; top: 20%; display: none; }
	.authAreaContent { width: 100%; height: 100%; background: #cef9e8; border: #87a1b0 solid 3px; padding: 10px; }
</style>
<!-- CONTENT AREA -->
	<div class="authArea">
		<? require '../php/auth.php'; 
		//If logged authorized, display PHP LOGGEDIN flag for JavaScript
		if($_SESSION['auth'] === 1)
			echo '<div id="LOGGEDIN" style="display:none;"></div>';
		?>
		
		
	</div>
<!-- END CONTENT AREA -->