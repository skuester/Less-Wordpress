<div id="services" class="text">
	<?php 
	$page = get_page_by_path('services');
	$about = new WP_Query('post_type=page&page_id='.$page->ID);
	if($about->have_posts()): $about->the_post();
	?>
		<div class="top-space">
		<?php the_content(); ?>
		</div>
		
	<?php else: ?>
		
		<p>
		Local Home Appliance has more than 30 years experience in serving you and your family by providing quality service and support.
		</p>
	<?php endif; wp_reset_postdata();?>
</div>