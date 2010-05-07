window.addEvent('domready',function() {
	
	//Add Post Table Sorting and Pagination	
	new SortingTable( 'posts', {
		paginator: new PaginatingTable( 'posts', 'posts_pagination', { per_page: 4 } )
	});
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
						styles: { position:'fixed', left: '200%', top: '300%', width: '0%', height: '0%', padding: '10px', border: 'solid black 8px', background: '#FFF', zIndex: '5001', display: 'none', overflow: 'auto' }
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
							json.each(function(perm) {
								var permRow = new Element('tr', { class: 'postUserPermissionRow' });
								new Element('td', { html: perm.user_id, styles: {display: 'none' }}).inject(permRow);
								new Element('td', { html: perm.alias }).inject(permRow);
								new Element('td', { html: perm.access_level }).inject(permRow);
								new Element('td', { html: perm.access_level }).inject(permRow);
								permRow.inject(userPermissionsTable.getElement('tbody'));
							});
							//~ //Delete User Permission On Right Click
							//~ //grab all sublink rows
							var userPermissions = $$('.postUserPermissionRow');
							userPermissions.each(function(element) {
								element.addEvent('contextmenu',function(e) {
									e.stop();
									if(e.target.tagName == "TD") {
										//grab data
										var user = e.target.getParent();
										var user_id = user.getFirst();
										var userAlias = user_id.getNext().get('html');
										user_id = user_id.get('html');
										new ConfirmBox({
											boxMSG: 'Are you sure that you no longer want "'+userAlias+'" to have Modify access to "'+post.title+'"?',
											back: '#F00',
											onConfirm: function() {
												//Delete The Sublink Table Entry
												new Request({
													method: 'post',
													url: 'codeCore/php/secure/createDeletePostUserPerm.php',
													onSuccess: function(response) {
														json = handleResponse(response);
														if(!json) return;
														if(json.status == 1)
															//remove sublinks row
															user.destroy();
													}
												}).send('user_id='+user_id+"&post_id="+post.post_id+"&rType=json");
											}
										});
									}
								});
							});
						}
					}).send('post_id='+post.post_id+'&rType=json');
					//Permissions - user adding singleton
					new Element('form', { 
						id: 'postUserPermissionsAddUser',
						class: 'singleton',
						method: 'post',
						action: '',
						html: '<input type="text" value="Search for a user alias" />\
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
						html: '<input type="text" value="Search for a group name" />\
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
								width: '0px',
								height: '0px',
								left: '200%',
								top: '300%'
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
						click: function(e) {
							e.target.set('value','');
						},
						submit: function(e) {
							e.stop();
						},
						keydown: function(e) {
							if(e.key == "enter") {
								//get X and Y positioning of the input
								LOC = getXY(e.target);
								new Request({
									url: 'codeCore/php/secure/sharedGetUsers.php',
									method: 'post',
									onRequest: function() {
										//add loading graphic
										e.target.addClass('loadingW');
									},
									onSuccess: function(response) {
										json = handleResponse(response);
										//if no results were found, clear the box and return
										if(!json || $type(json) != "array") {
											e.target.set('value','No Users Found'); 
											e.target.removeClass('loadingW');
											return;
										} 
										//create the results box
										var userResults = new Element('ul',{
											id: 'userSearchResults',
											class: 'resultsBox',
											styles: {
												position: 'absolute',
												height: e.target.offsetHeight*3,
												left: LOC.X,
												top: LOC.Y+e.target.offsetHeight+2,
												overflow: 'auto'
											},
											events: {
												//handle adding, this will be caught from the bubbling event 'click' from the li that will be added
												click: function(e) {
													if(e.target.className=="userAdd" || e.target.getParent().className=="userAdd") {
														if(e.target.className=="userAdd")
															var user_id = e.target.getElement('span');
														else
															var user_id = e.target.getParent().getElement('span');
														var userAlias = user_id.getParent().getFirst().get('html');
														user_id = user_id.get('html');
														var postUserPermissionsTable = e.target.getParent('div').getElement('table').getElement('tbody');
														var msgBox = $('postUserPermissionsAddUser').getElement('input[type="text"]');
														new Request({
															method: 'post',
															url: 'codeCore/php/secure/createAddPostUserPerm.php',
															onRequest: function() {
																msgBox.addClass('loadingW');
															},
															onSuccess: function(response) {
																json = handleResponse(response);
																if(json.status == 1) {
																	//add user permission row
																	newUserPermRow = new Element('tr', { class: 'postUserPermissionRow' });
																	new Element('td', { styles: { display: 'none' }, html: user_id}).inject(newUserPermRow);
																	new Element('td', { html: userAlias }).inject(newUserPermRow);
																	new Element('td', { html: '2' }).inject(newUserPermRow);
																	new Element('td', { html: '0' }).inject(newUserPermRow);
																	//inject row into this link sublink table
																	newUserPermRow.inject(postUserPermissionsTable);
																	//remove the results box
																	e.target.getParent('ul').destroy();
																} else { //Error Adding sublink
																	msgBox.set('value','User Exists or Error');
																	//remove the results box
																	e.target.getParent('ul').destroy();
																}
																msgBox.removeClass('loadingW');
															}
														}).send('user_id='+user_id+"&post_id="+post.post_id+"&access_level=2&rType=json");
													}
												}
											}
										});
										//add the results
										json.each(function(user) {
											var userResult = new Element('li',{
												class: 'userAdd',
												html: "<a>"+user.alias+"</a> - <a>"+user.nameFirst+" "+user.nameLast+")</a>"
											});
											//add the link id as a hidden element
											new Element('span', {styles: {display: 'none'},html: user.user_id }).inject(userResult);
											//add the result to the results box
											userResult.inject(userResults);
										});
										//Cancel Button
										new Element('div', {
											class: 'cancelButton',
											html: ' X ',
											events: {
												click: function(e) {
													e.stop();
													//CLOSE
													e.target.getParent('ul').destroy();
												}
											}
										}).inject(userResults,"top");
										//add the results box to the document
										userResults.inject(e.target.getParent());
										//remove loading graphic
										e.target.removeClass('loadingW');
										e.target.set('html','');
									}
								}).send('alias='+e.target.value+'&rType=json');
							}
						}
					});
					//Post Group Permissions - Add Group
					$('postGroupPermissionsAddGroup').addEvents({
						click: function(e) {
							e.target.set('value','');
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