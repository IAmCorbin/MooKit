/**
 * Authorized Area Javascript
 */
window.addEvent('domready', function() {
	
	//Setup Auth Area Links
	var authLinks = $$('.authAjaxLink');
	authLinks.each(function(link) {
		link.addEvent('click',function(e) {
			e.stop();
			//load script into secure content area if not blank
			if(link.get('href') != null) $('secureContent').load(link.get('href'));
		});
	});
	
	
	//LOGOUT BUTTON
	$('logout').addEvent('click',function(e) {
		new Request({
			method: 'post',
			url: 'codeSite/php/logout.php',
			onSuccess: function() {
				//fade out secure content
				$$('.secureArea').set('tween',{duration:'2000'}).fade('0');
				//destroy secure content and load public content
				(function() { 	
					$$('.secureArea').destroy();
					updateApp();
					//fade login and signup buttons back in
					$$('.login_buttonWrap').fade(1);
					$$('.signup_buttonWrap').fade(1);
				}).delay(2300,this);
			}
		}).send();
	});

});