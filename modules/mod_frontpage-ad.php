<?php 
$ad = new WP_Query('post_type=ads&location=front-page');
if($ad->have_posts()): $ad->the_post();
	$link = get_post_meta($ad->post->ID, '_ad-link', true);
	if($link) echo "<a href='$link'>";
	
		if(has_post_thumbnail()) the_post_thumbnail('large', array('alt' => $ad->post->post_content));
		else echo 'ERROR: Must Set Featured Image';
		
	if($link) echo "</a>";
endif; wp_reset_postdata();
?>