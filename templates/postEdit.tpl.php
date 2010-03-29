		<form id="pickPost" method="post" action="CodeCore/php/postGet.php">
			<input class="required msgPos:'pickPostError" name="post_id" type="text" size="5" /> 
			<input type="submit" class="button" value="Pick A Post By ID" />
			<div id="pickPostError"></div>
		</form>

		<div class="floatLeft">
			<form name="Post" id="updatePost" method="post" action="CodeCore/php/postUpdate.php">
					 <label>Title<br /><input id="postTitleEdit" class="required msgPos:'postEditTitleError'" type="text" size="60" name="title" value="<?= $postTitle?>" /></label><div id="postEditTitleError"></div><br /><br />
					 <label>Text<br /><textarea  id="postTextEdit" class="required msgPos:'postEditTextError'" name="text" rows=10 cols=70><?= $postText ?></textarea></label><div id="postEditTextError"></div><br />
					 <input type="hidden" id="postEditID" name="post_id" value="<?=$postID?>" />
				<input type="submit" class="button" value="Post" />
			</form>
		</div>
		<div class="floatLeft displayPost">
			<div id="renderTitle"><?= $postTitle; ?></div>
			<div id="renderText"><?= $postText; ?></div>
		</div>
