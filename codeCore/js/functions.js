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
		url: 'codeCore/php/updateContent.php',
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