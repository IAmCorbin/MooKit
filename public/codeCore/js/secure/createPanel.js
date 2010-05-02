window.addEvent('domready', function() {

	//*********************//
	//  Post Management  //
	//*********************//
	//find posts
	$('createGetPosts').addEvent('submit',function(e) {
		e.stop();
		var input = this.getChildren('input[name="title"]');
		this.set('send', {
			onRequest: function() {
				input.set('value','');
				input.addClass('loadingW');
			}.bind(this),
			onSuccess: function(response) {
				//grab the user's table body
				var usersTableBody = $('posts').getElement('tbody');
				//remove all the user rows from the table
				usersTableBody.getElements('tr').destroy();
				//add found users to table
				usersTableBody.set('html',response);
				//reload javascript
				//addAssets([""],["codeCore/js/secure/adminPost.js"]);
				input.removeClass('loadingW');
			}
		}).send();
	});

});