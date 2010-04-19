<?php
/**
  * contains DatabaseConnection class
  * @package MooKit
  */
/**
 * class DatabaseConnection
 *
 * Basic MySQL Database Connection class
 * Encapsulates a mysqli database object and privides methods for working with the database
 * 
 * @author Corbin Tarrant
 * @copyright Febuary 22th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
class DatabaseConnection { 
	/**
	 *@var $mysqli		mysqli database object
	 */ 
	var $mysqli = NULL;
	
	/**
	 * Constructor - Create persistent mysqli database object
	 *
	 *@param string $host 	the db host
	 *@param string $user 	the db user
	 *@param string $pass 	the db user password
	 *@param string $db 	the db to use
	 *
	 */
	function __construct($host=NULL,$user=NULL,$pass=NULL,$db=NULL) {
		include ROOT_DIR.'codeSite/php/DB.php';
		if($host == NULL) { $this->trigger_DB_error("PLEASE SETUP DATABASE CREDENTIALS IN /public/codeSite/php/DB.php FIRST!"); return false; }
		//create mysqli database object
		$this->mysqli = @new mysqli($host,$user,$pass,$db);
		//throw error if no mysqli object exists
		if(!$this->mysqli)
			$this->trigger_DB_error('E_DB_CONN');
	}
	/**
	 * Grab a single row from the database
	 *@param string $query		a valid mysql query
	 *@param string $rType		{@see formatResults}
	 *@return mixed 			returns row in desired format, true if successful but 0 rows, or false on fail
	 */
	public function get_row($query, $rType="object") {
		//check for valid connection
		if(!@$this->mysqli->ping()) {
			trigger_DB_error('E_DB_CONN');
			return false;
		}
		//Add LIMIT 1 to query if not passed
		if(!preg_match('/LIMIT 1;$/',$query))
			$query = preg_replace('/;$/',' LIMIT 1;',$query);
		// execute query
		if(!$results = @$this->mysqli->query($query)) {
			//log error
			$this->trigger_DB_error('E_DB_QUERY',$query);
			return false;
		} else {
			//query successful - no rows returned
			if(!is_object($results)) return true;
			if($results->num_rows === 0)
				return true;
			
			//return results in $rType format
			$results =  $this->formatResults($results,$rType);
			return $results[0];
		}	
	}
	/**
	 * Grab an array of rows from the database
	 *@param string $query		a valid mysql query
	 *@param string $rType		{@see formatResults}
	 *@return array 			returns an array of results, true if successful but 0 rows, or false on fail
	 */
	public function get_rows($query, $rType="object") {
		//check for valid connection
		if(!@$this->mysqli->ping()) {
			$this->trigger_DB_error('E_DB_CONN');
			return false;
		}

		// execute query
		if(!$results = @$this->mysqli->query($query)) {
			//log error
			$this->trigger_DB_error('E_DB_QUERY',$query);
			return false;
		} else {
			//query successful - no rows returned
			if(!is_object($results)) return true;
			if($results->num_rows === 0)
				return true;
			
			//return results in $rType format
			$results =  $this->formatResults($results,$rType);
			return $results;
		}	
	}
	/**
	  * Insert a new row in the Database
	  * @param string $query 	The Insert sql
	  * @returns int			the number rows affected
	  */
	public function insert($query) {
		//check for valid connection
		if(!@$this->mysqli->ping()) {
			$this->trigger_DB_error('E_DB_CONN');
			return false;
		}
		// execute query
		if(!$results = @$this->mysqli->query($query)) {
			//log error
			$this->trigger_DB_error('E_DB_QUERY',$query);
			return false;
		} else {
			//query successful - return number of rows affected
			return $this->mysqli->affected_rows;
		}	
	}
	/**
	  * Update Database Rows
	  * @param string $query 	The Update sql
	  * @returns int			the number rows affected
	  */
	public function update($query) {
		//call the insert function as it does the same thing, simply tests for a valid connection, executes query, and returned the number of rows affected
		return $this->insert($query);
	}
	/**
	  * Change a MySQLi_Result object to desired format
	  * @param MySQLi_Result object $results   	The mysqli result object you want to format
	  * @param string $rType				The format you want -  "assoc"- associative array, "json" - javascript object notation, "enum" - enumerated array, "object" -  array of objects
	  */
	public function formatResults(&$results, $rType="object") {
		switch($rType) {
			case "mysql":
				return $results;
			case "assoc" | "json" | "enum" | "object":
				//initialize resultSet array
				$resultSet = array();
			case "assoc" | "json":
				while($row = $results->fetch_assoc())
					$resultSet[] = $row;
			case "assoc":
				return $resultSet;
			case "json":
				return json_encode($resultSet);
			case "enum":
				while($row = $results->fetch_row())
					$resultSet[] = $row;
				return $resultSet;
			case "object":
				while($row = $results->fetch_object())
					$resultSet[] = $row;
				return $resultSet;
			case null;
				return true;
			default: //simply return true if no return data is desired
				return true;
		} //END SWITCH
	}
	/**
	  * Function to be thrown in the event of an error, logs the error
	  * @param string $error  string specifying the error to be thrown - send a custom string or one of the following:
	  * 		E_DB_CONN : error establishing mysqli connection
	  * 		E_DB_QUERY : error with a sql query 
	  * @param string $sql  optionally pass in the sql statement that triggered the error
	  *			
	  */
	public function trigger_DB_error($error, $sql = NULL) {
		switch($error) {
			case 'E_DB_CONN':
				$msg = date(DATE_RFC850)." : Error establishing MySQLi object DB connection";
				$phpErr = "There was an error creating the mysqli connection, error was logged to DBerrors.log";
				break;
			case 'E_DB_QUERY':
				$msg = date(DATE_RFC850)." : "."Error in query: $sql";
				$phpErr = "There was an error with the sql query, error was logged to DBerrors.log";
				break;
			default:
				$msg = $phpErr = $error;
				break;
		}
		$msg .= " | ".$this->mysqli->error."\n";
		
		//Error Log filename
		$errorLogPath = ERROR_LOG_DIR."DBerrors.xml";
		if(is_readable($errorLogPath)) { 
			// Create a new DOMDocument object with formatting
			$errorLog = new DOMDocument('1.0');
			$errorLog->formatOutput = true;
			$errorLog->preserveWhiteSpace = false;
			//Import error log file
			$errorLog->load($errorLogPath);
			//grab all errors
			//$errors = $errorLog->documentElement->getElementsByTagName("error");
			
			//Create the new Error XML
			$err = new SimpleXMLElement("<error></error>");
			$err->addChild('datetime',date('d M Y H:i T'));
			$err->addChild('type',$error);
			$err->addChild('msg',$msg);
			
			//convert the simpleXML to a DOMElement 
			$Err = dom_import_simplexml($err);
			//import the node, and all its children, to the document
			$Err = $errorLog->importNode($Err,true);
			// And then append it to the "<errors>" node
			$errorLog->documentElement->appendChild($Err);
			
			// save the modified error log
			$errorLog->save($errorLogPath);
		} else { 
			// SEND AN EMAIL TO ADMINISTRATOR IF ERROR LOGGING IS BROKEN
		}
		
		//trigger user php error for logging to PHPerrors.xml as well
		trigger_error($phpErr, E_USER_NOTICE);		
	}
	/**
	 * Return this connection link
	 * @return mysql connection link
	 */
	public function getLink() {
		return $this->mysqli;
	}
	/**
	 * Closes this connection
	 */
	public function cleanUp() {
		// close connection
		$this->mysqli->close();
	}
}
?>