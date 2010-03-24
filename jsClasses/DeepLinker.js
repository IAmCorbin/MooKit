/**
 * A Class to enable Deep Linking and page caching in an AJAX Application
 * March 23, 2010
 *
 * @author Corbin Tarrant
 *  ___            ___         __ 
 *    |   /\  |\/| |       __   |_/  |__   -   _
 *  _|_ /  \ |  | |___  |__|  | \  |__|  |  |  |
 * 
 * @link http://www.IAmCorbin.net
 * @version 1
 *
 * @package MooKit
 * 
 * @requires MooTools 1.2
 * @link http://mootools.net/
 */
var DeepLinker = new Class({
	Implements: [Options,Events],
	/** 
	  * @var int options.time 		duration between hash checks (in ms) 
	  * @var array options.cache 	preload the cache with hash objects containing page data
	  * @var bool options.cookies 	switch using cookies on/off
	  * @var bool options.DEBUG 	switch debug messages on/off
	  */
	options: {
		time: 1000,
		cache: [],
		cookies: true,
		cookieLife: 0,
		DEBUG: false
		/*
		onUpdate: $empty
		onComplete: $empty
		*/
	/** Constructor 
	 *@var this.hashMonitor 	ID of the periodical checkHash function to use for clearing later
	 *@var this.lastHash 	the last hash checked
	 *@var this.cache 		array of hashes containing cached page data
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
	/** check the current hash */
	},checkHash : function() { 
		if(window.location.hash !== this.lastHash) {
			this.debug("|------  HASH CHANGED  :"+window.location.hash+":------|");
			if(window.location.hash == '') window.location.hash = "#front"
			//if(this.lastHash) {
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
	},debug: function(input) { if(this.options.DEBUG && window.console) console.log(input); 
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