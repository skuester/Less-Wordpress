<?php 
/* Fancybox Plugin 1.0
 * 
 */
if(!is_admin()){
	wp_enqueue_style('fancybox', wp_ThisDirURL(__FILE__).'/jquery.fancybox-1.3.1.css.php');
	wp_enqueue_script('fancybox', wp_ThisDirURL(__FILE__).'/jquery.fancybox-1.3.1.pack.js', array('jquery'), '1.3.1', true);
	wp_enqueue_script('fancybox-easing', wp_ThisDirURL(__FILE__).'/jquery.easing-1.3.pack.js', array('jquery'), '1.3.1', true);
}

?>