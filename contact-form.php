<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
//Load Wordpress Headers if ajax
$ajax_form = FALSE;
if(!function_exists('get_bloginfo')) {
	include '../../../wp-load.php';
	$ajax_form = TRUE;
}
$formAction = ($ajax_form) ? get_bloginfo('template_directory').'/contact-form.php' : '';
$admin_email = get_bloginfo('admin_email');
$email = new simpleEmail(array('send_to' => $admin_email));
?>


	
	<noscript>
		<?php $email->response(array('class' => 'span-18 last top-space')); ?>
	</noscript>
	
	<form action="<?php echo $formAction; ?>" method="post" id="j-contact" class="j-contact">
	
		<div>
			<label for="contact-name">Your Name</label>
			<input type="text" name="contact-name" id="contact-name" tabindex="1" class="validate[required,custom[onlyLetter]]" value="<?php $email->v('contact-name');?>" />
			
			<label for="contact-email">Your Email</label>
			<input type="text" name="contact-email" id="contact-email" tabindex="2" class="validate[required,custom[email]]" value="<?php $email->v('contact-email');?>"/>
			
			<input type="submit" tabindex="4" class="button-big top-space-sm" name="contact-submit" id="contact-submit" value="Send"/>
		</div>
		
		
		<div>
			<label for="contact-message">Your Message</label>
			<textarea name="contact-message" id="contact-message" cols="32" rows="6" tabindex="3" class="validate[required]"><?php $email->v('contact-message');?></textarea>
		</div>
		
		<input type="hidden" name="contact-nonce" value="<?php echo wp_create_nonce('DEFAULT_c0ntact');?>"/>
	</form>
	
	<h2 class="j-contact-response">Thank You! We look forward to speaking with you.</h2>
