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
	//Add Table Sorting and Pagination	
	new SortingTable( 'links', {
		paginator: new PaginatingTable( 'links', 'links_pagination', { per_page: 5 } )
	});
	
	
	links = $('links').getElement('tbody').getElements('tr');
	links.addEvent('click',function(e) {
		if(e.target.className == "adminDeleteLink")
			new ConfirmBox({
				boxMSG: "Are you sure you want to delete this link?"
			});
		//make sure this is not a cell in the sublink subtable
		else if(e.target.getParent().getParent().getParent().id =="links") {
			//Create Link Edit Form
			new Element('div',{ 
				id: "adminEditingLink",
				styles: { position: 'fixed', left: '0', top: '0', width: '100%', height: '100%', display: 'none', background: '#000', zIndex: '5000' }
			}).inject(document.body);
			//Link Editing Lightbox Content
			var editingContent = new Element('div', {
				class: "adminEditingLinkContent",
				styles: { position:'fixed', left: '20%', top: '10px', width: '600px', height: '250px', padding: '10px', border: 'solid black 8px', background: '#FFF', zIndex: '5001', display: 'none' }
			});
			//close button
			new Element('div', { id: "adminEditingLinkClose", styles: { background: 'red', width: '25px', height: '25px' }, html: 'Cancel' }).inject(editingContent);
			//get link data from the event
			link_id = e.target.getParent().getFirst().get('html');
			name = e.target.getParent().getFirst().getNext().get('html');
			href = e.target.getParent().getFirst().getNext().getNext().get('html');
			desc = e.target.getParent().getFirst().getNext().getNext().getNext().get('html');
			weight = e.target.getParent().getFirst().getNext().getNext().getNext().getNext().get('html');
			//checkboxes
			if(e.target.getParent().getLast().getPrevious().getPrevious().getPrevious().getPrevious().get('html').toInt()) ajaxLink='checked="yes"'; else ajaxLink='';
			if(e.target.getParent().getLast().getPrevious().getPrevious().getPrevious().get('html').toInt()) menuLink='checked="yes"'; else menuLink=''; 
			//pick access_level select option
			if(e.target.getParent().getLast().getPrevious().getPrevious().get('html').toInt() ==  0) access_none="SELECTED"; else access_none="";
			if(e.target.getParent().getLast().getPrevious().getPrevious().get('html').toInt() ==  1) access_basic="SELECTED"; else access_basic="";
			if(e.target.getParent().getLast().getPrevious().getPrevious().get('html').toInt() ==  2) access_create="SELECTED"; else access_create="";
			if(e.target.getParent().getLast().getPrevious().getPrevious().get('html').toInt() ==  4) access_admin="SELECTED"; else access_admin="";
			//The Form Itself
			new Element('form', {
				id: "adminEditLinkForm",
				class: "adminEditLink",
				action: "codeCore/php/secure/adminEditLink.php",
				method: "post",
				html: '<h1>Edit Link</h1>\
						<label>\
							<span>Link Name</span>\
							<input name="name" type="text" size="20" value="'+name+'" />\
						</label>\
						<label>\
							<span>href</span>\
							<input name="href" type="text" size="40" value="'+href+'" />\
						</label>\
						<label>\
							<span>Description</span>\
							<input name="desc" type="text" size="20" value="'+desc+'" /><- Optional: \
						</label>\
						<label style="float: left;">\
							<span>Weight</span>\
							<input name="weight" type="text" size="5" value="'+weight+'" />\
						</label>\
						<label style="float: right;">\
							<span>Ajax link?</span>\
							<input name="ajaxLink"  '+ajaxLink+' value="1" type="checkbox" />\
						</label>\
						<label style="float: right;">\
							<span>Menu link?</span>\
							<input name="menuLink"  '+menuLink+' value="1" type="checkbox" />\
						</label>\
						<label style="float: right;">\
							<span>Access Level</span>\
							<select name="access_level">\
								<option '+access_none+' value="0">NONE</option>\
								<option '+access_basic+' value="1">BASIC</option>\
								<option '+access_create+' value="2">CREATE</option>\
								<option '+access_admin+' value="4">ADMIN</option>\
							</select>\
						</label>\
						<label style="clear: both;" >\
							<input type="hidden" name="link_id" value="'+link_id+'" />\
							<input id="adminEditLinkSubmit" type="submit" value="update" />\
						</label>'
			}).inject(editingContent);
			//inject all that into the document
			editingContent.inject(document.body);
			//Apply Lightbox Functionality and trigger it to open
			var editingLightbox = new LightBox('adminEditingLink', {
				onShow: function() { 
					
				},
				onHide: function() { 
					//hide form errors
					this.content.setStyle('display','none');
					//set animation options and remove signup form
					this.fadeLightbox.delay('500',this);
				},
				onRemove: function() {
					this.lightbox.destroy();
					this.content.destroy();
				}
			});
			editingLightbox.trigger();
			//Add Update Button Event
			$('adminEditLinkForm').addEvent('submit', function(e) {
				e.stop();
				this.set('send',{
					onSuccess: function(response) {
						console.log(this);
						json = handleResponse(response);
						if(!json)
							return;
						if(json.status == "OK") {
							editingLightbox.trigger();
							
						}
					}
				}).send();
			});
		}
	});
	
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