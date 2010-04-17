	<!-- CONTENT AREA -->
		<? 
		$security = new Security(); echo "ip:".$_SESSION['ip'];
		if($security->check()) { 
		?>
		<div class="secureArea">
			<div id="secureMenu">
				<? $Menu->output('div','span','secureLink','secureSubLink'); ?>
				<span id="logout" class="button">LOGOUT</span>
			</div>
			<div id="secureContent">
				<? foreach($userInfo as $field=>$value) {
					echo '<span class="userData">'.$field."--".$value."</span>";
				}?><br /><!-- display user data -->
				
				<div id="postArea">
					<?= $postEditTpl ?>
				</div>
			</div>
			<div id="LOGGEDIN"></div> <!-- JavaScript Flag -->
		</div>
		<? } else { ?>
		<div class="publicArea">
			<? echo $postTpl; ?>
		</div>
		<? } ?>
	<!-- END CONTENT AREA -->
