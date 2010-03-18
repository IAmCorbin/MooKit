<?php
if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 
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
	/** @var string $title title of this post */
	var $title;
	/** @var string $html html for this post */
	var $html;
	/** @var string $dateTime date and time post was created */
	var $createTime;
	/** @var string $dateTime date and time post was last modified */
	var $modTime;
	/** @var string $error status */
	var $error = NULL;	
	/** Constructor */	
	function __construct($title,$html,$createTime=NULL,$modTime=NULL) {
		//set post data
		$this->title = $title;
		$this->html = $html;
		$this->createTime = $createTime;
		$this->modTime = $modTime;
		//Filter User Input
		$inputFilter = new Filters;
		$this->title = $inputFilter->text($this->title);
		$this->title = $inputFilter->htmLawed($this->title); 
		$this->html = $inputFilter->htmLawed($this->html);
		//Check for Errors
		if($errors = $inputFilter->ERRORS())
			$error = 'ERROR_FILTER';		
		//prepare for query		
	}
	/** Magic PHP __get 
	 * @returns array|string
	*/
	public function __get($name) {
		return isset($this->$name) ? $this->$name : "";
	}
	/** Magic PHP __set */
	public function __set($name, $value) {
		$this->$name = $value;
	}
	/** Return the error status of the post 
	 * @returns string error status
	 */	
	public function errorStatus() { return $error; }
 }
 
 
 }
 ?>