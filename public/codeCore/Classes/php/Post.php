<?php
/**
  * contains Post Class
  * @package MooKit
  */
/**
 * Post Class
 *
 * A Class representing a set of related html elements that serve a certain purpose (blog post, static page, game, video, etc...)
 * 
 * @author Corbin Tarrant
 * @copyright March 16th, 2010
 * @link http://www.IAmCorbin.net
 * @package MooKit
 */
 class Post {
	/** @var DatabaseConnection $DB database object */
	var $DB;
	/** @var int $post_id - the post id */
	var $post_id;
	/** @var int $creator_id - post creator's id */
	var $creator_id;
	/** @var string $title - title of this post */
	var $title;
	/** @var string $html - html for this post */
	var $html;
	/** @var date $dateTime - date and time post was created */
	var $createTime;
	/** @var date $dateTime - date and time post was last modified */
	var $modTime;
	/** @var string $error status */
	var $error = NULL;	
	/** Constructor 
	  * @param int $post_id - only pass this if retrieving an existing post
	  * @param string $title - post title
	  * @param string $html - post html
	  */	
	function __construct($title=NULL,$html=NULL) {
		//Filter User Input
		$inputFilter = new Filters;
		$this->title = $inputFilter->text($this->title);
		$this->title = $inputFilter->htmLawed($this->title); 
		$this->html = $inputFilter->htmLawed($this->html);
		//Check for Errors
		if($inputFilter->ERRORS())
			$this->error = 'E_FILTER';		
		//set post data
		$this->creator_id = $_SESSION['user_id'];
		$this->title = $title;
		$this->html = $html;
		//add post
		if(!$this->addNew())
			$this->error = 'E_INSERT';
	}
	/**
	  * Add a new post to the database, using this objects data
	  * @returns the number of rows affected
	  */
	public function addNew() {
		$creator_id = mysqli_real_escape_string($this->creator_id);
		$title = mysqli_real_escape_string($this->title);
		$html = mysqli_real_escape_string($this->html);
		$query = "INSERT INTO `posts`(`creator_id`,`title`,`html`,`createTime`) VALUES($creator_id,$title,$html,NOW());";
		return $this->DB->insert($query);
	}
	/**
	  * Updates a post in the database
	  * @param int $post_id - the post to update
	  * @returns int - number of rows affected
	  */
	public function update($post_id) {
		$post_id = mysqli_real_escape_string($post_id);
		$title = mysqli_real_escape_string($this->title);
		$html = mysqli_real_escape_string($this->html);
		$query = "UPDATE `posts` SET `title`='$title' `html`='$html' `modTime`=NOW() WHERE `post_id`='$post_id';";
		return $this->DB->update($query);
	}
	/**
	  * Remove a post from the database
	  */
	public function delete($post_id) {
		$post_id = mysqli_real_escape_string($post_id);
		$query = "DELETE FROM `posts` WHERE `post_id`='$post_id';";
		return $this->DB->delete($query);
	}
	/**
	  * Grab a post from the database
	  * @param 	int 		$id 		the user id to get posts for
	  * @param 	string	$title	the title to search for - optional
	  * @param 	string 	$rType 	the return type for the posts
	  * @returns 	bool 	true on success, false on failure
	  */
	public static function get($user_id,$title,$rType="object") {
		//filter input
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		$title = $inputFilter->alphnum_($title,FALSE,TRUE);
		if($inputFilter->ERRORS()) { return "E_FILTER"; }
		//connect to Database
		$DB = new DatabaseConnection;
		//escape variables for query
		$q = $DB->escapeStrings(array('user_id'=>$user_id,'title'=>$title));
		$query = "SELECT `post_id`, `title`, `creator_id`,`createTime`,`modTime` FROM `posts` WHERE `title` LIKE '%".$q['title']."%' AND `creator_id`='".$q['user_id']."';";
		return $DB->get_rows($query,$rType);
	}
	/**
	  * add a new permission to this post
	  * @param string $U_G user or group permissions - should pass 'user' or 'group'
	  * @param int $id - the user or group id to add
	  * @param int $access_level - the permission level
	  * @return int - the number of rows affected
	  */
	public function addPermission($U_G, $id, $access_level) {
		if($U_G === 'user')
			$table = 'postUserPermissions';
		else if($U_G ==='group')
			$table = 'postGroupPermissions';
		else return false;
		$U_G = mysqli_real_escape_string($U_G);
		$id = mysqli_real_escape_string($id);
		$post_id = mysqli_real_escape_string($this->post_id);
		$access_level = mysqli_real_escape_string($access_level);
		$query = "INSERT INTO `$table`(`".$U_G."_id`,`post_id`,`access_level`) VALUES('$id','$post_id','$access_level');";
		return $this->DB->insert($query);
	}
	/**
	  * Modify a permission for this post
	  * @param string $U_G user or group permissions - should pass 'user' or 'group'
	  * @param int $id - the user or group id to mod
	  * @param int $access_level - the new permission level
	  * @return int - the number of rows affected
	  */
	public function changePermission($U_G, $id, $access_level) {
		if($U_G === 'user')
			$table = 'postUserPermissions';
		else if($U_G ==='group')
			$table = 'postGroupPermissions';
		else return false;
		$U_G = mysqli_real_escape_string($U_G);
		$id = mysqli_real_escape_string($id);
		$post_id = mysqli_real_escape_string($this->post_id);
		$access_level = mysqli_real_escape_string($access_level);
		$query = "UPDATE `$table` SET `".$U_G."_id`='$id' `access_level`='$access_level' WHERE `post_id`='$post_id';";
		return $this->DB->update($query);
	}
	/**
	  * Remove a permission from this post
	  * @param string $U_G user or group permissions - should pass 'user' or 'group'
	  * @return int - number of rows affected
	  */
	public function removePermission($U_G, $group_id) {
		if($U_G === 'user')
			$table = 'postUserPermissions';
		else if($U_G ==='group')
			$table = 'postGroupPermissions';
		else return false;
		$U_G = mysqli_real_escape_string($U_G);
		$id = mysqli_real_escape_string($id);
		$post_id = mysqli_real_escape_string($this->post_id);
		$query = "DELETE FROM `$table` WHERE `".$U_G."_id`='$id' AND `post_id`='$post_id';";
		return $this->DB->delete($query);
	}
	/** Return the error status of the post 
	 * @returns string error status
	 */	
	public function errorStatus() { return $this->error; }
 }
?>