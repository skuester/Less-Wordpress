<?php 
/* Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * Menu v1.1
 * 
 * TODO: Possible completion of Insert and Include Loctions methods to allow for more seemless 
 * managing of menus within the tempalte in a similar way that Joomla does. (Specify a location and include that location).
 * As of this version, wp_nav_menu is more than sufficient in its simplicity and ability to use in this way.
 */
class menu extends wphelper
{
	function __construct($mainArg, $alt_title = FALSE)
	{
		//Determine Value of $this->Menus
		if(is_string($mainArg)) {
			
			if(!$alt_title){
				if( is_null(json_decode($mainArg, true)) ) 
					$this->error('WPHELPER ERROR: Invalid JSON for Menu.');
				else 
					$options = json_decode($mainArg, true);
			}else{
				//this means the input was in the format 'tag', 'title'
				$options = array($mainArg => $alt_title);
			}
		}elseif(is_array($mainArg)) {
			
			//options given as assoc array
			$options = $mainArg;
		}else {
			
			$this->error('WPHELPER ERROR: Invalid input for Menu');
		}
		$this->menus = $options;
		
		
		if( function_exists('register_nav_menus')){
			add_action('init', array($this, 'register_menu'));
		}
	}
	
	function insert($slug = NULL, $args = NULL) 
	{
	//simple alias for include_location
		$this->include_location($slug, $args);
	}
	
	function include_location($slug = NULL, $args = NULL)
	{
	//an optional alternative to wp_nav_menu('menu=slug');
	
		//Special cases when only one nav is registered. Used primarily by the insert() alias method
		if(count($this->menus == 1)){
		//no slug needed - will include the only menu registered
		
		//first check if first (and only) argument is the options array
			//if true, give that value to $args, which is handled later on
			if(!is_null(json_decode($slug, true))) $args = $slug;
			
			//overwrite slug to the only possible value	
			$slug = key($this->menus);
		}else{
			
			if(is_null($slug)) $this->error('WPHELER ERROR: Must specify the menu location to include');
		}
		
		
		//Decode JSON string
		if(is_string($args)) 
		{
			if( is_null(json_decode($args, true)) ) 
				$this->error('WPHELPER ERROR: Invalid JSON.');
			else 
				$args = json_decode($args, true);
		}
		$opts = array('theme_location' => $slug);
		if(!is_null($args)) $opts = array_merge($opts, $args);
		
		if( has_nav_menu('main-nav'))
		{
			wp_nav_menu($opts);
		}
	}
	
	function register_menu()
	{
		//$this->menus must be assoc array ('slug'=> 'label')
		register_nav_menus($this->menus);
	}
}//end class

class menus //alias class for menu
{
	function __construct($mainArg, $altTitle = FALSE)
	{
		new menu($mainArg, $altTitle);
	}
}//end class
?>