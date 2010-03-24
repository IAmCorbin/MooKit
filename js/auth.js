/**
 * Authorized Area Javascript
 */
window.addEvent('domready', function() {
	
	//LOGOUT BUTTON
	$('logout').addEvent('click',function(e) {
		new Request({
			method: 'post',
			url: 'php/logout.php',
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