<?php 
/* TaxonomyMeta v.1.0
 * To add custom meta to any taxonomy type
 */
class taxonomyMeta extends wphelper
{
	function __construct($input)
	{
		$this->setOptions(array(
			'taxonomy' => FALSE,
			'custom_meta' => FALSE
		), $input);
		
		//Error Check Required Inputs
		if(!$this->taxonomy) $this->error('WPHELPER ERROR: Must specify a taxonomy for custom meta');
		if(!$this->custom_meta) $this->error('WPHELPER ERROR: Must provide custom meta for taxonomy:'.$this->taxonomy);
		
		//WP Hooks
		//Taxonomy Edit Form
		add_action($this->taxonomy.'_edit_form_fields', array(&$this, '_form_init'), 10, 2);
		
		//Taxonomy Save
		add_action('edited_'.$this->taxonomy, array(&$this, '_save'), 10, 2);
		
		//Init aditional db table
		add_action('init', array(&$this, '_db_init'));
	}

	function _db_init()
	{
		global $wpdb;
		$table_handle = $this->taxonomy.'meta';
		$table_name = $wpdb->prefix.$table_handle;
		//Set internal $wpdb handle
		$wpdb->$table_handle = $table_name;
		
		//Does Table Exist?
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name)
		{
			//No. Therefore Create or Update the Table
			/* 
			 * NOTE: Instad of manually executing SQL, we'll use the dbDelta function (wp-admin/includes/upgrade.php) per
			 * the recommendation of the WP Codex. See http://codex.wordpress.org/Creating_Tables_with_Plugins
			 */
			//Create dbDelta-friendly SQL (see above codex for more information)
			$sql = "CREATE TABLE " . $table_name . " (
				  meta_id bigint(20) NOT NULL AUTO_INCREMENT,
				  ". $this->taxonomy ."_id bigint(20) DEFAULT '0' NOT NULL,
				  meta_key VARCHAR(255) COLLATE utf8_general_ci,
				  meta_value longtext COLLATE utf8_general_ci,
				  UNIQUE KEY meta_id (meta_id)
				) COLLATE utf8_general_ci;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
		}
	}

	
	function _form_init($obj, $taxonomy)
	{
		foreach($this->custom_meta as $newMeta)
		{
			//Get Basic Params
			$options = $this->setLocalOptions(array(
				'meta_key' => FALSE,
				'show_ui' => TRUE
			), $newMeta);
			
			//Error Check for Required Options
			if(!$options->meta_key) $this->error('WPHELPER ERROR: Must provide a Key for Custom Meta');
			
			//Add Custom Meta editing UI
			if($options->show_ui) $this->render_input($newMeta, $obj, $taxonomy);
		}//end foreach
	}
	
	function render_input($newMeta, $obj, $taxonomy)
	{
		//VARS
		$options = $this->setLocalOptions(array(
			'meta_key' => FALSE,
			'label' => 'Custom Meta',
			'input_type' => 'text',
			'input_before' => FALSE,
			'input_after' => FALSE,
			'options' => FALSE, //For later use when multiple input types are supported
			'description' => FALSE
		), $newMeta);
		$value = get_metadata($obj->taxonomy, $obj->term_id, $options->meta_key, true );
		
		//Render Input
		if($options->input_before) $this->render_element($options->input_before, $value);
		?>
		<tr class="form-field">
			
	        <th scope="row" valign="top"><label for="series_thumbnail"><?php echo $options->label;?></label></th>
	        <td>
	            <input type="text" name="<?php echo $options->meta_key;?>" id="<?php echo $options->meta_key;?>" <?php if($value) echo 'value="'.$value.'"';?>/>
	           	<?php if($options->description):?>
	            <p class="description"><?php echo $options->description;?></p>
	            <?php endif;?>
	        </td>
	    </tr>
		<?php
		if($options->input_after) $this->render_element($options->input_after, $value);  	
	}
	
	function render_element($type, $meta_value)
	{
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label></label></th>
			<td>
				<?php 
				switch($type)
				{
					case 'img-meta':
						?><img src="<?php echo $meta_value;?>" alt=""/><?php 
						break;
				}
				?>
			
			</td>
		</tr>
		<?php 
	}
	
	
	function _save($term_id, $tt_id)
	{
		if(!$term_id) return;
		foreach($this->custom_meta as $meta)
		{
			if(isset($_POST[$meta['meta_key']]))
				update_metadata($this->taxonomy, $term_id, $meta['meta_key'], $_POST[$meta['meta_key']]);	
			else
				delete_metadata($this->taxonomy, $term_id, $meta['meta_key']);
		}
		
	}
}//end class
?>