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
	/** @var $VERSION				MooKit Version */
	var $VERSION = 'v0.7';
	/** @var $scriptsPublic			Public JavaScripts */
	var $scriptsPublic;
	/** @var Array $scriptsSecure		Authorized JavaScripts */
	var $scriptsSecure;
	/** @var Array $stylesPublic		Public CSS */
	var $stylesPublic;
	/** @var Array $stylesSecure		Authorized CSS */
	var $stylesSecure;
	/** @var Template $main			Main Template */
	var $main; 
	/**
	 * Constructor
	 *
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
		require_once 'codeCore/php/htmLawed1.1.9.1.php';
		require_once 'codeCore/php/functions.php';
		require_once 'codeSite/php/functions.php';
		
		//Start Session and regenerate Session ID for security
		session_start();
		session_regenerate_id();
		
		$_SESSION['SYSNAME'] = 'MooKit';
		
		//SECURITY
		//require authorized user for /secure/ files
		preg_match("/\/secure\//",$request)? $secure=true : $secure = false;
		if($secure) {
			//Security Check
			$security = new Security;
			if(!$security->check())  {
				//if not authorized return here without returning the file
				return;
			}
		}
		
		//FILE HANDLING
		if(is_readable($request) && !is_dir($request) ) {
			//return file and abort application
			include $request;
			exit();
		}
	}
	/**
	  * Application Initialization
	  *
	  *@param string $mainTpl		The Main Template Location
	  */ 
	public function INIT($mainTpl='templates/main.tpl.php') {
		//Setup main Template
		$this->main = new Template($mainTpl,false,true);
		//initialize arrays
		$this->scriptsPublic = array();
		$this->scriptsSecure = array();
		$this->stylesPublic = array();
		$this->stylesSecure = array();
		//set core JavaScripts
		$this->addScript('codeCore/Classes/js','mootools-1.2.4-core-yc.js');
		$this->addScript('codeCore/Classes/js','mootools-1.2.4.4-more.js');
		$this->addScript('codeCore/js/errorHandler.js');
		$this->addScript('codeCore/Classes/js','LightBox.js');
		$this->addScript('codeCore/Classes/js','DeepLinker.js');
		if(defined('DEBUG'))	$this->addScript('codeCore/js','debug.js');
	}
	/**
	  * Add a new CSS stylesheet
	  *
	  * @param bool $dir		stylesheet location - directory
	  * @param bool $style		stylesheet location - file
	  * @param bool $secure		Secure switch, only allow for authorized users
	  */
	public function addStyle($dir,$style, $secure=NULL) {
		//make sure file is .css or .css.php
		if( preg_match("/\.css$/",$style) || preg_match("/\.css\.php$/",$style)) {
			if($secure) {
				if($this->SECURE()) { //secure, use Regex to strip directory location and .css or .css.php
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
				if($this->SECURE()) {  //secure, use Regex to strip directory location and  '.js'
					array_push($this->scriptsSecure,'<script type="text/javascript" id="JS'.preg_replace("/\.js$/","",preg_replace("/.+\//","",$script)).'" src="'.$dir.'/'.$script.'"></script>'); 
				}
			} else //public
				array_push($this->scriptsPublic,'<script type="text/javascript" src="'.$dir.'/'.$script.'"></script>'); 
		}
	}
	/** Check for secure session */ 
	public function SECURE() { 
		if($_SESSION['auth'] === 1) 
			return true; 
		else 
			return false; 
	}
	/**
	  * Runs the application, outputs to the user's browser
	  * 
	  * @param bool styles 		used to disable all the styles
	  * @param bool scripts		used to disable all the scripts
	  */
	public function RUN($styles=TRUE,$scripts=TRUE) {
		if($styles) {
			//Grab all public stylesheets - all in style/
			foreach(new DirectoryIterator('style') as $style) { $this->addStyle('style',$style); }
			//if secure, add secure stylesheets - all in style/secure/
			if($this->SECURE()) { foreach(new DirectoryIterator('style/secure') as $style) { $this->addStyle('style/secure',$style,'secure'); }}
			
			//set all styles for main template
			$this->main->styles = array_merge($this->stylesPublic, $this->stylesSecure);
		}
		if($scripts) {
			//LOAD codeCore JavaScripts
			    //Grab all public JavaScripts - all in codeCore/js/
			    foreach(new DirectoryIterator('codeCore/js') as $script) { $this->addScript('codeCore/js',$script); }
			    //if secure, add secure JavaScript - all in codeCore/js/secure/
			    if($this->SECURE()) { foreach(new DirectoryIterator('codeCore/js/secure') as $script) { $this->addScript('codeCore/js/secure',$script,'secure'); }}
			//LOAD codeSite JavaScripts
			    //Grab all public JavaScripts - all in codeSite/js/
			    foreach(new DirectoryIterator('codeSite/js') as $script) { $this->addScript('codeSite/js',$script); }
			    //if secure, add secure JavaScript - all in codeSite/js/secure/
			    if($this->SECURE()) { foreach(new DirectoryIterator('codeSite/js/secure') as $script) { $this->addScript('codeSite/js/secure',$script,'secure'); }}
			
			//set all scripts for main template
			$this->main->scripts = array_merge($this->scriptsPublic, $this->scriptsSecure);
		}
		
		if(defined('DEBUG')) /* add debug area */		
			$this->main->debugTpl = new Template('templates/debug.tpl.php');
		else $this->main->debugTpl = null;
		
		//send output
		echo $this->main;
	}
}
?>