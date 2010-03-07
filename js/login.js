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
				if(!loginValidator.validate())
					//show form errors
					formErrors.setStyle('display','block');
				//don't close if input is not valid
				if(loginValidator.validate() || $('CLOSE')) {
					//make sure php validated user input
					if($('PHPVALIDATED') || $('CLOSE')) {
						//hide form errors
						formErrors.setStyle('display','none');
						this.content.set('tween',{duration: 'short', transition: 'quad' });
						this.content.tween('top','-300px'); 
						login.fadeLightbox.delay('200',this); 
						//remove phpValidation object
						$('PHPVALIDATED')? $('PHPVALIDATED').destroy() : 0;
						//remove CLOSE object
						$('CLOSE')? $('CLOSE').destroy() : 0;
					}
				}
			}
		});
		//Link Login Button to Login Layer
		$('login_buttonText').addEvent('click',function() { login.trigger(); });
		//Ajax form processing
		$('loginForm').addEvent('submit',function(e) {
			//stop normal form processing
			e.stop();
			//if valid user input
			if(loginValidator.validate()) {
				//send ajax request
				$('loginForm').set('send',{ 
					onRequest: function() {
						//ajax loader
						new Element('img',{src: 'img/ajax-loader.gif', id: 'loginProcessing'}).inject($$('.loginContent')[0]); 
					},
					onSuccess: function(response) { 
						$('debugBox').set('html',response); 
						login.trigger();
						
						//kill ajax loader bar
						$('loginProcessing').destroy(); 
						
						//read PHP flag and display or destroy auth area
						if($('LOGGEDIN')) {
							//console.log("LOGGEDIN!");
							if($('LOGGEDOUT'))
								$('LOGGEDOUT').destroy();
							authArea = $$('.authArea');
							authArea.setStyle('display','block');
							authArea.load('./php/auth.php');
						} else if($('LOGGEDOUT')) {
							//console.log("LOGGEDOUT!");
							if($('LOGGEDIN'))
								$('LOGGEDIN').destroy();
							$$('.authArea').setStyle('display','none');
						}
						$('loginForm').reset();
					}
				}).send();
			}
		});
		//X button (cancel window and close)
		var loginX = login.content.getElements('.X');
		loginX.each(function(X) { 
			X.addEvent('click',function() { 
				//create hidden CLOSE element to bypass form validation 
				new Element('div',{id: 'CLOSE', style: 'display: none'}).inject(document.body);
				login.trigger(); 
			}); 
		});
	//   END LOGIN BOX   //
	//--------------------------------//
});