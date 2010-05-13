/**
 * @class A Class to enable Deep Linking and page caching in an AJAX Application
 * @author Corbin Tarrant
 *  ___            ___         __ 
 *    |   /\  |\/| |       __   |_/  |__   -   _
 *  _|_ /  \ |  | |___  |__|  | \  |__|  |  |  |
 * 
 * {@link http://www.IAmCorbin.net }
 * @version 1
 * March 23, 2010
 * @package MooKit
 * 
 * @requires MooTools 1.2
 * {@link http://mootools.net/}
 *
 * @property 	{element}	container 			The deep linking container
 * @property	{Hash[]}		cache				Hash array of cached page content
 * @property	{int}		hashMonitor			The periodical ID of the hashMonitor ( to use for clearing later )
 * @property	{string}		lastHash				the last hash checked
 * @property 	{int} 		options.time 			Duration between hash checks (in ms)
 * @property 	{string[]} 	options.cache 			Preload the cache with hash objects containing page data
 * @property 	{bool} 		options.cookies 		Switch cookies on/off
 * @property 	{int} 		options.cookieLife 		Cookie life in days
* @property 	{bool} 		options.DEBUG 		Switch debug messages on/off
* @property 	{$empty}	options.onUpdate 		Event fires when hash has been changed and is not cached, user should pass in a function that will execute container.load with different content for different hashes
 */
var DeepLinker = new Class({
	Implements: [Options,Events],
	options: {
		time: 1000,
		cache: [],
		cookies: true,
		cookieLife: 0,
		DEBUG: false
		/*
		onUpdate: $empty
		*/
	/** 
	  * @constructor
	  * @param 	{element}	container		the deep linking container
	  * @param	{Array}		options		set options
	  */
	},initialize: function(container, options) {
		//set deep linking container
		this.container = $(container);
		//merge in passed options
		this.setOptions(options);
		this.cache = new Array();
		this.lastCache = null;
		//start monitoring hash
		this.hashMonitor = (function() { this.checkHash(); }.bind(this)).periodical(this.options.time);
	/** @function check the current hash */
	},checkHash : function() { 
		if(window.location.hash !== this.lastHash) {
			this.debug("|------  HASH CHANGED  :"+window.location.hash+":------|");
			if(window.location.hash == '') window.location.hash = "#front"
				var cacheLocation = null;
				//check each page in cache to see if the data is already cached OR check for cookie
				if(this.options.cookies)
					var cached = Cookie.read(window.location.hash)
				else
					var cached = this.cache.some(function(item, index) { 
										if(item.hasValue(window.location.hash)) {
											cacheLocation = index;
											return true;
				/*if cache was found*/		}});
				if(cached) {
					//get cached content
					if(this.options.cookies) {
						this.debug("ALREADY CACHED: LOADING FROM COOKIE");
						var content = Cookie.read(window.location.hash);
						document.title = Cookie.read(window.location.hash+"title");
					} else {
						this.debug("ALREADY CACHED: LOADING FROM HASH");
						var content = this.cache[cacheLocation].get('content');
						document.title = this.cache[cacheLocation].get('title');
					}
					this.debug("content to load: "+content);
					//display cached content
					this.container.set('html',content);
				} else {
					//setup to cache when load is complete
					this.container.set('load',{
						onComplete: function() {
							this.saveCache();
						}.bind(this)
					});
					this.debug("LOADING CONTENT NOW - onUpdate fires");
					this.fireEvent('update');
				}
		} else if(window.location.hash == "#kill") {
			$clear(this.hashMonitor);
			console.dir(this.cache);
			this.debug('DeepLinker : Hash Monitor Has Been Terminated');
		} else if(window.location.hash == this.lastHash)
			this.debug("|------  No Hash Change   ||Cached Pages: "+this.cache.length+"|| ------|");
		
		//set the current hash to last hash for next check
		this.lastHash =  window.location.hash;
	/** 
	  * @function debugging function
	  * @param	{string}	input	debug message
	  */
	},debug: function(input) { if(this.options.DEBUG && window.console) console.log(input); 
	/** @function save the current content to appropriate cache */
	},saveCache: function() { 
		//do not cache secure content
		if(!$('LOGGEDIN')) {
			//cache content
			if(!this.lastHash) {
				if(this.options.cookies) {
					this.debug("CACHING TO COOKIE NOW - FIRST CACHE");
					Cookie.write(window.location.hash,this.container.get('html'), { duration: this.options.cookieLife }) ;
					Cookie.write(window.location.hash+"title",document.title, { duration: this.options.cookieLife }) ;
				} else {
					this.debug("CACHING TO HASH NOW - FIRST CACHE");
					this.cache[0] = new Hash({hash:window.location.hash,title:document.title,content:this.container.get('html')});
				}
			} else {
				if(this.options.cookies) {
					this.debug("CACHING TO COOKIE NOW : "+this.cache.length+this.container.get('html'));
					Cookie.write(window.location.hash,this.container.get('html'), { duration: this.options.cookieLife }) ;
				} else {
					this.debug("CACHING TO HASH NOW : "+this.cache.length+this.container.get('html'));
					this.cache[this.cache.length] = new Hash({hash:window.location.hash,title:document.title,content:this.container.get('html')});
				}
			}
		}
	}
});