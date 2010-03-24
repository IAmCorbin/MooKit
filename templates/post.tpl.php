<? foreach($posts as $post) { ?>
	<div class="floatLeft">
		<div class="postTitle"><?= $post->title; ?></div>
		<div class="postHtml"><?= $post->html; ?></div>
	</div>
<? } ?>