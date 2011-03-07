<?php
$cssDir = dirname(__FILE__) . '/lib/css';
require $cssDir .'/grid/lessc-cache-file.class.php';

new LesscCacheFile($cssDir);
