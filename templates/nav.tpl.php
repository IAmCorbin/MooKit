<style type="text/css">
	#nav { position: absolute; bottom: 100px; }
	.link { font-size: 30px; color: #0F0; background: #FF0; margin: 8px; display: block; }
	.link:hover { background: #AA0; }
</style>
<!-- NAVIGATION -->
	<div id="nav">
	<? foreach($links as $link): ?>
		<a class="link" href=<?= $link['href']?>><?=$link['name']?></a><br />
	<? endforeach; ?>
	</div>
<!-- END NAVIGATION -->