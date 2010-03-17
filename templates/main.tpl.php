<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<title><?=$title ?></title>

	<link rel="stylesheet" type="text/css" href="style/style.php" />
	<? foreach($styles as $style) echo $style."\n\t"; ?>

	<script type="text/javascript" src="js/mootools-1.2.4-core-yc.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.4.4-more.js"></script>
	<script type="text/javascript" src="jsClasses/LightBox.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<? foreach($scripts as $script) echo $script."\n\t"; ?>
	
</head>
<body>
	<?= $navTpl  // NAVIGATION BAR // ?>
	
	<? if(!$_SESSION['auth']) { ?> <!-- only display login and signup if not logged in -->
		<?= $loginTpl // LOGIN FORM // ?>
		<?= $signupTpl // SIGNUP FORM //?>
	<? } ?>
	
	<div id="content">
	<?= $contentTpl // CONTENT // ?>
	</div>
	<?= $debugTpl  // DEBUG AREA // ?>
	
<!-- CODE VALIDATION BADGES -->
<p id="w3_validated">
	<a href="http://validator.w3.org/check?uri=referer">
		<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
	</a>
</p> 

</body>
</html>