window.addEvent('domready',function() {
	//Edit Posts
	//grab links
	var posts = $('posts').getElement('tbody').getElements('tr');
	//Link Events
	posts.addEvent('click',function(e) {
		if(e.target.className == "createDeletePost") {
			//Delete Post
			$$('td.createDeletePost').addEvent('click',function() {
				post = this.getParent();	
				postID = post.getFirst();
				postTitle = postID.getNext().get('html');
				postID = postID.get('html');
				
				new ConfirmBox({
					boxCurved: 'curved',
					back: '#F00',
					boxMSG: 'Delete Post '+postID+":"+postTitle+'?',
					onConfirm: function() {
						//Send Request to Delete User Script
						new Request({
							url: 'codeCore/php/secure/createDeletePost.php',
							onSuccess: function(response) {
								json = handleResponse(response);
								if(!json) return;
								if(json.status == 1)
									//remove user row from display
									post.destroy();
							}					
						}).send('post_id='+postID);
					}
				});
			});
		} else {
			//Add class to flag which row to update when complete
			this.addClass('EDITING');
			//get the post from the database
			var post_id = this.getFirst();
			new Request({
				url: 'codeCore/php/secure/createGetPosts.php',
				onSuccess: function(response) {
					var json = handleResponse(response);
					if(!json) return;
					var post = json[0];
					//Create Post Edit Lightbox Layer
					new Element('div',{ 
						id: "createEditingPost",
						styles: { position: 'fixed', left: '0', top: '0', width: '100%', height: '100%', display: 'none', background: '#000', zIndex: '5000' }
					}).inject(document.body);
					//Post Editing Lightbox Content Box
					var editingContent = new Element('div', {
						class: "createEditingPostContent",
						styles: { position:'fixed', left: '50%', top: '50px', width: '0%', height: '0%', padding: '10px', border: 'solid black 8px', background: '#FFF', zIndex: '5001', display: 'none', overflow: 'auto' }
					});
					//close button
					new Element('div', { id: "createEditingPostClose", styles: { cursor: 'pointer', background: 'red', width: '25px', height: '25px' }, html: 'Cancel' }).inject(editingContent);
								var title = post_id.getNext();
					var formBox = new Element('div', {
									class: "floatL",
									styles: { width: '40%' },
									html: '<h1>Edit Post</h1>'
								})
					//The Form Itself
					new Element('form', {
						id: "createEditPostForm",
						action: "codeCore/php/secure/createEditPost.php",
						method: "post",
						html: '<input type="hidden" name="post_id" value="'+post.post_id+'" />\
							  <label>Title<br /><input id="postTitleEdit" class="required msgPos:\'postEditTitleError\'" type="text" value="'+post.title+'" size="60" name="title" /></label><div id="postEditTitleError"></div><br /><br />\
							  <label>Text<br /><textarea  id="postTextEdit" class="required msgPos:\'postEditTextError\'" name="html" rows=10 cols=70>'+post.html+'</textarea></label><div id="postEditTextError"></div><br />\
							  <input type="submit" class="button" value="Post" />'
					}).inject(formBox);
					formBox.inject(editingContent);
					//Permission Editing Box
					var permBox = new Element('div', {
						class: "floatL", 
						styles: { width: '40%', borderLeft: 'dashed 5px black', marginLeft: '10px', paddingLeft: '10px' },
						html: ''
					});
					//User Permission Editing Table
					var userPermissionsTable = new Element('div', { class: 'floatL', styles: { margin: '10px' }, html: 'User Permissions' });
					new Element('table', {
						id: "createPostUserPermissionsTable",
						html: '<thead>\
								<th style="display: none;">user_id</th><th>User</th><th>Modify</th><th>Denied</th>\
							</thead>\
							<tbody>\
							</tbody>'
					}).inject(userPermissionsTable);
					//Get Post User Permissions from Database and inject table rows
					new Request({
						url: 'codeCore/php/secure/createGetPostUserPerms.php',
						onSuccess: function(response) {
							json = handleResponse(response);
							if(!json || $type(json) != "array") return;
							debug(userPermissionsTable);
							json.each(function(perm) {
								var permRow = new Element('tr');
								new Element('td', { html: perm.user_id, styles: {display: 'none' }}).inject(permRow);
								new Element('td', { html: perm.alias }).inject(permRow);
								new Element('td', { html: perm.access_level }).inject(permRow);
								new Element('td', { html: perm.access_level }).inject(permRow);
								permRow.inject(userPermissionsTable.getElement('tbody'));
							});
						}
					}).send('post_id='+post.post_id+'&rType=json');
					//Permissions - user adding singleton
					new Element('form', { 
						id: 'postUserPermissionsAddUser',
						class: 'singleton',
						method: 'post',
						action: '',
						html: '<input type="text" value="Singleton user adding form here" />\
							<input type="submit" value="add user" />'
					}).inject(userPermissionsTable);
					//Get Post Group Permissions from Database  and inject table rows
					var postGroupPermissionRows = "<tr><td>Groups</td><td>will go</td><td>here</td></tr>";
					//Group Permission Editing Table
					var groupPermissionsTable = new Element('div', { class: 'floatL', styles: { margin: '10px' }, html: 'Group Permissions' });
					new Element('table', {
						id: "createPostGroupPermissionsTable",
						html: '<thead>\
								<th>User</th><th>Modify</th><th>Denied</th>\
							</thead>\
							</tbody>\
								'+postGroupPermissionRows+'\
							  </tbody>'
					}).inject(groupPermissionsTable);
					//Permissions - group adding singleton
					new Element('form', { 
						id: 'postGroupPermissionsAddGroup',
						class: 'singleton',
						method: 'post',
						action: '',
						html: '<input type="text" value="Singleton group adding form here" />\
							<input type="submit" value="add group" />'
					}).inject(groupPermissionsTable);
					//Add User and Group Permission Tables to Permission Section and Add to the Editing Box
					userPermissionsTable.inject(permBox);
					groupPermissionsTable.inject(permBox);
					permBox.inject(editingContent);
					//Now Inject Everything into the document
					editingContent.inject(document.body);
					//Apply Lightbox Functionality and trigger it to open
					var editingLightbox = new LightBox('createEditingPost', {
						onShow: function() { 
							this.content.morph({
								width: '1500%',
								height: '500%',
								left: '10%',
								top: '10%'
							});
						},
						onHide: function() { 
							//remove editing flag
							var updateRow = posts.getParent().getElement('.EDITING')[0];
							updateRow.removeClass('EDITING');
							this.content.morph({
								width: '0%',
								height: '0%',
								left: '50%',
								top: '50%'
							});
							this.fadeLightbox.delay('200',this);
						},
						onRemove: function() {
							this.lightbox.destroy();
							this.content.destroy();
						}
					});
					editingLightbox.trigger();
					var postEditValidator = new Form.Validator.Inline($('createEditPostForm'));
					//Update Post
					$('createEditPostForm').addEvent('submit', function(e) {
						e.stop();
						if(!postEditValidator.validate()) return;
						this.set('send',{
							onSuccess: function(response) {
								json = handleResponse(response);
								if(!json) return;
								if(json.status == "1") {
									//update table row
									var updateRow = posts.getParent().getElement('.EDITING')[0];
									updateRow.getChildren('td[name="title"]').set('html',json.title);
									updateRow.getChildren('td[name="modTime"]').set('html',json.modTime);
									//close lightbox
									editingLightbox.trigger();
								}
							}.bind(this)
						}).send();
					});
					//Post User Permissions - Add User
					$('postUserPermissionsAddUser').addEvents({
						click: function() {
							this.getElement('input[type="text"]').set('value','');
						},
						submit: function(e) {
							e.stop();
							alert('GET USER');
						}
					});
					//Post Group Permissions - Add Group
					$('postGroupPermissionsAddGroup').addEvents({
						click: function() {
							this.getElement('input[type="text"]').set('value','');
						},
						submit: function(e) {
							e.stop();
							alert('GET GROUP');
						}
					});
				}
			}).send('rType=json&post_id='+post_id.get('html'));
		}
	});
});