<? 
require_once 'includes.php';
?>
<? if($_SESSION['auth'] === 1) { ?>
	<div class="authAreaContent">
		<p>YOU HAVE BEEN AUTHORIZED</p><br />
		<?$_SESSION['DB']->query("SELECT * FROM `users` WHERE `alias`='".$_SESSION['user']."' LIMIT 1;",null,"display");?>
	</div>
<? } ?>
