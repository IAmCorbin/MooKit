<?php
/**
 * Check for Magic Quotes and Strip if on and return mysql_real_escape_string
 */
function magicMySQL($DB,$var) {
	if(get_magic_quotes_gpc()) $var = stripslashes($var);
	return mysqli_real_escape_string($DB,$var);
}

?>