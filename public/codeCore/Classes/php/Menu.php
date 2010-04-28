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
	  * @param string $desc		link description
	  * @param string $weight	link weight
	  * @param string $ajax		ajax link class
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function add($name, $href, $desc, $weight,$ajax=NULL, $sublinks=NULL) {
		array_push($this->links,new Link($name,$href,$desc,$weight,$ajax,$sublinks));
	}
	/**
	  * Add a new SubLink to the last created Link
	  * @param string $name		link name
	  * @param string $href		link location	
	  * @param string $desc		link description
	  * @param string $weight	link weight
	  * @param string $ajax		ajax link class
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function addSub($name, $href,$desc,$weight, $ajax=NULL, $sublinks=NULL) {
		$this->links[sizeof($this->links)-1]->addSub($name, $href, $desc, $weight, $ajax, $sublinks);
	}
	/**
	  * Sort all the links and sublinks by weight
	  */
	public function sortWeight() {
		
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
			<? if(isset($link->desc))	echo "<span class=\"linkDesc\">$link->desc</span>";
			if(isset($link->sublinks))  { ?>
				<?	foreach($link->sublinks as $sublink): /* <!-- Optional Sublinks --> */ ?>
					<<? echo $sublinkContainer?>>
						<a class="<? echo $sublinkClass?> <? if(isset($sublink->ajax)) { echo $sublink->ajax; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $sublink->href ?>"><?=$sublink->name ?></a>
						<? if(isset($sublink->desc))	echo "<span class=\"linkDesc\">$sublink->desc</span>"; ?>
					</<? echo $sublinkContainer?>>
				 <? endforeach;//$links->sublinks' ?> 
			<? } ?> 
		</<? echo $linkContainer ?>>
	<? endforeach; //$links->links  
	
	}
	/** 
	  * Create the main menu from database tables
	  * @param string $ajax -  the CSS class for ajax links
	  * @param string $ajax -  the CSS class for ajax sublinks
	  */
	public static function buildMain($ajax='ajaxLink',$subAjax='ajaxLink') {
		//grab links
		$access_level = Security::clearance();
		$menuLinks = Link::getSome('',TRUE,"object",false,$access_level);
		//create Menu object
		$mainMenu = new Menu;
		//store last link id to determine if this is a sublink row
		$lastLink_id = null;
		foreach($menuLinks as $link) {
			//set ajax link class if flagged
			if($link->ajaxLink)  $link->ajax = $ajax;  else $link->ajax=NULL;
			//avoid double display of links
			if($link->link_id != $lastLink_id) {
				//add this link to the menu
				$mainMenu->add($link->name,$link->href,$link->desc,$link->weight,$link->ajax);
				//add first sublink
				if($link->sublink_id) {
					//set ajax link class if flagged
					if($link->sub_ajaxLink)  $link->sub_ajaxLink = $subAjax;  else $link->sub_ajaxLink=NULL;
					//add first sublink
					$mainMenu->addSub($link->sub_name,$link->sub_href,$link->sub_desc,$link->sub_weight,$link->sub_ajaxLink);
				}
			} else {
				//set ajax link class if flagged
				if($link->sub_ajaxLink)  $link->sub_ajaxLink = $subAjax;  else $link->sub_ajaxLink=NULL;
				//add subsequent sublink
				$mainMenu->addSub($link->sub_name,$link->sub_href,$link->sub_desc,$link->sub_weight,$link->sub_ajaxLink);
			}
			$lastLink_id = $link->link_id;
		}
		$mainMenu->sortWeight();
		return $mainMenu;
	}
}
?>