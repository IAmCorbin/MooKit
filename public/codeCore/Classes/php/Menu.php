<?php
/**
  * contains Menu Class and Link Class
  * @package MooKit
  */
/**
 * A Class for creating a Menu
 *
 * Creates an array of link objects with optional sublink objects
 *
 * @author Corbin Tarrant
 * @copyright March 30th, 2010
 * @package MooKit
 */
class Menu {
	/** @var array $links	an array of Link objects	*/
	var $links;
	
	/**
	 * Constructor
	 *
	 * @param string $path
	 */
	public function __construct($links=NULL) {
		if($links)
			$this->links = $links;
		else
			$this->links = array();
	}
	/** 
	  * Create a new Link
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $ajax		ajax link switch
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function add($name, $href, $ajax=NULL, $sublinks=NULL) {
		array_push($this->links,new Link($name,$href,$ajax,$sublinks));
	}
	/**
	  * Add a new SubLink to the last created Link
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $ajax		ajax link switch
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function addSub($name, $href, $ajax=NULL, $sublinks=NULL) {
		$this->links[sizeof($this->links)-1]->add($name, $href, $ajax, $sublinks);
	}
	/**
	  * Output all the links and sublinks in formatted html
	  * @param string $linkContainer		must be a valid html element, these will hold the links
	  * @param string $sublinkContainer	must be a valid html element, these will hold the sublinks
	  * @param string $linkClass			CSS class for links
	  * @param string $sublinkClass		CSS class for sublinks
	  */
	public function output($linkContainer="div",$sublinkContainer="span",$linkClass='link',$sublinkClass="sublink") {
		foreach($this->links as $link): ?>
		<<?echo $linkContainer ?>>
			<a class="<?=$linkClass?> <? if(isset($link->ajax)) { echo $link->ajax; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $link->href; ?>"><? echo $link->name; ?></a>
			<? if(isset($link->sublinks))  { ?>
			<div>
				<!-- Optional Sublinks -->
				<?	foreach($link->sublinks as $sublink): ?>
					<<? echo $sublinkContainer?>>
						<a class="<? echo $sublinkClass?> <? if(isset($sublink->ajax)) { echo $sublink->ajax; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $sublink->href ?>"><?=$sublink->name ?></a>
					</<? echo $sublinkContainer?>>
				 <? endforeach;//$links->sublinks' ?> 
			 </div>
			<? } ?> 
		</<? echo $linkContainer ?>>
	<? endforeach; //$links->links  
	
	}
}
/**
 * A Class for creating a Link
 *
 * Creates an array of link objects with optional sublink objects
 *
 * @author Corbin Tarrant
 * @copyright March 30th, 2010
 * @package MooKit
 */
class Link {
	/** @var string $name	link name	*/
	var $name;
	/** @var string $href	link location	*/
	var $href;
	/** @var string $ajax	ajax link switch	*/
	var $ajax;
	/** @var Array $href	sublinks, an array of Link objects */
	var $sublinks;
	
	/**
	  * Constructor
	  *
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $ajax		ajax link switch
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function __construct($name,$href,$ajax=NULL,$sublinks=NULL) {
		$this->name = $name;
		$this->href = $href;
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
	  * @param string $ajax		ajax link switch
	  */
	public function add($name, $href, $ajax=NULL) {
		array_push($this->sublinks,new Link($name,$href,$ajax));
	}
	/**
	  * Return link as an associative array
	  * @returns array
	  */
	public function getLink() {
		return get_object_vars($this);
	}
	/**
	 * Magic PHP __toString 
	 *
	 * @returns the merged template
	 */
	public function __toString() {
		
	}
}
?>