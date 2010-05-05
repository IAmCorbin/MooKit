window.addEvent('domready', function() {

	//Add User Table Sorting and Pagination	
	new SortingTable( 'users', {
		paginator: new PaginatingTable( 'users', 'users_pagination', { per_page: 5 } )
	});

	//delete user
	$$('td.adminDeleteUser').addEvent('click',function() {
		user = this.getParent();	
		userID = user.getFirst();
		userAlias = userID.getNext().get('html');
		userID = userID.get('html');
		
		new ConfirmBox({
			boxCurved: 'curved',
			back: '#F00',
			boxMSG: 'Delete '+userAlias+'?',
			onConfirm: function() {
				//Send Request to Delete User Script
				new Request({
					url: 'codeCore/php/secure/adminDeleteUser.php',
					onSuccess: function(response) {
						json = handleResponse(response);
						if(!json) return;
						if(json.status == 1)
							//remove user row from display
							user.destroy();
					}					
				}).send('user_id='+userID);
			}
		});
	});
	
	
	
	//increase user access level
	$$('span.adminAccessInc').addEvent('click',function() {
		//grab elements
		var user = this.getParent('tr');
		var userID = user.getFirst();
		var userAlias = userID.getNext().get('html');
		var userAccess = this.getPrevious();
		var userTitle = this.getParent().getNext();
		userID = userID.get('html');
		
		new ConfirmBox({
			boxCurved: 'curved',
			back: '#0F0',
			boxMSG: 'Increase '+userAlias+'\'s Access Level?',
			onConfirm: function() {
				//Send Request to User Access Increase Script
				new Request({
					url: 'codeCore/php/secure/adminAccessInc.php',
					onSuccess: function(response) {
						json = handleResponse(response);
						if(!json) return;
						//display new access level
						if(json.status == 1) {
							userAccess.set('html',json.access);
							userTitle.set('html',json.title);
						}
					}					
				}).send('user_id='+userID);
			}
		});
	});
	
	//decrease user access level
	$$('span.adminAccessDec').addEvent('click',function() {
		//grab elements
		var user = this.getParent('tr');
		var userID = user.getFirst();
		var userAlias = userID.getNext().get('html');
		var userAccess = this.getNext();
		var userTitle = this.getParent().getNext();
		userID = userID.get('html');
		
		new ConfirmBox({
			boxCurved: 'curved',
			back: '#00F',
			boxMSG: 'Decrease '+userAlias+'\'s Access Level?',
			onConfirm: function() {
				//Send Request to User Access Decrease Script
				new Request({
					url: 'codeCore/php/secure/adminAccessDec.php',
					onSuccess: function(response) {
						json = handleResponse(response);
						if(!json) return;
						//display new access level
						if(json.status == 1) {
							userAccess.set('html',json.access);
							userTitle.set('html',json.title);
						}
					}					
				}).send('user_id='+userID);
			}
		});
	});
	
});