<?php 
/* Coupon Template
 * 
 * Description: For use within the coupon loop.
 * Requirements: Needs a var of $coupons to be set
 */
global $coupons;
global $printCoupon;
$exp = get_post_meta($coupons->post->ID, '_coupon-expiration', true);
$code = get_post_meta($coupons->post->ID, '_coupon-code', true);
?>

<?php if(isset($printCoupon)):?>
<div class="article bottom-space coupon">
<?php else: ?>
<a href="<?php bloginfo('url'); ?>/promo?print=<?php the_ID();?>&id=<?php echo wp_create_nonce('lha-coupon-2011-print');?>" class="article bottom-space coupon block-nohover">
<?php endif;?>

	<h1 class="logo-copy">LOCAL HOME APPLIANCE</h1>
	<h2 class="single-space"><?php the_title();?></h2>
	<?php the_content(); ?>
	
	<div class="coupon-meta hold-float">
		<?php if ($exp): ?><p class="left"><strong>Expires:</strong> <?php echo $exp;?></p><?php endif;?>
		<?php if ($code): ?><p class="right"><strong>Coupon Code:</strong> <?php echo $code;?></p><?php endif;?>
	</div>
	
	<?php //PRINT FLOAT ?>
	<?php if(!isset($printCoupon)):?>
		<p class="coupon-print">Print</p>
	<?php endif;?>
	
<?php if(isset($printCoupon)):?>
</div>
<?php else: ?>
</a>
<?php endif; ?>