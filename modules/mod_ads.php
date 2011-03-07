<ul class="ads">
<?php 
//Exlude Front Page Ads
$excludeCat = get_term_by('slug', 'front-page', 'location');
$excludeCat = $excludeCat->term_id;

$ads = new WP_Query('post_type=ads');
if($ads->have_posts()): while($ads->have_posts()): $ads->the_post();
		$link = get_post_meta($post->ID, '_ad-link', true);
		
		
		//Get The Post Term ID
		$terms = wp_get_post_terms($ads->post->ID, 'location');
		$term = $terms[0]->term_id;
		if($term !== $excludeCat): $ads_count++;?>
		
			<li>
				<a href="<?php echo $link; ?>" class="ad">
					<?php the_post_thumbnail(array(285, 250));?>
				</a>
			</li>
		<?php endif;?>
		
	<?php endwhile; if($ads_count == 1): ?>
		<li>
			<p class="ad ad-temp">Your Add Here</p>
		</li>
	<?php endif; ?>

<?php else: ?>
	<li>
		<p class="ad ad-temp">Your Add Here</p>
	</li>
	
	<li>
		<p class="ad ad-temp">Your Add Here</p>
	</li>
<?php endif; wp_reset_postdata(); ?>	
</ul>