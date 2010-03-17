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
							debug('postEdit.js : '+response);
							//convert JSON from PHP to JavaScript object
							var json = JSON.decode(response);
							switch(json.status) {
								case "OK":
									debug('postEdit.js : '+"OK");
									//set form to processed values
									this.getElement('input').set('value',json.title);
									this.getElement('textarea').set('value',json.html);
									//render Title
									$('renderTitle').set('html',json.title);
									//render Text
									$('renderText').set('html',json.html);
									break;
								case "ERROR_FILTER":
									debug('postEdit.js : '+"FILTER ERROR");
									break;
								case "ERROR_QUERY":
									debug('postEdit.js : '+"QUERY ERROR");
									break;
								default:
									debug('postEdit.js : '+"DEFAULT");
									break;
							}
								
					}.bind(this)
				}).send();
			}
		
	});

});