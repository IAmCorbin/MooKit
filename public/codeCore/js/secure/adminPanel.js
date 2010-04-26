window.addEvent('domready', function() {

	//*********************//
	//User Administration//
	//*********************//
	//find users
	$('adminGetUsers').addEvent('submit',function(e) {
		e.stop();
		var input = this.getChildren('input[name="alias"]');
		this.set('send', {
			onRequest: function() {
				input.set('value','');
				input.addClass('loadingW');
			}.bind(this),
			onSuccess: function(response) {
				//grab the user's table body
				var usersTableBody = $('users').getElement('tbody');
				//remove all the user rows from the table
				usersTableBody.getElements('tr').destroy();
				//add found users to table
				usersTableBody.set('html',response);
				//reload javascript
				addAssets([""],["codeCore/js/secure/adminUsers.js"]);
				input.removeClass('loadingW');
			}
		}).send();
	});
	
	//**********************//
	//Menu Administration//
	//**********************//
	//find links
	$('adminGetLinks').addEvent('submit',function(e) {
		e.stop();
		var input = this.getChildren('input[name="name"]');
		this.set('send', {
			onRequest: function() {
				input.set('value','');
				input.addClass('loadingW');
			}.bind(this),
			onSuccess: function(response) {
				//grab the links's table body
				var linksTableBody = $('links').getElement('tbody');
				//remove all the link rows from the table
				linksTableBody.getElements('tr').destroy();
				//add found links to table
				linksTableBody.set('html',response);
				//reload javascript
				addAssets([""],["codeCore/js/secure/adminLinks.js"]);
				input.removeClass('loadingW');
			}
		}).send();
	});
	
	
	//Add New Link Event
	$('adminAddLink').addEvent('submit',function(e) {
		e.stop();
		this.set('send',{
			onSuccess: function(response) {
				json = handleResponse(response);
				if(!json)
					return;
			}
		}).send();
	});
	
});