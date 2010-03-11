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
							debug(response);
							//convert JSON from PHP to JavaScript object
							var json = JSON.decode(response);
							switch(json.status) {
								case "OK":
									debug("OK");
									//set form to processed values
									this.getElement('input').set('value',json.titleLawed);
									this.getElement('textarea').set('value',json.textLawed);
									//render Title
									$('renderTitle').set('html',json.titleLawed);
									//render Text
									$('renderText').set('html',json.textLawed);
									break;
								case "ERROR_FILTER":
									debug("FILTER ERROR");
									break;
								case "ERROR_QUERY":
									debug("QUERY ERROR");
									break;
								default:
									debug("DEFAULT");
									break;
							}
								
					}.bind(this)
				}).send();
			}
		
	});

});