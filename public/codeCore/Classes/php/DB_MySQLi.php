<?php
/**
  * contains DB_MySQLi class
  * @package MooKit
  */
/**
 * class DB_MySQLi
 *
 * DB_MySQL Database Connection class
 * Encapsulates a mysqli database object and privides methods for working with the database ( Includes Prepared Statements )
 * 
 * @author Corbin Tarrant
 * @birth May 5th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
class DB_MySQLi { 
	/** @var 	$mysqli	mysqli database object */ 
	var $mysqli = NULL;
	/** @var 	$stmt 	holds the current prepared statement */
	var $stmt = NULL;
	/** @var $status - holds 1 or error status **/
	var $STATUS = "1";
	/**
	 * Constructor - Create mysqli database object
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
	 * Checks for LIMIT 1 on query and adds it if not existent, runs through get_rows, and then returns the first result
	 * @param 	string 	$query		a valid mysql query to prepare and excecute
	 * @param 	string	$types		mysqli->bind_param($types)
	 * @param	array	$vars		the variables to bind
	 * @param 	string 	$rType		{@see formatResults}
	 * @return 	mixed 	returns the row in the desired format or false
	 */
	public function get_row($query, $types=NULL, $vars=NULL, $rType="object") {
		//Add LIMIT 1 to query if not passed
		if(!preg_match('/LIMIT 1;$/',$query))
			$query = preg_replace('/;$/',' LIMIT 1;',$query);
		if($results = $this->get_rows($query, $types, $vars, $rType))
			return $results[0];
		else 
			return false;
	}
	/**
	 * Grab an array of rows from the database
	 * @param 	string 	$query		a valid mysql query to prepare and excecute
	 * @param 	string	$types		mysqli->bind_param($types)
	 * @param	array	$vars		the variables to bind
	 * @param 	string 	$rType		{@see formatResults}
	 * @return 	mixed	array of results or false
	 */
	public function get_rows($query, $types=NULL, $vars=NULL, $rType="object") {
		//check for valid connection
		if(!@$this->mysqli->ping()) {
			$this->trigger_DB_error('E_DB_CONN');
			return false;
		}
		//prepare query
		if($this->prepare($query)) {
			if($types && $vars) {
				//bind variables, execute query, bind results, and format
				if($this->bind_param($types,$vars)) {
					if($this->bind_results($results,$rType)) {
						$this->formatResults($results, $rType);
						return $results;
					} else
						return false;
				} else
					return false;
			} else{ //skip variable binding, execute query, bind results, and format
				if($this->execute()) {
					if($this->bind_results($results,$rType)) {
						$this->formatResults($results, $rType);
						return $results;
					} else
						return false;
				} else
					return false;
			}
		} else
			return false;
	}
	/**
	  * Insert a new row in the Database
	  * @param 	string 	$query 		The Insert sql
	  * @param 	string	$types		mysqli->bind_param($types)
	  * @param	array	$vars		the variables to bind
	  * @returns 	int		the number rows affected
	  */
	public function insert($query, $types=NULL, $vars=NULL) {
		//check for valid connection
		if(!@$this->mysqli->ping()) {
			$this->trigger_DB_error('E_DB_CONN');
			return false;
		}
		// execute query
		//prepare query
		if($this->prepare($query)) {
			if($types && $vars) {
				//bind variables, execute query, get affected rows and return
				if($this->bind_param($types,$vars)) {
					$affectedRows = $this->stmt->affected_rows;
					$this->closeStmt();
					return $affectedRows;
				} else
					return false;
			} else { //skip variable binding, execute query, get affected rows and return
				if($this->execute()) {
					$affectedRows = $this->stmt->affected_rows;
					$this->closeStmt();
					return $affectedRows;
				} else
					return false;
			}
		} else
			return false;
	}
	/**
	  * Update Database Rows
	  * @param string $query 	The Update sql
	  * @returns int			the number rows affected
	  */
	public function update($query) {
		//call the insert function as it does the same thing, tests for a valid connection, executes query, and returned the number of rows affected
		return $this->insert($query);
	}
	/**
	  * Delete Database Rows
	  * @param string $query 	The Update sql
	  * @returns int			the number rows affected
	  */
	public function delete($query) {
		//call the insert function as it does the same thing, tests for a valid connection, executes query, and returned the number of rows affected
		return $this->insert($query);
	}
	/**
	  * Setup a Prepared Statement
	  * @param 	string	$query	the query to prepare
	  * @param	bool		$exec	switch to execute the statement
	  * @return	mysqli->prepare() status
	  */
	public function prepare($query,$exec=FALSE) {
		if(!$this->stmt = $this->mysqli->prepare($query)) {
			$this->trigger_DB_error("E_DB_PREPARE",$query);
			return false;
		}
		//execute statement
		if($exec)
			return $this->execute();
		return true;
	}
	/**
	  * Bind prepared statement query variables
	  * @param 	string	$types		mysqli->bind_param($types)
	  * @param	array	$vars		the variables to bind
	  * @param	bool		$exec		switch to execute the statement
	  */
	public function bind_param($types, $vars, $exec=TRUE) {
		//prepend types to the beginning of variables to pass into the bind_param function
		array_unshift($vars,$types);
		//bind the variables
		if(!call_user_func_array(array($this->stmt,'bind_param'),$vars)) {
			$this->trigger_DB_error("E_DB_BIND_PARAM");
			$this->closeStmt();
			return false;
		}
		//execute statement
		if($exec)
			return $this->execute();
		return true;
	}
	/**
	  * Executes the current stmt
	  * @param	bool		$bind	switch to trigger bind_results
	  * @returns bool
	  */
	public function execute() {
		if(!$this->stmt->execute()) {
			trigger_DB_error("E_DB_EXEC");
			$this->closeStmt();
			return false;
		} else 
			return true;
	}
	/**
	  * Bind The Query Results to variables
	  * @param	array	$results		results will be bound to this array, each row will be an associative array
	  * @param	bool		$close		switch to close the stmt
	  */
	public function bind_results(&$results, $close=TRUE) {
		$results = array();
		$fields = array();
		$pointers = array();
		$metadata = $this->stmt->result_metadata();
		$count = 0;
		//Grab column names and set pointer references
		while($field = $metadata->fetch_field()) {
			$pointers[] = &$fields[$field->name];
		}
		//bind variables
		if(!call_user_func_array(array($this->stmt,'bind_result'), $pointers)) {
			trigger_DB_error("E_DB_BIND_RESULTS");
			$this->closeStmt();
			return false;
		}
		//set rows
		while($this->stmt->fetch()) {
			$row = array();
			foreach($fields as $k=>$v) {
				$row[$k] = $v;
			}
			$results[$count] = $row;
			$count++;
		}
		$metadata->free();
		//close statement
		if($close) 
			$this->closeStmt();
		return true;
	}
	/** 
	  * Close the current Prepared Statment 
	  * @returns bool
	  */
	public function closeStmt() {
		return $this->stmt->close();
	}
	/**
	  * Change query results to desired format
	  * @param 	array  	$results		The mysqli result object you want to format
	  * @param 	string 	$rType		The format you want -  "object" -  array of objects, "assoc"- associative array, "json" - javascript object notation, "enum" - enumerated array
	  */
	public function formatResults(&$results, $rType="object") {
		switch($rType) {
			case "object": 
				//cast to objects
				for($x=0; $x< sizeof($results); $x++) {
					$results[$x] = (object)$results[$x];
				}
				break;
			case "json":
				json_encode($results);
				break;
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
		$this->STATUS = $error;
		switch($error) {
			case 'E_DB_CONN':
				$msg = date(DATE_RFC850)." : Error establishing MySQLi object DB connection";
				$phpErr = "There was an error creating the mysqli connection, error was logged to DBerrors";
				break;
			case 'E_DB_PREPARE':
				$msg = date(DATE_RFC850)." : "."Error preparing query: $sql";
				$phpErr = "There was an error preparing the query, error was logged to DBerrors";
				break;
			case 'E_DB_BIND_PARAM':
				$msg = date(DATE_RFC850)." : "."Error binding parameters to prepared query";
				$phpErr = "There was an error binding parameters to preparied query, error was logged to DBerrors";
				break;
			case 'E_DB_EXEC':
				$msg = date(DATE_RFC850)." : "."Error executing prepared query";
				$phpErr = "There was an error executing the preparied query, error was logged to DBerrors";
				break;
			case 'E_DB_BIND_RESULTS':
				$msg = date(DATE_RFC850)." : "."Error binding prepared query results";
				$phpErr = "There was an error binding the preparied query results, error was logged to DBerrors";
				break;
			case 'E_DB_QUERY':
				$msg = date(DATE_RFC850)." : "."Error in query: $sql";
				$phpErr = "There was an error with the sql query, error was logged to DBerrors";
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
}
?>