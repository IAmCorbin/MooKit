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
		console.log(user);
		
		//create elements for confirmation lightbox
		new Element('div',{
			id: 'adminDelete'+userAlias+'Confirm',
			class: 'adminDeleteUserConfirm'
		}).inject(document.body);
		new Element('div',{
			class: 'adminDeleteUserConfirmContent curved adminDelete'+userAlias+'ConfirmContent',
			html: 'Delete User:'+userAlias+' ?</div><br />'+
					'<span class="curved adminDeleteUserConfirmClose" id="adminDelete'+userAlias+'ConfirmYES">YES</span>'+
					'<span class="curved adminDeleteUserConfirmClose" id="adminDelete'+userAlias+'ConfirmClose">NO</span>'
		}).inject(document.body);
		//create the delete confirmation box
		deleteUserConfirm = new LightBox('adminDelete'+userAlias+'Confirm', {
			onShow: function() {
				deleteUser = 'adminDelete'+userAlias+'ConfirmYES'
				$(deleteUser).addEvent('click',function() {					
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
					
					this.trigger();
				}.bind(this));
			},
			onHide: function() { 
				this.fadeLightbox.delay('200',this);
			},
			onRemove: function() {
				//destroy confirmation box
				$('adminDelete'+userAlias+'Confirm').destroy();
				$$('.adminDeleteUserConfirmContent').destroy();
			},	
		}).trigger();
	});
	
	
	
	//increase user access level
	$$('span.adminAccessInc').addEvent('click',function() {
		
		//grab elements
		userAlias = this.getParent().getParent().getFirst().get('html');
		userAccess = this.getPrevious();
		userTitle = this.getParent().getNext();
		
		//create elements for confirmation lightbox
		new Element('div',{
			id: 'adminAccessInc'+userAlias,
			class: 'adminAccessInc'
		}).inject(document.body);
		new Element('div',{
			class: 'adminAccessIncContent curved adminAccessInc'+userAlias+'Content',
			html: 'Increase Access for :'+userAlias+' ?</div><br />'+
					'<span class="curved adminAccessIncClose" id="adminAccessInc'+userAlias+'YES">YES</span>'+
					'<span class="curved adminAccessIncClose" id="adminAccessInc'+userAlias+'Close">NO</span>'
		}).inject(document.body);
		//create the delete confirmation box
		accessInc = new LightBox('adminAccessInc'+userAlias, {
			onShow: function() {
				increaseAccess = 'adminAccessInc'+userAlias+'YES'
				$(increaseAccess).addEvent('click',function() {					
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
					
					
					this.trigger();
				}.bind(this));
			},
			onHide: function() { 
				this.fadeLightbox.delay('200',this);
			},
			onRemove: function() {
				//destroy confirmation box
				confirmD = $('adminAccessInc'+userAlias);
				if(confirmD) confirmD.destroy();
				confirmD2 = $$('.adminAccessIncContent');
				if(confirmD2) confirmD2.destroy();
			},	
		}).trigger();		
	});
	
	//decrease user access level
	$$('span.adminAccessDec').addEvent('click',function() {
		
		//grab elements
		userAlias = this.getParent().getParent().getFirst().get('html');
		userAccess = this.getPrevious().getPrevious();
		userTitle = this.getParent().getNext();
		
		//create elements for confirmation lightbox
		new Element('div',{
			id: 'adminAccessDec'+userAlias,
			class: 'adminAccessDec'
		}).inject(document.body);
		new Element('div',{
			class: 'adminAccessDecContent curved adminAccessDec'+userAlias+'Content',
			html: 'Decrease Access for :'+userAlias+' ?</div><br />'+
					'<span class="curved adminAccessDecClose" id="adminAccessDec'+userAlias+'YES">YES</span>'+
					'<span class="curved adminAccessDecClose" id="adminAccessDec'+userAlias+'Close">NO</span>'
		}).inject(document.body);
		//create the delete confirmation box
		accessDec = new LightBox('adminAccessDec'+userAlias, {
			onShow: function() {
				decreaseAccess = 'adminAccessDec'+userAlias+'YES'
				$(decreaseAccess).addEvent('click',function() {					
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
					
					this.trigger();
				}.bind(this));
			},
			onHide: function() { 
				this.fadeLightbox.delay('200',this);
			},
			onRemove: function() {
				//destroy confirmation box
				confirmD = $('adminAccessDec'+userAlias);
				if(confirmD) confirmD.destroy();
				confirmD2 = $$('.adminAccessDecContent');
				if(confirmD2) confirmD2.destroy();
				
			},	
		}).trigger();
	});
});