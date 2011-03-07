<?php header("Content-type: text/css"); 
require_once strstr($_SERVER['SCRIPT_FILENAME'], 'wp-content', true) .'wp-load.php';
?>

/*
 * FancyBox - jQuery Plugin
 * Simple and fancy lightbox alternative
 *
 * Examples and documentation at: http://fancybox.net
 * 
 * Copyright (c) 2008 - 2010 Janis Skarnelis
 *
 * Version: 1.3.1 (05/03/2010)
 * Requires: jQuery v1.3+
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

#fancybox-loading {
	position: fixed;
	top: 50%;
	left: 50%;
	height: 40px;
	width: 40px;
	margin-top: -20px;
	margin-left: -20px;
	cursor: pointer;
	overflow: hidden;
	z-index: 1104;
	display: none;
}

* html #fancybox-loading {	/* IE6 */
	position: absolute;
	margin-top: 0;
}

#fancybox-loading div {
	position: absolute;
	top: 0;
	left: 0;
	width: 40px;
	height: 480px;
	background-image: url('fancybox.png');
}

#fancybox-overlay {
	position: fixed;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
	background: #000;
	z-index: 1100;
	display: none;
}

* html #fancybox-overlay {	/* IE6 */
	position: absolute;
	width: 100%;
}

#fancybox-tmp {
	padding: 0;
	margin: 0;
	border: 0;
	overflow: auto;
	display: none;
}

#fancybox-wrap {
	position: absolute;
	top: 0;
	left: 0;
	margin: 0;
	padding: 20px;
	z-index: 1101;
	display: none;
}

#fancybox-outer {
	position: relative;
	width: 100%;
	height: 100%;
	background: #FFF;
}

#fancybox-inner {
	position: absolute;
	top: 0;
	left: 0;
	width: 1px;
	height: 1px;
	padding: 0;
	margin: 0;
	outline: none;
	overflow: hidden;
}

#fancybox-hide-sel-frame {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: transparent;
}

#fancybox-close {
	position: absolute;
	top: -15px;
	right: -15px;
	width: 30px;
	height: 30px;
	background-image: url('fancybox.png');
	background-position: -40px 0px;
	cursor: pointer;
	z-index: 1103;
	display: none;
}

#fancybox_error {
	color: #444;
	font: normal 12px/20px Arial;
	padding: 7px;
	margin: 0;
}

#fancybox-content {
	height: auto;
	width: auto;
	padding: 0;
	margin: 0;
}

#fancybox-img {
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border: none;
	outline: none;
	line-height: 0;
	vertical-align: top;
	-ms-interpolation-mode: bicubic;
}

#fancybox-frame {
	position: relative;
	width: 100%;
	height: 100%;
	border: none;
	display: block;
}

#fancybox-title {
	position: absolute;
	bottom: 0;
	left: 0;
	font-family: Arial;
	font-size: 12px;
	z-index: 1102;
}

.fancybox-title-inside {
	padding: 10px 0;
	text-align: center;
	color: #333;
}

.fancybox-title-outside {
	padding-top: 5px;
	color: #FFF;
	text-align: center;
	font-weight: bold;
}

.fancybox-title-over {
	color: #FFF;
	text-align: left;
}

#fancybox-title-over {
	padding: 10px;
	background-image: url('fancy_title_over.png');
	display: block;
}

#fancybox-title-wrap {
	display: inline-block;
}

#fancybox-title-wrap span {
	height: 32px;
	float: left;
}

#fancybox-title-left {
	padding-left: 15px;
	background-image: url('fancybox.png');
	background-position: -40px -90px;
	background-repeat: no-repeat;
}

#fancybox-title-main {
	font-weight: bold;
	line-height: 29px;
	background-image: url('fancybox-x.png');
	background-position: 0px -40px;
	color: #FFF;
}

#fancybox-title-right {
	padding-left: 15px;
	background-image: url('fancybox.png');
	background-position: -55px -90px;
	background-repeat: no-repeat;
}

#fancybox-left, #fancybox-right {
	position: absolute;
	bottom: 0px;
	height: 100%;
	width: 35%;
	cursor: pointer;
	outline: none;
	background-image: url('blank.gif');
	z-index: 1102;
	display: none;
}

#fancybox-left {
	left: 0px;
}

#fancybox-right {
	right: 0px;
}

#fancybox-left-ico, #fancybox-right-ico {
	position: absolute;
	top: 50%;
	left: -9999px;
	width: 30px;
	height: 30px;
	margin-top: -15px;
	cursor: pointer;
	z-index: 1102;
	display: block;
}

#fancybox-left-ico {
	background-image: url('fancybox.png');
	background-position: -40px -30px;
}

#fancybox-right-ico {
	background-image: url('fancybox.png');
	background-position: -40px -60px;
}

#fancybox-left:hover, #fancybox-right:hover {
	visibility: visible;    /* IE6 */
}

#fancybox-left:hover span {
	left: 20px;
}

#fancybox-right:hover span {
	left: auto;
	right: 20px;
}

.fancy-bg {
	position: absolute;
	padding: 0;
	margin: 0;
	border: 0;
	width: 20px;
	height: 20px;
	z-index: 1001;
}

#fancy-bg-n {
	top: -20px;
	left: 0;
	width: 100%;
	background-image: url('fancybox-x.png');
}

#fancy-bg-ne {
	top: -20px;
	right: -20px;
	background-image: url('fancybox.png');
	background-position: -40px -162px;
}

#fancy-bg-e {
	top: 0;
	right: -20px;
	height: 100%;
	background-image: url('fancybox-y.png');
	background-position: -20px 0px;
}

#fancy-bg-se {
	bottom: -20px;
	right: -20px;
	background-image: url('fancybox.png');
	background-position: -40px -182px; 
}

#fancy-bg-s {
	bottom: -20px;
	left: 0;
	width: 100%;
	background-image: url('fancybox-x.png');
	background-position: 0px -20px;
}

#fancy-bg-sw {
	bottom: -20px;
	left: -20px;
	background-image: url('fancybox.png');
	background-position: -40px -142px;
}

#fancy-bg-w {
	top: 0;
	left: -20px;
	height: 100%;
	background-image: url('fancybox-y.png');
}

#fancy-bg-nw {
	top: -20px;
	left: -20px;
	background-image: url('fancybox.png');
	background-position: -40px -122px;
}

/* IE */

#fancybox-loading.fancybox-ie div	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_loading.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-close		{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_close.png', sizingMethod='scale'); } 


.fancybox-ie #fancybox-title-over	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_title_over.png', sizingMethod='scale'); zoom: 1; }
.fancybox-ie #fancybox-title-left	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_title_left.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-title-main	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_title_main.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-title-right	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_title_right.png', sizingMethod='scale'); }

.fancybox-ie #fancybox-left-ico		{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_nav_left.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-right-ico	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_nav_right.png', sizingMethod='scale'); }

.fancybox-ie .fancy-bg { background: transparent !important; }

.fancybox-ie #fancy-bg-n	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_n.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-ne	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_ne.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-e	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_e.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-se	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_se.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-s	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_s.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-sw	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_sw.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-w	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_w.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-nw	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/lib/js/fancybox/fancy_shadow_nw.png', sizingMethod='scale'); }
