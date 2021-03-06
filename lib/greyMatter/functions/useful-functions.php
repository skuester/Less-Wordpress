<?php
/* WP This Dir URL
 * v 1.2
 * 
 * Returns the URL to the current directory, given a param of __FILE__
 * Dependencies: WP-dependent
 *
 * CHANGE LOG
 * 1.2 Added is_ie() to detect Internet Explorer
 */
 

/* Is IE
 * Detects Internet Explorer, returns Boolean. 
 * URI: http://www.anyexample.com/programming/php/how_to_detect_internet_explorer_with_php.xml
 * v 1.0
 */
function is_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}
 
 
 /* WP This Dir URL
  * Get the current directory URL in wordpress. 
  * Useful for link URL's within plugins
  * v 1.0
  */
function wp_ThisDirURL($file)
{
	$tURL = get_bloginfo('template_directory');
	$fromTDir = strstr(dirname($file), basename($tURL));
	$fromTDir = str_replace('\\', '/', $fromTDir);
	$url= dirname($tURL).'/'.$fromTDir;
	return $url;
}



/* Is Divisible
 * Check If a Number is divisible by another
 * v 1.1
 */
function is_divisible($subject, $number)
{
	//Last Digit of the subject (so if subject is 15, get 5)
	$lastdigit = substr($subject, -1);
	
	switch($number)
	{
		case 2:
			if($lastdigit == 0 || $lastdigit == 2 || $lastdigit == 4 || $lastdigit == 6 || $lastdigit == 8) return TRUE;
			else return FALSE;
			break;
			
		case 5:
			if($lastdigit == 0 || $lastdigit == 5) return TRUE;
			else return FALSE;
			break;
	}
}


function build_wp_thumbnail_url($img_url, $height = NULL, $width = NULL)
{
	//determine config vars
	$thumb_h = (!is_null($height)) ? $height : get_option('thumbnail_size_h');
	$thumb_w = (!is_null($width)) ? $width : get_option('thumbnail_size_w');
	
	$thumb = preg_replace('/(\.(jpg|jpeg|bmp|gif|png)$)/', '-'.$thumb_w.'x'.$thumb_h.'$1', $img_url);
	
	return $thumb;
}


function get_post_meta_img_thumbnail($meta_tag, $height = NULL, $width = NULL){
	//returns the modified file name of a meta image based on the configured thumbnail height and width
	//or specified height and width
	global $post;
	$image = get_post_meta($post->ID, $meta_tag, true);
	
	return build_wp_thumbnail_url($image, $height, $width);
}


function get_the_post_thumbnail_url($size = 'full', $altPost = NULL){
	if(is_null($altPost))
		global $post;
	else
		$post = $altPost;
	
	$image_id = get_post_thumbnail_id($post->ID);  
	$image_url_height_width = wp_get_attachment_image_src($image_id,$size);  
	$image_url = $image_url_height_width[0]; 
	return $image_url;	
}

/* Specific Excerpt
 * v 1.0
 * 
 * Renders the_excerpt with the specified length. 
 * Best for specific, acute instances
 * 
 * NOTE: not sure if it works in anything but a special, WP_Query loop
 */
function specific_excerpt($obj, $length = 250, $more = '...' )
{
	$newExcerpt = substr($obj->post->post_content, 0, $length);
	echo '<p>'.$newExcerpt.$more.'</p>';
}
?>