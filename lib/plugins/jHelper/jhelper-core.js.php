<?php header("content-type: application/x-javascript");

//PHP values
$jhTemplateDir = $_GET['t'];

echo "
	/* 
	 * jHelper v1.1
	 * 
	 * Requires:
	 * - jQuery
	 * - jQuery form validation engine
	 * - FancyBox
	 * 
	 * Description: Implement and control JS functionality using convention-based html classes. 
	 *				This is a self-instantiating object. (See bottom of prototype)
	 * 
	 * CHANGE LOG:
	 * 1.1  -jHelper core now generated with PHP in order to access data derived from php, such as the template directory.
	 * 		-Non-php selectors are now defined in prototype.init. 
	 *
	 * TODO: Make this.modalLinks selector 'a.j-modal' to increase speed? Would this limit the function?
	 */
	
	var jHelper = function() {
	
		//Module Values from PHP
		this.templateDirectory = '$jhTemplateDir'; 
		
		//Run Master INIT
		this.init();
	};
";

/* NOTES: 
 * This.templateDirectory could be set using this.getSiteRoot() + '/wp-content/themes/' + theme
 * 
 * 
 * //Load prototype - doesn't require jHelper.prototype to be namespaced with $(function(){});
 * var file = document.createElement('script');
 * file.setAttribute("type", "text/javascript");
 * file.setAttribute("src", "http://localhost/localhomeappliance/wp-content/themes/default-1-3/lib/plugins/jHelper/jhelper-prototype.js");
 * document.getElementsByTagName('head')[0].appendChild(file);
*/

?>