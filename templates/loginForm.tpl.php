<style>
	/* LOGIN FORM */
	#login { position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: none; background: #000; z-index: 1000;}
	.loginContent { position:fixed !important; position: absolute; /*ie6 and above*/ left: 40%; top: -200px; width: 270px; height: 200px; padding: 10px; border: solid black 8px; background: #AFA;  z-index: 1001; display: none;}
	.loginContent div.X { z-index: 1001; position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; }
	#loginClose { z-index: 1001; display: none;}
	#loginForm>div {  width: 100%; height: 100%;} /* Input Containers */
        #loginForm>div>div { position: relative; } /* Inputs */
        /*#loginForm>div>div>div*/ div.formError { position: relative; top: -15px; left: 35%; width: 150px; height: 35px; overflow: hidden;  font-size: 12px; font-weight: bold; display: none;} /* input errors */
	/* END LOGIN FORM */
</style>	
<!-- LOGIN FORM -->
	<div class="login_buttonWrap">
	    <a class="curved login_buttonSlide" id="login_buttonSlide"><span>It's Fun!</span></a>
	    <a class="curved login_buttonText slideBtn" id="login_buttonText">Log <span>In</span></a>
	</div>
	<div id="loginOpen"></div>
	<div id="login"></div>
	<div class="loginContent curved"><div class="curved X button">X</div>
		<form id="loginForm" method="post" action="php/login.php">
			<div>
				<div><label>Username:<input class="required msgPos:'loginUserError'" name="user" id="loginUser" type="text" size="25" /></label><div class="formError" id="loginUserError"></div></div>
				<div><label>Password:<input class="required  msgPos:'loginPassError'" name="pass" id="loginPass" type="password" size="25" /></label><div class="formError" id="loginPassError"></div></div>
				<input type="submit" id="loginClose" class="button" value="Login" />
			</div>
		</form>
	</div>
<!-- END LOGIN FORM -->