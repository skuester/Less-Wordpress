<?php

if(!is_admin()){
	wp_enqueue_script('jquery-ui', wp_ThisDirURL(__FILE__).'/jquery-ui-1.8.4.min.js', array('jquery'), '1.8.4', true);
	wp_enqueue_script('jquery-hoverintent', wp_ThisDirURL(__FILE__).'/jquery.hoverIntent.min.js', array('jquery'), '1.0', true);
}

?>
	