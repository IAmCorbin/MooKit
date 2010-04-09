<?php
/**
  * contains Template Class
  * @package MooKit
  */
/**
 * A very simple PHP Template Class
 *
 * Allows for seperation of presentation logic and data gathering logic
 *
 * @author Corbin Tarrant
 * @author adapted from {@link http://seanhess.net/posts/simple_templating_system_in_php }
 * @copyright March 6th, 2010
 * @package MooKit
 */
class Template {
	/** @var array $vars variables to xfer */
	var $vars;
	/** @var string $path path to template file */
	var $path;
	/** @var string $result result of template merge */
	var $result;
	/** @var string $parent parent template */
	var $parent;
	/** @var bool $gzip gzip encoding flag */
	var $gzip = false;
	
	/**
	 * Constructor
	 *
	 * @param string $path path to the template file you want to load
	 * @param array $vars array of  (key=>value) variables
	 * @param bool $gzip set gzip encoding on/off
	 */
	public function __construct($path=false, $vars=false, $gzip=false) {
		$this->vars = ($vars === false) ? array() : $vars;
		$this->extract($vars);
		$this->path($path);
		if($gzip)
			$this->gzip = true;
	}
	/**
	 * Magic PHP __toString 
	 *
	 * @returns the merged template
	 */
	public function __toString() {
		return $this->run();
	}
	/** take an array of (key=>value) and set to $this->vars */
	public function extract($vars) {
		if($vars)
			foreach ($vars as $property => $value)
				$this->vars[$property] = $value;
	}
	/** set a parent template */
	public function parent($parent) {
		$this->parent = $parent;
	}
	/** Set the path to the template */
	public function path($path) {
		$this->path = $path;
	}
	/** Magic PHP __set */
	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	/** Magic PHP __get 
	 * @returns array|string
	*/
	public function __get($name) {
		return isset($this->vars[$name]) ? $this->vars[$name] : "";
	}
	/** 
	 * Merge a parent template's variables to this template's scope
	 * @returns array if this template has a parent this returns the merged $vars arrays, otherwise it returns this templates $vars
	 */
	public function mergevars()	{
		if (isset($this->parent))
			return array_merge($this->parent->mergevars(), $this->vars);
		else
			return $this->vars;
	}
	/**
	 * The Main Event
	 *
	 * Merge the vars with the template {and encode }
	 * @returns the results of the var and template merge { gziped }
	 */
	public function run()	{
		//start output buffering
		ob_start();
		//merge variables recursively and set to current scope
		extract ($this->mergevars());
		//include the template file now that variables are setup in this scope
		include $this->path;
		//store the result of the template file merged with data
		$this->result = ob_get_contents();
		//Clean (erase) the output buffer and turn off output buffering
		ob_end_clean();
		//if gzip encoding turned on for this template, encode now
		if($this->gzip === true) {
			//gzip encode if browser supports
			if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE) {
				$this->result = gzencode($this->result);
				header('Content-Encoding: gzip');
			}
		}
		//return the results of the merge for this template
		return $this->result;
	}
}
?>