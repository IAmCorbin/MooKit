/**
 * Authorized Area Javascript
 */
window.addEvent('domready', function() {
	
	//Setup Auth Area Links
	var authLinks = $$('.authAjaxLink');
	authLinks.each(function(link) {
		link.addEvent('click',function(e) {
			e.stop();
			//load script into secure content area
			$('secureContent').load(link.get('href'));
		});
	});
	
	
	//LOGOUT BUTTON
	$('logout').addEvent('click',function(e) {
		new Request({
			method: 'post',
			url: 'CodeCore/php/logout.php',
			onSuccess: function() {
				//remove all auth content from page
				$$('.secureArea').set('tween',{duration:'long'}).fade('0');
				$$('.login_buttonWrap').fade(1);
				$$('.signup_buttonWrap').fade(1);
				(function() { $$('.secureArea').destroy(); }).delay(1200,this);
			}
		}).send();
	});

});