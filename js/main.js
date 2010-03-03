window.addEvent('domready', function() {
	
	if($('LOGGEDIN')) {
		authArea = $$('.authArea');
		authArea.setStyle('display','block');
	}		
	
	//Sliding Button Animation
	$$('.slideBtn').each(function(btn) {
		var prev = btn.getPrevious('a').set('tween',{ duration: 200 });
		var span = prev.getElement('span');
		btn.addEvents({
			//slide out
			mouseenter: function(e) { 
				prev.tween('width',245);
				span.fade('in');
			},
			//slide back in
			mouseleave:function(e) {
				prev.tween('width',70);
				span.fade('out');
			}
		});
	});
	
	//store all form errors and set fade
	var formErrors = $$('.formError');
	formErrors.fade(0.4);
	
	//-----------------------------------//
	// SETUP SIGNUP BOX  //
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
						login.fadeLightbox.delay('500',this);
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
	
	
	//--------------------------------//
	// SETUP LOGIN BOX //
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
	
	//debug box
	$('debugBox').set('tween',{duration: 100});
	$('debugBox').addEvents({
		//raise box on mouseenter
		mouseenter: function() {
			this.tween('height',this.getStyle('height').toInt()+140+'px');
		},
		//lower box on mouseleave
		mouseleave: function() {
			if( this.getStyle('height').toInt() > 140 )
				this.tween('height',this.getStyle('height').toInt()-140+'px');
			else //prevent a negative height value for IE
				this.tween('height','10px');
		},	
		//expand further and stay up if clicked, toggle back when clicked again
		click: function() {
			if(this.getStyle('height').toInt() < 160 )
				this.tween('height','400%');
			else if(this.getStyle('height').toInt() > 160)
				this.tween('height','25%');
		}
	});

}); //END DOMREADY EVENT


