
<div id="slideshow">
	<?php 
	$slides = new WP_Query('post_type=slideshow&order_by=title&order=ASC');
	if($slides->have_posts()): while($slides->have_posts()): $slides->the_post();
	$link = get_post_meta($slides->post->ID, '_slide-link', true);
	?>
		<?php if($link):?>
			<a href="<?php echo $link; ?>" class="slide">
		<?php else: ?>
			<div class="slide">
		<?php endif;?>
		
				<?php the_content();?>
				<?php if(has_post_thumbnail()) the_post_thumbnail(array(674, 350)); ?>
		
		<?php if($link): ?></a><?php else: ?></div><?php endif;?>
		
	<?php endwhile; else: ?>
	
		<img src="<?php bloginfo('template_directory');?>/img/default-banner.jpg" alt="Local Home Appliance" />
	<?php endif; wp_reset_postdata();?>
	
	
</div>