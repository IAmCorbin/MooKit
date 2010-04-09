<?php
/**
  * contains DatabaseConnection class
  * @package MooKit
  */
/**
 * class DatabaseConnection
 *
 * Basic MySQL Database Connection class
 * Encapsulates a mysqli database object
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
	 *@var bool $CONNECTED	true or false connection status
	 */ 
	var $CONNECTED = false;
	/**	
	 *@var string $host		the db host
	 */ 
	 var $host = NULL; 
	/**		
	 *@var string $user		the db user
	 */ 
	var $user = NULL; 
	/**
	 *@var string $pass		the db user password
	 */ 
	var $pass = NULL; 
	/**
	 *@var string $db		the db to use
	 */ 
	var $db = NULL;
	
	/**
	 * Constructor - Create persistent mysqli database object
	 *
	 *@param string $host 	the db host
	 *@param string $user 	the db user
	 *@param string $pass 	the db user password
	 *@param string $db 	the db to use
	 *
	 *@returns bool		true on success, false on fail
	 */
	function __construct($host=NULL,$user=NULL,$pass=NULL,$db=NULL,$dummy=TRUE) {
		if(!!$dummy) {
			try{
				include $_SERVER['DOCUMENT_ROOT'].NAMESPACE.'/public/codeSite/php/DB.php';
				if($host == NULL) { echo "PLEASE SETUP DATABASE CREDENTIALS IN /public/codeSite/php/DB.php FIRST!"; return false; }
				//create mysqli database object
				$this->mysqli = @new mysqli($host,$user,$pass,$db);
				//throw error if no mysqli object exists
				if(!$this->mysqli)
					throw new Exception('Error creating mysqli object');
				//check for mysqli connection error
				//if($this->mysqli->connect_errno)
				//	throw new Exception('mysqli error #'.$this->mysqli->connect_errno.' : '.$this->mysqli->connect_error);
			} catch(Exception $e) {
				$msg = date(DATE_RFC850)." : ".$e->getMessage()."\n";
				error_log( $msg , 3, $_SERVER['DOCUMENT_ROOT']."MooKit/logs/DBerrors.log"); //save error to logfile
				if(DEBUG) echo $msg; //if debug mode is on, echo the error msg
				return false;
			}
			
			return true;
		}
	}
	/**
	 * Run a query on the database
	 *@param string $query		a valid mysql query
	 *@param string $rType		return type default = mysql result set. Options - "object" array of objects, "enum" enumerated array, "assoc" associative array
	 *@param string $display	if string "display" is passed, then {@link displayResults()} is called
	 *@return $results 			returns an array of results or false
	 */
	public function query($query, $rType="assoc", $display=NULL) {
		//check for valid connection
		if(@$this->mysqli->ping()) {
			// execute query
			if(!$results = $this->mysqli->query($query)) {
				//log error
				$msg = date(DATE_RFC850)." : "."Error in query: $query".$this->mysqli->error."\n";
				error_log($msg, 3, $_SERVER['DOCUMENT_ROOT']."MooKit/logs/DBerrors.log"); //save error to logfile
				if(DEBUG) echo $msg; //if debug mode is on, echo the error msg
				return false;
			} else {
				//query successful
				if($results->num_rows === 0)
					return true;
				//handle display option
				if($display == "display")
					$this->displayResults($results);
					
				//return results in $rType format
				switch($rType) {
					case "mysql":
						return $results;
						break;
					case "assoc" | "enum" | "object":
						//initialize resultSet array
						$resultSet = array();
					case "assoc":
						while($row = $results->fetch_assoc())
							$resultSet[] = $row;
						return $resultSet;
						break;
					case "enum":
						while($row = $results->fetch_row())
							$resultSet[] = $row;
						return $resultSet;
						break;
					case "object":
						while($row = $results->fetch_object())
							$resultSet[] = $row;
						return $resultSet;
						break;
					case "json":
						while($row = $results->fetch_assoc())
							$resultSet[] = $row;
						return json_encode($resultSet);
					case null;
						return true;
						break;
					default: //simply return true if no return data is desired
						return true;
						break;
				} //END SWITCH
			}
		} else
			return null;
	}
	/**
	 * Outputs a mysql result set in a styled box
	 *@param $results a mysql result set
	 */
	public function displayResults($results) {
		// make sure results are not empty
		if ( $results->num_rows > 0) {
			//style
			echo '<style type="text/css">
					.displayResultsBox { background: #444; border: solid #555 10px; padding: 2px; padding: 15px 10px 15px 10px; }
					.displayResultsCell { background: #FCC; padding: 5px; border: solid #FFF 1px;  }
				</style>'."\n";
				
			echo '<table class="displayResultsBox">'."\n";
			//display field names
			echo '<tr class="displayResultsBox">'."\n";
			
			while($field = $results->fetch_field()) 		
				echo '<td class="displayResultsCell">'. $field->name .'</td>'."\n";
			echo "</tr>\n";
			$results->field_seek(0); //reset field to beginning of row
			//display rows
			echo '<tr class="displayResultBox">';
			while($row = $results->fetch_row()) {
				$n = -1;
				while( ++$n < sizeof($row) )
					echo '<td class="displayResultsCell">'.$row[$n]."</td>\n";
			}
			echo "<tr>\n</table>\n";
			//reset results to first row
			$results->data_seek(0);
		} else {
			// no results : print status message
			echo "<div class=\"dataResults\"><div>No rows found!</div></div>";
		}
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