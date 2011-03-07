<?php
/* Comment Meta
 * V 1.0
 * 
 * Register Comment Meta Data
 */

class commentMeta extends wphelper
{
	function __construct($options)
	{
		$this->meta = array();
		//Store array of meta data keys or single key in an array
		if(is_array($options)) {
			foreach( $options as $meta)
			{
				$this->meta[] = $meta;
			}
		} else { $this->meta[0] = $options;	}
		
		//Hook
		add_action('comment_post', array(&$this, '_save'), 1);
	}
	
	function _save($comment_id)
	{
		foreach($this->meta as $key )
		{
			add_comment_meta($comment_id, $key, $_POST[$key], true);
		}
	}
	
	//	Sample Comment Meta Retrieval
	//	$GLOBALS['comment'] = $comment;
	//	$meta_array = get_comment_meta($comment_id, $this->name);
	//	$meta = $meta_array[0];
	//	echo $meta;
}