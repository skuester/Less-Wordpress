<?php 
$posts = new WP_Query('order_by=date');
//IF more than 5 items, add two-col class 
?>
<ul class="text top-space article-list <?php if($posts->post_count > 5) echo 'two-col'; ?>">
	<?php if($posts->have_posts()): while($posts->have_posts()): $posts->the_post(); ?>
	
		<li><a href="<?php the_permalink();?>"><?php the_title();?></a></li>
		
	<?php endwhile; endif; wp_reset_postdata(); ?>
</ul>