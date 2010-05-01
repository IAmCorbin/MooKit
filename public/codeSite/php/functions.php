<?
function updateUserInfo() {
	return "<div>".(isset($_SESSION['alias'])? "Welcome ".$_SESSION['alias']." (".getHumanAccess($_SESSION['access_level']).")" : "Welcome Guest - Please Sign Up or Log In to access more features")
										."</div><div>Time: ".date(DATE_RFC822)."</div>"
										."<div>You are visiting from ".$_SERVER['REMOTE_ADDR']."(".$_SERVER['REMOTE_ADDR'].")</div>"
										."<div>Using ".$_SERVER['HTTP_USER_AGENT']."</div>";
}
?>