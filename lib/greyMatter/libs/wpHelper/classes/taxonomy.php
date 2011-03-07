<?php
/*
 * Copyright (C) 2010 DarkGrey Media. All Rights Reserved.
 * Taxonomy v1.4
 * 
 * Chagelog
 * 1.2 Updated Labeling & Supports most common params
 * 1.3 This is now a child class of the wphelper core
 * 1.4 Now Supports adding Custom Meta to Taxonomy
 * 
 * TODO: Add support for multiple input types!
 */
class taxonomy extends wphelper
{
	function __construct($options)
	{
		//var_dump($options);
		//Set Options
		$this->setOptions(array(
			'tag' => NULL,
			'for' => $options['default_for'],
			'hierarchical' => TRUE,
			'public' => TRUE,
			'rewrite' => TRUE,
			'label' => NULL,
			'singular_label' => NULL,
			'custom_meta' => FALSE,
		), $options);
		
		//Set Options with Dependant Defaults
		$this->setOptions(array(
			'show_ui' => $this->public,
			'search_items' => 'Search '.$this->label,
			'all_items' => 'All '.$this->label,
			'parent_item' => 'Parent '.$this->singular_label,
			'edit_item' => 'Edit '.$this->singular_label,
			'update_item' => 'Update '.$this->singular_label,
			'add_new_item' => 'Add New '.$this->singular_label,
			'new_item_name' => 'New '.$this->singular_label.' Name'
		), $options);
		
		
		//Set Options with Double Dependant Defaults
		$this->setOptions(array(
			'show_tagcloud' => $this->show_ui,
			'parent_item_colon' => $this->parent_item.':'
		), $options);
	
		//WP Hook to init taxonomy
		add_action('init', array($this, 'new_taxonomy'));
		
		//Internal Hook to init custom taxonomy meta
		if($this->custom_meta)
		{
			$metaInput = array();
			$metaInput['taxonomy'] = $this->tag;
			$metaInput['custom_meta'] = $this->custom_meta;
			new taxonomyMeta($metaInput);
		}
	}
	
	function new_taxonomy()
	{
		$labels = array(
			'name' => $this->label,
			'singular_name' => $this->singular_label,
			'search_items' => $this->search_items,
			'all_items' => $this->all_items,
			'parent_item' => $this->parent_item,
			'patent_item_colon' => $this->parent_item_colon,
			'edit_item' => $this->edit_item,
			'update_item' => $this->update_item,
			'add_new_item' => $this->add_new_item,
			'new_item_name' => $this->new_item_name
		);
		register_taxonomy($this->tag, array($this->for), array(
										"labels" => $labels, 
										"hierarchical" => $this->hierarchical, 
										"public" => $this->public,
										"show_ui" => $this->show_ui,
										"show_tagcloud" => $this->show_tagcloud,
										"rewrite" => $this->rewrite
										));  
	}
}//taxonomy class
?>