<?php 
$recent = new WP_Query('sort_by=date');
if ($recent->have_posts()): $recent->the_post();
?>
<a class="block span-12 border article-preview" href="<?php the_permalink();?>">

	
	<!-- 130x130 -->
	<?php if(has_post_thumbnail($recent->ID)):?>
		<div class="span-4 border">
			<?php the_post_thumbnail(array(130,130), array('class' => 'thumbnail'));?>
		</div>
		
		<div class="span-8 last">
	<?php else: //No thumbnail column ?>
		<div class="span-12 last">
	<?php endif;?>
	
			<div class="article">
				<h3 class="top-space half-space"><?php the_title();?></h3>
				<?php the_excerpt();?>
			</div>
		</div>
</a><!-- most recent -->
<?php else: ?>
	<a class="block span-12 border">
		<div class="article article-preview">
			<h3 class="top-space half-space">Tips and Tricks</h3>
			<p>Check out our complete list of tips and tricks that will save you time, money, and more than a couple headaches.</p>
		</div>
	</a>
<?php endif; wp_reset_postdata(); //If Post ?>