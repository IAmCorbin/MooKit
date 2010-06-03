<?php
/**
  * Gloabl Core Functions
  * @package MooKit
  */
/////////////////////////////////////////////////////////////////////
//Database Information Retrieval Functions//
/////////////////////////////////////////////////////////////////////
/**
  * Search and retrieve user information from the database
  * @param 	string 	$rType 	the return type desired - if "rows" is passed it will build table rows from object
  * @param 	string 	$alias 	the user alias to search for
  * @return	mixed	users in desired rType
  */
function sharedGetUsers($rType="object", $alias=NULL) {
	
	//build rows if requested and user is an administrator (this should only be done in the administrator panel)
	if($rType === "rows" && (Security::clearance() & ACCESS_ADMIN) ) {
		$users = User::get($alias,"object");
		$rows = '';
		foreach($users as $user) {
			$access_level = getHumanAccess($user->access_level);
			$rows .= "<tr>".
					"<td>$user->user_id</td>".
					"<td>$user->alias</td>".
					"<td>$user->nameFirst</td>".
					"<td>$user->nameLast</td>".
					"<td>$user->email</td>".
					"<td><span class=\"adminAccessDec\">-</span>&nbsp;&nbsp;&nbsp;<span>$user->access_level</span><span class=\"adminAccessInc\">+</span></td>".
					"<td>$access_level</td>".
					'<td class="adminDeleteUser">X</td>'.
				"</tr>";
		}
		return $rows;
	} else
		return User::get($alias,$rType);
}
/**
  * Administrator - Search and return found links from the database
  * @param 	string 	$rType 		the return type desired - if "rows" is passed it will build table rows from object
  * @param 	string 	$name 		the link name to search for
  * @param 	bool 	$menuLink 	switch to grab only menu links
  * @param 	bool 	$notSubs 		switch to turn off the sublink table join
  * @return	mixed	links in desired rType
  */
function adminGetLinks($rType="object", $name='', $menuLink=FALSE, $notSubs=FALSE) {
	//build rows if requested
	if($rType === "rows") {
		//grab links and sublinks from the database
		$links = Link::get($name,$menuLink,"object",$notSubs,ACCESS_ADMIN);
		$lastLink_id = null;
		$rows = '';
		if(is_array($links))
			foreach($links as $link) {
				//avoid double display of links
				if($link->link_id != $lastLink_id) {
					$access_level = getHumanAccess($link->access_level);
					$rows .= "<tr>".
							"<td name=\"link_id\">$link->link_id</td>".
							"<td name=\"name\">$link->name</td>".
							"<td name=\"href\">$link->href</td>".
							"<td name=\"desc\">$link->desc</td>".
							"<td name=\"weight\">$link->weight</td>".
							"<td name=\"ajaxLink\">$link->ajaxLink</td>".
							"<td name=\"menuLink\">$link->menuLink</td>".
							"<td name=\"access_level\">$link->access_level</td>".
							"<td name=\"sublinks\">".
							//SubLinks Editing Table
								"<table class=\"subLinks\">".
									"<thead>".
										"<th style=\"display: none;\">id</th>".
										"<th>name</th>".
										"<th>href</th>".
										"<th>desc</th>".
									"</thead>".
									"<tbody>";
									foreach($links as $sublink) {
										if($link->link_id === $sublink->link_id && $sublink->sublink_id) {
											$rows.="<tr class=\"sublinkRow\">".
												"<td style=\"display: none;\">".$sublink->sublink_id."</td>".
												"<td>".$sublink->sub_name."</td>".
												"<td>".$sublink->sub_href."</td>".
												"<td>".$sublink->sub_desc."</td>".
											"</tr>";
										}
									}
								$rows.="</tbody>".
								"</table>".
								'<form class="adminAddSublink singleton"><input type="text" name="name" size="20" value="Add a Sublink" /></form>'.
							"</td>".
							'<td class="adminDeleteLink">X</td>'.
						"</tr>";
				}
				$lastLink_id=$link->link_id;
			}
		return $rows;
	} else
		//grab links and sublinks from the database
		return Link::get($name,$menuLink,$rType,$notSubs,ACCESS_ADMIN);
}
/**
  * Search and return found posts from the database
  * @param 	string 	$rType 	the return type desired - if "rows" is passed it will build table rows from object
  * @param 	string 	$title 	the post title to search for
  * @param 	int	 	$post_id 	the post id to search for
  * @return	mixed	links in desired rType
  */
function createGetPosts($rType="object", $title=NULL, $post_id=NULL) {		
	//build rows if requested
	if($rType == "rows") {
		$posts = Post::get($_SESSION['user_id'],$title,$post_id);
		$rows = '';
		if(is_array($posts))
			foreach($posts as $post) {
				$rows .= "<tr>".
							"<td name=\"post_id\">$post->post_id</td>".
							"<td name=\"title\">$post->title</td>".
							"<td name=\"creator_id\">$post->creator</td>".
							"<td name=\"createTime\">$post->createTime</td>".
							"<td name=\"modTime\">$post->modTime</td>".
							'<td class="createDeletePost">X</td>'.
						"</tr>";
			}
		return $rows;
	} else {
		 return Post::get($_SESSION['user_id'],$title,$post_id,$rType);
	}
}
/////////END///////////////////////////////END////////////////
//Database Information Retrieval Functions//
/////////END///////////////////////////////END////////////////

/////////////////////////////////////////////////////////////////////
//                    Assorted Functions               //
/////////////////////////////////////////////////////////////////////
/** check an array for existing keys, checks each key with array_key_exists
  * @param 	array 	$keyArray	an array containing all the keys to check for
  * @param 	array 	$array 		the array to test - passed by reference
  * @param 	bool 	$setBlank	switch to set missing key values to empty strings instead of returning false
  * @param 	bool 	$blankChk	switch to check for blank values and return false if found
  * @return	bool		true/false
  */
 function array_keys_exist($keyArray, &$array, $setBlank=FALSE, $blankChk=FALSE) {
	foreach($keyArray as $key) {
		if(!array_key_exists($key, $array)) {
			if($setBlank) {
				$array[$key] = '';
			} else
				return false;
		}
		if($blankChk)
			if($array[$key] == '')
				return false;
	}
	return true;
}
/**
  * Translate a bitwise user access level into a human readable title
  * @param	int	$access_level		the access level to translate
  */
function getHumanAccess($access_level) {
	switch($access_level) {
		case 0:
			return $human = "Unauthorized User";
		case 1:
			return $human = "Basic User";
		case 3:
			return $human = "Creator";
		case 7:
			return $human = "Administrator";
		default:
			return $human = "Unknown (Error?)";
			
	}
}

/**
  * return references for an array of values
  * @param	array	$arr		the array to return references for
  */ 
function makeValuesReferenced($arr){
    $refs = array();
    foreach($arr as $key => $value)
        $refs[$key] = &$arr[$key];
    return $refs;

}


/**
  * print a line break with an optional centered message - used for debugging
  * @param	string	$msg	the message
  * @param	int		$rows	the number of rows
  * @param	string	$char	the character to use
  * @param	int		$cols	number of columns
  */
function linebreak($msg=NULL,$rows=1,$char="~",$cols=100) {
	if($msg && !$rows) $rows = 4;
	for($x = 0; $x < $rows; $x++) {
		for($y = 0; $y < $cols; $y++)
			echo $char;
		echo "<br />";
		//optional message
		if($msg && $x==round($rows/2)-1) {
			$msgLength = strlen($msg);
			$msgMiddle = round(($cols/2)-($msgLength/2));
			for($y = 0; $y < $cols-$msgLength; $y++) {
				echo $char;
				//echo message in the middle
				if($y == $msgMiddle) echo $msg;
			}
			echo "<br />";
		}
	}
}
/** 
  * Error Handling and Logging 
  * {@see http://php.net/manual/en/function.set-error-handler.php}
**/
function ErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    //Error Log filename
      $errorLogPath = ERROR_LOG_DIR."PHPerrors.xml";
    //how long to keep errors 
      defined('PHP_ERROR_EXPIRE')? $errorExpireTime = strtotime(PHP_ERROR_EXPIRE) : $errorExpireTime = NULL;
    // define an assoc array of error string
    // in reality the only entries we should
    // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
    // E_USER_WARNING and E_USER_NOTICE
      $errortype = array (
		  E_ERROR              => 'Error',
		  E_WARNING            => 'Warning',
		  E_PARSE              => 'Parsing Error',
		  E_NOTICE             => 'Notice',
		  E_CORE_ERROR         => 'Core Error',
		  E_CORE_WARNING       => 'Core Warning',
		  E_COMPILE_ERROR      => 'Compile Error',
		  E_COMPILE_WARNING    => 'Compile Warning',
		  E_USER_ERROR         => 'User Error',
		  E_USER_WARNING       => 'User Warning',
		  E_USER_NOTICE        => 'User Notice',
		  E_STRICT             => 'Runtime Notice',
		  E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
		  );
    // set of errors for which a var trace will be saved
      $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    
	if(is_readable($errorLogPath)) { 
		// Create a new DOMDocument object with formatting
		$errorLog = new DOMDocument('1.0');
		$errorLog->formatOutput = true;
		$errorLog->preserveWhiteSpace = false;
		//Import error log file
		$errorLog->load($errorLogPath);
		//grab all errors
		$errors = $errorLog->documentElement->getElementsByTagName("error");
		
		
		//check time and remove old errors
		if($errorExpireTime) {
			$errorsLength = $errors->length-1;
			for($n = $errorsLength; $n >= 0; $n--) {
				$error = $errors->item($n);
				//get error time
				$errorTime = $error->getElementsByTagName("datetime");
				//convert time
				$errorTime = strtotime($errorTime->item(0)->nodeValue);
				//compare and remove if expired
				if( $errorTime < $errorExpireTime)
					$errorLog->documentElement->removeChild($error);
			}
		}
		
		//Create the new Error XML
		$err = new SimpleXMLElement("<error></error>");
		$err->addChild('datetime',date('d M Y H:i T'));
		$err->addChild('num',$errno);
		$err->addChild('type',$errortype[$errno]);
		$err->addChild('msg',$errmsg);
		$err->addChild('scriptname',$filename);
		$err->addChild('linenum',$linenum);
	
		if (in_array($errno, $user_errors)) {
			ob_start();
			var_export($vars);
			$vars = ob_get_contents();
			ob_clean(); //cleanup ob
			$err->addChild('vartrace', $vars);
		}
		//convert the simpleXML to a DOMElement 
		$Err = dom_import_simplexml($err);
		//import the node, and all its children, to the document
		$Err = $errorLog->importNode($Err,true);
		// And then append it to the "<root>" node
		$errorLog->documentElement->appendChild($Err);
		
		// save the modified error log
		$errorLog->save($errorLogPath);
	} else { 
		// SEND AN EMAIL TO ADMINISTRATOR IF ERROR LOGGING IS BROKEN
	}
    
	// !!!
	// Add Administrator Email notification depending on error type
	// !!!
    //if ($errno == E_USER_ERROR) {
	//mail("phpdev@example.com", "Critical User Error", $err);
    //}
}
set_error_handler("ErrorHandler");
/////////END///////////////////////////////END////////////////
//                    Assorted Functions               //
/////////END///////////////////////////////END////////////////
?>