<?php
/**
 * @package Local Home Appliance
 * @subpackage Default_Theme
 */
//Include jQuery ===========================================================
if (!is_admin()) {
	wp_deregister_script('jquery');
	wp_register_script('jquery', ('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js'), false);
	wp_enqueue_script('jquery');
}

//Include GreyMatter Library ================================================
require_once('lib/greyMatter/greymatter.php');

//TEMPLATE HELPERS ==========================================================

/* Get Module v1.0
 * Includes modules based on the naming convention: mod_[NAME].php
 * Function must be defined here for the include path to be accurate.
 */ 
function get_module($mod)
{
	include "modules/mod_$mod.php";
}

// DEFAULT THEME SUPPORT ====================================================

// Feed Links, Widgetize Sidebar, Add Thumbnail Support
automatic_feed_links();
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}
add_theme_support('post-thumbnails');

// WP CONFIG =================================================================

new wpSettings('{ "excerpt_length": 10 }');

// POST TYPES ================================================================

//require_once('lib/php/init-posttype.php');


// OTHER =====================================================================

//new metaBox('{
//	"add_to":"page",
//	"box_id":"page-thumb-size",
//	"box_title":"Featured Image Size",
//	"inputs":[
//		{"html":"<p>Page images should be <strong>590 x 200</strong></p>"}
//	]
//}');

