<?php
/**
  * contains MooKit Class
  * @package MooKit
  */
/**
 * A Class used to build a new MooKit application
 *
 * @author Corbin Tarrant
 * @copyright March 29th, 2010
 * @package MooKit
 */
class MooKit {
	/** @var 	string		$VERSION		MooKit Version */
	var $VERSION = 'v0.8';
	/** @var 	array		$scriptsPublic		Public JavaScripts */
	var $scriptsPublic;
	/** @var 	array 		$scriptsSecure		Authorized JavaScripts */
	var $scriptsSecure;
	/** @var 	array 		$stylesPublic		Public CSS */
	var $stylesPublic;
	/** @var 	array 		$stylesSecure		Authorized CSS */
	var $stylesSecure;
	/** @var	Template 	$main			Main Template */
	var $main; 
	/**
	 * Constructor
	 *
	 * Handles the request and returns requested files if allowed
	 * @param string $request			The application request $_GET['request'] - this is set by .htaccess
	 *								For Reference:
	 * 								## Force connections through index.php for handling
	 * 								## if not already index.php
	 *								RewriteCond %{REQUEST_URI} !/index\.php$
	 *								## and request has not already been set
	 *								RewriteCond %{QUERY_STRING} !request=
	 *								RewriteRule ^(.+)$ /index.php?request=$1 [L]
	 */
	
	public function __construct($request) {
		//Functions
		require_once ROOT_DIR.'codeCore/php/htmLawed1.1.9.1.php';
		require_once ROOT_DIR.'codeCore/php/functions.php';
		require_once ROOT_DIR.'codeSite/php/functions.php';
		
		//Start Session and regenerate Session ID for security
		session_start();
		session_regenerate_id();
		
		$_SESSION['SYSNAME'] = 'MooKit';
		
		//SECURITY
		//require authorized user for /secure/ files
		preg_match("/\/secure\//",$request)? $secure=true : $secure = false;
		if($secure) {
			//Security Check
			if(!Security::clearance()) {
				//if not authorized return here without returning the file
				return;
			}
		}

		//FILE HANDLING
		if(is_readable($request) && !is_dir($request) ) {
			
			if(preg_match('/\.(php|css|js)$/',$request)) {
				//return file if a valid type
				include $request;
				exit();
			}
			if(preg_match('/\.(gif|jpg|jpeg|png)$/',$request,$type)) {
				//return image
				if($type[0]=='.jpg' || $type[0]=='.jpeg') $type='jpeg';
				if($type[0]=='.gif') $type='gif';
				if($type[0]=='.png') $type='png';
				//set content type
				header('Content-Type: image/'.$type[0]);
				//output image
				readfile($request);
				exit();
			}
		}
		
		//~ if(file_exists('cache/cache.htm')) {
			//~ require ROOT_DIR.'cache.htm';
			//~ exit("CACHED!");
		//~ }
	}
	/**
	  * Application Initialization
	  *
	  *@param string $mainTpl		The Main Template Location
	  */ 
	public function INIT($mainTpl='templates/main.tpl.php') {
		//Setup main Template
		$this->main = new Template($mainTpl);
		//initialize arrays
		$this->scriptsPublic = array();
		$this->scriptsSecure = array();
		$this->stylesPublic = array();
		$this->stylesSecure = array();
		//set core JavaScripts
		$this->addScript('codeCore/Classes/js','mootools-1.2.4-core-nc.js');
		$this->addScript('codeCore/Classes/js','mootools-1.2.4.4-more.js');
		if(DEBUG) $this->addScript('codeCore/js','debug.js'); else array_push($this->scriptsPublic,'<script type="text/javascript"> var DEBUG = false </script>');
		$this->addScript('codeCore/js','errorHandler.js');
		//load core JavaScript Classes
		$this->addScript('codeCore/Classes/js','LightBox.js');
		$this->addScript('codeCore/Classes/js','DeepLinker.js');
		$this->addScript('codeCore/Classes/js','PaginatingTable.js');
		$this->addScript('codeCore/Classes/js','SortingTable.js');
		
	}
	/**
	  * Add a new CSS stylesheet
	  *
	  * @param 	string 	$dir		stylesheet location - directory
	  * @param 	string 	$style	stylesheet location - file
	  * @param 	bool 	$secure	Secure switch, only allow for authorized users
	  */
	public function addStyle($dir,$style, $secure=NULL) {
		//make sure file is .css or .css.php
		if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style)) {
			if($secure) {
				if(Security::clearance()) { //secure, use Regex to strip directory location and .css or .css.php
					array_push($this->stylesSecure,'<link id="CSS'.preg_replace("/\.css$/","",preg_replace("/\.css\.php$/","",preg_replace("/.+\//","",$style))).'" rel="stylesheet" type="text/css" href="'.$dir.'/'.$style.'" />');
				}
			} else //public
				array_push($this->stylesPublic,'<link rel="stylesheet" type="text/css" href="'.$dir.'/'.$style.'" />'); 
		}
	}
	/**
	  * Add a new JavaScript file
	  *
	  * @param bool $dir		JavaScript location - directory
	  * @param bool $script		JavaScript location - file
	  * @param bool $secure		Secure switch, only allow for authorized users
	  */
	public function addScript($dir, $script, $secure=NULL) {
		//make sure file is .js
		if( preg_match("/\.js$/",$script) ) {
			if($secure) {
				if(Security::clearance()) {  //secure, use Regex to strip directory location and  '.js'
					array_push($this->scriptsSecure,'<script type="text/javascript" id="JS'.preg_replace("/\.js$/","",preg_replace("/.+\//","",$script)).'" src="'.$dir.'/'.$script.'"></script>'); 
				}
			} else //public
				array_push($this->scriptsPublic,'<script type="text/javascript" src="'.$dir.'/'.$script.'"></script>'); 
		}
	}
	/**
	  * Cache a php script
	  */
	public function cachePHP($path, $cachefile=NULL) {
		if(!$cachefile)
			$cachefile = 'cache/'.preg_replace('/\//','_',$path);
		//start the output buffer
		ob_start();
		//output template to buffer
		require $path;
		// open the cache file for writing
		$file = fopen($cachefile, 'w');
		// save the contents of the output buffer to the file
		fwrite($file, ob_get_contents());		
		//turn off output buffer
		ob_end_clean();
		// close the file
		fclose($file);
	}
	/**
	  * Cache contents of a template to a file
	  * @param Template $template 	the template to cache
	  * @param string $cachefile 		[path and] filename of cache file
	  */
	public function cacheTpl(&$tpl, $cachefile="cache/cache.htm") {
		//start the output buffer
		ob_start();
		//output template to buffer
		echo $tpl;
		// open the cache file for writing
		$file = fopen($cachefile, 'w');
		// save the contents of the output buffer to the file
		fwrite($file, ob_get_contents());		
		//turn off output buffer
		ob_end_clean();
		// close the file
		fclose($file);
	}
	/**
	  * Runs the application, outputs to the user's browser
	  * 
	  * @param bool styles 		switch styles on/off
	  * @param bool scripts		switch scripts on/off
	  */
	public function RUN($styles=TRUE,$scripts=TRUE) {
		if($styles) {
			//Grab all public stylesheets - all in style/
			foreach(new DirectoryIterator('style') as $style) { 
				//make sure file is .css or .css.php
				if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style))
					$this->addStyle('style',$style); 
			}
			//if secure, add secure stylesheets - all in style/secure/
			if(Security::clearance()) { foreach(new DirectoryIterator('style/secure') as $style) { 
				//make sure file is .css or .css.php
				if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style))
					$this->addStyle('style/secure',$style,'secure'); }}
			
			//set all styles for main template
			$this->main->styles = array_merge($this->stylesPublic, $this->stylesSecure);
		}
		if($scripts) {
			//LOAD codeCore JavaScripts
			    //Grab all public JavaScripts - all in codeCore/js/
			    foreach(new DirectoryIterator('codeCore/js') as $script) { 
				//do not include debug.js here - that is handled in the constructor
				if($script != 'debug.js')
					//make sure file is .js
					if(preg_match("/\.js$/",$script))
						$this->addScript('codeCore/js',$script); 
			    }
			//LOAD codeSite JavaScripts
			    //Grab all public JavaScripts - all in codeSite/js/
			    foreach(new DirectoryIterator('codeSite/js') as $script) { 
					//make sure file is .js
					if(preg_match("/\.js$/",$script))
						$this->addScript('codeSite/js',$script); 
				}
			//set all scripts for main template
			$this->main->scripts = array_merge($this->scriptsPublic, $this->scriptsSecure);
		}
		
		if(DEBUG) /* add debug area */		
			$this->main->debugTpl = new Template('templates/debug.tpl.php');
		else $this->main->debugTpl = null;
		
		
		//cache page
		//$this->cacheTpl($this->main);
		//send output gzip encoded to browser
		echo $this->main->run(false);
	}
}
?>