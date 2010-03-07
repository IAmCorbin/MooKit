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
				if(!signupValidator.validate()) {
					//show form errors
					formErrors.setStyle('display','block');
				}
				//don't close if input is not valid
				if(signupValidator.validate() || $('CLOSE')) {
					//make sure php validated user input
					if($('PHPVALIDATED') || $('CLOSE')) {
						//hide form errors
						formErrors.setStyle('display','none');
						this.content.set('tween', { duration: '500', transition: 'quad' });
						this.content.tween('top','1600%');
						signup.fadeLightbox.delay('500',this);
						//remove phpValidation object
						$('PHPVALIDATED')? $('PHPVALIDATED').destroy() : 0;
						//remove CLOSE object
						$('CLOSE')? $('CLOSE').destroy() : 0;
					}
				}
			}
		});
		//Link Signup button to Signup Layer
		$('signup_buttonText').addEvent('click',function() { signup.trigger(); });
		//Ajax form processing
		$('signupForm').addEvent('submit',function(e) { 
			//stop normal form processing
			e.stop();
			//if valid user input
			if(signupValidator.validate()) {
				//send ajax request
				$('signupForm').set('send',{ 
					onRequest: function() { 
						//ajax loader
						new Element('img',{src: 'img/ajax-loader.gif', id: 'signupProcessing'}).inject($$('.loginContent')[0]); 
					},
					onSuccess: function(response) { 
							$('debugBox').set('html',response); 
							$('signupProcessing').destroy(); 
							signup.trigger(); 
							$('signupForm').reset();
					} 
				}).send();
			}
		});
		//X button (cancel window and close)
		var signupX = signup.content.getElements('.X');
		signupX.each(function(X) { 
			X.addEvent('click',function() { 
				//create hidden CLOSE element to bypass form validation
				new Element('div',{id: 'CLOSE', style: 'display: none'}).inject(document.body);
				signup.trigger(); 
			}); 
		});
	//  END SIGNUP BOX   //
	//--------------------------------//
});