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
	/** @var 	DB_MySQLi 	$DB 		database object */
	var $DB;
	/** @var 	string 		$json_status	stores the status (success/error) of post manipulation, and any variables to be sent back to javascript */
	var $json_status = NULL;
	/** @var 	int 			$post_id 	 	the post id */
	var $post_id;
	/** @var 	int 			$creator_id  	post creator's id */
	var $creator_id;
	/** @var 	string 		$title 	 	title of this post */
	var $title;
	/** @var 	string 		$html 	 	html for this post */
	var $html;
	/** @var 	date 		$dateTime 	date and time post was created */
	var $createTime;
	/** @var 	date 		$dateTime 	date and time post was last modified */
	var $modTime;
	/** Constructor 
	  * @param 	array 	$userInput 		array filled with user input : if creating a new post pass keys{ title, html }, if updating a post pass keys{ post_id, title, html }
	  * @param 	bool		$newPost			switch to create or update a post
	  * @param 	function 	$newUserCallback 	function that will be called if a new post is successfully added
	  */	
	function __construct($userInput, $newPost=TRUE, $newPostCallback=NULL) {
		//make sure $userInput is an array
		if(!is_array($userInput)) {
			$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
			return;
		}
		//check for valid passed data
		if(!array_keys_exist(array('title','html'),$userInput)) {
				$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
				return;
			}
		//Filter User Input
		$inputFilter = new Filters;
		$this->title = $inputFilter->htmLawed($inputFilter->text($userInput['title']));
		$this->html = $inputFilter->htmLawed($userInput['html']);
		//Check for Errors
		if($inputFilter->ERRORS()) {
			$this->json_status =  json_encode(array('status'=>"E_FILTERS",'title'=>$this->title));		
			return;
		}
		//connect to database
		$this->DB = new DB_MySQLi;
		if($newPost) {
			$this->creator_id = $_SESSION['user_id'];
			//add post
			if($this->addNew()) { //Fire New User Callback if it was passed
				$this->json_status = json_encode(array('status'=>'1','title'=>$this->title));
				if(is_callable($newPostCallback))
					call_user_func($newPostCallback);
				return;
			} else {
				$this->json_status =  json_encode(array('status'=>"E_INSERT",'title'=>$this->title));
				return;
			}
		} else {
			//check for valid passed data
			if(!array_key_exists('post_id',$userInput)) {
				$this->json_status = json_encode(array('status'=>'E_MISSING_DATA'));
				return;
			}
			//Filter id 
			$post_id = $inputFilter->number($userInput['post_id']);
			if($inputFilter->ERRORS()) {
				$this->json_status =  json_encode(array('status'=>"E_FILTERS",'title'=>$this->title));
				return;
			}
			//update post
			if($this->update($post_id)) {
				$this->json_status = json_encode(array('status'=>'1','title'=>$this->title,'modTime'=>date('Y-m-d H:i:s')));
				return;
			} else {
				$this->json_status =  json_encode(array('status'=>"E_UPDATE",'title'=>$this->title));
				return;
			}
		}
	}
	/**
	  * Add a new post to the database, using this objects data
	  * @returns the number of rows affected
	  */
	public function addNew() {
		return $this->DB->insert("INSERT INTO `posts`(`creator_id`,`title`,`html`,`createTime`) VALUES(?,?,?,NOW());",
							'iss',array($this->creator_id, $this->title, $this->html));
	}
	/**
	  * Updates a post in the database
	  * @param int $post_id - the post to update
	  * @returns int - number of rows affected
	  */
	public function update($post_id) {
		return $this->DB->update("UPDATE `posts` SET `title`=?, `html`=?, `modTime`=NOW() WHERE `post_id`=?;",
							  'ssi', array($this->title, $this->html, $post_id));
	}
	/**
	  * Grab a post from the database
	  * @param 	int 		$id 		the user id to get posts for
	  * @param 	string	$title	the title to search for - optional
	  * @param 	string	$title	the id of the post to grab - optional
	  * @param 	string 	$rType 	the return type for the posts
	  * @returns 	mixed	results
	  */
	public static function get($user_id,$title=NULL,$post_id=NULL,$rType="object") {
		//filter input
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		if($title) $title = $inputFilter->alphnum_($title,FALSE,TRUE);
		if($post_id) $post_id = $inputFilter->number($post_id);
		if($inputFilter->ERRORS()) {
			return json_encode(array('status'=>"E_FILTERS",'user_id'=>$user_id,'title'=>$title,'post_id'=>$post_id));
		}
		//connect to Database
		$DB = new DB_MySQLi;
		//set columns to grab
		$columns = "`posts`.`post_id`, `users`.`alias` AS creator, `posts`.`title`, `posts`.`html`, `posts`.`createTime`, `posts`.`modTime`";
		if($post_id) {
			//grab a single post by ID
			return $DB->get_row("SELECT $columns FROM `posts`,`users` WHERE `posts`.`creator_id`=`users`.`user_id` AND `posts`.`post_id`=? LIMIT 1;",
							'i',array($post_id),$rType);
		} else {
		//grab all the user's posts
		$posts = $DB->get_rows("SELECT $columns FROM `posts`,`users` WHERE `posts`.`creator_id`=`users`.`user_id` AND `posts`.`title` LIKE CONCAT('%',?,'%') AND `posts`.`creator_id`=? LIMIT 30;",
							'si',array($title,$user_id),$rType);
		//grab all the posts the user has specific permissions for 
		$otherPosts = $DB->get_rows("SELECT $columns FROM (`posts` JOIN `users` ON `posts`.`creator_id`=`users`.`user_id` AND `posts`.`title` LIKE CONCAT('%',?,'%')) 
								  JOIN `postUserPermissions` ON `posts`.`post_id`=`postUserPermissions`.`post_id`
								    AND `postUserPermissions`.`access_level`&".ACCESS_WRITE.
								    " AND `postUserPermissions`.`user_id`=?;",
								'si',array($title,$user_id),$rType);
		//merge results
		if(is_array($otherPosts))
			return array_merge($posts, $otherPosts);
		else
			return $posts;
		}
	}
	/**
	  * Remove a post from the database
	  * @param 	int	$post_id		the id of the post to delete
	  * @returns 	json_status
	  */
	public static function delete($post_id) {
		//Filter id 
		$inputFilter = new Filters;
		$post_id = $inputFilter->number($post_id);
		if($inputFilter->ERRORS()) {
			return json_encode(array('status'=>"E_FILTERS"));
		}
		//connect to database
		$DB = new DB_MySQLi;
			//turn off mysqli autocommit to process as a transaction
			$DB->mysqli->autocommit(FALSE);
			//remove all sublinks
			$DB->delete("DELETE FROM `postUserPermissions` WHERE `post_id`=?;",
					      'i',array($post_id));
			//remove link
			$DB->delete("DELETE FROM `postGroupPermissions` WHERE `post_id`=?;",
					      'i',array($post_id));
			//delete post
			$DB->delete("DELETE FROM `posts` WHERE `post_id`=?;",
						       'i',array($post_id));
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
	  * Change post user and group permissions
	  * @param 	int 		$post_id 		the post_id to change permissions for
	  * @param 	int 		$id 			the user or group id to add
	  * @param 	int 		$access_level 	the new bitwise permission level - write=2, deny=1
	  * @param 	string 	$U_G 		user or group permissions - should pass 'user' or 'group'
	  * @return 	bool 	the number of rows affected
	  */
	public static function chmod($post_id, $id, $access_level, $U_G="user") {
		if($U_G === 'user') {
			$q['table'] = 'postUserPermissions';
			$q['id'] = 'user_id';
		} else if($U_G ==='group') {
			$q['table'] = 'postGroupPermissions';
			$q['id'] = 'group_id';
		} else return false;
		//filter input
		$inputFilter = new Filters;
		$post_id = $inputFilter->number($post_id);
		$id = $inputFilter->number($id);
		$access_level = $inputFilter->number($access_level);
		if($inputFilter->ERRORS()) {
			$this->json_status =  json_encode(array('status'=>"E_FILTERS"));
			return;
		}
		//connect to database
		$DB = new DB_MySQLi;
		//if setting $access_level to 0, just delete the row
		if($access_level == 0) {
			if($DB->delete("DELETE FROM `".$q['table']."` WHERE `".$q['id']."`=? AND `post_id`=?;",
					     'ii',array($id,$post_id))) {
				$this->json_status = json_encode(array('status'=>'1'));
				return;
			} else
				$this->json_status = json_encode(array('status'=>'E_DELETE'));
				return;
		}
		//check current access state
		if($old_access = $DB->get_row("SELECT `access_level` FROM `".$q['table']."` WHERE `post_id`=? AND `".$q['id']."`=?;",
							      'ii',array($post_id,$id))) {
			//Access Exists, Update access_level
			 if($this->DB->update("UPDATE `".$q['table']."` SET `".$q['id']."`=?, `access_level`=? WHERE `post_id`=?;",
							   'iii',array($id, $access_level, $post_id))) {
				$this->json_status = json_encode(array('status'=>'1'));
				return;
			} else {
				$this->json_status = json_encode(array('status'=>'E_UPDATE'));
				return;
			}
		} else {
			//Access Does Not Exist, Insert new row
			if($this->DB->insert("INSERT INTO `".$q['table']."`(`".$q['id']."`,`post_id`,`access_level`) VALUES(?,?,?);",
						         'iii',array($id,$post_id,$access_level))) {
				$this->json_status = json_encode(array('status'=>'1'));
				return;
			} else {
				$this->json_status = json_encode(array('status'=>'E_INSERT'));
				return;
			}
		}
	}
	/**
	  * Add a new user permission for a post
	  * @param 	int 		$user_id 		the user id to addpermissions for
	  * @param 	int 		$post_id 		the post id to add permissions for
	  * @param 	int 		$access_level	the permission level
	  * @param 	string 	$rType 		the return type for the permissions
	  * @returns 	int		status
	  */
	public static function addUserPerm($user_id, $post_id, $access_level, $rType="object") {
		//filter input
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		$post_id = $inputFilter->number($post_id);
		$access_level = $inputFilter->number($access_level);
		if($inputFilter->ERRORS()) { return json_encode(array('status'=>"E_FILTERS")); }
		//connect to Database
		$DB = new DB_MySQLi;
		//make sure permission does not already exist
		if($existCheck = $DB->get_row("SELECT `user_id` FROM `postUserPermissions` WHERE `user_id`=? AND `post_id`=?;",
					'ii', array($user_id, $post_id))) {
			if($rType  === "json")
				return json_encode(array('status'=>'0'));
			else
				return $existCheck;
		}
		//Add new user permission
		return  $DB->insert("INSERT INTO `postUserPermissions`(`user_id`,`post_id`,`access_level`) VALUES(?,?,?);",
						'iii' ,array($user_id, $post_id, $access_level), $rType);
	}
	/**
	  * Grab a post's User Permissions from the database
	  * @param 	int 		$post_id 		the post id to get permissions for
	  * @param 	string 	$rType 		the return type for the permissions
	  * @returns 	results
	  */
	public static function getUserPerms($post_id=NULL,$rType="object") {
		//filter input
		$inputFilter = new Filters;
		$post_id = $inputFilter->number($post_id);
		if($inputFilter->ERRORS()) { return json_encode(array('status'=>"E_FILTERS")); }
		//connect to Database
		$DB = new DB_MySQLi;
		return  $DB->get_rows("SELECT `perms`.`user_id`, `users`.`alias`, `perms`.`access_level` FROM `postUserPermissions` AS `perms`, `users` 
							WHERE `perms`.`user_id`=`users`.`user_id` AND `perms`.`post_id`=?;",
							   'i' ,array($post_id), $rType);
	}
		/**
	  * Remove a user permission for a post
	  * @param 	int 		$user_id 		the user id to addpermissions for
	  * @param 	int 		$post_id 		the post id to add permissions for
	  * @param 	string 	$rType 		the return type for the permissions
	  * @returns 	bool 	status
	  */
	public static function deleteUserPerm($user_id, $post_id, $rType="object") {
		//filter input
		$inputFilter = new Filters;
		$user_id = $inputFilter->number($user_id);
		$post_id = $inputFilter->number($post_id);
		if($inputFilter->ERRORS()) { return json_encode(array('status'=>"E_FILTERS")); }
		//connect to Database
		$DB = new DB_MySQLi;
		return  $DB->delete("DELETE FROM `postUserPermissions` WHERE `user_id`=? AND `post_id`=?;",
						'ii' ,array($user_id, $post_id), $rType);
	}
 }
?>