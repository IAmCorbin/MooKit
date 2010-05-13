<?
/**
  * contains Link Class
  * @package MooKit
  */
/**
 * A Class for creating a Link
 *
 * Link objects can also contain optional sublink objects
 *
 * @author Corbin Tarrant
 * @birth March 30th, 2010
 * @package MooKit
 */
class Link {
	/** @var 	DB_MySQLi	$DB 		database object */
	var $DB= NULL;
	/** @var 	string 		$json_status	stores the status (success/failure) of user manipulation, to be sent back to javascript */
	var $json_status = NULL;
	/** @var 	string 		$name		link name	*/
	var $name;
	/** @var 	string 		$href		link location	*/
	var $href;
	/** @var 	string 		$desc		optional description */
	var $desc;
	/** @var 	int 			$weight		the link weight - used for display ordering */
	var $weight;
	/** @var 	bool			$ajaxLink		ajax link switch	*/
	var $ajaxLink;
	/** @var 	bool 		$menuLink	menu link switch */
	var $menuLink;
	/** @var 	int 			$access_level 	the access_level required to use the link */
	var $access_level;
	/** @var 	array 		$sublinks		an array of Link objects */
	var $sublinks;
	
	/**
	  * Constructor
	  *
	  * @param 	array	$userInput	array containing keys { name, href, desc, weight, ajaxLink, menuLink, access_level }
	  * @param 	array 	$sublinks		optional array of sublinks
	  */
	public function __construct($userInput, $sublinks=NULL) {
		//make sure $userInput is an array
		if(!is_array($userInput)) {
			$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
			return;
		}
		
		//make sure these keys exist and are not blank
		if(!array_keys_exist(array("name","href"),$userInput, FALSE,TRUE)) {
			$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
			return;
		}
		//if required keys passed, set any other missing to empty strings
		array_keys_exist(array("name","href","desc","weight","ajaxLink","menuLink","access_level"), $userInput, TRUE);
		
		//filter input and set object data
		$inputFilter = new Filters();
		$this-> name = $inputFilter->text($userInput['name']);
		$this->href = $inputFilter->text($userInput['href']);
		$this->desc = $inputFilter->text($userInput['desc'],false,true); //allow blank field
		if($userInput['weight'] != '') $this->weight = $inputFilter->number($userInput['weight']);
			else $this->weight = 0;
		if($userInput['ajaxLink'] != '') $this->ajaxLink =  $inputFilter->number($userInput['ajaxLink']);
		if($userInput['menuLink'] != '') $this->menuLink = $inputFilter->number($userInput['menuLink']);
		if($userInput['access_level'] != '') $this->access_level = $inputFilter->number($userInput['access_level']);
			else $this->access_level = 0;
		//check for errors and set status
		if($inputFilter->ERRORS()) {
			$this->json_status = json_encode(array('status'=>"E_FILTERS",'name'=>$this->name,'href'=>$this->href,'desc'=>$this->desc,'weight'=>$this->weight));
			return;
		}
		//set sublinks if passed
		if($sublinks)
			$this->sublinks = $sublinks;
		else
			$this->sublinks = array();
	}
	/** 
	  * Create a new SubLink and adds it to $this->sublinks
	  * @param 	array	$userInput	array containing keys { name, href, desc, weight, ajaxLink, menuLink, access_level }
	  * @param	array	$sublinks		optional array of sublinks
	  */
	public function addSub($userInput, $sublinks=NULL) {
		array_push($this->sublinks,new Link($userInput,$sublinks));
	}
	/**
	  * Add this link to the database
	  */
	public function insert() {
		//establish database connection
		$this->DB = new DB_MySQLi;
		//set type of link
		if($this->ajaxLink) $ajaxLink = 1; else $ajaxLink =0;
		if($this->menuLink) $menuLink = 1; else $menuLink = 0;
		//attempt insert
		if($this->DB->insert("INSERT INTO `links`(`name`,`href`,`desc`,`ajaxLink`,`menuLink`,`weight`,`access_level`) 
						  VALUES(?,?,?,?,?,?,?);",
						  'sssiiii',array($this->name,$this->href,$this->desc,$ajaxLink,$menuLink,$this->weight,$this->access_level))) {
			$this->json_status = json_encode(array('status'=>'1','name'=>$this->name,'href'=>$this->href,'desc'=>$this->desc,'ajaxLink'=>$ajaxLink,'menuLink'=>$menuLink,'weight'=>$this->weight,'access_level'=>$this->access_level));
			return true;
		} else {
			$this->json_status =  json_encode(array('status'=>'E_INSERT'));
			return false;
		}
	}
	/**
	  * Updates a link in the database
	  * @param 	int 	$link_id 		the link to update
	  * @returns 	int 	number of rows affected
	  */
	public function update($link_id) {
		//check for valid id passed
		if(preg_match('/[^0-9]/',$link_id))
			$this->json_status = json_encode(array('status'=>"E_ID"));
		//establish database connection
		$this->DB = new DB_MySQLi;
		//set type of link
		if($this->ajaxLink) $ajaxLink = 1; else $ajaxLink = 0;
		if($this->menuLink) $menuLink = 1; else $menuLink = 0;
		//attempt update
		if($this->DB->update("UPDATE `links` SET `name`=?, `href`=?, `desc`=?, `ajaxLink`=?, `menuLink`=?, `weight`=?, `access_level`=? 
						   WHERE `link_id`=?;",
						   'sssiiiii',array($this->name, $this->href, $this->desc, $ajaxLink, $menuLink, $this->weight, $this->access_level, $link_id))) {
			$this->json_status = json_encode(array('status'=>'1','name'=>$this->name,'href'=>$this->href,'desc'=>$this->desc,'ajaxLink'=>$ajaxLink,'menuLink'=>$menuLink,'weight'=>$this->weight,'access_level'=>$this->access_level));
			return true;
		} else {
			$this->json_status =  json_encode(array('status'=>'E_UPDATE'));
			return false;
		}
	}
	/**
	  * Removes a link from the database
	  * @param 	int 	$link_id - 	the link to remove
	  * @returns 	int 	the number of rows affected
	  */
	public static function delete($link_id) {
		//check for valid input and return error if not valid
		$inputFilter = new Filters;
		$inputFilter->number($link_id);
		if($inputFilter->ERRORS()) return json_encode(array('status'=>"E_FILTER"));
		//establish database connection
		$DB = new DB_MySQLi;
			//turn off mysqli autocommit to process as a transaction
			$DB->mysqli->autocommit(FALSE);
			//remove all sublinks
			$DB->delete("DELETE FROM `sublinks` WHERE `link_id`=?;",
					      'i',array($link_id));
			//remove link
			$DB->delete("DELETE FROM `links` WHERE `link_id`=?;",
					      'i',array($link_id));
			//rollback or commit
			if($DB->STATUS !== "1") {
				$DB->mysqli->rollback();
			} else if($DB->STATUS === "1")
				$DB->mysqli->commit();
		//close the database connection
		$DB->close();
		return json_encode(array('status'=>$DB->STATUS));
	}
	/** 
	  * Grabs some of the links from the database with their associated sublinks
	  * @param 	string 	$name 		link name to search for
	  * @param 	bool 	$menuLink 	flag to grab only menu links
	  * @param 	string 	$rType 		the return type for the links
	  * @param 	bool 	$notSubs 		switch to turn off the sublink table join
	  * @param 	bool 	$access_level 	the maximum access level of the links to grab
	  * @returns 	mixed 	all the found links
	  */
	public static function get($name='',$menuLink=FALSE,$rType="object",$notSubs=FALSE,$access_level=FALSE) {
		$inputFilter = new Filters;
		//connect to Database
		$DB = new DB_MySQLi;
		//filter $name
		$name = $inputFilter->text($name,FALSE,TRUE);
		if($inputFilter->ERRORS()) { $name=''; }
		if(!$access_level) $access_level = "0";
		if($menuLink)
			$WHERE = " WHERE `links`.`menuLink`=1 AND `links`.`name` LIKE CONCAT('%',?,'%') ";
		else
			$WHERE = " WHERE `links`.`name` LIKE CONCAT('%',?,'%') ";		
		$WHERE .= " AND `links`.`access_level` <= ? ";
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
		return $DB->get_rows($query,
						    'si',array($name,$access_level),$rType);
	}
	/**
	  * Adds a new sublink record in the sublinks table
	  * @param 	int 	$link_id 		the parent link
	  * @param 	int 	$link_id 		the child link
	  * @return 	int 	number of rows affected
	  */
	public static function insertSub($link_id, $sublink_id) {
		//check for valid input
		$inputFilter = new Filters;
		$link_id = $inputFilter->number($link_id);
		$sublink_id = $inputFilter->number($sublink_id);
		if($inputFilter->ERRORS()) return "E_FILTER";
		
		$DB = new DB_MySQLi;
		$DB->insert("INSERT INTO `sublinks`(`link_id`,`sublink_id`) VALUES(?,?);",
				     'ii',array($link_id,$sublink_id));
		return json_encode(array('status'=>$DB->STATUS));
	}
	/**
	  * deletes a sublink record from the sublinks table
	  * @param	int	$link_id 		the parent link
	  * @param	int	$link_id 		the child link
	  * @return	int 	number of rows affected
	  */
	public static function deleteSub($link_id,$sublink_id) {
		//check for valid input
		$inputFilter = new Filters;
		$link_id = $inputFilter->number($link_id);
		$sublink_id = $inputFilter->number($sublink_id);
		if($inputFilter->ERRORS()) return "E_FILTER";
		
		$DB = new DB_MySQLi;
		$DB->delete("DELETE FROM `sublinks` WHERE `link_id`=? AND `sublink_id`=?;",
		  		      'ii',array($link_id,$sublink_id));
		return json_encode(array('status'=>$DB->STATUS));
	}
}
?>