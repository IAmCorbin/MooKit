<?

if(!isset($_POST['title'])) $_POST['title'] = '';
echo createGetPosts("rows", $_POST['title']);

?>