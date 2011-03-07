<?php 
/* GreyMatter v 1.2
 * 
 * GreyMatter is the main library for DarkGrey Media
 * Change Log:
 * 1.11 Classes and Functions now auto loaded by DGM_autoload
 * 1.2 This file now broadened to load entire app core 
 */
//Include Core
$thisDir = dirname(__FILE__);
require_once $thisDir . '/greymatter-core.php';

//Instantiate Core
$App = new greymatter();

//Load GreyMatter Classes, Functions
$App->load_dir(array(
    $thisDir . '/classes',
    $thisDir . '/functions'
    ));

//Load Libs
$App->load_plugins($thisDir . '/libs');

//load Plugins
$App->load_plugins(dirname($thisDir) . '/plugins');

//Load Javascript
$App->load_scripts(dirname($thisDir) . '/js');
?>
