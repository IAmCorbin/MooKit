/**
  * @function update the application on user status change (log in/out)
  */
function updateApp() {
	refreshMenu();
}
/**
  * @function refresh the site menu
  */ 
function refreshMenu() {
	new Request.HTML({
		url: 'codeSite/php/updateMenu.php',
		onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) { 
			$('mainNav').fade(0);
			(function() { $('mainNav').set('html',responseHTML); }).delay(500);
			(function() { $('mainNav').fade(1); }).delay(500);
			//update menu javascript -- delay for a second to make sure the menu html has finished setting
			(function() { addAssets([""],["codeSite/js/menu.js"]); } ).delay(1000);
		}
	}).send();
}