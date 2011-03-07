<?php 
/* 
 * ManagePostsFilter v1.1
 * Thanks to Vladimir Prelovac for much of the source material
 * url: http://www.packtpub.com/article/managing-posts-with-wordpress-plugin
 * 
 * Change Log
 * 1.1 This is now a child class of the wphelper core 
 */
class managePostsFilter extends wphelper
{
	
	function __construct($options)
	{	
		$this->setOptions(array(
			'taxonomy' => FALSE,
			'post_type' => FALSE,
			'dropdown_label' => FALSE
		), $options);
		
		//Error Check
		if(!$this->taxonomy) $this->error('WPHELPER ERROR: Must specify a taxonomy for Manage Posts Filter');
		if(!$this->taxonomy) $this->error('WPHELPER ERROR: Must specify a post type for Manage Posts Filter');
		
		//Add WP hook
		add_action('load-edit.php', array($this, 'handle_load_edit'));
	}
	
	function handle_load_edit()
	{
		//add a search filter box
		//NOTE: filtering the "post_where" seems unnecessary as posts can already be filtered with a url
		//string of ?post_type=type&taxonomy=term. Creating a dropdown list with the same name as the taxonomy
		//acheives this result without having to modify the Wordpress database query
		//UNUSED: add_filter('posts_where', array($this, 'filter_the_query'));
		
		//Hook into the manage posts filter for the specified post type
		if($_GET['post_type'] == $this->post_type) add_action('restrict_manage_posts', array($this, 'build_dropdown_list'));
	}
	
	function filter_the_query($where)
	{
		//This function is UNNESSECARY as of version
		//Change the query to receive only posts with the desired category
		global $wpdb;
		
		$term = (isset($_GET['taxonomy_term']));
		
		if($_GET['post_type'] == $this->post_type)
		{
			$where .= "AND ID IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='$this->taxonomy' AND meta_value='$term'";
			return $where;
		}
	}
	
	function build_dropdown_list()
	{
		//This function renders to select box
		
		//Build Dropdown Label is not specified.
		if($this->dropdown_label) {
			$dropdown_label = $this->dropdown_label;
		}else{
			//Default "Show All [Taxonomy Plural]"
			$taxInfo = get_taxonomies(array('name'=>$this->taxonomy, '_builtin' => false), 'objects');
			$taxPluralName = $taxInfo[$this->taxonomy]->labels->name;
			
			$dropdown_label = 'Show All '.$taxPluralName;
		}
		
		//Get the list of categories
		$categories = get_categories(array(
						'taxonomy' => $this->taxonomy,
						'orderby' => 'name',
						'order' => 'ASC'
						));
							
		//Render the Select Box ?>
		<?php if($categories):?>
		<select name="<?php echo $this->taxonomy;?>" id="<?php echo $this->taxonomy;?>" class="postform">
			<option value="0"><?php echo $dropdown_label;?></option>
			<?php foreach($categories as $category): $term = $category->slug; $name = $category->name;?>
				<option value="<?php echo $term;?>" <?php if($_GET[$this->taxonomy] == $term) echo 'selected="selected"';?>><?php echo $name;?></option>
			<?php endforeach;?>			
		</select>
		<?php endif;
	}
	
}//end class




?>