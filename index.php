<?
require_once 'php/includes.php';
new DatabaseConnection('localhost','test','test','test');
//if session auth is not set, set it to 0
isset($_SESSION['auth'])? 0: $_SESSION['auth'] = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<title>User Login Test </title>

	<link rel="stylesheet" type="text/css" href="style/style.php" />

	<script type="text/javascript" src="js/mootools-1.2.4-core-yc.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.4.4-more.js"></script>
	<script type="text/javascript" src="js/Lightbox.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

</head>
<body>
	
	<div class="authArea">
		<? require 'php/auth.php'; ?>
	</div>
	
	<div id="loginOpen"></div>
	<div id="login"></div>
	<div class="loginContent curved"><div class="curved X button">X</div>
		<form id="loginForm" method="post" action="php/login.php">
			<div>
				<label for="loginUser">Username:</label><input class="required" name="user" id="loginUser" type="text" size="25" />
				<label>Password:<input class="required" name="pass" id="loginPass" type="password" size="25" /></label>
				<input type="submit" id="loginClose" class="button" value="Login" />
			</div>
		</form>
	</div>

	<div id="signupOpen"></div>
	<div id="signup"></div>
	<div class="signupContent curved"><div class="curved X button">X</div>
		<form id="signupForm" method="post" action="php/signup.php">
			<div class="floatLeft">
				<label>Username:<input class="required" name="user" type="text" size="30" /></label>
				<label>First Name:<input class="required" name="first" type="text" size="30" /></label>
				<label>Last Name:<input class="required" name="last" type="text" size="30" /></label>
			</div>
			<div class="floatLeft">
				<label>Password:<input class="required" name="pass" type="password" size="30" /></label>
				<label>Verify Password:<input class="required" name="vpass" type="password"" size="30" /></label>
				<label>Email:<input class="required validate-email " name="email" type="text" size="30" /></label>
			</div>
			<div class="clearBoth">
				<input type="submit" id="signupClose" class="button" value="Signup" />
			</div>
		</form>
	</div>

	<div class="login_buttonWrap">
	    <a class="curved login_buttonSlide" id="login_buttonSlide"><span>It's Fun!</span></a>
	    <a class="curved login_buttonText slideBtn" id="login_buttonText">Log <span>In</span></a>
	</div>

	<div class="signup_buttonWrap">
	    <a class="curved signup_buttonSlide" id="signup_buttonSlide"><span>You know you want to</span></a>
	    <a class="curved signup_buttonText slideBtn" id="signup_buttonText">Sign <span>Up</span></a>
	</div>

	<div id="debugBox">

	</div>

	<p id="w3_validated">
		<a href="http://validator.w3.org/check?uri=referer">
			<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
		</a>
	</p>
</body>
</html>