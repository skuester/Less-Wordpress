<?php
/* 
 * Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * Wphelper Core v1.2
 * 
 * Change Log
 * 1.1 Added setLocalOptions method to return a local object in the same method that setOptions does on a global scale.
 * 1.2 Added renderInputHtml to centralize form generation
 */

class wphelper
{	
	function error($message)
	{
		trigger_error($message, E_USER_ERROR);
	}
	
	function setOptions($params, $userValues)
	{
		//First Get User Input
		$this->_getUserValues($userValues);
		//Now Determine whether to use user input or default
		foreach($params as $option => $default)
		$this->$option = (isset($this->userValues[$option])) ? $this->userValues[$option] : $default;
	}
	
	function setLocalOptions($params, $inputValues)
	{
		//Instantiate Return Object
		$returnObj = (object)NULL;
		//Determine whether to use user input or default
		foreach($params as $option => $default)
		{
			$returnObj->$option = (isset($inputValues[$option])) ? $inputValues[$option] : $default;
		}
		return $returnObj;
	}
	
	private function _getUserValues($userValues = NULL) 
	{
		//decode userValues (if JSON)
		if(is_string($userValues)) 
		{
			if( is_null(json_decode($userValues, true)) ) 
				$this->error('WPHELPER ERROR: Invalid JSON');
			else 
				$userValues = json_decode($userValues, true);
		}
		$this->userValues = $userValues;
	}
	
	function resolvePremadeOptions($options)
	{
		foreach($options as $k=>$v)
			{
				switch($k)
				{
					case 'categories':
						$array_position = array_search($k, array_keys($options));
						//get the taxonomy from the input, 'categories':'taxonomy'
						$tax = $options[$k];
						//get list of category objects from WP
						$cats = get_categories(array(
							'taxonomy' => $tax,
							'orderby' => 'name',
							'order' => 'ASC',
							'hide_empty' => 0
						));
	
						//Init the categories array
						$categories = array();
						
						//Only proceed if get_categories returns a valid result
						if($cats) {
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
				}
			}
		return $options;
	}//resolvePremadeOptions
	
	function renderInputHtml($input)
	{
		/* Params:
		 * Type > HTML (overrides)
		 * Label
		 * Name
		 * ID
		 * Delete Link
		 * Option > Options (overrides?)
		 * 
		 * Dependancies:
		 * properly formatted $input array
		 */
		
		global $post;
		
		//Set Options
		$op = $this->setLocalOptions(array(
			'type' => 'text',
			'html' => FALSE,
			'label' => FALSE,
			'name' => FALSE,
			'id' => FALSE,
			'delete_link' => FALSE,
			'option' => FALSE,
			'options' => FALSE,
			'get_as' => 'meta'  //retrieve input value as meta or option
		), $input);
		
		//PREMADE "OPTIONS" PARAMS
		if($op->options)
			$op->options = $this->resolvePremadeOptions($op->options);
		
			
		//Get input value, and place into $value
		switch($op->get_as)
		{
			case 'meta':
				$getValue = get_post_meta($post->ID, $op->name, true);
				break;
				
			case'option':
				$getValue = get_option($op->name, '');
				break;
		}
		$value = ($getValue !== '') ? $getValue : FALSE;
		
		
		//render HTML
		?><div class="wph-inputbox wph-inputbox-input"><?php 
		
		
		//label
		if($op->label){
			?><label class="wph-inputbox input-<?php echo $op->id;?>" for="<?php echo $op->name;?>"><strong><?php echo $op->label;?></strong></label><?php 
		}
		
		//Include JS Delete Link if Desired
		if($op->delete_link){
			?><a href="#" class="metabox-delete" id="delete-<?php echo $op->id;?>" onclick="WPHDeletePostMeta('<?php echo $op->name;?>', '<?php echo wp_create_nonce($this->box_id);?>'); return false;">Delete</a><?php 
		}

		if($op->html):
			echo $op->html;
		
		else:
			//premade input
			switch($op->type)
			{
				//TODO: Change use of <p> for labeling
				case 'checkbox':
					if($op->options) {
						$i = 0;
						foreach($op->options as $v=>$l):?>
							<input type="checkbox" name="<?php echo $op->name.'[]';?>" id="<?php echo $op->id.'-'.$i;?>" value="<?php echo $v; ?>" class="wph-inputbox input-<?php echo $id;?>" <?php if($value && in_array($v, $value)) echo 'checked';?>/><p><?php echo $l; ?></p>
							<?php $i++; ?>
						<?php endforeach;
						
					}elseif($op->option) {  //it is a single
						?><input type="checkbox" name="<?php echo $op->name;?>" id="<?php echo $op->id;?>" value="yes" class="wph-inputbox input-<?php echo $op->id;?>" <?php if($value) echo 'checked = "checked"';?>/><p><?php echo $op->option; ?></p><?php 
					}else{
						$this->error('WPHELPER ERROR: Must Supply Option(s)');
					}
					break;
					
					
				case 'password':
					?><input type="password" name="<?php echo $op->name;?>" id="<?php echo $op->id;?>" class="wph-inputbox input-<?php echo $op->id;?>" <?php if($value) echo 'value="'.$value.'"';?>/><?php 
					break;
					
					
				case 'radio':
					$i=0;
					foreach($op->options as $v => $l)
					{
						//first option is always selected by default
						?><input type="radio" name="<?php echo $op->name;?>" id="<?php echo $op->id.'-'.$i;?>" class="wph-inputbox input-<?php echo $op->id;?>" value="<?php echo $v;?>" <?php if((!$value && $i == 0) || ($value && $v == $value)) echo 'checked';?>/><p><?php echo $l;?></p><?php 
						$i++;
					}
					break;
					
					
				case 'select':
					if(!$op->options) $this->error('WPHELPER ERROR: Must specify options for Select Input');
					?>
					<select name="<?php echo $op->name;?>" id="<?php echo $op->id;?>" class="wph-inputbox input-<?php echo $op->id;?>">
						<?php foreach($op->options as $v=>$l):?>
							<option value="<?php echo $v;?>" <?php if($value && $value == $v) echo 'selected';?> ><?php echo $l;?></option>
						<?php endforeach;?>
					</select>
					<?php
					break;
					
					
				case 'text':
					?><input type="text" name="<?php echo $op->name; ?>" id="<?php echo $op->id; ?>" class="wph-inputbox input-<?php echo $op->id;?>" <?php if($value) echo 'value="'.$value.'"';?> /><?php 
					break;
			}//switch
		endif;
		
		?></div><?php //close div wrapper
	}//renderInputHtml
	
}//wphelperCore







