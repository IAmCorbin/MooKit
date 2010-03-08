<!-- CONTENT AREA -->
	<div class="authArea">
		<? require '../php/auth.php'; 
		//If logged authorized, display PHP LOGGEDIN flag for JavaScript
		if($_SESSION['auth'] === 1)
			echo '<div id="LOGGEDIN" style="display:none;"></div>';
		?>
		
		
	</div>
<!-- END CONTENT AREA -->