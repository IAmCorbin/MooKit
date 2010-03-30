<?php
if(!defined('INSITE'))  echo 'Not Authorized. Please Visit <a href="../">The Main Site</a>'; else { 
/**
  * contains MooKit Class
  * @package MooKit
  */
/**
 * A Class used to build a new MooKit application
 *
 * @author Corbin Tarrant
 * @author adapted from {@link http://seanhess.net/posts/simple_templating_system_in_php }
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
	 * @param string $mainTpl		The Main Template Location
	 * @param 
	 * @param 
	 */
	public function __construct($mainTpl='templates/main.tpl.php') {
		//Setup main Template
		$this->main = new Template($mainTpl,false,true);
		//initialize arrays
		$this->scriptsPublic = array();
		$this->scriptsSecure = array();
		$this->stylesPublic = array();
		$this->stylesSecure = array();
		//set core JavaScripts
		$this->addScript('CodeCore/js/debug.js');
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
	  * @param bool $secure		Secure switch, only allow for authorized users
	  */
	public function addScript($script, $secure=NULL) {
		if($secure) {
			if($this->SECURE()) {  //secure, use Regex to strip directory location and  '.js'
				array_push($this->scriptsSecure,'<script type="text/javascript" id="JS'.preg_replace("/\.js$/","",preg_replace("/.+\//","",$script)).'" src="'.$script.'"></script>'); 
			}
		} else //public
			array_push($this->scriptsPublic,'<script type="text/javascript" src="'.$script.'"></script>'); 
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
	  */
	public function RUN() {
	
		//Grab all public stylesheets
		foreach(new DirectoryIterator('style') as $style) { $this->addStyle('style',$style); }
		//if secure, add secure stylesheets
		if($this->SECURE()) { foreach(new DirectoryIterator('style/secure') as $style) { $this->addStyle('style/secure',$style,'secure'); }}
		
		//set all styles and scripts for main template
		$this->main->styles = array_merge($this->stylesPublic, $this->stylesSecure);
		$this->main->scripts = array_merge($this->scriptsPublic, $this->scriptsSecure);
		
		if(defined('DEBUG')) /* add debug area */		
			$this->main->debugTpl = new Template('templates/debug.tpl.php');
		else $this->main->debugTpl = null;
		
		//send output
		echo $this->main;
	}
}

} //end if(defined('INSITE')
?>