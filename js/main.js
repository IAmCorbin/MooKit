var DEBUG = true;
function debug(input) { if(DEBUG && window.console) console.log(input); }

window.addEvent('domready', function() {

	//Set Up AJAX DeepLinker
	var DL = new DeepLinker('content',{
				cookies: false,
				onUpdate: function() {
					switch(window.location.hash) {
						case "#test1":
							this.container.load('php/test1.php');
							document.title="MooKit Version 1 - test1";
							break;
						case "#test2":
							this.container.load('php/test2.php');
							document.title="MooKit Version 1 - test2";
							break;
						case "#test3":
							this.container.load('php/test3.php');
							document.title="MooKit Version 1 - test3";
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
	//Setup Ajax Links	
	var links = $$('.ajaxLink');
	links.each(function(link) {
		link.addEvent('click',function(e) {
			e.stop();
			window.location.hash = link.get('href');
		});
	});

	//secureArea display fix
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


