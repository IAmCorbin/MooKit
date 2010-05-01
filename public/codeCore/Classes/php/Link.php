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
	/** @var string $name		link name	*/
	var $name;
	/** @var string $href		link location	*/
	var $href;
	/** @var string $desc		optional description */
	var $desc;
	/** @var int $weight		the link weight - used for display ordering */
	var $weight;
	/** @var string $ajax		ajax link class	*/
	var $ajaxLink;
	/** @var bool $menuLink		is this a menu link? */
	var $menuLink;
	/** @var int $access_level 	the access_level required to use the link */
	var $access_level;
	/** @var Array $sublinks		an array of Link objects */
	var $sublinks;
	/** @var string $status    		holds 1 or database errors */	
	var $status = "1";
	var $ERROR = NULL;
	
	/**
	  * Constructor
	  *
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $desc 		optional description
	  * @param int $weight		the link weight - used for display ordering
	  * @param string $ajax		ajax link class
	  * @param bool $menuLink 	is this a menu link?
	  * @param int $access_level 	the access_level required to use the link
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function __construct($name, $href, $desc=NULL, $weight=0, $ajaxLink=NULL, $menuLink=0, $access_level=0, $sublinks=NULL) {
		//filter input and set object data
		$inputFilter = new Filters();
		$this-> name = $inputFilter->text($name);
		$this->href = $inputFilter->text($href);
		$this->desc = $inputFilter->text($desc,false,true); //allow blank field
		$this->weight = $inputFilter->number($weight);
		if($ajaxLink != '') $this->ajaxLink =  $inputFilter->number($ajaxLink);
		if($menuLink != '') $this->menuLink = $inputFilter->number($menuLink);
		$this->access_level = $inputFilter->number($access_level);
		//check for errors and set status
		if($inputFilter->ERRORS()) {
			if(DEBUG) { echo $this->name; var_dump($inputFilter->errors); }
			$this->status = $this->ERROR = "E_FILTER";
		}
		//set sublinks if passed
		if($sublinks)
			$this->sublinks = $sublinks;
		else
			$this->sublinks = array();
	}
	/** 
	  * Create a new SubLink and adds it to $this->sublinks
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $desc		link description	
	  * @param int $weight		the link weight - used for display ordering
	  * @param string $ajaxLink	ajax link switch
	  * @param bool $menuLink 	is this a menu link?
	  * @param int $access_level 	the access_level required to use the link
	  */
	public function addSub($name, $href, $desc=NULL, $weight=0, $ajaxLink=NULL, $menuLink=0, $access_level=0, $sublinks=NULL) {
		array_push($this->sublinks,new Link($name,$href,$desc,$weight,$ajaxLink,$menuLink,$access_level,$sublinks));
	}
	/**
	  * Add this link to the database
	  */
	public function insert() {
		//if there is an error, do not attempt insert
		if($this->ERROR) return $this->ERROR;
		//establish database connection
		$this->DB = new DatabaseConnection;
		//place query variables into array for escaping
		$q = array('name'=>$this->name, 'href'=>$this->href, 'desc'=>$this->desc);
		//escape
		$q = $this->DB->escapeStrings($q);
		if($this->ajaxLink) $ajaxLink = 1; else $ajaxLink =0;
		if($this->menuLink) $menuLink = 1; else $menuLink = 0;
		//build query
		$query = "INSERT INTO `links`(`name`,`href`,`desc`,`ajaxLink`,`menuLink`,`weight`,`access_level`) VALUES('".$q['name']."','".$q['href']."','".$q['desc']."','$ajaxLink','$menuLink','$this->weight','$this->access_level');";

		//attempt insert
		if(!$this->status = $this->DB->insert($query))
			$this->status = $this->ERROR = "E_INSERT";
		return $this->status;
	}
	/**
	  * Updates a link in the database
	  * @param int $link_id - the link to update
	  * @returns int - number of rows affected
	  */
	public function update($link_id) {
		//check for valid id passed
		if(preg_match('/[^0-9]/',$link_id))
			$this->status = "E_ID";
		//if there is an error, do not attempt update
		if($this->ERROR) return $this->status;
		//establish database connection
		$this->DB = new DatabaseConnection;
		//place query variables into array for escaping
		$q = array('name'=>$this->name, 'href'=>$this->href, 'desc'=>$this->desc);
		//escape
		$q = $this->DB->escapeStrings($q);
		if($this->ajaxLink) $ajaxLink = 1; else $ajaxLink = 0;
		if($this->menuLink) $menuLink = 1; else $menuLink = 0;
		//build query
		$query = "UPDATE `links` SET `name`='".$q['name']."', `href`='".$q['href']."', `desc`='".$q['desc']."', `ajaxLink`=$ajaxLink, `menuLink`=$menuLink, `weight`=".(int)$this->weight.", `access_level`=".(int)$this->access_level." WHERE `link_id`=".(int)$link_id.";";
		//attempt update
		if(!$this->status = $this->DB->update($query)) {
			$this->status = $this->ERROR = "E_UPDATE";
		}
		return $this->status;
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
		if($inputFilter->ERRORS()) return "E_FILTER";
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
	  * Grabs some of the links from the database with their associated sublinks
	  * @param string $name - link name to search for
	  * @param bool $menuLink - flag to grab only menu links
	  * @param string $rType - the return type for the links
	  * @param bool $notSubs - switch to turn off the sublink table join
	  * @param bool $access_level - the maximum access level of the links
	  * @returns object - all the found links
	  */
	public static function get($name='',$menuLink=FALSE,$rType="object",$notSubs=FALSE,$access_level=FALSE) {
		$inputFilter = new Filters;
		//connect to Database
		$DB = new DatabaseConnection;
		//filter and escape $name
			$name = $inputFilter->text($name);
			if($inputFilter->ERRORS()) { $name=''; }
			$name = $DB->escapeString($name);
		if($menuLink) 
			$WHERE = " WHERE `links`.`menuLink`=1 AND `links`.`name` LIKE '%$name%' ";
		else
			$WHERE = " WHERE `links`.`name` LIKE '%$name%' ";
		if(!$access_level)
			$access_level = "0";
		$WHERE .= " AND `links`.`access_level` <= '".$access_level."' ";
		//select links with thier associated sublinks
		if(!$notSubs) {
			$JOIN = "LEFT JOIN `sublinks` ON `links`.`link_id`=`sublinks`.`link_id` ".
				     "LEFT JOIN `links` AS `subDetails` ON `sublinks`.`sublink_id`=`subDetails`.`link_id`";
			$sublinkID = ",`sublinks`.`sublink_id`, ";
			$subDetails = "`subDetails`.`name` AS `sub_name`, ". 
					"`subDetails`.`href` AS `sub_href`, ".
					"`subDetails`.`desc` AS `sub_desc`, ".
					"`subDetails`.`weight` AS `sub_weight`, ".
					"`subDetails`.`menuLink` AS `sub_menuLink`, ".
					"`subDetails`.`ajaxLink` AS `sub_ajaxLink`, ".
					"`subDetails`.`access_level` AS `sub_access_level` ";
		} else {
			$JOIN = '';
			$sublinkID = '';
			$subDetails = '';
		}
		//compile query
		$query = "SELECT `links`.* ".$sublinkID.$subDetails."FROM `links` ".$JOIN.$WHERE.";";
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
		if($inputFilter->ERRORS()) return "E_FILTER";
		
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
		if($inputFilter->ERRORS()) return "E_FILTER";
		
		$query = "DELETE FROM `sublinks` WHERE `link_id`='$link_id' AND `sublink_id`='$sublink_id';";
		$DB = new DatabaseConnection;
		return $DB->delete($query);
	}
}
?>