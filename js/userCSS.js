window.addEvent('domready', function() {

	$('userCSS').addEvent('submit',function(e) {
		e.stop(); //prevent normal form processing
		
		//Create new form validator
		var cssValidator = new Form.Validator.Inline($('userCSS'));
		if(cssValidator.validate()) {
			//send ajax request
			this.set('send',{ 
				onSuccess: function(response) { 
						console.log(response);
						//convert JSON from PHP to JavaScript object
						var json = JSON.decode(response);
						this.getElement('textarea').set('value',json.css);
				}.bind(this)
			}).send();
		}
		
	});

});