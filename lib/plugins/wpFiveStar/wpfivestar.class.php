<?php 
/* WP Five Star v 1.0
 * Requires: wpHelper 1.52 or later
 * 
 */
class wpFiveStar extends wphelper
{
	function __construct($params)
	{
		//Store params
		$this->params = $params;
		
		//Add Title Meta for Reviews
		new commentMeta(array('_rating-title'));
		//Add Comment Meta (using wphelper)
		new commentMeta($this->params);
		//Add Inputs for each Indicator
		add_filter('comment_form_default_fields', array(&$this, 'add_fields'), 10, 1);
		
		//Update global rating when approved or unapproved
		add_action('transition_comment_status', array(&$this, 'update_ratings'), 10, 3);
		//Update global rating when approved comment is deleted
		add_action('delete_comment', array(&$this, '_remove_review'), 10, 3);
		
		//enqueue Styles and Scripts (exclude from admin pages)
		if(!is_admin()) {
			wp_enqueue_style('wpFiveStar', WPFIVESTAR_ABSPATH . '/css/main.css');
			wp_enqueue_script('wpFiveStar', WPFIVESTAR_ABSPATH . '/js/main.js');
		}
	}
	
	function add_fields($fields, $show_url = FALSE)
	{
		//Remove the URL field (by default)
		if (!$show_url) {
			unset($fields['url']);
		}
		
		//Default Label
		$fields['pwfs-instruction'] = '<label class="block top-space">Please rate us on the following, 1 being the worst, and 5 being the best.</label>';
		
		$fields['wpfs-open'] = '<ul id="wpfs-rating-input" class="wpfs-rating-input wpfs-paramlist">';
		foreach ($this->params as $label => $name)
		{
			$fields[$name] = "
			<li class='comment-form-$name single-space wpfs-param'>
				<p class='wpfs-param-label'>$label</p>
				<div class='wpfs-stars'>
					<input type='radio' name='$name' value='1'/><span>1</span>
					<input type='radio' name='$name' value='2'/><span>2</span>
					<input type='radio' name='$name' value='3'/><span>3</span>
					<input type='radio' name='$name' value='4'/><span>4</span>
					<input type='radio' name='$name' value='5'/><span>5</span>
				</div>
			</li>";
		}
		$fields['wpfs-close'] = '</ul>';
		
		$fields['_rating-title'] = "
		<br/>
		<p class='comment-form-title single-space top-space'>
			<label for='_rating-title'>Review Title</label>
			<input type='text' name='_rating-title' id='wpfs-rating-title'/>
		</p>";
		
		return $fields;
	}
	
	function update_ratings($newStatus, $oldStatus, $comment)
	{
		//Save approved votes (votes switched from unapproved to approved)
		if($newStatus == 'approved' && $oldStatus == 'unapproved')	
			$this->_add_review($comment->comment_ID);
		
		//Destroy unapproved votes (votes once counted, but no longer valid)
		if($newStatus == 'unapproved' && $oldStatus == 'approved') 
			$this->_remove_review($comment->comment_ID);
	}
	
	private function _add_review($commentID)
	{
		//Get current rating or Create New One
		$rating = (get_option('wpFiveStar_rating')) ? get_option('wpFiveStar_rating') : array();

		//Update Total Votes
		$rating['votes']++;
		//Update each paramater
		foreach($this->params as $label => $index) 
		{
			//Get the user rating
			$userRating = get_comment_meta($commentID, $index, true);
			//Calc new total
			$rating[$index] += $userRating;
			//Create label for each param
			$rating['labels'][$index] = $label;
		}
		//Save new rating
		update_option('wpFiveStar_rating', $rating);
	}
	
	private function _remove_review($commentID)
	{
		$status = wp_get_comment_status($commentID);
		if($rating = get_option('wpFiveStar_rating') && $status == 'approved')
		{
			$rating['votes']--;
			foreach($this->params as $label => $index)
			{
				$userRating = get_comment_meta($commentID, $index, true);
				$rating[$index]-= $userRating;
			}
			
			//Delete option if votes == zero
			if($rating['votes'] == 0)
				delete_option('wpFiveStar_rating');
			else
				update_option('wpFiveStar_rating', $rating);
		}
	}
}//end Class

/* =======================================================================
 * Public Functions
 * For use with wpFiveStar Class or to ease implelemtation
 * ======================================================================= */

/* WP Five Star Summary 1.0
 * Public function to render the review summary
 */
function wpFiveStar_summary()
{
	if($rating = get_option('wpFiveStar_rating')):
		echo '<ul class="wpfs-summary wpfs-paramlist">';
		foreach ($rating as $r => $v)
		{
			//Iterate through all ratings, excluding votes and labels
			if ($r !== 'votes' && $r !== 'labels')
			{
				$average = $v / $rating['votes'];
				$float_average = round($average, 1);
				$average = round($average);
				echo '
					<li class="wpfs-param wpfs-rating-'.$average.'">
						<p class="wpfs-param-label">' . $rating['labels'][$r] . ': </p>
						<div class="wpfs-stars">
							<div class="wpfs-star wpfs-star-1" name="1"></div>
							<div class="wpfs-star wpfs-star-2" name="2"></div>
							<div class="wpfs-star wpfs-star-3" name="3"></div>
							<div class="wpfs-star wpfs-star-4" name="4"></div>
							<div class="wpfs-star wpfs-star-5" name="5"></div>
						</div>
					</li>';
			}
		}
		echo '</ul>';
		
	else:
			echo '
				<h2>No reviews have been written yet.</h2>
				<h3>Be the first to leave your review!</h3>
				';
	endif;
}

/* WP Five Star Rating 1.0
 * Internal Function to render each infividual star rating
 */
function wpFiveStar_rating()
{
	if($rating = get_option('wpFiveStar_rating')):
		echo '<ul class="wpfs-rating wpfs-paramlist">';
		foreach ($rating as $r => $v)
		{
			//Iterate through all ratings, excluding votes and labels
			if ($r !== 'votes' && $r !== 'labels')
			{
				$value = get_comment_meta(get_comment_ID(), $r, true);
				echo '
					<li class="wpfs-param wpfs-rating-'.$value.'">
						<p class="wpfs-param-label">' . $rating['labels'][$r] . ': </p>
						<div class="wpfs-stars">
							<div class="wpfs-star wpfs-star-1" name="1"></div>
							<div class="wpfs-star wpfs-star-2" name="2"></div>
							<div class="wpfs-star wpfs-star-3" name="3"></div>
							<div class="wpfs-star wpfs-star-4" name="4"></div>
							<div class="wpfs-star wpfs-star-5" name="5"></div>
						</div>
					</li>';
			}
		}
		echo '</ul>';
	endif;
}


/* WP Five Star Reviews 1.0
 * Public function to render the individual reviews and review form
 */
function wpFiveStar_reviews()
{
	comments_template(WPFIVESTAR_PATH .'/wpfivestar.reviews.php'); 
}

function wpFiveStar_review_form()
{	
	
	echo '<div id="wpfs-review-form">';
	
	//Show Comment Form
	comment_form(array(
			'comment_field' => '<p class="comment-form-comment">
									<label for="comment" class="block">Type your review in the space below</label>
									<textarea class="wpfs-comment" id="comment" name="comment" aria-required="true"></textarea>
								</p>',
			'comment_notes_before' => '',
			'comment_notes_after' => '', 
			'id_submit' => 'comment-submit',
			'label_submit' => 'Submit',
			'title_reply' => 'Write a Review'
		));	
		
	echo '</div>';
}



/* WP Five Stat Comment 1.0
 * For use internally by the wpFiveStar class
 */
function wpFiveStar_comment($comment, $args, $depth) 
{
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-body">

      		<div class="span-8">
      			<div class="article">
		         	<h4 class="single-space comment-author"><?php echo get_comment_author_link(); ?></h4>
		         
		         	<div class="comment-meta commentmetadata">
		      			<?php printf(__('Written %1$s'), get_comment_date()) ?><?php edit_comment_link(__('(Edit)'),'  ','') ?>
		      		</div>
		      		
		         	<?php if ($comment->comment_approved == '0') : ?>
				         <em><?php _e('Your comment is awaiting moderation.') ?></em>
				    <?php endif; ?>
			    </div>
		    </div>
		    
		    <div class="span-8 last">
		    	<div class="article">
				    <?php wpFiveStar_rating(); ?>
				</div>
		    </div>
		    
		    <div class="span-16 last">
		    	<div class="article top-space">
		    		<p class="half-space comment-title"><strong><?php echo get_comment_meta(get_comment_ID(), '_rating-title', true);?></strong></p>
				    <?php comment_text(); ?>
				    <?php //get_comment_link($comment->comment_ID); ?>
			    </div>
 			</div>
     	</div><!-- comment-body--> <?php
}