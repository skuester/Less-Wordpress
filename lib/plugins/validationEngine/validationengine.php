<?php 
if(!is_admin()){
	wp_enqueue_style('validationEngine', wp_ThisDirURL(__FILE__).'/validationEngine.jquery.css');
	wp_enqueue_script('validationEngine-en', wp_ThisDirURL(__FILE__).'/jquery.validationEngine-en.js', array('jquery'), '1.0', true);
	wp_enqueue_script('validationEngine', wp_ThisDirURL(__FILE__).'/jquery.validationEngine.js', array('jquery', 'validationEngine-en'), '1.0', true);
}
	
?>