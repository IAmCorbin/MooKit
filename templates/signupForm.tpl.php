	<!-- SIGNUP FORM -->
		<div class="signup_buttonWrap">
		    <a class="curved signup_buttonSlide" id="signup_buttonSlide"><span>You know you want to</span></a>
		    <a class="curved signup_buttonText slideBtn" id="signup_buttonText">Sign <span>Up</span></a>
		</div>
		<div id="signupOpen"></div>
		<div id="signup"></div>
		<div class="signupContent curved"><div id="signupClose" class="curved button">X</div>
			<form id="signupForm" method="post" action="CodeCore/php/signup.php">
				<div class="floatLeft">
					<div><label>Username:<input id="signupUser" class="required  msgPos:'signupUserError'" name="user" type="text" size="30" /></label><div class="formError" id="signupUserError"></div></div>
					<div><label>First Name:<input class="required  msgPos:'signupFirstError'" name="first" type="text" size="30" /></label><div class="formError" id="signupFirstError"></div></div>
					<div><label>Last Name:<input class="required msgPos:'signupLastError'" name="last" type="text" size="30" /></label><div class="formError" id="signupLastError"></div></div>
				</div>
				<div class="floatLeft">
					<div><label>Password:<input class="required msgPos:'signupPassError'" name="pass" type="password" size="30" /></label><div class="formError" id="signupPassError"></div></div>
					<div><label>Verify Password:<input class="required msgPos:'signupVPassError'" name="vpass" type="password" size="30" /></label><div class="formError" id="signupVPassError"></div></div>
					<div><label>Email:<input class="required validate-email msgPos:'signupEmailError'" name="email" type="text" size="30" /></label><div class="formError" id="signupEmailError"></div></div>
				</div>
				<div class="clearBoth">
					<input type="submit" class="button" value="Signup" />
				</div>
				<div id="signupPHPError" class="phpError"></div>
			</form>
		</div>
	<!-- END SIGNUP FORM -->
