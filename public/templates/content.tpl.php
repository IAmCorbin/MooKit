	<!-- CONTENT AREA -->
		<div class="publicArea">
			<? echo $postTpl; ?>
		</div>
		<? if(isset($userInfo)) { ?>
			<div>
				<? foreach($userInfo as $field=>$value) {
					echo '<span class="userData">'.$field."--".$value."</span>"; } ?>
			</div>
		<? } ?>
	<!-- END CONTENT AREA -->
