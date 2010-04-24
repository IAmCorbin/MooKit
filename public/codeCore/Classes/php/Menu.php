<?php
/**
  * contains Menu Class
  * @package MooKit
  */
/**
 * A Class for creating a Menu
 *
 * Creates an array of link objects with optional sublink objects
 *
 * @author Corbin Tarrant
 * @birth March 30th, 2010
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
	  * @param string $ajax		ajax link class
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function add($name, $href, $ajax=NULL, $sublinks=NULL) {
		array_push($this->links,new Link($name,$href,$ajax,$sublinks));
	}
	/**
	  * Add a new SubLink to the last created Link
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $ajax		ajax link class
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function addSub($name, $href, $ajax=NULL, $sublinks=NULL) {
		$this->links[sizeof($this->links)-1]->addSub($name, $href, $ajax, $sublinks);
	}
	/**
	  * Output all the links and sublinks in formatted html
	  * @param string $linkClass			CSS class for links
	  * @param string $sublinkClass		CSS class for sublinks
	  * @param string $linkContainer		must be a valid html element, these will hold the links
	  * @param string $sublinkContainer	must be a valid html element, these will hold the sublinks
	  */
	public function output($linkClass='link',$sublinkClass="sublink",$linkContainer="div",$sublinkContainer="span") {
		foreach($this->links as $link): ?>
		<<?echo $linkContainer ?>>
			<a class="<?=$linkClass?> <? if(isset($link->ajax)) { echo $link->ajax; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $link->href; ?>"><? echo $link->name; ?></a>
			<? if(isset($link->desc))	echo "<span>$link->desc</span>";
			if(isset($link->sublinks))  { ?>
				<?	foreach($link->sublinks as $sublink): /* <!-- Optional Sublinks --> */ ?>
					<<? echo $sublinkContainer?>>
						<a class="<? echo $sublinkClass?> <? if(isset($sublink->ajax)) { echo $sublink->ajax; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $sublink->href ?>"><?=$sublink->name ?></a>
						<? if(isset($sublink->desc))	echo "<span>$sublink->desc</span>"; ?>
					</<? echo $sublinkContainer?>>
				 <? endforeach;//$links->sublinks' ?> 
			<? } ?> 
		</<? echo $linkContainer ?>>
	<? endforeach; //$links->links  
	
	}
}
?>