<?php 
/* WP Settings
 * v 1.0
 * 
 * A Class to configure various wordpress options not available in the menu
 * 
 * TODO:
 * 1. Add option to disable more link jumping
 */
class wpSettings extends wphelper
{
	function __construct($params)
	{
		//Get User Input
		$this->setOptions(array(
			'excerpt_length' => FALSE,
			'excerpt_more' => FALSE,
			'update_nag' => FALSE
		), $params);
		
		//Set If Options Present
		if($this->excerpt_length) add_filter('excerpt_length', array(&$this, 'set_excerpt_length'));
		if($this->excerpt_more) add_filter('excerpt_more', array(&$this, 'set_excerpt_more'));
		
		//Set by default
	}
	
	//These are the same functions - refactor later?
	function set_excerpt_length($length)
	{
		return $this->excerpt_length;
	}
	
	function set_excerpt_more($more)
	{
		return $this->excerpt_more;
	}
	
	//Don't show update pop-up for non-super admins
	function set_update_nag()
	{
		if($this->update_nag == FALSE) {
		// kill the admin nag
			if (!current_user_can('edit_users')) {
				add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
				add_filter('pre_option_update_core', create_function('$a', "return null;"));
			}
		}
	}
}

?>