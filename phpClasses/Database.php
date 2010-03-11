<?php
/**
 * class DatabaseConnection
 *
 * Basic MySQL Database Connection class
 * 
 * @author Corbin Tarrant
 * @copyright Febuary 22th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
class DatabaseConnection { 
	/**
	 *@var $connection		mysql database link
	 */ 
	var $connection = NULL;
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
	 * Constructor - makes $_SESSION['DB'] a reference to this DatabaseConnection object and connects to database
	 *
	 *@param string $host 	the db host
	 *@param string $user 	the db user
	 *@param string $pass 	the db user password
	 *@param string $db 	the db to use
	 */
	function __construct($host='localhost',$user='test',$pass='test',$db='test') {
		
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		
		$_SESSION['DB'] =& $this;
		
		$this->connect();
		
	}
	/**
	 * Connect to the database or throw an error
	 */
	public function connect() {
		try {
			// Create connection to MYSQL database
			$this->connection = @mysql_connect($this->host, $this->user, $this->pass);
			//select database
			@mysql_select_db ($this->db); 
			//check for valid connection
			if (!$this->connection)
			    throw new Exception('MySQL Connection Database Error: ' . mysql_error());
			else
			    $this->CONNECTED = true;
		}
		 catch (Exception $e) {
			error_log( date(DATE_RFC850)." : ".$e->getMessage()."\n", 3, $_SERVER['DOCUMENT_ROOT']."MooKit/logs/DBerrors.log");
		}
	}
	/**
	 * Run a query on the database
	 *@param string $query		a valid mysql query
	 *@param string $rType		return type default = mysql result set. Options - "object" array of objects, "enum" enumerated array, "assoc" associative array
	 *@param string $display	if string "display" is passed, then {@link displayResults()} is called
	 *@return $results 			returns a mysql result set or false
	 */
	public function query($query, $rType="mysql", $display=NULL) {
		//if connection was lost, reconnect
		if(!$this->connection)
			$this->connect();
		//if a valid connection now exists
		if($this->connection) {
			// execute query
			if($results = @mysql_query($query, $this->connection) or die (error_log(date(DATE_RFC850)." : "."Error in query: $query".mysql_error()."\n", 3, $_SERVER['DOCUMENT_ROOT']."MooKit/logs/DBerrors.log"))) {
				//handle display option
				if($display == "display") {
					$this->displayResults($results);
					@mysql_data_seek($results,0);
				}
				//return results in $rType format
				switch($rType) {
					case "mysql":
						return $results;
						break;
					case "assoc" | "enum" | "object":
						//initialize resultSet array
						$resultSet = array();
					case "assoc":
						while($row = @mysql_fetch_assoc($results))
							$resultSet[] = $row;
						return $resultSet;
						break;
					case "enum":
						while($row = @mysql_fetch_row($results))
							$resultSet[] = $row;
						return $resultSet;
						break;
					case "object":
						while($row = @mysql_fetch_object($results))
							$resultSet[] = $row;
						return $resultSet;
						break;
					default:
						//simply return true if no return data is desired
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
		// see if any rows were returned
		if ( @mysql_num_rows($results) > 0) {
			//style
			echo '<style type="text/css">
					.displayResultsBox { background: #444; border: solid #555 10px; padding: 2px; padding: 15px 10px 15px 10px; }
					.displayResultsCell { background: #FCC; padding: 5px; border: solid #FFF 1px;  }
				</style>'."\n";
				
			echo '<table class="displayResultsBox">'."\n";
			//display field names
			$fields = @mysql_num_fields($results);
			echo '<tr class="displayResultsBox">'."\n";
			for($n = 1; $n<$fields; $n++) 		echo '<td class="displayResultsCell">'. @mysql_field_name($results,$n).'</td>'."\n";
			echo "</tr>\n";
			//display rows
			echo '<tr class="displayResultBox">';
			while($row = @mysql_fetch_row($results)) {
				$n = 0;
				while( ++$n < sizeof($row) )
					echo '<td class="displayResultsCell">'.$row[$n]."</td>\n";
			}
			echo "<tr>\n</table>\n";
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
		return $this->connection;
	}
	/**
	 * Closes this connection
	 */
	public function cleanUp() {
		// close connection
		@mysql_close($this->connection);
	}
}
?>