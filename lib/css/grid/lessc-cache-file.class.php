<?php 
/* Lessc Cache File
 * v 1.0
 * 
 * @description Creates and serves a cached version of a folder of .less files if needed.
 * NOTE: Default css/less files can be set in the get_files method.
 */
class LesscCacheFile
{
    var $cssDir, 
        $gridDir, 
        $libDir, 
        $cache, 
        $files, 
        $time,
        $needs_recache;

	function __construct($cssDir, $gridDir = FALSE, $libDir = FALSE)
	{
		//Begin output buffer
		//I think this is legal to place here?
		if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler");
		else ob_start();
		
		//Set Class Vars
        $this->cssDir = $cssDir;
        $this->gridDir = ($gridDir) ? $gridDir : $this->cssDir . '/grid';
        $this->libDir = ($libDir) ? $libDir : $this->cssDir . '/less-lib';
		$this->cache = $cssDir.'/cache.css';
        $this->files = $this->get_files();
        $this->time = $this->get_mod_time();
		
		//Init
        $this->check_if_needs_recache();

        if (!$this->needs_recache
            && isset($_SERVER['If-Modified-Since'])
            && strtotime($_SERVER['If-Modified-Since']) >= $this->time
        ): 
            header("HTTP/1.0 304 Not Modified");
        else:
            $this->serve_file();
        endif;
	}

    function get_files()
    {
        /* ------------------------------------------
         * Defaults
         * ------------------------------------------ 
         */
        //Required files explicitly defined here.
        //Include Grid, css3 LESS classes, and default style.less (there mixins should be defined)
        $files = array(
            $this->gridDir . '/reset.css',
            $this->gridDir . '/settings.less',
            $this->gridDir . '/grid.less',
            $this->libDir . '/css3.less', 
            $this->cssDir . '/style.less'
        );

        /* -----------------------------------------
         * Load Files from CSS DIR
         * -----------------------------------------
         */
        foreach (glob($this->cssDir . '/*.less', GLOB_NOSORT) as $f)
        { 
            if (!is_dir($f) 
                && basename($f) !== 'style.less'
            ) $files[] = $f;
        }

        return $files;
    }
	
	function get_mod_time()
	{
		//Get Current Time
		$time = mktime(0,0,0,21,5,1980);
		
		//Get last modified time
		foreach($this->files as $file) {
			$fileTime = filemtime($file);
			if($fileTime > $time) $time = $fileTime;
		}

        return $time;
	}

	function check_if_needs_recache()
    {
        if (file_exists($this->cache)):
            $cacheTime = filemtime($this->cache);
            if ($cacheTime < $this->time):
                $this->time = $cacheTime;
                $this->needs_recache = TRUE;
            else:
                $this->needs_recache = FALSE;
            endif;

        else:
            $this->needs_recache = TRUE;

        endif;
	}

    function serve_file()
    {
        header('Content-type: text/css');
        header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $this->time) . ' GMT');

        if ($this->needs_recache):
            require $this->gridDir . '/lessc.inc.php';
            $lc = new lessc();

            $css = '';
            foreach ($this->files as $file)
            {
                $css .= file_get_contents($file);
            }

            //Parse with LESS CSS
            $css = $lc->parse($css);
            //Write to cache file
            file_put_contents($this->cache, $css);
            //Serve parsed css
            echo $css;

        else:
            //Cache.css is already created and current
            readfile($this->cache);
        endif;
    }
}
