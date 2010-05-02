<?
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {

	$postTitle = "Test Post";
	$postText = "Hello Post!";
?>

<div id="createPanel">
	<div id="createPanelPosts">
		<div class="adminTitle">Post Management</div>
		<br />
		<form class="singleton" id="createGetPosts" method="post" action="codeCore/php/secure/createGetPosts.php">
			<input type="text" name="title" size="20" value="<? if(isset($_POST['title'])) echo $_POST['title']; ?>" />
			<input type="submit" value="find posts" />
		</form>
		<ul id="posts_pagination" class="pagination"></ul>
		<table id="posts">
			<thead>
				<th>ID<span class="sortArrow"></span></th>
				<th>Title<span class="sortArrow"></span></th>
				<th>Creator Name<span class="sortArrow"></span></th>
				<th>Creation Time<span class="sortArrow"></span></th>
				<th>Modified Last<span class="sortArrow"></span></th>
				<th class="nosort">Delete</th>
			</thead>
			<tbody>
<?
	if(!isset($_POST['title'])) $_POST['title'] = '';
	echo createGetPosts("rows", $_POST['title']);
?>				
			</tbody>
		</table>
	</div>
	<div class="floatL">
		<form id="createAddPost" method="post" action="codeCore/php/secure/createAddPost.php">
				 <label>Title<br /><input id="postTitleEdit" class="required msgPos:'postEditTitleError'" type="text" size="60" name="title" value="<?= $postTitle?>" /></label><div id="postEditTitleError"></div><br /><br />
				 <label>Text<br /><textarea  id="postTextEdit" class="required msgPos:'postEditTextError'" name="text" rows=10 cols=70><?= $postText ?></textarea></label><div id="postEditTextError"></div><br />
				 <input type="hidden" id="postEditID" name="post_id" value="<?=$postID?>" />
			<input type="submit" class="button" value="Post" />
		</form>
	</div>
	<div class="floatL displayPost">
		<div id="renderTitle"><?= $postTitle; ?></div>
		<div id="renderText"><?= $postText; ?></div>
	</div>
</div>
<?
} else
	echo "Unauthorized";
?>