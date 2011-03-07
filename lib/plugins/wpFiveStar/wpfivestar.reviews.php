<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package wpFiveStar
 * @subpackage Default_Theme
 */
?>
			
<div id="wpfs-comments">
<?php if ( have_comments() ) : ?>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<div class="navigation">
			<div class="left"><?php previous_comments_link('Prev'); ?></div>
			<div class="right"><?php next_comments_link('Next'); ?></div>
		</div> <!-- .navigation -->
		<hr/>
	<?php endif; // check for comment navigation ?>

	
	<ul class="commentlist">
		<?php wp_list_comments('callback=wpFiveStar_comment&order_by=date&order=DESC'); ?>
	</ul>
	
				
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<hr/>
		<div class="navigation navigation-bottom">
			<div class="left"><?php previous_comments_link( __( 'Prev' ) ); ?></div>
			<div class="right"><?php next_comments_link( __( 'Next' ) ); ?></div>
		</div><!-- .navigation -->
	<?php endif; // check for comment navigation ?>
	
<?php else :  // or, if we don't have comments: ?>
		<?php if ( ! comments_open() ) : ?>
			<p class="nocomments article top-space">Reviews are currently unavailable.</p>
		<?php else: ?>
			<p class="nocomments article top-space">Sorry, no reviews are available at this time.</p>
		<?php endif; // end ! comments_open() ?>
		
<?php endif; // end have_comments() ?>
</div>

