var DEBUG = true;
function debug(input) { if(DEBUG && window.console) console.log(input); }
/**
  * @function dynamically add array of CSS Stylesheets and JavaScripts
  * @param array styles 	array of styles ( full directory path and filenames )
  * @param array scripts 	array of scripts ( full directory path and filenames )
  */
function addAssets(styles,scripts) {
	//if previously loaded, destroy old css and javascript 
	styles.each(function(style) { 
		style = 'CSS'+style.replace(/.+\//,"").replace(/\.css\.php$/,"").replace(/\.css$/,"");
		if($(style))	$(style).destroy();
	});
	scripts.each(function(script) {
		script = 'JS'+script.replace(/.+\//,"").replace(/\.js$/,"");
		if($(script))	$(script).destroy();
	});
						
	//load CSS
	styles.each(function(style) { //for id : strip directory and extention, prepend "CSS"
		new Asset.css(style, { id: 'CSS'+style.replace(/.+\//,"").replace(/\.css\.php$/,"").replace(/\.css$/,"") });
	});
	//load javascript
	scripts.each(function(script) { //for id : strip directory and extention, prepend "JS"
		new Asset.javascript(script, { id: 'JS'+script.replace(/.+\//,"").replace(/\.js$/,"") });
	});
}
/**
  * @function refresh the content area of the page
  * @param bool assets 	add assets switch
  * @param bool secure 	secure authUpdate switch
  */
function refreshContent(assets, secure) { 
	//unless assets are turned off, add them by default
	if(assets != 0)	assets=1;
	new Request.JSON({
		url: 'CodeCore/php/authUpdate.php',
		onSuccess: function(responseJSON, responseTEXT) {
			debug("responseJSON : ");
			$('content').setStyle('opacity','0');
			console.dir(responseJSON);
			debug("responseTEXT : "+responseTEXT);
			//set html
			$('content').set('html',responseJSON.html);
			(function() { $('content').set('tween',{duration: '1000'}).fade('1'); }).delay(500); //fade in content
			//add stylesheets and JavaScripts
			if(assets==1)	addAssets(responseJSON.styles,responseJSON.scripts);
		}
	}).send('json={"secure": '+secure+'}');
}
/**
  * CodeCore Main JavaScript Event
  */
window.addEvent('domready', function() {

	//Set Up AJAX DeepLinker
	var DL = new DeepLinker('content',{
				cookies: false,
				onUpdate: function() {
					switch(window.location.hash) {
						case "#test1":
							this.container.load('CodeCore/php/test1.php');
							document.title="MooKit Version 1 - test1";
							break;
						case "#test2":
							this.container.load('CodeCore/php/test2.php');
							document.title="MooKit Version 1 - test2";
							break;
						case "#test3":
							this.container.load('CodeCore/php/test3.php');
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
