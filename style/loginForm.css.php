<?php header("Content-type: text/css");  ?>

/* LOGIN FORM */
	#login { position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: none; background: #000; z-index: 1000;}
	.loginContent { position:fixed !important; position: absolute; /*ie6 and above*/ left: 40%; top: -200px; width: 270px; height: 200px; padding: 10px; border: solid black 8px; background: #AFA;  z-index: 1001; display: none;}
	#loginClose { z-index: 1001; position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; }
	#loginForm>div {  width: 100%; height: 100%;} /* Input Containers */
        #loginForm>div>div { position: relative; } /* Inputs */
        /*#loginForm>div>div>div*/ div.formError { position: relative; top: -15px; left: 35%; width: 150px; height: 35px; overflow: hidden;  font-size: 12px; font-weight: bold; display: none;} /* input errors */
	
	#signupReminder { position: absolute; left: 35px; width: 150px; height: 40px; font-size: 18px; font-family: monospace; font-weight: bold; text-align: right;}
/* END LOGIN FORM */