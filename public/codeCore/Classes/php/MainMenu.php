<?php
/**
  * contains MainMenu Classes
  * @package MooKit
  */
/**
 * A Class for creating the main site menu, populated from a database
 *
 * Creates the main site database from database tables of links and sublinks taking the user's access level into account
 *
 * @author Corbin Tarrant
 * @birth April 24th, 2010
 * @package MooKit
 */
class MainMenu {
	/** @var $menu 	the main menu */
	var $menu;
	/** @var $DB DatabaseConnection - database object */
	var $DB;
	
	/**
	  * Constructor
	  */
	public function __construct() {
		$this->menu = new Menu();
		$this->DB = new DatabaseConnection;
		//Get Basic Links
		$basicLinks = $this->getLinks($_SESSION['access_level'] & ACCESS_BASIC);
			foreach($basicLinks as $link) {
				$this->menu->add($link->name,$link->href,$link->desc,$link->ajax);
				foreach($link->sublinks as $sublink) {
					$this->menu->addSub($sublink->name,$sublink->href,$sublink->desc,$sublink->ajax);
				}
			}
		//Get Create Links
		$createLinks = $this->getLinks($_SESSION['access_level'] & ACCESS_CREATE);
			foreach($createLinks as $link) {
			$this->menu->add($link->name,$link->href,$link->desc,$link->ajax);
			foreach($link->sublinks as $sublink) {
				$this->menu->addSub($sublink->name,$sublink->href,$sublink->desc,$sublink->ajax);
			}
		}
		//Get Admin Links
		$adminLinks = $this->getLinks($_SESSION['access_level'] & ACCESS_ADMIN);
			foreach($adminLinks as $link) {
			$this->menu->add($link->name,$link->href,$link->desc,$link->ajax);
			foreach($link->sublinks as $sublink) {
				$this->menu->addSub($sublink->name,$sublink->href,$sublink->desc,$sublink->ajax);
			}
		}
	}
	/**
	  * Get all links for a certain access_level
	  */
	public function getLinks($access_level) {
		$query = "SELECT * FROM `links` WHERE `access_level`='$access_level' AND `mainMenu`='1';";
		return $this->DB->get_rows($query);
	}
}