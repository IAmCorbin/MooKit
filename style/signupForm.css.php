<?php header("Content-type: text/css");  ?>

/* SIGNUP FORM */
	#signup { position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: none; background: #000; z-index: 2000;}
	.signupContent { position:fixed !important; position: absolute; /*ie6 and above*/ left: 20%; top: -200px; width: 600px; height: 250px; padding: 10px; border: solid black 8px; background: #AFA; z-index: 2001; display: none;}
	#signupClose { position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; }
        #signupForm>div {width: 50%;} /* Input Containers */
        #signupForm>div>div { position: relative; height: 50px; width: 60%;} /* Inputs */
        #signupForm>div>div>div { position: absolute; top: -15px; left: 55%; width: 150px; height: 35px; overflow: hidden;  font-size: 12px; font-weight: bold; display: none; } /* input errors */
/* END SIGNUP FORM */