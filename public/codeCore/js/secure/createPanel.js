window.addEvent('domready', function() {

	//*********************//
	//  Post Management  //
	//*********************//
	//Find Posts
	$('createGetPosts').addEvent('submit',function(e) {
		e.stop();
		var input = this.getChildren('input[name="title"]');
		this.set('send', {
			onRequest: function() {
				input.set('value','');
				input.addClass('loadingW');
			}.bind(this),
			onSuccess: function(response) {
				var json = handleResponse(response);
				if(!json) return;
				if(json.status == 1) {
					//grab the user's table body
					var postsTableBody = $('posts').getElement('tbody');
					//remove all the user rows from the table
					postsTableBody.getElements('tr').destroy();
					//add found users to table
					postsTableBody.set('html',json.html);
					//reload javascript
					//addAssets([""],["codeCore/js/secure/adminPost.js"]);
				}
				input.removeClass('loadingW');
			}
		}).send();
	});
	
	var postValidator = new Form.Validator.Inline($('createAddPost'));
	//Add New Post
	$('createAddPost').addEvent('submit',function(e) {
		e.stop();
		if(!postValidator.validate()) return;
		this.set('send',{
			onSuccess: function(response) {
				json = handleResponse(response);
				if(!json) return;
				if(json.status == 1) {
					//create new link row
					var newPostRow = new Element('tr');
					new Element('td', { name: "post_id", html: 'new' }).inject(newPostRow);
					new Element('td', { name: "title", html: json.title }).inject(newPostRow);
					new Element('td', { name: "creator_id", html: 'New Post: Reload to get details' }).inject(newPostRow);
					new Element('td', { name: "createTime", html: 'New Post: Reload to get details' }).inject(newPostRow);
					new Element('td', { name: "modTime", html: 'New Post: Reload to get details' }).inject(newPostRow);
					new Element('td', { class: "createDeletePost", html: "." }).inject(newPostRow);
					//add new row to links table
					newPostRow.inject($('posts').getElement('tbody'));
					this.reset();
				}
			}.bind(this)
		}).send();
	});
});