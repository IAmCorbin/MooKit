/**
  * @function Sinple Debug Function to easily allow all debugging messages to be turned on/off with a switch
  * @param the debug message to display
  */
function debug(input) { if(DEBUG && window.console) console.log(input); }


/**
  * @function take a php response, check for errors, and return the JSON
  * @param the php response from an ajax call
  * @param Error Box - for error display
  */
function handleResponse(response, errorBox) {
	if(DEBUG)
		$('debugBox').set('html',response);
	//make sure proper JSON was returned and not a php error
	if(!response.test('^\{[^\}]+\}$')) {
		if(DEBUG) 
			alert('JSON ERROR! : '+response);
		
		//display an error message to the user
		$(errorBox).setStyle('display','block');
		$(errorBox).set('html',"Fatal Error. Please Contact Administrator if this persists");
		
		//Log Error
		new Request.JSON({
			url: 'codeCore/php/logError.php',
			onSuccess: function(responseJSON, responseTEXT) {
				debug("PHP error logged");
			}
		}).send('json={"error": "'+response.clean()+'"}');
		
		return false;
	}
	//decode JSON and return
	return JSON.decode(response);
}

/**
  * @function take a php response, check for errors, and return the JSON
  * @param json.status
  * @param Error Box - for error display
  */
function checkJSONerrors(status, errorBox) {
	switch(status) {
		case "E_MISSING_DATA":
			$(errorBox).set('html',"Missing Data");
			break;
		case "E_FILTERS":
			$(errorBox).set('html',"Invalid Input, please try again or contact the administrator if this problem persists");
			break;
		case "E_BADPASS":									
			$(errorBox).set('html',"The passwords you entered do not match");
			break;
		case "E_DUPLICATE":
			$(errorBox).set('html',"Alias or Email Address already exists, try a different alias or email address");
			break;
		case "E_INSERT":
			$(errorBox).set('html',"Error adding user, please try again later. If problem persists contact the administrator");
			break;
	}
}

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
			debug(responseJSON);
			debug("responseTEXT : "+responseTEXT);
			//set html
			$('content').set('html',responseJSON.html);
			(function() { $('content').set('tween',{duration: '1000'}).fade('1'); }).delay(500); //fade in content
			//add stylesheets and JavaScripts
			if(assets==1)	addAssets(responseJSON.styles,responseJSON.scripts);
		}
	}).send('json={"secure": '+secure+'}');
}