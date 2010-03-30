<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<title><?=$title ?></title>

	<? foreach($styles as $style) echo $style."\n\t"; ?>

	<? foreach($scripts as $script) echo $script."\n\t"; ?>
	
</head>
<body>
	<div id="mainNav">
		<?= $Menu->output(); //$navTpl  // NAVIGATION BAR // ?>
	</div>
	
	<?= $loginTpl // LOGIN FORM // ?>
	<?= $signupTpl // SIGNUP FORM //?>
	
	<div id="content">
		<?= $contentTpl // CONTENT // ?>
	</div>
	
	<?= $debugTpl ?>
	
<!-- CODE VALIDATION BADGES -->
<p id="w3_validated">
	<a href="http://validator.w3.org/check?uri=referer">
		<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
	</a>
</p> 

</body>
</html>