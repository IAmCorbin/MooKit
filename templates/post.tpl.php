<? foreach($posts as $post) { ?>
	<div>
		<div class="postTitle"><?= $post->title; ?></div>
		<div class="postHtml"><?= $post->html; ?></div>
	</div>
	<div class="break"></div>
<? } ?>