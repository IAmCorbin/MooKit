/**
  * Main JavaScript Event
  */
window.addEvent('domready', function() {

	//display outdated browser error if applicable
	//Outdated Web Browser Message
	if(Browser.Engine.trident4 || Browser.Engine.webkit419) {
		outdatedBrowserError = $('outdatedBrowserError');
		outdatedBrowserError.appendText('You are using an outdated browser, some features of this site may not work. ');
		new Element('a', {
			target: '_blank',
			href: 'http://getfirefox.com',
			html: 'Get Firefox Here'
		}).inject(outdatedBrowserError);
		outdatedBrowserError.setStyles({
			fontSize: '25px',
			fontWeight: 'bold',
			fontFamily: 'Monospace',
			background: '#A55',
			border: 'solid 3px #FAA',
			width: '60%',
			margin: 'auto',
			display: 'block'
		});
		
	}

	//grab content area
	contentArea = $('content');
	//set fade speed
	contentArea.set('tween',{duration: '100' });
	//Set Up AJAX DeepLinker
	var DL = new DeepLinker('content',{
				cookies: false,
				onUpdate: function() {
					switch(window.location.hash) {
						case "#welcome":
							this.container.set('html',"WELCOME!!!");
							document.title="MooKit Version 1 - Logged In";
							break;
						case "#front":
							fancyLoad(this.container,'codeSite/php/test3.php');
							document.title="MooKit Version 1 - test1";
							break;
						case "#test1":
							fancyLoad(this.container,'codeSite/php/test1.php');
							document.title="MooKit Version 1 - test1";
							break;
						case "#test2":
							fancyLoad(this.container,'codeSite/php/test2.php');
							document.title="MooKit Version 1 - test2";
							break;
						case "#test3":
							fancyLoad(this.container,'codeSite/php/test3.php');
							document.title="MooKit Version 1 - test3";
							break;
						case "#adminPanel":
							CORE_LOAD('AdminPanel',this.container);
							break;
						case "#logout":
							new Request.HTML({
								url: 'codeSite/php/logout.php',
								onRequest: function() {
									contentArea.fade(0);
								},
								onSuccess: function() {
									updateApp();
									//fade login and signup buttons back in
									$$('.login_buttonWrap').fade(1);
									$$('.signup_buttonWrap').fade(1);
									window.location.hash = "#test2";
								}
							}).send();
							
							break;
						case "#secret":
							new Element('div',{html: "WOW, LOOK WHAT YOU FOUND!"}).inject(document.body,'bottom');
							break
						default:
							this.saveCache();
							break;
					}
				}
			});

	//hide signup/login buttons if already logged in
	if($('LOGGEDIN')) {
		$$('.login_buttonWrap').fade(0);
		$$('.signup_buttonWrap').fade(0);
	}
	
	//Sliding Button Animation
	$$('.slideBtn').each(function(btn) {
		var prev = btn.getPrevious('a').set('tween',{ duration: 200 });
		var span = prev.getElement('span');
		btn.addEvents({
			//slide out
			mouseenter: function(e) { 
				this.getParent().tween('width',245);
				(function() { prev.tween('width',245); }).delay(200);
				span.fade('in');
			},
			//slide back in
			mouseleave:function(e) {
				prev.tween('width',70);
				this.getParent().tween('width',70);
				span.fade('out');
			}
		});
	});

}); //END DOMREADY EVENT