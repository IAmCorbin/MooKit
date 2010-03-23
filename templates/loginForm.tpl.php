	<!-- LOGIN FORM -->
		<div class="login_buttonWrap">
		    <a class="curved login_buttonSlide" id="login_buttonSlide"><span>It's Fun!</span></a>
		    <a class="curved login_buttonText slideBtn" id="login_buttonText">Log <span>In</span></a>
		</div>
		<div id="loginOpen"></div>
		<div id="login"></div>
		<div class="loginContent curved"><div id="loginClose" class="curved button">X</div>
			<form id="loginForm" method="post" action="php/login.php">
				<div>
					<div><label>Username:<input class="required msgPos:'loginUserError'" name="user" id="loginUser" type="text" size="25" /></label><div class="formError" id="loginUserError"></div></div>
					<div><label>Password:<input class="required  msgPos:'loginPassError'" name="pass" id="loginPass" type="password" size="25" /></label><div class="formError" id="loginPassError"></div></div>
					<input type="submit" class="button" value="Login" />
					<div id="loginPHPError" class="phpError"></div>
				</div>
			</form>
		</div>
		
		<div class="loginContent curved" id="signupReminder">Need to Register?</div>
	<!-- END LOGIN FORM -->
