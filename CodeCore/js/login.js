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
						switch(json.status) {
							case  "LOGGEDIN":
								$('loginPHPError').setStyle('display','none');	
								$('loginPHPError').setStyle('display','none');	
								//update hash with location
								window.location.hash = "#welcome";
								
								login.trigger();
								//load content
								new Request({
									method: 'post',
									url: 'CodeCore/php/authUpdate.php',
									onSuccess: function(response) {
										//decode JSON return
										json = JSON.decode(response);
										//set html
										$('content').setStyle('opacity','0');
										$('content').set('html',json.html);
										(function() { $('content').set('tween',{duration: '1000'}).fade('1'); }).delay(500); //fade in secure content
										
										//if previously loaded, destroy old javascript and css
										json.styles.each(function(style) { 
											style = 'CSS'+style.replace(/.+\//,"").replace(/\.css\.php$/,"").replace(/\.css$/,"");
											if($(style))	$(style).destroy();
										});
										json.scripts.each(function(script) {
											script = 'JS'+script.replace(/.+\//,"").replace(/\.js$/,"");
											if($(script))	$(script).destroy();
										});
										
										//load CSS
										json.styles.each(function(style) { //for id : strip directory and extention, prepend "CSS"
											new Asset.css(style, { id: 'CSS'+style.replace(/.+\//,"").replace(/\.css\.php$/,"").replace(/\.css$/,"") });
										});
										//load javascript
										json.scripts.each(function(script) { //for id : strip directory and extention, prepend "JS"
											new Asset.javascript(script, { id: 'JS'+script.replace(/.+\//,"").replace(/\.js$/,"") });
										});
									}
								}).send();
								//clear the login form
								$('loginForm').reset();
								break;
							case "LOGGEDOUT":
								$('loginPHPError').setStyle('display','none');	
								//remove all auth content from page
								$$('.secureArea').set('tween',{duration:'2000'}).fade('0');
								(function() { $$('.secureArea').destroy(); }).delay(2300,this);
								$$('.login_buttonWrap').fade(1);
								$$('.signup_buttonWrap').fade(1);
								break;
							case "IN":
								$('loginPHPError').setStyle('display','none');	
								login.trigger(); 
								$('loginForm').reset(); 
								break;
							case "ERROR_FILTER":
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