
		<div class="floatLeft">
			<form name="Post" id="post" method="post" action="php/postUpdate.php">
					<input type="hidden" name="post_id" value="<?=$postID?>" />
					 <label>Title<br /><input type="text" size="60" name="title" value="<?= $postTitle?>" /></label><br /><br />
					 <label>Text<br /><textarea name="text" rows=10 cols=70><?= $postText ?></textarea></label><br />
				<input type="submit" class="button" value="Post" />
			</form>
		</div>
		<div class="floatLeft displayPost">
			<div id="renderTitle"><?= $postTitle; ?></div>
			<div id="renderText"><?= $postText; ?></div>
		</div>
