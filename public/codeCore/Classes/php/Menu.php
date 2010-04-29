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
	  * @param string $name		name
	  * @param string $href		location	
	  * @param string $desc		description
	  * @param int $weight		weight
	  * @param bool $ajaxLink	ajax flag
	  * @param bool $ajaxLink	menu flag
	  * @param int $access_level	access level
	  * @param Array $sublinks	optional array of sublinks
	  */
	public function add($name, $href, $desc=NULL, $weight=0,$ajaxLink=NULL, $menuLink=0, $access_level=0, $sublinks=NULL) {
		array_push($this->links,new Link($name,$href,$desc,$weight,$ajaxLink,$menuLink,$access_level,$sublinks));
	}
	/**
	  * Add a new SubLink to the last created Link
	  * @see $this->add
	  */
	public function addSub($name, $href, $desc=NULL, $weight=0,$ajaxLink=NULL, $menuLink=0, $access_level=0, $sublinks=NULL) {
		$this->links[sizeof($this->links)-1]->addSub($name, $href, $desc, $weight, $ajaxLink, $menuLink, $access_level, $sublinks);
	}
	/**
	  * Sort all the links and sublinks by weight
	  */
	public function sortWeight() {
		//used to frag another sort check when links are swapped
		$SORTEDLINKS = FALSE;
		while(!$SORTEDLINKS) {
			$SORTEDLINKS = TRUE;
			//sort links
			$numLinks = sizeof($this->links)-1;
			for($x=0; $x<$numLinks; $x++) {
				//sort links
				if( (int)$this->links[$x]->weight > (int)$this->links[$x+1]->weight ) {
					list($this->links[$x+1],$this->links[$x]) = array($this->links[$x],$this->links[$x+1]);
					$SORTEDLINKS = FALSE;
				}
			}
		}
		//used to flag another sort check when sublinks are swapped
		$SORTEDSUBLINKS = FALSE;
		while(!$SORTEDSUBLINKS) {
			$SORTEDSUBLINKS = TRUE;
			//sort sublinks
			for($x=0; $x<$numLinks; $x++) {
				$numSublinks = sizeof($this->links[$x]->sublinks)-1;
				for($y = 0; $y < $numSublinks; $y++) { 
					//compare sublink weight with next weight and swap if needed
					if( (int)$this->links[$x]->sublinks[$y]->weight > (int)$this->links[$x]->sublinks[$y+1]->weight ) {
						list($this->links[$x]->sublinks[$y+1],$this->links[$x]->sublinks[$y]) = array($this->links[$x]->sublinks[$y],$this->links[$x]->sublinks[$y+1]);
						$SORTEDSUBLINKS = FALSE;
					}
				}
			}
		}
	}
	/**
	  * Output all the links and sublinks in formatted html
	  * @param string $linkContainer		must be a valid html element, these will hold the links
	  * @param string $sublinkContainer	must be a valid html element, these will hold the sublinks
	  * @param string $linkClass			CSS class for links
	  * @param string $sublinkClass		CSS class for sublinks
	  * @param string $ajaxLinkClass		CSS class for ajaxlinks
	  */
	public function output($linkContainer="div",$sublinkContainer="span",$linkClass='link',$sublinkClass="sublink",$ajaxLinkClass='ajaxLink') {
		foreach($this->links as $link): ?>
		<<?echo $linkContainer ?>>
			<a class="<?=$linkClass?> <? if($link->ajaxLink) { echo $ajaxLinkClass; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $link->href; ?>"><? echo $link->name; ?></a>
			<? if(isset($link->desc) && $link->desc!='')	echo "<span class=\"linkDesc\">$link->desc</span>";
			if(isset($link->sublinks))  { ?>
				<?	foreach($link->sublinks as $sublink): /* <!-- Optional Sublinks --> */ ?>
					<<? echo $sublinkContainer?>>
						<a class="<? echo $sublinkClass?> <? if($sublink->ajaxLink) { echo $ajaxLinkClass; } ?>" <? echo 'target="_blank"'; ?> href="<? echo $sublink->href ?>"><?=$sublink->name ?></a>
						<? if(isset($sublink->desc) && $sublink->desc!='')	echo "<span class=\"linkDesc\">$sublink->desc</span>"; ?>
					</<? echo $sublinkContainer?>>
				 <? endforeach;//$links->sublinks' ?> 
			<? } ?> 
		</<? echo $linkContainer ?>>
	<? endforeach; //$links->links  
	
	}
	/** 
	  * Create the main menu from database tables using the user's access_level
	  * @param string $ajax -  the CSS class for ajax links
	  * @param string $ajax -  the CSS class for ajax sublinks
	  */
	public static function buildMain() {
		//grab links
		$access_level = Security::clearance();
		$menuLinks = Link::get('',TRUE,"object",false,$access_level);
		//create Menu object
		$mainMenu = new Menu;
		//store last link id to determine if this is a sublink row
		$lastLink_id = null;
		foreach($menuLinks as $link) {
			//avoid double display of links
			if($link->link_id != $lastLink_id) {
				//add this link to the menu
				$mainMenu->add($link->name,$link->href,$link->desc,$link->weight,$link->ajaxLink,$link->menuLink,$link->access_level);
				//add first sublink
				if($link->sublink_id) {
					//add first sublink
					$mainMenu->addSub($link->sub_name,$link->sub_href,$link->sub_desc,$link->sub_weight,$link->sub_ajaxLink,$link->sub_menuLink,$link->sub_access_level);
				}
			} else {
				//add subsequent sublink
				$mainMenu->addSub($link->sub_name,$link->sub_href,$link->sub_desc,$link->sub_weight,$link->sub_ajaxLink,$link->sub_menuLink,$link->sub_access_level);
			}
			$lastLink_id = $link->link_id;
		}
		//sort weight and then return sorted menu
		$mainMenu->sortWeight();
		return $mainMenu;
	}
}
?>