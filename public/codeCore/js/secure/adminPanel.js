window.addEvent('domready', function() {

	//delete user
	$$('td.adminDeleteUser').addEvent('click',function() {
		userAlias = this.getParent().getFirst().get('html');
		debug("Delete user : "+userAlias+"?");
		
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
					debug("DELETING "+userAlias);
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
		
		userAlias = this.getParent().getParent().getFirst().get('html');
		debug("Increase access for : "+userAlias+"?");
		
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
					debug("INCREASING ACCESS FOR "+userAlias);
					this.trigger();
				}.bind(this));
			},
			onHide: function() { 
				this.fadeLightbox.delay('200',this);
			},
			onRemove: function() {
				//destroy confirmation box
				$('adminAccessInc'+userAlias).destroy();
				$$('.adminAccessIncContent').destroy();
			},	
		}).trigger();		
	});
	
	//decrease user access level
	$$('span.adminAccessDec').addEvent('click',function() {
		
		userAlias = this.getParent().getParent().getFirst().get('html');
		debug("Decrease access for : "+userAlias+"?");
		
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
					debug("DECREASING ACCESS FOR "+userAlias);
					this.trigger();
				}.bind(this));
			},
			onHide: function() { 
				this.fadeLightbox.delay('200',this);
			},
			onRemove: function() {
				//destroy confirmation box
				$('adminAccessDec'+userAlias).destroy();
				$$('.adminAccessDecContent').destroy();
			},	
		}).trigger();
	});
});