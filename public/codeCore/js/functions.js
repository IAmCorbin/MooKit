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
	//allow a reponse of 1
	if(response == 1)
		return response;
	//make sure proper JSON was returned and not a php error
	if(!response.test('^\\[?(\{[^\}]+\}\,?)+\\]?$')) {
		if(DEBUG) 
			alert('JSON ERROR! : '+response);
		
		if(errorBox) {	
			//display an error message to the user
			$(errorBox).setStyle('display','block');
			$(errorBox).set('html',"Fatal Error. Please Contact Administrator if this persists");
		}
		
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
		case "E_NOAUTH":
			$(errorBox).set('html',"Unauthorized");
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
  * @function convert an access_level integer to the access title
  * @param the access_level to convert
  */
function getHumanAccess(access_level) {
	switch(access_level) {
		case 0:
			return "Unauthorized User";
		case 1:
			return "Basic User";
		case 3:
			return "Creator";
		case 7:
			return "Administrator";
		default:
			return "Unknown (Error?)";
			
	}
}
/**
  * @function load the user edit interface
  * @param the module name
  * @param the container to load into
  */
function CORE_LOAD(module, container) {
	switch(module) {
		case 'AdminPanel':
			$(container).set('load',{
				onSuccess: function(r1,r2,r3) {
					if(r3 == "Unauthorized")
						return;
					addAssets(["style/secure/adminPanel.css.php"],["codeCore/js/secure/adminPanel.js","codeCore/js/secure/adminUsers.js","codeCore/js/secure/adminLinks.js"]);
				}
			});
			fancyLoad($(container),'codeCore/php/secure/adminPanel.php');
			break;
		default:
			break;
	}
}

/**
  * @function create smooth ajax loading (fade out/in)
  * @param the container to load into (also the fading element)
  * @param what to load
  */
function fancyLoad(container, url, speed) {
	speed = typeof(speed) == "undefined" ? 250 : speed;
	container.fade(0);
	( function() {container.load(url); } ).delay(speed);
	( function() { container.fade(1); } ).delay(speed*2);
}

/**
  * Find the absolute position of an element on the page by iterating through all parents
  * returns an object with the X and Y position
  */
function getXY(obj) {
	if (obj) {
		var curleft = 0;
		var curtop = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft;
			curtop = obj.offsetTop;
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			}
		}
		return { X: curleft, Y: curtop };
	}
}