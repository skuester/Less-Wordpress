<?php
/*
 * WPHELPER 1.54
 * 
 * This is a group of classes to speed up wordpress development
 * NOTE: Loaded (via require_once) by the GreyMatter library
 * 
 * Change Log
 * 
 * 1.52 Added Comment Meta (registry function to save comment meta)
 * 1.53 Added wpSettings class for wordpress configuration
 * 1.54 Classes now auto-included from the classes folder. The need for 
 * 		wphelper-config and possible user configuration has been eliminated.
 * 		WPHELPER_PATH now auto-senses its location based on __FILE__ and get_bloginfo()
 */


/* WPHELPER PATH
 * Method: 
 * 1. Based on bloginfo, get path starting with the template dir to the wpHelper folder
 * 2. Replace "\" to correct windows stupidity (convert path to url)
 * 3. Combine: URL to themes folder + url to wpHelper folder
 */
$tURL = get_bloginfo('template_directory');
$fromTDir = strstr(dirname(__FILE__), basename($tURL));
$fromTDir = str_replace('\\', '/', $fromTDir);
define('WPHELPER_PATH', dirname($tURL).'/'.$fromTDir);

/* Auto Includer v 1.0
 * Includes all files in the CLASSES folder, starting with the *-core.php file first
 */
foreach ($files = glob(dirname(__FILE__) . '/classes/*.php', GLOB_NOSORT) as $f):
	//Get Core First
	if(strstr($f, '-core.php')){ 
		require_once $f;
		//Get sub classes
		foreach($files as $class){ 
			if(!strstr($class, '-core.php')) require_once $class; 
		}
		break;
	}
endforeach;

?>
