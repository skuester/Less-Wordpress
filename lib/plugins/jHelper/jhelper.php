<?php 
/* jHelper v1.0
 * 
 * DarkGrey Media self-instantiating js library
 * Note: $templateDir is the URL of the root template folder, where loaded ajax forms, etc will be.
 */

//Plugin Config - currently WordPress specific
$templateDir = get_bloginfo('template_directory');

wp_enqueue_style('jHelper', wp_ThisDirURL(__FILE__).'/jhelper.css');
wp_enqueue_script('jHelper', wp_ThisDirURL(__FILE__).'/jhelper-core.js.php?t='.$templateDir, array('jquery'), '1.0', true);
wp_enqueue_script('jHelper-prototype', wp_ThisDirURL(__FILE__).'/jhelper-prototype.js', array('jquery', 'jHelper'), '1.0', true);
?>