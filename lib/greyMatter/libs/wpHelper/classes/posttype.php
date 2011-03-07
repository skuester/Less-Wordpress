<?php
/*
 * Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * PostType v1.3
 * 
 * Changelog
 * 1.2 Updated Labeling
 * 1.3 This is now a child class of the wphelper core
 */
class postType extends wphelper
{
	function __construct($options)
	{
		//Set Options
		$this->setOptions(array(
			'label' => NULL,
			'singular_label' => NULL,
			'add_new' => 'Add New',
			'tag' => NULL,
			'supports' => array('title', 'editor', 'thumbnail'),
			'public' => TRUE,
			'menu_position' => NULL,
			'menu_icon' => NULL,
			'hierarchical' => FALSE,
			'rewrite' => TRUE,
			'capability_type'=> 'post',
			'taxonomy' => NULL
		), $options);
		
		//Set Options with Dependant Defaults
		$this->setOptions(array(
			'add_new_item' => 'Add New '.$this->singular_label,
			'edit_item' => 'Edit '.$this->singular_label,
			'new_item' => 'New '.$this->singular_label,
			'view_item' => 'View '.$this->singular_label,
			'search_items' => 'Search '.$this->label,
			'not_found' => 'No '.$this->label.' found',
			'not_found_in_trash' => 'No '.$this->label.' found in Trash',
			
			'publicly_queryable' => $this->public,
			'exclude_from_search' => !$this->public,
			'show_ui' => $this->public
			
		), $options);

		//add post type hook
		add_action('init', array($this, 'new_type'));
		
		if($this->taxonomy !== NULL)
		{
			//Create a default "for" value (for the new taxonomy) that points to this new post type. 
			//This will be used if the user does not specify a for value - which can be an array.
			$this->taxonomy['default_for'] = $this->tag;
			new taxonomy($this->taxonomy);
		}
	}

	function new_type()
	{
		$labels = array(
			'name' => $this->label,
			'singular_name' => $this->singular_label,
			'add_new' => $this->add_new,
			'add_new_item' => $this->add_new_item,
			'edit_item' => $this->edit_item,
			'new_item' => $this->new_item,
			'view_item' => $this->view_item,
			'search_items' => $this->search_items,
			'not_found' => $this->not_found,
			'not_found_in_trash' => $this->not_found_in_trash
		);
		$args = array(
			'labels' => $labels,
			'public' => $this->public,
			'show_ui' => $this->show_ui,
			'publicly_queryable' => $this->publicly_queryable,
			'exclude_from_search' => $this->exclude_from_search,
			'capability_type' => $this->capability_type,
			'hierarchical' => $this->hierarchical,
			'rewrite' => $this->rewrite,
			'supports' => $this->supports,
			'menu_position' => $this->menu_position,
			'menu_icon' => $this->menu_icon
		);	
		register_post_type($this->tag, $args);
	}
	
}//postType class
?>