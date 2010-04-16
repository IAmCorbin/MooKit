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
							//decode JSON and check for status
							json = JSON.decode(response);
							$('debugBox').set('html',response); 
							//if an error is detected, replace user's input with filtered input sent back from php so they can correct it
							if(json.status.test('^ERROR')) {
								this.getElement('input[name=alias]').set('value',json.alias);
								this.getElement('input[name=nameFirst]').set('value',json.nameFirst);
								this.getElement('input[name=nameLast]').set('value',json.nameLast);
								this.getElement('input[name=email]').set('value',json.email);
								$('signupPHPError').setStyle('display','block');
							}
							switch(json.status) {
								case "ADDED":
									debug("USER ADDED!"); 
									$('signupPHPError').setStyle('display','none');
									signup.trigger(); 
									$('signupForm').reset();
									break;
								//If User was not added, display the proper error message
								case "ERROR_FILTER":
									$('signupPHPError').set('html',"Invalid Field, please try again or contact the administrator if this problem persists");
									break;
								case "ERROR_BADPASS":									
									$('signupPHPError').set('html',"The passwords you entered do not match");
									break;
								case "ERROR_DUPLICATE":
									$('signupPHPError').set('html',"Alias or Email Address already exists, try a different alias or email address");
									break;
								case "ERROR_ADDING":
									$('signupPHPError').set('html',"Error adding user, please try again later. If problem persists contact the administrator");
									break;
								default:
									break;
							}
					}.bind(this) 
				}).send();
			}
		});


	//  END SIGNUP BOX   //
	//--------------------------------//
});