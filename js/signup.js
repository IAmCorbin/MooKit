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
			if(!signupValidator.validate()) {
				//show form errors
				formErrors.setStyle('display','block');
			}
			//if valid user input
			if(signupValidator.validate()) {
				//send ajax request
				$('signupForm').set('send',{ 
					onRequest: function() { 
						//ajax loader
						new Element('img',{src: 'img/ajax-loader.gif', id: 'signupProcessing'}).inject($$('.signupContent')[0]); 
					},
					onSuccess: function(response) { 
							$('signupProcessing').destroy(); 
							//decode JSON and check for status
							json = JSON.decode(response);
							$('debugBox').set('html',response); 
							switch(json.status) {
								case "ADDED":
									debug("USER ADDED!"); 
									$('signupPHPError').setStyle('display','none');
									signup.trigger(); 
									$('signupForm').reset();
									break;
								case "ERROR_FILTER":
									$('signupPHPError').setStyle('display','block');
									$('signupPHPError').set('html',"Invalid Username or Password, please try again or contact the administrator");
									$('signupForm').reset();
									break;
								case "ERROR_BADPASS":
									$('signupPHPError').setStyle('display','block');
									$('signupPHPError').set('html',"Passwords do not match");
									break;
								case "ERROR_DUPLICATE":
									$('signupPHPError').setStyle('display','block');
									console.log("DUPLICATE");
									$('signupPHPError').set('html',"User already exists, try a different username");
									break;
								case "ERROR_ADDING":
									$('signupPHPError').setStyle('display','block');
									$('signupPHPError').set('html',"Error adding user, please try again later. If problem persists contact the administrator");
									break;
								default:
									break;
							}
					} 
				}).send();
			}
		});

	//  END SIGNUP BOX   //
	//--------------------------------//
});