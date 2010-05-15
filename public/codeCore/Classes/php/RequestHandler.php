<?php
/**
  * contains RequestHandler Class
  * @package MooKit
  */
/**
 * Handle the $_GET['REQUEST'] set by .htaccess
 *
 * The RequestHandler will take the $_GET['REQUEST'] set by .htaccess  and either return allowed filetypes and exit(); or continue processing unaltered
 *
 * @author Corbin Tarrant
 * @copyright March 29th, 2010
 * @package MooKit
 */
class RequestHandler {
	/**
	 * Constructor
	 *
	 * Handles the request and returns requested files if allowed
	 * @param 	string 	$request		The application request $_GET['request'] - this is set by .htaccess
	 */			
	public function __construct($request) {
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
}

?>