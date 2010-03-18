<?php
/**
 * Check for Magic Quotes and Strip if on and return mysql_real_escape_string
 */
function magicMySQL($var) {
	if(get_magic_quotes_gpc())  {
		$var = stripslashes($var);
		return mysql_real_escape_string($var);
	} else {
		return mysql_real_escape_string($var);
	}
}

?>