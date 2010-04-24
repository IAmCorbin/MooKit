window.addEvent('domready', function() {

	//Add Table Sorting and Pagination	
	new SortingTable( 'users', {
		paginator: new PaginatingTable( 'users', 'users_pagination', { per_page: 5 } )
	});

	//find users
	$('adminFindUsers').addEvent('submit',function(e) {
		e.stop();

		this.set('send', {
			onRequest: function() {
				input = this.getChildren('input[name=alias]');
				input.setStyle('background','#AFA');
			}.bind(this),
			onSuccess: function(response) {
				$('adminPanel').set('html',response);
				//reload javascript
				addAssets([""],["codeCore/js/secure/adminPanel.js"]);
			}
		}).send();
	});

	//delete user
	$$('td.adminDeleteUser').addEvent('click',function() {
		userAlias = this.getParent().getFirst().get('html');
		user = this.getParent();		
		
		new ConfirmBox({
			boxCurved: 'curved',
			back: '#F00',
			boxMSG: 'Delete '+userAlias+'?',
			onConfirm: function() {
				//Send Request to Delete User Script
				new Request.JSON({
					url: 'codeCore/php/secure/adminDeleteUser.php',
					onSuccess: function(responseJSON, responseTEXT) {
						json = handleResponse(responseTEXT,null);
						if(!json) return;
						if(json.status == 1)
							//remove user row from display
							user.destroy();
					}					
				}).send('alias='+userAlias);
			}
		});
	});
	
	
	
	//increase user access level
	$$('span.adminAccessInc').addEvent('click',function() {
		
		//grab elements
		userAlias = this.getParent().getParent().getFirst().get('html');
		userAccess = this.getPrevious();
		userTitle = this.getParent().getNext();
		
		
		new ConfirmBox({
			boxCurved: 'curved',
			back: '#0F0',
			boxMSG: 'Increase '+userAlias+'\'s Access Level?',
			onConfirm: function() {
				//Send Request to User Access Increase Script
				new Request.JSON({
					url: 'codeCore/php/secure/adminAccessInc.php',
					onSuccess: function(responseJSON, responseTEXT) {
						json = handleResponse(responseTEXT,null);
						if(!json) return;
						//display new access level
						if(json.status == 1) {
							userAccess.set('html',json.access);
							userTitle.set('html',json.title);
						}
					}					
				}).send('alias='+userAlias+'&access_level='+userAccess.get('html'));
			}
		});
	});
	
	//decrease user access level
	$$('span.adminAccessDec').addEvent('click',function() {
		
		//grab elements
		userAlias = this.getParent().getParent().getFirst().get('html');
		userAccess = this.getNext();
		userTitle = this.getParent().getNext();
		
		new ConfirmBox({
			boxCurved: 'curved',
			back: '#00F',
			boxMSG: 'Decrease '+userAlias+'\'s Access Level?',
			onConfirm: function() {
				//Send Request to User Access Decrease Script
				new Request.JSON({
					url: 'codeCore/php/secure/adminAccessDec.php',
					onSuccess: function(responseJSON, responseTEXT) {
						json = handleResponse(responseTEXT,null);
						if(!json) return;
						//display new access level
						if(json.status == 1) {
							userAccess.set('html',json.access);
							userTitle.set('html',json.title);
						}
					}					
				}).send('alias='+userAlias+'&access_level='+userAccess.get('html'));
			}
		});
	});
	
	//**********************//
	//Menu Administration//
	//**********************//
	$('adminAddLink').addEvent('submit',function(e) {
		e.stop();
		this.set('send',{
			onSuccess: function(response) {
				json = handleResponse(response);
				if(!json)
					return;
				debug(this);
			}
		}).send();
	});
	
});