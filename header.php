<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
<!DOCTYPE html>
<html>

<head>
<!--	<meta http-equiv="refresh" content="1"/>-->

	<title><?php bloginfo('name'); ?></title>
	<meta name="description" content="<?php bloginfo('description');?>"/>
	<meta name="keywords" content="" />
	
	<?php //Stylesheet ============= ?>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/style-loader.less.php" type="text/css" media="screen" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/style-loader-ie.css.php" type="text/css"/>
	<![endif]-->
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
