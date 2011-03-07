	<ol class="text top-space article-list">
		<?php 
		//Currently listing only 5 most recent
		$five = new WP_Query('posts_per_page=5');
		if($five->have_posts()): while($five->have_posts()): $five->the_post(); ?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endwhile; endif; wp_reset_postdata();?>
	</ol>