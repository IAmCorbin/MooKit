<?
/**
  * Variable function used to display updated Menu on user status change ( logged in/out )
  * @returns the content template object
  */
function updateMenu() {

	//$DB = new DatabaseConnection;
	
	$mainMenu = new Menu;
	//Unauthorized
	$mainMenu = new Menu;
	$mainMenu->add('IAmCorbin.net','http://www.iamcorbin.net');
	  $mainMenu->addSub('Skip Intro','http://www.iamcorbin.net/?intro=1');
	  $mainMenu->addSub('The Desert','http://www.iamcorbin.net/desert');
	  $mainMenu->addSub('pssst','secret','ajaxLink');
	
	$access_level = Security::clearance();
	if($access_level & ACCESS_BASIC) {
		//Basic User
		$mainMenu->add('Testing Ajax Links','');
		  $mainMenu->addSub('test1','test1','ajaxLink');
		  $mainMenu->addSub('test2','test2','ajaxLink');
		  $mainMenu->addSub('test3','test3','ajaxLink');
	}
	if($access_level & ACCESS_CREATE) {
		//Creator
		$mainMenu->add('MetalDisco.org','http://www.metaldisco.org');
	}
	if($access_level & ACCESS_ADMIN) {
		//Administrator
		$mainMenu->add('Front','','ajaxLink');
		$mainMenu->add('Administrator Panel','adminPanel','ajaxLink');
	}
	if($access_level != ACCESS_NONE) {
		$mainMenu->add('LogOut','logout','ajaxLink');
	}
	
	return $mainMenu;
}
?>