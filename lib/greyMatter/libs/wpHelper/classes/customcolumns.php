<?php 
/* Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * Custom Columns v1.2
 * 
 * Changelog
 * 1.2 A ManagePostsFilter can now be created by specifying "dropdown_taxonomy" and optionally "dropdown_label"
 * 1.3 This is now a child class of the wphelper core
 */
class customColumns extends wphelper
{
	function __construct($options)
	{
		//Set Options
		$this->setOptions(array(
			'post_type' => FALSE,
			'columns' => FALSE,
			'style' => FALSE,
			'script' => FALSE,
			'dropdown_taxonomy' => FALSE,
			'dropdown_label' => FALSE
		),$options);
		
		//Computed Vals
		$filterType = 'manage_edit-'.$this->post_type.'_columns';
		
		//Error Check
		if(!$this->post_type) $this->error('WPHELPER ERROR: Must Specify A Post Type for Custom Columns');
		
		
		//WP Operations
		add_filter($filterType, array($this, '_edit_columns'));  
		add_action("manage_posts_custom_column",  array($this, '_render_columns')); 
		if($this->dropdown_taxonomy) new managePostsFilter(array(
										'taxonomy' => $this->dropdown_taxonomy,
										'dropdown_label' => $this->dropdown_label,
										'post_type' => $this->post_type
										));
		
		if($this->style) wp_enqueue_style($this->post_type.'-col-style', $this->style, array(), '1.0', 'all');
		if($this->script) wp_enqueue_script($this->post_type.'-col-script', $this->script, array('jquery'),'1.0');
	}
	
	function _edit_columns($columns)
	{  
	//Combine custom columns with defaults
		$customColumns = array();
		foreach($this->columns as $col)
		{
			$customColumns[$col['tag']] = $col['label'];
		}
		
		//Default Columns
        $columns = array(  
            "cb" => "<input type=\"checkbox\" />"
        	);
        	
        //Merge and Return Columns
        $columns = array_merge($columns, $customColumns);
        return $columns;  
	}  
  
	function _render_columns($column)
	{  
		global $post;
		
		foreach($this->columns as $col)
		{
			//Determine Column Vars
			$tag = (isset($col['tag'])) ? $col['tag'] : FALSE;
			$content = (isset($col['content'])) ? $col['content'] : FALSE;
			
			//ERROR CHECK
			//default wordpress column tags are excluded from certain error checks
			$default_wp_tags = array(
									'title', 
									'date'
								);
			if(!$tag) $this->error('WPHELPER ERROR: Must specify a tag for this Column');
			if(!in_array($tag, $default_wp_tags) && !$content){
				$this->error('WPHELPER ERROR: Must specify the content for column: '.$tag);
			}
			
			if($tag == $column)
			{
				//column selected, act according to the content type and paramater eg. "post":"excerpt"
				foreach($content as $type => $param):
				
					switch($type)
					{
						case 'function':
							call_user_func($param, $column);
							break;
						
						case 'html':
							echo $param;
							break;
						
						case 'meta':
							echo get_post_meta($post->ID, $param, true);
							break;
							
						case 'meta-img':
							echo '<img class="wph-meta-img" src="'.get_post_meta($post->ID, $param, true).'" alt="" /> ';
							break;
							
						case 'meta-p':
							echo '<p>'.get_post_meta($post->ID, $param, true).'</p>';
							break;
						
						case 'post':
							switch($param) 
							{
								case 'content':
									the_content();
									break;
								
								case 'date':
									//wordpress default F j, Y
									the_time('F j, Y');
									break;
					
										case 'date-lFj,Y':
											the_date('l F j, Y');
											break;
											
										case 'date-DFj,Y':
											the_time('D F j, Y');
											break;
							
										case 'date-m/d/y':
											the_time('m/d/y');
											break;
											
										case 'date-m.d.y':
											the_time('m.d.y');
											break;
											
										case 'date-d/m/y':
											the_time('d/m/y');
											break;
											
										case 'date-d.m.y':
											the_time('d.m.y');
											break;
											
										case 'date-FjS,Y':
											the_time('F jS, Y');
											break;
											
										case 'date-m/d':
											the_time('m/d');
											break;
			
										case 'date-d/m':
											the_time('d/m');
											break;
											
										case 'date-F':
											the_time('F');
											break;
											
										case 'date-Y':
											the_time('Y');
											break;
											
										case 'date-l':
											the_time('l');
											break;
																
								case 'excerpt':
									the_excerpt();
									break;
								
								case 'permalink':
									the_permalink();
									break;
									
								case 'thumbnail':
									if(function_exists('has_post_thumbnail')){
										if(has_post_thumbnail($post->ID)) the_post_thumbnail();
									}else{
										echo 'Thumbnails are not supported';
									}
									break;
									
										case 'thumbnail-medium':
											if(function_exists('has_post_thumbnail')){
												if(has_post_thumbnail($post->ID)) the_post_thumbnail('medium');
											}else{
												echo 'Thumbnails are not supported';
											}
											break;
											
										case 'thumbnail-large':
											if(function_exists('has_post_thumbnail')){
												if(has_post_thumbnail($post->ID)) the_post_thumbnail('large');
											}else{
												echo 'Thumbnails are not supported';
											}
											break;
											
										case 'thumbnail-thumbnail':
											if(function_exists('has_post_thumbnail')){
												if(has_post_thumbnail($post->ID)) the_post_thumbnail('thumbnail');
											}else{
												echo 'Thumbnails are not supported';
											}
											break;
									
								case 'time':
									the_time();
									break;
											
								case 'title':
									the_title();
									break;
							}
							break;
							
						case 'taxonomy':
							echo get_the_term_list($post->ID, $param, '', ', ','');
							break;	

						case 'taxonomy-list':
							echo get_the_term_list($post->ID, $param, '', '<br/>','');
							break;
							
					}//switch
				endforeach;
			}
		}//loop
  
	}//render_columns
}//end class
?>