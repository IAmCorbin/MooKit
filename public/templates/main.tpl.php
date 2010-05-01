<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<!--  
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<title><?=$title ?></title>

	<? if(isset($styles)) foreach($styles as $style) echo $style."\n\t"; ?>

	<? if(isset($scripts)) foreach($scripts as $script) echo $script."\n\t"; ?>
</head>
<body>
	<div id="userInfo">
		<? if(isset($userInfo)) echo$userInfo; ?>
	</div>
	
	<div id="mainNav">
		<? if(isset($Menu)) $Menu->output('span','span','link','sublink'); // NAVIGATION BAR // ?>
	</div>
	
	<div style="display: none;" id="outdatedBrowserError"></div>
	<? if(isset($loginTpl)) echo $loginTpl; // LOGIN FORM // ?>
	<? if(isset($signupTpl)) echo $signupTpl; // SIGNUP FORM //?>
	
	<div id="content">
		<?= $contentTpl // CONTENT // ?>
	</div>
	
	<? if(isset($debugTpl)) echo $debugTpl; ?>
	
<!-- CODE VALIDATION BADGES -->
<p id="w3_validated">
	<a href="http://validator.w3.org/check?uri=referer">
		<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
	</a>
</p> 

	<!-- Flag to tell JavaScript the user is logged in -->
	<? if( Security::clearance() )  {?>
	<div id="LOGGEDIN"></div>
	<?  } ?>

</body>
</html>