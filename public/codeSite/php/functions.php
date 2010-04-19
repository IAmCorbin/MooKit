<?
/**
  * Variable function used to display updated Menu on user status change ( logged in/out )
  * @returns the content template object
  */
function updateMenu() {

	$DB = new DatabaseConnection;
	
	if(Security::clearance()) {
		//Authorized
		$mainMenu = new Menu;
		$mainMenu->add('IAmCorbin.net','http://www.iamcorbin.net');
		  $mainMenu->addSub('Skip Intro','http://www.iamcorbin.net/?intro=1');
		  $mainMenu->addSub('The Desert','http://www.iamcorbin.net/desert');
		$mainMenu->add('MetalDisco.org','http://www.metaldisco.org');
		$mainMenu->add('Testing Ajax Links','');
		  $mainMenu->addSub('test1','test1','ajaxLink');
		  $mainMenu->addSub('test2','test2','ajaxLink');
		  $mainMenu->addSub('test3','test3','ajaxLink');
		$mainMenu->add('LogOut','logout','ajaxLink');
	} else{
		//Unauthorized
		$mainMenu = new Menu;
		$mainMenu->add('IAmCorbin.net','http://www.iamcorbin.net');
		  $mainMenu->addSub('Skip Intro','http://www.iamcorbin.net/?intro=1');
		  $mainMenu->addSub('The Desert','http://www.iamcorbin.net/desert');
		  $mainMenu->addSub('pssst','secret','ajaxLink');
	}
	return $mainMenu;
}
?>