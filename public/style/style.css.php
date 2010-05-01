<?php header("Content-type: text/css"); 

$curved = '25px';

?>

* { margin: 0; padding: 0; z-index: 0; }
html, body { height: 100%; }
body { background-image : url('../img/back.jpg'); }
input[type="text"], input[type="password"] { border: inset #AAA 1px; padding: 2px 5px 2px 5px; }

<? /* <!-- TABLE STYLE --> */ ?>
	table { clear: left; border: double black 4px; }
	table *, ul.pagination * { font-family: Monospace; }
	table tr td { padding: 5px;}
	table thead th { padding: 5px; height: 50px; font-weight: bold; font-size: 20px; background: #CCF; vertical-align: top; }
		table thead th span { display: block; position: relative; bottom: 0px; left: 40%; width: 14px; height: 15px; }
		table thead th.forward_sort {  background: #88F; }
		table thead th.reverse_sort { background: #C8B; }
	table tbody tr { background-color: #AAF; cursor: pointer; }
		table tbody tr:hover { background-color: #FAA; }
		table tbody tr.alt { background-color: #88D; }
	ul.pagination li { margin-left: 50px; margin: 10px; float: left; list-style-type: none; }
		ul.pagination li a.currentPage { background: #AAA }

/* SHORTCUTS */
	.floatL { float: left; }
	.clearB { clear: both; }

/* SLIDE BUTTONS */
	.login_buttonWrap, .signup_buttonWrap { width:70px; height:36px; overflow:hidden; font-weight:bold; font-size:11px; margin:10px; }
	.login_buttonWrap {  position: absolute; right: 3px; top: 3px; z-index: 1050; }
	.login_buttonSlide{ width:70px; height:36px; background-color:#093d6f; color:#fff; top:0px; right:0px; position:absolute; line-height:36px; text-align:left; }
		.login_buttonSlide span{ /* display:none; */ visibility:hidden; padding-left:20px; color:#fff; }
	.login_buttonText { width:64px; height:30px; background-color:#fff; color:#000; position:absolute; top:3px; right:3px; text-transform:uppercase; line-height:30px; text-align:center; cursor:pointer; }
		.login_buttonText span{ color:#008ddd; }

	.signup_buttonWrap {  position: absolute; left: 3px; top: 3px; z-index: 2050; }
	.signup_buttonSlide { width:70px; height:36px; background-color:#093d6f; color:#fff; top:0px; left:0px; position:absolute; line-height:36px; text-align:right; }
		.signup_buttonSlide span{ /* display:none; */ visibility:hidden; padding-right:20px; color:#fff; }
	.signup_buttonText { width:64px; height:30px; background-color:#fff; color:#000; position:absolute; top:3px; left:3px; text-transform:uppercase; line-height:30px; text-align:center; cursor:pointer; }
		.signup_buttonText span{ color:#008ddd; }
/* END SLIDE BUTTONS */

/* DEFAULT LINK STYLES */
	.link > a:first-child { font-size: 20px; color: #0FF; background: #AAF; padding: 3px; padding: 5px; margin: 2px; }
		.link > a:first-child:hover { background: #88F; }
	.sublink { position: absolute; }
	.sublink > a:first-child { font-size: 10px; color: #00F; background: #0FF; margin: 20px; padding: 2px; }
		.sublink > a:first-child:hover { background: #0CC; }
	.linkDesc { display: block; visibility: hidden; opacity: 0; position: absolute; padding: 3px; background: #CCC; border: solid #AAA 2px; z-index: 1000 }

/* FORM VALIDATION */
	.validation-passed { background-color: #99cc99 !important; } 						/* This style is applied to input fields after successful validation */
	.validation-advice, .phpError { border: solid #600 2px; background: #F88; color: #000;  }  /* This style is applied to the error messages */
	.validation-failed, .phpError { background-color: #cc9999; }  						/* This style is applied to input fields after validation failed */
	.phpError { display: none; width: 80%; }

/* STRUCTURE */
	div#userInfo { background: #AAF; border: solid 5px #AAA; width: 400px; position: absolute; right: 2%; top: 20%; padding: 10px; } 
		div#userInfo:hover { background: #AFA; border: solid 5px #FFF; }
		div#userInfo > div { border: solid black 1px; padding: 5px; background: #77F;}
		div#userInfo > div:hover { background: #99F;}
	#mainNav { margin: 10px auto 10px auto; left: 10px; top: 2px; width: 80%; }
	#content { float: left; margin: 5% 5% 5% 5%; padding-bottom: 50px;  }

/* CUSTOM */
	.curved, input[type="password"], 
		input[type="text"], div#userInfo, span.linkDesc, .link > a:first-child, .sublink > a:first-child
		{ -moz-border-radius:<?echo $curved?>; <?/* Firefox */?> -webkit-border-radius:<?echo $curved?>; <?/* Safari and chrome */?> -khtml-border-radius:<?echo $curved?>; <?/* Linux browsers */?> border-radius:<?echo $curved?>; <?/* CSS3 */?> }
	.button { background: #AAA; border: outset #666 4px; margin: 8px;}
		.button:hover { background: #888; border: outset 3px; cursor: pointer;}
	.blueBox { width: 50px; height: 50px; background: blue; }
	form.singleton { display: inline; padding: 4px; border: solid #555 5px; background: #333; }
		form.singleton input { border: none; background: #333; color: #AAA; }
		form.singleton input[type="submit"] { border-left: solid 5px #555; }	

/* OTHER */
	#w3_validated { position: absolute; bottom: 10px; right: 10px; }
	#debugBox { width: 80%; height: 10px; position:fixed !important; position: absolute; /*ie6 and above*/ bottom: 0px; left: 10%; background: #FFF; border: solid #000 4px; border-bottom: none; overflow: auto;}
	.break { width: 100%; border-bottom: 3px solid #000; margin: 15px 0 15px 0; }

<? /* <!-- LOADING ANIMATIONS --> */ ?>
.loadingB { background: url('../img/anim/loadingW.gif') no-repeat; !important; }
.loadingW { background: url('../img/anim/loadingW.gif') no-repeat !important; }