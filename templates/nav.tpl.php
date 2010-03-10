	<!-- NAVIGATION -->
		<div id="nav">
		<? foreach($links as $link): ?>
			<a class="link" href="<?=$link['href']?>"><?=$link['name']?></a><br />
		<? endforeach; ?>
		</div>
	<!-- END NAVIGATION -->
