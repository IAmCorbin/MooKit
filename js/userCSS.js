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
						//remove old user style
						if($('userCSSstyle'))
							$('userCSSstyle').destroy();
						//add user style to head
						var userStyle = new Element('style',{
							id: 'userCSSstyle',
							type: 'text/css',
							html: json.css
						});
						userStyle.inject(document.head,'bottom');
						
				}.bind(this)
			}).send();
		}
		
	});

});