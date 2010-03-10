window.addEvent('domready', function() {
	

	$('post').addEvent('submit',function(e) {
		e.stop(); //prevent normal form processing
		
		//Create new form validator
		var postValidator = new Form.Validator.Inline($('post'));
		if(postValidator.validate()) {
				//send ajax request
				this.set('send',{ 
					onRequest: function() {
						//ajax loader
						
					},
					onSuccess: function(response) { 
						//convert JSON from PHP to JavaScript object
						var json = JSON.decode(response);
						//render Title
						$('renderTitle').set('html',json.title);
						//render Text
						$('renderText').set('html',json.text);
					}
				}).send();
			}
		
	});

});