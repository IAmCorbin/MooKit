window.addEvent('domready', function() {
	//--------------------------------//
	// SETUP LOGIN BOX //
	//store all form errors and set fade
	var formErrors = $$('.formError');
	if(formErrors) formErrors.fade(0.4);
	
		//Create new form validator
		var loginValidator = new Form.Validator.Inline($('loginForm'));
		//Create Login Layer
		var login = new LightBox('login', {
			onShow: function() { 
				this.content.set('tween',{duration: 'long', transition: Fx.Transitions.Bounce.easeOut });
				this.content.tween('top','0px');
				$('loginForm').getElements('input')[0].focus();
			},
			onHide: function() { 
				//hide form errors
				formErrors.setStyle('display','none');
				//set animation prefs and move login box off screen
				this.content.set('tween',{duration: 'short', transition: 'quad' });
				this.content.tween('top','-300px'); 
				login.fadeLightbox.delay('200',this); 
			},
			onRemove: function() { 
				
			}
		});
		//Link Login Button to Login Layer
		$('login_buttonText').addEvent('click',function() { login.trigger(); });
		//Ajax form processing
		$('loginForm').addEvent('submit',function(e) {
			//stop normal form processing
			e.stop();
			if(!loginValidator.validate())
				//show form errors
				formErrors.setStyle('display','block');
			//if valid user input
			else {
				//send ajax request
				$('loginForm').set('send',{ 
					onRequest: function() {
						//ajax loader
						new Element('img',{src: 'img/ajax-loader.gif', id: 'loginProcessing'}).inject($$('.loginContent')[0]); 
					},
					onSuccess: function(response) { 
						//kill ajax loader bar
						$('loginProcessing').destroy(); 
						if(DEBUG)
							$('debugBox').set('html',response);
							
						//process response -- this will handle any errors and return the json or false
						json = handleResponse(response,'loginPHPError');
						if(!json)
							return;
							
						//if an error is detected, replace user's input with filtered input sent back from php so they can correct it
						if(json.status.test('^E_')) {
							this.getElement('input[name=alias]').set('value',json.alias);
							$('loginPHPError').setStyle('display','block');
						}
							
						switch(json.status) {
							case  "1":
								//clear PHPError
								$('loginPHPError').setStyle('display','none');	
								//update hash with location
								window.location.hash = "#welcome";
								//close login LightBox
								login.trigger();
								updateApp();
								//clear the login form
								$('loginForm').reset();
								//fade out login/signup buttons
								$$('.login_buttonWrap').fade(0);
								$$('.signup_buttonWrap').fade(0);
								break;
							default:
								//check and display JSON errors
								checkJSONerrors(json.status,'loginPHPError');
								break;
						}		
					}.bind(this)
				}).send();
			}
		});
	//   END LOGIN BOX   //
	//--------------------------------//
});