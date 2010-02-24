<?php
/**
 * class DatabaseConnection
 *
 * Basic Database Connection class
 * 
 * Febuary 22th, 2010
 * Code by Corbin
 * http://www.IAmCorbin.net
 */
class DatabaseConnection { 
	/**
	 *@var $connection - a mysql database link
	 *@var BOOL $CONNECTED - true or false connection status
	 *@var STRING $host - the db host
	 *@var STRING $user - the db user
	 *@var STRING $pass - the db user password
	 *@var STRING $db - the db to use
	 */
	private $connection = NULL;
	private $CONNECTED = false;
	private $host = NULL;
	private $user = NULL;
	private $pass = NULL;
	private $db = NULL;
	/**
	 * Constructor - makes $_SESSION['DB'] a reference to this DatabaseConnection object and connects to database
	 *@param STRING $host = the db host
	 *@param STRING $user = the db user
	 *@param STRING $pass = the db user password
	 *@param STRING $db = the db to use
	 */
	function __construct($host,$user,$pass,$db) {
		
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		//link connection to session
		$_SESSION['DB'] =& $this;
		$_SESSION['DBconn'] =& $this->connection;
		
		$this->connect();
	}
	/**
	 * Connect to the database or throw an error
	 */
	public function connect() {
		try {
			// Create connection to MYSQL database
			$this->connection = @mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db ($this->db);
			if (!$this->connection) {
			    throw new Exception('MySQL Connection Database Error: ' . mysql_error());
			} else {
			    $this->CONNECTED = true;
			}	
		}
		 catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	/**
	 * Run a query on the database
	 *@param STRING $query - a valid mysql query
	 *@param STRING $display - if "display" is passed, then output to the page
	 *@return $results - if results are returned from the query, returns a mysql result set
	 */
	public function query($query, $display=NULL) {
		//if connection was lost, reconnect
		if(!$this->connection)
			$this->connect();
		// execute query
		$results = mysql_query($query, $this->connection) or die ("Error in query: $query. ".mysql_error());

		if($display == "display") {
			$this->displayResults($results);
		}
		if($results)
			return $results;
	}
	/**
	 * Outputs a mysql result set to the page
	 *@param $results - a mysql result set
	 */
	public function displayResults($results) {
		// see if any rows were returned
		if (mysql_num_rows($results) > 0) {
			// if results found : print results one after another
			echo "<div class=\"dataResults\">";
			while($row = mysql_fetch_row($results)) {
				echo "<div>";
				$n = 0;
				while( $n++ < sizeof($row) )
					echo "<span>".$row[$n]."</span>";
				echo "</div>";
			}
			echo "</div>";
		} else {
			// no results : print status message
			echo "<div class=\"dataResults\"><div>No rows found!</div></div>";
		}
	}
	/**
	 * Closes this connection
	 */
	public function cleanUp() {
		// close connection
		mysql_close($this->connection);
	}
}
?>