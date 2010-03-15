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
				//remove auth content
				$$('.authArea').getElements('.authAreaContent')[0].set('html','');
				$$('.authArea').setStyle('display','none');
			}
		}).send();
	});

});