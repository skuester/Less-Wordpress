<?php
/* Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * AdminPanel v1.5
 * 
 * A class for creating a (relatively) simple admin form, generated with the same html structure as 
 * default Wordpress menus.
 * 
 * Change Log
 * 1.4 This is now a child class of the wphelper core
 * 1.5 "add_to" paramater now supports custom post types | added "categories":"taxonomy" premade values to options
 * 
 * TODO: Add ability for adding POSITION arguments to the _addPanel() method
 * 
 */
class adminPanel extends wphelper
{
	
	function __construct($options)
	{	
		//Menu Settings
		$this->setOptions(array(
			'id' => NULL,
			'type' => 'menu',
			'label' => 'Custom Admin Panel',
			'title' => NULL,
			'page_title' => NULL,
			'style' => FALSE,
			'script' => FALSE,
			'add_to' => FALSE,
			'capability' => level_2,
			'action' => '',
			'icon' => NULL,
			'position' => NULL,
			'form' => FALSE
		), $options);
		
		//Determine Special ADD_TO Settings
		//Reference found at codex.wordpress.org/Adding_Administration_Menus#Sub-Menus
		switch($this->add_to)
		{
			case 'dashboard':
				$this->add_to = 'index.php';
				break;
			case 'posts':
				$this->add_to = 'edit.php';
				break;
			case 'media':
				$this->add_to = 'upload.php';
				break;
			case 'links':
				$this->add_to = 'link-manager.php';
				break;
			case 'pages':
				$this->add_to = 'edit.php?post_type=page';
				break;
			case 'comments':
				$this->add_to = 'edit-comments.php';
				break;
			case 'appearance':
				$this->add_to = 'themes.php';
				break;
			case 'plugins':
				$this->add_to = 'plugins.php';
				break;
			case 'users':
				$this->add_to = 'users.php';
				break;
			case 'tools':
				$this->add_to = 'tools.php';
				break;
			case 'settings':
				$this->add_to = 'options-general.php';
				break;
			default:
				$this->add_to = 'edit.php?post_type='.$this->add_to;
				break;		
		}
		
		//Error Check
		if(!$this->id) $this->error('WPHELPER ERROR: Must Specify an ID');
		
		//WP Hooks
		add_action('admin_menu',array($this, '_addPanel'));
		add_action('admin_init', array($this, '_init'));
		
		//Run Save If Needed
		if(isset($_POST['submit'])) $this->_save();
	}
	
	function _init()
	{
		//STYLES
		wp_enqueue_style($this->id .'-style', WPHELPER_PATH.'/css/adminpanel.css', false, '1.2', 'all');
		//optional custom style (dependent on default style)
		if($this->style) wp_enqueue_style($this->id .'-style-custom', $this->style, $this->id .'-style', '1.0', 'all');
		
		//SCRIPTS
		//optional custom script (jQuery accessible)
		if($this->script) wp_enqueue_script($this->id .'-script-custom', $this->script, array('jquery'),'1.0');
	}
	
	function _addPanel()
	{
		//Determine if submenu or menu
		if(!$this->add_to) //no add to value given
			add_menu_page($this->page_title, $this->label, $this->capability, $this->id, array($this, '_buildPanel'), $this->icon, $this->position);
		else	
			add_submenu_page($this->add_to, $this->page_title, $this->label, $this->capability, $this->id, array($this, '_buildPanel'));
	}
	
	function _buildPanel()
	{
		//Global Required: 
		/* Name
		 * 
		 */
		
		if(!$this->form) $this->error('WPHELPER ERROR: No Form Defined!');
		?>
		<div class="wrap" id="red">
			<h2><?php echo $this->title; ?></h2>
			<form method="post" action="<?php echo $this->action; ?>" id="<?php echo $this->id;?>">
				<table class="wph-admin" cellspacing="0">
					<tbody>
			
			<?php
			foreach($this->form as $input)
			{
				//Determine input element vars
				$type = (isset($input['type'])) ? $input['type'] : 'text';
				
				$name = (isset($input['name'])) ? $input['name'] : FALSE; //required
				
				$id = (isset($input['id'])) ? $input['id'] : $name;
				
				$value = (isset($input['value'])) ? $input['value'] : FALSE; //required for check box
				$values = (isset($input['values'])) ? $input['values'] : FALSE; //required for select and check box
				
				$option = (isset($input['option_label'])) ? $input['option_label'] : FALSE;
				$options = (isset($input['options'])) ? $input['options'] : FALSE; // required for select and checkbox
				
				//PREMADE "OPTIONS" PARAMS
				if($options){
					foreach($options as $k=>$v)
					{
						switch($k)
						{
							case 'categories':
								$array_position = array_search($k, array_keys($options));
								$tax = $options[$k];
								$categories = array();
								//get list of category objects from WP
								$cats = get_categories(array(
									'taxonomy' => $tax,
									'orderby' => 'name',
									'order' => 'ASC',
									'hide_empty' => 0
								));
			
								//Only proceed if get_categories returns a valid result
								if($cats) 
								{
									//build categories array
									foreach ($cats as $cat) { $categories[$cat->slug] = $cat->name; }
								}else{
									//build indicator if no galleries exist
									$categories['_wph-empty'] = '- empty -';
								}
								//populate new values
								foreach ($categories as $v=>$l){ $options[$v] = $l; }
								//destroy categories index
								unset($options['categories']);
								break;
						}//switch
					}//foreach
				}
				

				$label = (isset($input['label'])) ? $input['label'] : 'Custom Input';
				$description = (isset($input['description'])) ? $input['description'] : '';
				
				//custom and custom_row inputs
				$inputHtml = (isset($input['input'])) ? $input['input'] : FALSE;
				$html = (isset($input['html'])) ? $input['html'] : FALSE;
				
				?>
				

				<tr>
				<?php // If a custom row is desired?>
				<?php if($type == 'custom_row'):?>
					<?php 
					//use html option if present, or look for label, input, and description
					if($html){
						?><td colspan="3"><?php echo $html;?></td><?php 
					}elseif ($label !== 'Custom Input' || $inputHtml || $description !== ''){ 
						//Show only if attrubutes are not their defaults
						//Note - this logic must be updated if default values are edited above
						if($label !== 'Custom Input'): ?><td><?php echo $label;?></td><?php endif;
						if($inputHtml):?><td class="wph-admin-inputs"><?php echo $inputHtml;?></td><?php endif;
						if($description !== ''):?><td><?php echo $description; ?></td><?php endif; 
					}else{
						$this->error('WPHELPER ERROR: Must specify Custom Row content via HTML or label, input, or description');
					}
					?>

				<?php else: //custom row is not desired ?>
					<td>
						<strong><?php echo $label; ?></strong>
					</td>
					
					<td class="wph-admin-inputs"><?php
					 
						switch($type)
						{
							case 'checkbox':
								if($options) {
									$i = 0;
									foreach($options as $v=>$l):?>
										<input type="checkbox" name="<?php echo $name.'[]';?>" id="<?php echo $id.'-'.$i;?>" value="<?php echo $v; ?>" <?php if( get_option($name) && in_array($v, get_option($name)) ) echo 'checked';?>/><p><?php echo $l; ?></p>
										<?php $i++; ?>
									<?php endforeach;
									
								}elseif($option) {  //it is a single
									?><input type="checkbox" name="<?php echo $name;?>" id="<?php echo $id;?>" value="yes" <?php if(get_option($name)) echo 'checked';?>/><p><?php echo $option; ?></p><?php 
								}else{
									$this->error('WPHELPER ERROR: Must Supply Option(s)');
								}
								break;
								
							case 'custom':
								if($html) echo $html;
								else $this->error('WPHELPER ERROR: Must provide input HTML');
								break;	

							case 'password':
								?><input type="password" name="<?php echo $name;?>" id="<?php echo $id;?>" <?php if(get_option($name)) echo get_option($name);?>/><?php 
								break;
								
							case 'radio':
								$i=0;
								foreach($options as $v => $l)
								{
									//first option is always selected by default
									?><input type="radio" name="<?php echo $name;?>" id="<?php echo $id.'-'.$i;?>" value="<?php echo $v;?>" <?php if((!get_option($name) && $i == 0) || (get_option($name) && $v == get_option($name))) echo 'checked';?>/><p><?php echo $l;?></p><?php 
									$i++;
								}
								break;
								
							case 'select':
								if(!$options) $this->error('WPHELPER ERROR: Must specify options for Select Input');
								?>
								<select name="<?php echo $name;?>" id="<?php echo $id;?>">
									<?php foreach($options as $v=>$l):?>
									
										<option value="<?php echo $v;?>" <?php if(get_option($name) && get_option($name) == $v) echo 'selected';?> ><?php echo $l;?></option>
									<?php endforeach;?>
								</select>
								<?php
								break;
								
							case 'text':
								?><input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" <?php if(get_option($name)) echo 'value="'.get_option($name).'"';?> /><?php 
								break;
						}//input switch
								
						?></td>
							
					<td>
						<p class="wph-admin-descrip"><?php echo $description; ?></p>
					</td>
				<?php endif; //end check for custom_row ?>
				</tr><!-- section --><?php 
			}//main loop (foreach)
			?>
				</tbody>
			</table>
			
			<p class="submit">
				<input class="button-primary" type="submit" name="submit" id="wph-submit" value="Save Changes"/>
			</p>

						
			<input type="hidden" name="<?php echo $this->id;?>_nonce" value="<?php echo wp_create_nonce($this->id.'_wph-nonce');?>" />
			</form>
		</div><!-- wph-wrap --><?php 
	}//buildPanel
	
	function _save(){  

	    global $post;  
	    //Run some security checks (borrowed from WPAlchemy)
		// check autosave
		if (!wp_verify_nonce($_POST[$this->id.'_nonce'], $this->id.'_wph-nonce')) return $post->id; 
		
		// check user permissions
		if ($_POST['post_type'] == 'page') 
			if (!current_user_can('edit_page', $post_id)) return $post->id;
		else 
			if (!current_user_can('edit_post', $post_id)) return $post->id;

		//Checks passed - add post data.
		if($this->form)
		{
			
			foreach($this->form as $input)
			{
				//Will only update menu items with a name attribute
				if(isset($input['name'])):
				
					//if the name is set, but no post value, send error 
					//(ie. name specified in options, but no name attr on input element)
					if($input['type'] == 'custom' || $input['type'] == 'custom_row'){
						
						if(is_null($_POST[$input['name']]))
							$this->error('WPHELPER ERROR: No post value for the name: '.$input['name']);
						
					}
				
					if(is_array($_POST[$input['name']])) {
						
						foreach($_POST[$input['name']] as $x){ $x = trim($x); }
						$option_data = $_POST[$input['name']];
						
					}else{
						
						$option_data = trim($_POST[$input['name']]);
					}
					
					if($input['name'] !== '') update_option($input['name'], $option_data);
					elseif($input['name'] == '') delete_option($input['name']);
					
				endif;
			}
		}
		
	     
	}//end save
	
}//end class