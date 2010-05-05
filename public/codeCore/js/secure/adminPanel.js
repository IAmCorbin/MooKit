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
				//handle response from php
				var json = handleResponse(response);
				if(!json) return;
				if(json.status == 1) {
					//remove all the user rows from the table
					usersTableBody.getElements('tr').destroy();
					//add found users to table
					usersTableBody.set('html',json.html);
					//reload javascript
					addAssets([""],["codeCore/js/secure/adminUsers.js"]);
				}
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
				if(!json) return;
				//create new link row
				var newLinkRow = new Element('tr');
				new Element('td', { name: "link_id", html: 'new' }).inject(newLinkRow);
				new Element('td', { name: "name", html: json.name }).inject(newLinkRow);
				new Element('td', { name: "href", html: json.href }).inject(newLinkRow);
				new Element('td', { name: "desc", html: json.desc }).inject(newLinkRow);
				new Element('td', { name: "weight", html: json.weight }).inject(newLinkRow);
				new Element('td', { name: "ajaxLink", html: json.ajaxLink }).inject(newLinkRow);
				new Element('td', { name: "menuLink", html: json.menuLink }).inject(newLinkRow);
				new Element('td', { name: "access_level", html: json.access_level }).inject(newLinkRow);
				new Element('td', { name: "sublinks", html: 'New Link: Reload to add sublinks or delete' }).inject(newLinkRow);
				new Element('td', { class: "adminDeleteLink", html: "." }).inject(newLinkRow);
				//add new row to links table
				newLinkRow.inject($('links').getElement('tbody'));
				this.reset();
			}.bind(this)
		}).send();
	});
	
});