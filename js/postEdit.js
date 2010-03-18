window.addEvent('domready', function() {
	
	//Create new form validator
	var pickPostValidator = new Form.Validator.Inline($('pickPost'));
	$('pickPost').addEvent('submit',function(e) {
		e.stop(); //prevent normal form processing
		
		if(pickPostValidator.validate()) { //Validated
			this.set('send',{
				onSuccess: function(response) {
					debug('postEdit.js : postGet.php returns '+response);
					if(response && response!='1') {
						//convert JSON from PHP to JavaScript object
						var json = JSON.decode(response);
						//set form to new processed values
						$('postEditID').set('value',json[0].post_id);
						$('postTitleEdit').set('value',json[0].title);
						$('postTextEdit').set('value',json[0].html);
						//render Title and Html for Preview
						$('renderTitle').set('html',json[0].title);
						$('renderText').set('html',json[0].html);
					} else {
						$('postEditID').set('value','0');
						//set form to ERROR VALUES
						$('postTitleEdit').set('value',"INVALID POST ID, PLEASE ENTER ANOTHER");
						$('postTextEdit').set('value',"INVALID POST ID, PLEASE ENTER ANOTHER");
						//render ERROR VALUES for Preview
						$('renderTitle').set('html',"INVALID POST ID, PLEASE ENTER ANOTHER");
						$('renderText').set('html',"INVALID POST ID, PLEASE ENTER ANOTHER");
					}
				}
			}).send();
		}
	});
	
	//Create new form validator
	var updatePostValidator = new Form.Validator.Inline($('updatePost'));
	$('updatePost').addEvent('submit',function(e) {
		e.stop(); //prevent normal form processing
		
		if(updatePostValidator.validate()) {
			//send ajax request
			this.set('send',{ 
				onRequest: function() {
					//ajax loader
				},
				onSuccess: function(response) { 
					debug('postEdit.js : postUpdate.php returns '+response);
					//convert JSON from PHP to JavaScript object
					var json = JSON.decode(response);
					switch(json.status) {
						case "OK":
							debug('postEdit.js : updatePost : '+"OK");
							//set form to new processed values
							this.getElement('input').set('value',json.title);
							this.getElement('textarea').set('value',json.html);
							//render Title and Html for Preview
							$('renderTitle').set('html',json.title);
							$('renderText').set('html',json.html);
							break;
						case "ERROR_FILTER":
							debug('postEdit.js : updatePost : '+"FILTER ERROR");
							break;
						case "ERROR_QUERY":
							debug('postEdit.js : updatePost : '+"QUERY ERROR");
							break;
						case "ERROR_ID":
							debug('postEdit.js : updatePost : '+"ID ERROR");
							break;
						default:
							debug('postEdit.js : updatePost : '+"DEFAULT");
						break;
					}
				}.bind(this)
			}).send();
		}
		
	});

});