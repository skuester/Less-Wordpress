<div class="text">
	<?php 
	$page = get_page_by_path('about');
	$about = new WP_Query('post_type=page&page_id='.$page->ID);
	if($about->have_posts()): $about->the_post();
	?>
		<h2 class="h-important top-space"><?php the_title(); ?></h2>
		
		<?php
		//Custom, single-use Excerpt Length
		specific_excerpt($about);
		?>
		
	<?php else: ?>
		<h2 class="h-important top-space">About Us</h2>
		
		<p>
		Local Home Appliance has more than 30 years experience in serving you and your family by providing quality service and support.
		</p>
	<?php endif; wp_reset_postdata();?>
</div>