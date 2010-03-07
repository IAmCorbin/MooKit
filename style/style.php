<?php header("Content-type: text/css"); 

$curved = '25px';

?>

* { margin: 0; padding: 0; z-index: 0; }
html, body { height: 100%; }
body { background-image : url('../img/back.jpg'); }

/* SHORTCUTS */
	.floatLeft { float: left; }
	.clearBoth { clear: both; }
/* END SHORTCUTS */

/* SLIDE BUTTONS */
	.login_buttonWrap, .signup_buttonWrap { width:245px; height:36px; overflow:hidden; font-weight:bold; font-size:11px; margin:10px; }
	.login_buttonWrap {  position: absolute; right: 3px; top: 3px; }
	.login_buttonSlide{ width:70px; height:36px; background-color:#093d6f; color:#fff; top:0px; right:0px; position:absolute; line-height:36px; text-align:left; }
	.login_buttonSlide span{ /* display:none; */ visibility:hidden; padding-left:20px; color:#fff; }
	.login_buttonText { width:64px; height:30px; background-color:#fff; color:#000; position:absolute; top:3px; right:3px; text-transform:uppercase; line-height:30px; text-align:center; cursor:pointer; }
	.login_buttonText span{ color:#008ddd; }

	.signup_buttonWrap {  position: absolute; left: 3px; top: 3px; }
	.signup_buttonSlide { width:70px; height:36px; background-color:#093d6f; color:#fff; top:0px; left:0px; position:absolute; line-height:36px; text-align:right; }
	.signup_buttonSlide span{ /* display:none; */ visibility:hidden; padding-right:20px; color:#fff; }
	.signup_buttonText { width:64px; height:30px; background-color:#fff; color:#000; position:absolute; top:3px; left:3px; text-transform:uppercase; line-height:30px; text-align:center; cursor:pointer; }
	.signup_buttonText span{ color:#008ddd; }
/* END SLIDE BUTTONS */

/* FORM VALIDATION */
	.validation-passed { background-color: #99cc99 !important; } 						/* This style is applied to input fields after successful validation */
	.validation-advice { border: solid #600 2px; background: #F88; color: #000;  }  /* This style is applied to the error messages */
	.validation-failed { background-color: #cc9999; }  								/* This style is applied to input fields after validation failed */
/* END FORM VALIDATION */

/* STRUCTURE */
	.authArea {  position: absolute; width: 60%; height: 60%; left: 20%; top: 20%; display: none; }
	.authAreaContent { width: 100%; height: 100%; background: #cef9e8; border: #87a1b0 solid 3px; }
	.dataResults div { background: #000; padding: 2px; }
	.dataResults span { background: #AAA; border: solid black 1px; }
/* END STRUCTURE  */

/* CUSTOM */
	.curved { -moz-border-radius:<?echo $curved?>; <?/* Firefox */?> -webkit-border-radius:<?echo $curved?>; <?/* Safari and chrome */?> -khtml-border-radius:<?echo $curved?>; <?/* Linux browsers */?> border-radius:<?echo $curved?>; <?/* CSS3 */?> }
	.button { background: #AAA; border: outset #666 4px; margin: 8px;}
	.button:hover { background: #888; border: outset 3px; cursor: pointer;}
/*  END CUSTOM */

/* OTHER */
	#w3_validated { position: absolute; bottom: 10px; right: 10px; }
/* END OTHER */