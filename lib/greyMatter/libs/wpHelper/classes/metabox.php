<?php
/*
 * Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * MetaBox v1.51
 * 
 * Changelog
 * 1.4	Added premade "Options" parameters
 * 1.5	Added "save_as" param ("meta" [default], and "option")
 * 1.51 Modified _setup to use the core renderInputHtml method
 */
class metaBox extends wphelper
{
	function __construct($options)
	{	
		//List on global vars and their defaults
		$this->setOptions(array(
			'inputs' => FALSE,
			'input' => FALSE,
			'box_id' => NULL,
			'box_title' => NULL,
			'add_to' => 'post',
			'position' => 'side',
			'style' => FALSE,
			'save_as' => 'meta'
		), $options);

		add_action('admin_init', array($this, '_init')); 		
	}
	
	function _init()
	{
		// runs only in post.php and post-new.php (this includes pages also)
		//Borrowed from WPAlchemy
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL ;
		if ($uri AND !strpos($uri,'post.php') AND !strpos($uri,'post-new.php')) return;
		
		add_meta_box($this->box_id, $this->box_title, array($this, '_setup'), $this->add_to, $this->position, "low");
		add_action('save_post', array($this, '_save')); 
		
		//Ajax Delete Hook
		add_action('wp_ajax_WPHDeletePostMeta', array($this, '_ajax_delete'));
		
		wp_enqueue_script('wph-metaBox-script', WPHELPER_PATH.'/js/metabox.js', array('jquery'), '1.0');
		wp_enqueue_style('wphelper_style', WPHELPER_PATH.'/css/metabox.css');
		
		//optional user style
		if($this->style) {
			wp_enqueue_style($this->box_id.'_style', $this->style, 'wph-inputbox_style');
		}
	}
	
	function _setup()
	{ 
		?>
		<div class="wph-inputbox">
		<?php 
			//If multiple inputs or single input
			if($this->inputs){
				foreach($this->inputs as $input)
					$this->renderInputHtml($input);		
	
			}elseif ($this->input){
				$this->renderInputHtml($this->input);
			}
		
			//Setup Nonce
			?>
			<input type="hidden" name="<?php echo $this->box_id; ?>_nonce" value="<?php echo wp_create_nonce($this->box_id);?>" />
			<?php
		?>
		</div>
		<?php  
	}//_SETUP  
	
	function _ajax_delete()
	{
		global $post;
		$metaKey = (isset($_POST['meta_key'])) ? $_POST['meta_key'] : FALSE;
		$ajaxNonce = (isset($_POST['ajax_nonce'])) ? $_POST['ajax_nonce'] : FALSE;
		
		var_dump($_POST);
		echo 'HELLO';
		//Check nonce
		//if(!wp_verify_nonce($ajaxNonce, $this->box_id)) return $post->id;
		
		switch($this->save_as)
		{
			case 'meta':
				delete_post_meta($post->id, $metaKey);
				break;
			case 'option':
				delete_option($post->id, $metaKey);
				break;
		}
	}
	
	function _save()
	{  
		
	    global $post;  
	    //Run some security checks (borrowed from WPAlchemy)
		// check autosave
		if (defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE) return $post->id;
		// make sure data came from our meta box, verify nonce
		if (!wp_verify_nonce($_POST[$this->box_id.'_nonce'],$this->box_id)) return $post->id;
		// check user permissions
		if ($_POST['post_type'] == 'page') 
			if (!current_user_can('edit_page', $post_id)) return $post->id;
		else 
			if (!current_user_can('edit_post', $post_id)) return $post->id;

		//Checks passed - add post data.
		//if only one (convenience) input is provided, place in inputs array as usual
		
		if($this->input) { $this->inputs = array($this->input); }
		if($this->inputs)
		{
			foreach($this->inputs as $input)
			{
				//save only when name is specified
				if(!is_null($input['name']))
				{
					if(is_array($_POST[$input['name']])){
						
						foreach($_POST[$input['name']] as $x) { $x = trim($x); }
						$option_data = $_POST[$input['name']]; 
						
						
					}else{
						
						$option_data = trim($_POST[$input['name']]);
						
					}
					
					//Determine the final act of saving
					if($option_data !== ''):
					//save
						switch($this->save_as)
						{
							case 'meta':
								update_post_meta($post->ID, $input['name'], $option_data);
								break;
							
							case 'option':
								update_option($input['name'], $option_data);
								break;
						}
					else:
					//Delete Empty Option/Meta
						switch($this->save_as)
						{
							case 'meta':
								delete_post_meta($post->ID, $input['name']);
								break;
								
							case 'option':
								delete_option($input['name']);
								break;
						}
					endif;
				}
			}
		}	
		 
	}//save
	
}//class inputBox
?>