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
						//set form to processed values
						this.getElement('input').set('value',json.title);
						this.getElement('textarea').set('value',json.text);
						//render Title
						$('renderTitle').set('html',json.title);
						//render Text
						$('renderText').set('html',json.text);
					}.bind(this)
				}).send();
			}
		
	});

});