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
						styles: { position:'fixed', left: '20%', top: '10px', width: '600px', height: '600px', padding: '10px', border: 'solid black 8px', background: '#FFF', zIndex: '5001', display: 'none' }
					});
					//close button
					new Element('div', { id: "createEditingPostClose", styles: { cursor: 'pointer', background: 'red', width: '25px', height: '25px' }, html: 'Cancel' }).inject(editingContent);
								var title = post_id.getNext();
					//The Form Itself
					new Element('form', {
						id: "createEditPostForm",
						action: "codeCore/php/secure/createEditPost.php",
						method: "post",
						html: '<h1>Edit Post</h1>\
								<input type="hidden" name="post_id" value="'+post.post_id+'" />\
								<label>Title<br /><input id="postTitleEdit" class="required msgPos:\'postEditTitleError\'" type="text" value="'+post.title+'" size="60" name="title" /></label><div id="postEditTitleError"></div><br /><br />\
								<label>Text<br /><textarea  id="postTextEdit" class="required msgPos:\'postEditTextError\'" name="html" rows=10 cols=70>'+post.html+'</textarea></label><div id="postEditTextError"></div><br />\
								<input type="submit" class="button" value="Post" />'
					}).inject(editingContent);
					//inject all that into the document
					editingContent.inject(document.body);
					//Apply Lightbox Functionality and trigger it to open
					var editingLightbox = new LightBox('createEditingPost', {
						onShow: function() { 
							
						},
						onHide: function() { 
							//remove editing flag
							var updateRow = posts.getParent().getElement('.EDITING')[0];
							updateRow.removeClass('EDITING');
							this.content.setStyle('display','none');
							this.fadeLightbox.delay('200',this);
						},
						onRemove: function() {
							this.lightbox.destroy();
							this.content.destroy();
						}
					});
					editingLightbox.trigger();
					var postEditValidator = new Form.Validator.Inline($('createEditPostForm'));
					//Add Update Button Event
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
				}
			}).send('rType=json&post_id='+post_id.get('html'));
		}
	});
});