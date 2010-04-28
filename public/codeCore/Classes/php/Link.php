<?
/**
  * contains Link Class
  * @package MooKit
  */
/**
 * A Class for creating a Link
 *
 * Creates an array of link objects with optional sublink objects
 *
 * @author Corbin Tarrant
 * @birth March 30th, 2010
 * @package MooKit
 */
class Link {
	/** @var DatabaseConnection $DB optional database object */
	var $DB= NULL;
	/** @var string $name	link name	*/
	var $name;
	/** @var string $href	link location	*/
	var $href;
	/** @var string $desc	optional description */
	var $desc;
	/** @var int $weight	the link weight - used for display ordering */
	var $weight;
	/** @var string $ajax	ajax link class	*/
	var $ajax;
	/** @var Array $sublinks	an array of Link objects */
	var $sublinks;
	/** @var string $status    holds OK or database errors */	
	var $status = "1";
	
	/**
	  * Constructor
	  *
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $desc 		optional description
	  * @param int $weight		the link weight - used for display ordering
	  * @param string $ajax		ajax link class
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function __construct($name,$href,$desc=NULL,$weight,$ajax=NULL,$sublinks=NULL) {
		$this->name = $name;
		$this->href = $href;
		$this->desc = $desc;
		$this->weight = $weight;
		$this->ajax = $ajax;
		if($sublinks)
			$this->sublinks = $sublinks;
		else
			$this->sublinks = array();
	}
	/** 
	  * Create a new SubLink
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $desc		link description	
	  * @param int $weight		the link weight - used for display ordering
	  * @param string $ajax		ajax link switch
	  */
	public function addSub($name, $href,$desc=NULL,$weight,$ajax=NULL) {
		array_push($this->sublinks,new Link($name,$href,$desc,$weight,$ajax));
	}
	/**
	  * Add this link to the database
	  * @param bool $mainMenu - switch to put link in main menu
	  * @param int $weight - link weight
	  * @param int $access_level - link access level
	  */
	public function insert($mainMenu=FALSE,$access_level=0) {
		//check for valid input and return error if not valid
		$inputFilter = new Filters;
		$inputFilter->number($link_id);
		if($inputFilter->ERRORS()) return "E_DATA";
		//establish database connection
		$this->DB = new DatabaseConnection;
		//escape query variables
		$name = mysqli_real_escape_string($this->DB->mysqli,$this->name);
		$href = mysqli_real_escape_string($this->DB->mysqli,$this->href);
		$desc = mysqli_real_escape_string($this->DB->mysqli,$this->desc);
		if($this->ajax) $ajax = '1';
		$weight = mysqli_real_escape_string($this->DB->mysqli,$weight);
		if($mainMenu) $mainMenu = 1; else $mainMenu = 0;
		$query = "INSERT INTO `links`(`name`,`href`,`desc`,`ajaxLink`,`mainMenu`,`weight`,`access_level`) VALUES('$name','$href','$desc','$ajax','$mainMenu','$weight','$access_level');";
		if(!$return = $this->DB->insert($query))
			$this->status = "E_INSERT";
		return $return;
	}
	/**
	  * Updates a link in the database
	  * @param int $link_id - the link to update
	  * @param bool $mainMenu - main menu switch
	  * @param int $weight - optional link weight
	  * @returns int - number of rows affected
	  * @param int $access_level - link access level
	  */
	public function update($link_id,$mainMenu,$access_level) {
		$this->DB = new DatabaseConnection;
		$link_id = mysqli_real_escape_string($this->DB->mysqli,$link_id);
		$name = mysqli_real_escape_string($this->DB->mysqli,$this->name);
		$href = mysqli_real_escape_string($this->DB->mysqli,$this->href);
		$desc = mysqli_real_escape_string($this->DB->mysqli,$this->desc);
		$ajax = mysqli_real_escape_string($this->DB->mysqli,$this->ajax);
		$weight = mysqli_real_escape_string($this->DB->mysqli,$this->weight);
		if($mainMenu) $mainMenu = 1; else $mainMenu = 0;
		if(!$weight) $weight = 0;
		if(!$access_level) $access_level = 0;
		$query = "UPDATE `links` SET `name`='$name', `href`='$href', `desc`='$desc', `ajaxLink`='$ajax', `mainMenu`='$mainMenu', `weight`='$weight', `access_level`='$access_level' WHERE `link_id`='$link_id';";
		
		$return = $this->DB->update($query);
		if($return==0)
			return $return;
		if(!$return)
			$this->status = "E_UPDATE";
		return $return;
	}
	/**
	  * Removes a link from the database
	  * @param int $link_id - the link to remove
	  * @returns int - the number of rows affected
	  */
	public static function delete($link_id) {
		//check for valid input and return error if not valid
		$inputFilter = new Filters;
		$inputFilter->number($link_id);
		if($inputFilter->ERRORS()) return "E_DATA";
		//establish database connection
		$DB = new DatabaseConnection;
			//turn off mysqli autocommit to process as a transaction
			$DB->mysqli->autocommit(FALSE);
			//remove all sublinks
			$query = "DELETE FROM `sublinks` WHERE `link_id`='$link_id';";
			$DB->delete($query);
			//remove link
			$query = "DELETE FROM `links` WHERE `link_id`='$link_id';";
			$DB->delete($query);
			//rollback or commit
			if($DB->STATUS !== "1") {
				$DB->mysqli->rollback();
			} else if($DB->STATUS === "1")
				$DB->mysqli->commit();
		//close the database connection
		$DB->mysqli->close();
		return $DB->STATUS;
	}
	/** 
	  * Grabs all the links from the database with their associated sublinks
	  * @param bool $mainMenu - flag to grab only mainMenu links
	  * @param string $rType - the return type for the links
	  * @returns object - all the found links
	  */
	public static function getAll($mainMenu=false,$rType="object") {
		if($mainMenu) $mainMenu = " WHERE `links`.`mainMenu`=1 ";
		//select all links with thier associated sublinks
		$query = "SELECT `links`.* , `sublinks`.`sublink_id`, ".
					"`subDetails`.`name` AS `sub_name`, ". 
					"`subDetails`.`href` AS `sub_href`, ".
					"`subDetails`.`desc` AS `sub_desc`, ".
					"`subDetails`.`weight` AS `sub_weight`, ".
					"`subDetails`.`mainMenu` AS `sub_mainMenu`, ".
					"`subDetails`.`ajaxLink` AS `sub_ajaxLink`, ".
					"`subDetails`.`access_level` AS `sub_access_level` ".
						"FROM `links` ".
						"LEFT JOIN `sublinks` ON `links`.`link_id`=`sublinks`.`link_id` ".
						"LEFT JOIN `links` AS subDetails ON `sublinks`.`sublink_id`=subDetails.`link_id`".$mainMenu.";";
		$DB = new DatabaseConnection;
		return $DB->get_rows($query,$rType);
	}
	/** 
	  * Grabs some of the links from the database with their associated sublinks
	  * @param string $name - link name to search for
	  * @param bool $mainMenu - flag to grab only mainMenu links
	  * @param string $rType - the return type for the links
	  * @param bool $notSubs - switch to turn off the sublink table join
	  * @param bool $access_level - the maximum access level of the links
	  * @returns object - all the found links
	  */
	public static function getSome($name='',$mainMenu=false,$rType="object",$notSubs=false,$access_level=false) {
		$inputFilter = new Filters;
		$name = $inputFilter->text($name);
		//check for malicious input
		if($inputFilter->ERRORS()) { $name=''; }
		if($mainMenu) 
			$WHERE = " WHERE `links`.`mainMenu`=1 AND `links`.`name` LIKE '%$name%' ";
		else
			$WHERE = " WHERE `links`.`name` LIKE '%$name%' ";
		if(!$access_level)
			$access_level = "0";
		$WHERE .= " AND `links`.`access_level` <= '".$access_level."' ";
		//select links with thier associated sublinks
		if(!$notSubs) {
			$sublinkID = ",`sublinks`.`sublink_id`, ";
			$subDetails = "`subDetails`.`name` AS `sub_name`, ". 
					"`subDetails`.`href` AS `sub_href`, ".
					"`subDetails`.`desc` AS `sub_desc`, ".
					"`subDetails`.`weight` AS `sub_weight`, ".
					"`subDetails`.`mainMenu` AS `sub_mainMenu`, ".
					"`subDetails`.`ajaxLink` AS `sub_ajaxLink`, ".
					"`subDetails`.`access_level` AS `sub_access_level` ";
			$JOIN = "LEFT JOIN `sublinks` ON `links`.`link_id`=`sublinks`.`link_id` ".
				     "LEFT JOIN `links` AS `subDetails` ON `sublinks`.`sublink_id`=`subDetails`.`link_id`";
		} else {
			$sublinkID = '';
			$subDetails = '';
			$JOIN = '';
		}
		//compile query
		$query = "SELECT `links`.* ".$sublinkID.$subDetails."FROM `links` ".$JOIN.$WHERE.";";
		//connect to Database
		$DB = new DatabaseConnection;
		//run query and return the result
		return $DB->get_rows($query,$rType);
	}
	/**
	  * Adds a new sublink record in the sublinks table
	  * @param int $link_id - the parent link
	  * @param int $link_id - the child link
	  * @return int - number of rows affected
	  */
	public static function insertSub($link_id, $sublink_id) {
		//check for valid input
		$inputFilter = new Filters;
		$link_id = $inputFilter->number($link_id);
		$sublink_id = $inputFilter->number($sublink_id);
		if($inputFilter->ERRORS()) return "E_DATA";
		
		$query = "INSERT INTO `sublinks`(`link_id`,`sublink_id`) VALUES($link_id,$sublink_id);";
		$DB = new DatabaseConnection;
		return $DB->insert($query);
	}
	/**
	  * deletes a sublink record from the sublinks table
	  * @param int $link_id - the parent link
	  * @param int $link_id - the child link
	  * @return int - number of rows affected
	  */
	public static function deleteSub($link_id,$sublink_id) {
		//check for valid input
		$inputFilter = new Filters;
		$link_id = $inputFilter->number($link_id);
		$sublink_id = $inputFilter->number($sublink_id);
		if($inputFilter->ERRORS()) return "E_DATA";
		
		$query = "DELETE FROM `sublinks` WHERE `link_id`='$link_id' AND `sublink_id`='$sublink_id';";
		$DB = new DatabaseConnection;
		return $DB->delete($query);
	}
}
?>