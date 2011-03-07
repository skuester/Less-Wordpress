<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
			
			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyten' ); ?></p>
			</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
	<div class="navigation">

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			
				<div class="left"><?php previous_comments_link('Prev'); ?></div>
				<div class="right"><?php next_comments_link('Next'); ?></div>
			
<?php endif; // check for comment navigation ?>
	</div> <!-- .navigation -->



			<ul class="commentlist">
				<?php
					/* provide array to wp_list_comments to use a function
					 * to format the comments. eg. array('callback' => 'function')
					 */
					wp_list_comments('callback=wpFiveStarComment');
					//modify comment layout (place metadata on bottom
				?>
			</ul>
			
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation navigation-bottom">
				<div class="nav-previous"><?php previous_comments_link( __( 'Prev' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Next' ) ); ?></div>
			</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
	<p class="nocomments">Comments are Closed.</p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>



<?php // COMMENT FORM CUSTOMIZATION =================================  
//Filter All Fields
add_filter('comment_form_default_fields', 'sk_this_themes_comment_fields', 10, 1);
function sk_this_themes_comment_fields($fields)
{
	//NOTE: These fields not displayed if logged in.
	//Remove URL Field
	unset($fields['url']);
	//Pass fields back to filter
	return $fields;
}
//Wrap from inside form to under comment fields
//Add Action to comment_form_top and comment_form (bottom)
add_action('comment_form_top', 'sk_comment_form_inside_top');
function sk_comment_form_inside_top()
{ 
	echo '<div class="form-fields">'; 
}
add_filter('comment_form_field_comment', 'sk_this_comment', 10, 1);
function sk_this_comment($field)	
{
	$field .= '</div>';
	return $field;
}
?>
<?php comment_form(array(
		'comment_field' => '<p class="comment-form-comment">
								<label for="comment">Type your review in the space below</label>
								<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
							</p>',
		'comment_notes_before' => '', /* <div class="form-fields"> */
		'comment_notes_after' => '', /* </div> */
		'id_submit' => 'comment-submit',
		'label_submit' => 'Submit',
		'class_submit' => 'button-blue',
		'title_reply' => 'Write a Review'
	));?>
</div><!-- #comments -->
