<?php
/**
 * Check for Magic Quotes and Strip if on
 */
function magicStripper($var) {
	if(get_magic_quotes_gpc())  {
		$var = stripslashes($var);
		return mysql_real_escape_string($var);
	} else {
		return mysql_real_escape_string($var);
	}
}

?>