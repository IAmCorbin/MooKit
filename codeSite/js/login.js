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
				$$('.login_buttonWrap').fade(0);
				$$('.signup_buttonWrap').fade(0);
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
						$('debugBox').set('html',response);
						//kill ajax loader bar
						$('loginProcessing').destroy(); 
						//decode JSON and check for status
						json = JSON.decode(response);
						switch(json.status) {
							case  "LOGGEDIN":
								//clear PHPError
								$('loginPHPError').setStyle('display','none');	
								//update hash with location
								window.location.hash = "#welcome";
								//close login LightBox
								login.trigger();
								//refresh content
								refreshContent(1,1);
								//clear the login form
								$('loginForm').reset();
								break;
							case "LOGGEDOUT":
								//clear PHPError
								$('loginPHPError').setStyle('display','none');	
								//fade out secure content
								$$('.secureArea').set('tween',{duration:'2000'}).fade('0');
								//destroy secure content and load public content
								(function() { 	
									$$('.secureArea').destroy(); 
									refreshContent(0,0);
								}).delay(2300,this);
								//fade login and signup buttons back in
								$$('.login_buttonWrap').fade(1);
								$$('.signup_buttonWrap').fade(1);
								break;
							case "IN":
								//clear PHPError
								$('loginPHPError').setStyle('display','none');	
								login.trigger(); 
								$('loginForm').reset(); 
								break;
							case "ERROR_FILTER":
								//show PHPError
								$('loginPHPError').setStyle('display','block');
								$('loginPHPError').set('html',"Invalid Username or Password, please try again or contact the administrator");
								$('loginForm').reset();
								break;
						}		
					}
				}).send();
			}
		});
	//   END LOGIN BOX   //
	//--------------------------------//
});