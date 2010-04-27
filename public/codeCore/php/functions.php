<?php
/**
 * Check for Magic Quotes and Strip if on and return mysql_real_escape_string
 */
function magicMySQL($DB,$var) {
	return mysqli_real_escape_string($DB,$var);
}

/** Error Handling and Logging **/
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


 function array_keys_exist($keyArray, $array) {
	foreach($keyArray as $key) {
		if(!array_key_exists($key, $array))
			return false;
	}
 
	return true;
}

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
  * @param string $alias - the user alias to search for
  * @param string $rType - the return type desired - if "rows" is passed it will build table rows from object
  */
function adminGetUsers($alias, $rType="object") {
	$DB = new DatabaseConnection;
				
	if(isset($alias)) {
		$inputFilter = new Filters;
		$alias = $inputFilter->text($alias,true);
		$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` WHERE `alias` LIKE '%$alias%' LIMIT 20;";
	} else {
		$query = "SELECT `alias`,`nameFirst`,`nameLast`,`email`,`access_level` FROM `users` LIMIT 10;";
	}
	//build rows if requested
	if($rType === "rows") {
		$users = $DB->get_rows($query);
		foreach($users as $user) {
			$access_level = getHumanAccess($user->access_level);
			$return .= "<tr>".
					"<td>$user->alias</td>".
					"<td>$user->nameFirst</td>".
					"<td>$user->nameLast</td>".
					"<td>$user->email</td>".
					"<td><span class=\"adminAccessDec\">-</span>&nbsp;&nbsp;&nbsp;<span>$user->access_level</span><span class=\"adminAccessInc\">+</span></td>".
					"<td>$access_level</td>".
					'<td class="adminDeleteUser">X</td>'.
				"</tr>";
		}
		return $return;
	} else
		return $DB->get_rows($query,$rType);
}
/**
  * Search and return found links from the database
  * @param string $name - the link name to search for
  * @param bool $mainMenu - flag to grab only mainMenu links
  * @param string $rType - the return type desired - if "rows" is passed it will build table rows from object
  * @param bool $notSubs - switch to turn off the sublink table join
  */
function adminGetLinks($name, $mainMenu=false, $rType="object",$notSubs=false) {
	if($rType === "rows") {
		$links = Link::getSome($name,$mainMenu,"object",$notSubs);
		//grab all existing links and sublinks from the database
			$lastLink_id = null;
			
			foreach($links as $link) {
				//avoid double display of links
				if($link->link_id != $lastLink_id) {
					$access_level = getHumanAccess($link->access_level);
					$return.= "<tr>".
							"<td name=\"link_id\">$link->link_id</td>".
							"<td name=\"name\">$link->name</td>".
							"<td name=\"href\">$link->href</td>".
							"<td name=\"desc\">$link->desc</td>".
							"<td name=\"weight\">$link->weight</td>".
							"<td name=\"ajaxLink\">$link->ajaxLink</td>".
							"<td name=\"menuLink\">$link->mainMenu</td>".
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
											$return.="<tr class=\"sublinkRow\">".
												"<td style=\"display: none;\">".$sublink->sublink_id."</td>".
												"<td>".$sublink->sub_name."</td>".
												"<td>".$sublink->sub_href."</td>".
												"<td>".$sublink->sub_desc."</td>".
											"</tr>";
										}
									}
								$return.="</tbody>".
								"</table>".
								'<form class="adminAddSublink singleton"><input type="text" name="name" size="20" value="Add a Sublink" /></form>'.
							"</td>".
							'<td class="adminDeleteLink">X</td>'.
						"</tr>";
				}
				$lastLink_id=$link->link_id;
			}
			return $return;
	} else
		return Link::getSome($name,$mainMenu,$rType,$notSubs);
}
?>