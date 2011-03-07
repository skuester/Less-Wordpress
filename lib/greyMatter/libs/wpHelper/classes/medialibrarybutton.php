<?php 
/*
 * MediaLibraryButton
 * v 0.5 *NOT for public consumption* THIS VERSION CURRENTLY MEETS ONLY THE NEEDS OF TRANSIT WAUCONDA 
 * Function for adding a custom button to the media Library modal window
 * 
 * TODO: Test 'Function" param to allow user to specify a trigger to run on 'media_send_to_editor'
 * TODO: Enable user to add a "tr" (addFields method)
 * TODO: Make this more flexible for ajax buttons to do this other than update post meta
 */
if(!class_exists('mediaLibraryButton')):
class mediaLibraryButton extends wphelper
{
	
	function __construct($options)
	{		
		$this->setOptions(array(
			'label' => NULL,
			'button_label' => FALSE,
			'id'=> FALSE,
			'class' => FALSE,
			'callback' => FALSE,
			'script' => FALSE,
			'helps' => NULL,
			'value' => NULL,
			'action' => 'update_post_meta',
			'meta_key' => FALSE
		), $options);
		
		$this->setOptions(array(
			'name' => $this->id
		), $options);
		
		//Error Check
		if(!$this->button_label) $this->error('WPHELPER ERROR: Must specify a Button Label for custom media library button');
		if($this->action == 'update_post_meta' && !$this->meta_key) $this->error('WPHELPER ERROR: Missing Meta Key for custom media library button');
		
		
		//Enqueue Script
		wp_enqueue_script('wph-medialibrarybutton', WPHELPER_PATH.'/js/media-library-button.js', array('jquery'), '1.0');
		
		//WP HOOKS
		add_filter('attachment_fields_to_edit', array(&$this, 'addFields'), 10, 2);
		//ajax meta update hook
		add_action('wp_ajax_WPHUpdatePostMeta', array(&$this, 'ajax_UpdatePostMeta'));
	}
	
	function ajax_UpdatePostMeta(){
		$post_id = $_POST['parent_post_id'];
		$ajax_nonce = $_POST['ajax_nonce'];
		$meta_key = $_POST['meta_key'];
		$meta_data = $_POST['meta_data'];
		
		if(wp_verify_nonce($ajax_nonce, $this->id))
		{
			update_post_meta($post_id, $meta_key, $meta_data);
		}else{
			return 0;
		}
		return 1;
	}
	
	function addFields($form_fields, $post)
	{
		global $wp_version;
		$file = wp_get_attachment_url($post->ID);
		$parent_post_id = $_GET['post_id'];
		$ajax_nonce = wp_create_nonce($this->id);
		
		switch($this->action)
		{
			//This is the default
			case 'update_post_meta':
				$function = "WPHUpdatePostMeta($parent_post_id, '$this->meta_key', '$file', '$ajax_nonce')";
				break;
				
			default:
				$function = $this->action;
				break;
		}
					
		$html = '
			<button class="button '.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$parent_post_id.'" 
				onclick="'.$function.'; return false;">'.$this->button_label.'</button>		
			';
			
			//add field to WP form_fields array
			$form_fields[$this->id] = array(
				'label' => $this->label,
				'input' => 'html',
				'html' => $html,
				'value' => $this->value,
				'helps' => $this->helps
			);
		
		//RETURN FORM FIELDS
		return $form_fields;
	}//addButon
		
}//class
endif;



?>