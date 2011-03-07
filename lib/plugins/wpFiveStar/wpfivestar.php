<?php 
/* WP Five Star v 1.0
 * 
 * Simple Five Star Review Plugin
 */

//CONFIG ==========================

//Relative path to wpFiveStar root directory, from the template root (begin with "/")
define('WPFIVESTAR_PATH', '/lib/plugins/wpFiveStar');
define ('WPFIVESTAR_ABSPATH', get_bloginfo('template_directory').WPFIVESTAR_PATH);

//=================================

//Load WP Five Star class
require_once 'wpfivestar.class.php';
?>