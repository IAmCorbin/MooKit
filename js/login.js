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
			if(loginValidator.validate()) {
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
						if(json.status === "LOGGEDIN") {
							login.trigger();
							//load content
							new Request({
								method: 'post',
								url: 'php/jsAuth.php',
								onSuccess: function(response) {
									//set html
									content = $('content');
									content.setStyle('opacity','0');
									content.set('html',response);
									(function() { $('content').set('tween',{duration: '1000'}).fade('1'); }).delay(500);
									//load javascript
									var myScript = new Asset.javascript('js/post.js');
									var myScript = new Asset.javascript('js/userCSS.js');
									var myScript = new Asset.javascript('js/mainAuth.js');
								}
							}).send();
							
							//clear the login form
							$('loginForm').reset();
						} else if(json.status === "LOGGEDOUT") {
							//remove all auth content from page
							$$('.secureArea').set('tween',{duration:'2000'}).fade('0');
							(function() { $$('.secureArea').destroy(); }).delay(2300,this);
						} else if(json.status === "IN") {  
							login.trigger(); 
							$('loginForm').reset(); 
						}
					}
				}).send();
			}
		});
	//   END LOGIN BOX   //
	//--------------------------------//
});