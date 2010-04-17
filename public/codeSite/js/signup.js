window.addEvent('domready', function() {
	//-----------------------------------//
	// SETUP SIGNUP BOX  //
	//store all form errors and set fade
	var formErrors = $$('.formError');
	if(formErrors) formErrors.fade(0.4);
	
		//Create new form validator
		var signupValidator = new Form.Validator.Inline($('signupForm'));
		var signup = new LightBox('signup', {
			onShow: function() { 
				this.content.setStyle('top','-350px');
				this.content.set('tween',{duration: 'long', transition: Fx.Transitions.Bounce.easeOut });
				this.content.tween('top','100px'); 
				$('signupForm').getElements('input')[0].focus();
			},
			onHide: function() { 
				//hide form errors
				formErrors.setStyle('display','none');
				//set animation options and remove signup form
				this.content.set('tween', { duration: '500', transition: 'quad' });
				this.content.tween('top','1600%');
				signup.fadeLightbox.delay('500',this);
			}
		});
		//Link Signup button to Signup Layer
		$('signup_buttonText').addEvent('click',function() { signup.trigger(); });
		//Ajax form processing
		$('signupForm').addEvent('submit',function(e) { 
			//stop normal form processing
			e.stop();
			if(!signupValidator.validate())
				//show form errors
				formErrors.setStyle('display','block');
			//if valid user input
			else {
				//send ajax request
				$('signupForm').set('send',{ 
					onRequest: function() { 
						//ajax loader
						new Element('img',{src: 'img/ajax-loader.gif', id: 'signupProcessing'}).inject($$('.signupContent')[0]); 
					},
					onSuccess: function(response) { 
							$('signupProcessing').destroy(); 
							
							//process response -- this will handle any errors and return the json or false
							json = handleResponse(response,'signupPHPError');
							if(!json)
								return;
								
							//if an error is detected, replace user's input with filtered input sent back from php so they can correct it
							if(json.status.test('^E_')) {
								this.getElement('input[name=alias]').set('value',json.alias);
								this.getElement('input[name=nameFirst]').set('value',json.nameFirst);
								this.getElement('input[name=nameLast]').set('value',json.nameLast);
								this.getElement('input[name=email]').set('value',json.email);
								$('signupPHPError').setStyle('display','block');
							}
							//check return status
							switch(json.status) {
								case "OK":
									debug("USER ADDED!");
									$('signupPHPError').setStyle('display','none');
									signup.trigger(); 
									$('signupForm').reset();
									break;
								default:
									//check and display JSON errors
									checkJSONerrors(json.status,'signupPHPError');
									break;
							}
					}.bind(this) 
				}).send();
			}
		});


	//  END SIGNUP BOX   //
	//--------------------------------//
});