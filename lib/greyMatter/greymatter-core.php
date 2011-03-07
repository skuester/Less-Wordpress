<?php 
/* Greymatter
 * v 1.2
 *
 * CHANGELOG
 * 1.1 Load_Dir method added
 * 1.2 Load_Scripts and Load_Styles method added (wp)
 */
class greymatter
{
    var $scripts;

	function __construct()
	{	
		//Init Actions Here
	}
	
	/* Load Plugins
	 * v 1.0
	 * 
	 * Auto-include template plugin files
	 */
	public function load_plugins($dir)
	{	
		foreach(glob($dir.'/*', GLOB_NOSORT) as $folder){
			if(is_dir($folder)) 
			{
				//Get plugin folder name and normalize to lower case
				$file = strtolower(basename($folder));
				//Get plugin index of the same name
				$index = "$folder/$file.php";
				
				//Auto Include
				if(file_exists($index)) require_once $index;
			}	
		}
	}

    /* Load Dir
     * v 1.1
     *
     * Include each level 1 PHP file in a directory
     *
     * CHANGELOG
     * 1.1 Can accept an array of directories to load.
     */
    public function load_dir($dir)
    {
        if (is_array($dir)):
            foreach ($dir as $d) {
                self::load_dir($d);
            }
        else:
            foreach (glob($dir.'/*.php', GLOB_NOSORT) as $file) {
                require_once $file;
            }
        endif;
    }

    /* Load Scripts
     * v 1.0
     *
     * WordPress helper to automatically enqueue all scripts from a directory
     * NOTE: This function assumes all scripts are jquery dependent and will 
     * load the scripts into the footer
     *
     * REQUIRES: PHP 5.3
     */
    public function load_scripts($dir)
    {
        if (is_array($dir)):
            foreach ($dir as $d) self::load_scripts($d);
        else:
            add_action('init', function() {
                foreach (glob($dir .'/*.js', GLOB_NOSORT) as $file) {
                    enqueue_script('greymatter-' . basename($file), $file, array('jquery'), '1', true);
                }
            });

        endif;
    }

    /* Load Styles
     * v 1.0
     *
     * WordPress helper to automatically enqueue all styles from a directory
     *
     * REQUIRES: PHP 5.3
     */
    public function load_styles($dir)
    {
        if (is_array($dir)):
            foreach ($dir as $d) self::load_styles($d);
        else:
            add_action('init', function() {
                foreach (glob($dir .'/*.[css,less,pcss]', GLOB_NOSORT) as $file) {
                    enqueue_style('greymatter-' . basename($file), $file);
                }
            });

        endif;
    }

    /* NAME FROM FILE
     * v 1.0
     *
     * Create a convention-based name from a filename.
     * Currently only works with PHP files
     *
     * NAMING CONVENTION:
     * File name = class name = mysql table name.
     * Instantiated var will be the Capitalized file name. Optionally, any 
     * letter following a "-" will be capitalized as well.
     *
     * Eg: 
     * FILE (INPUT)     | CONTROLLER            | TABLE     | OUTPUT
     * -------------------------------------------------------------------
     * file.php         | files_controller()    | file      | "File" 
     * file-name.php    | filenames_controller()| filename  | "FileName"
     * f_so-name.php    | f_sonames_controller()| f_soname  | "F_SoName" 
     */
    function create_name_from_file($name)
    {
        str_replace('.php', '', $name);
        $name = ucfirst($name);
        $name = preg_replace('/-([a-z])/e', "strtoupper('\\1')", $name);
        return $name;
    }
    
    /* ------------------------------------------------------
     * APP NOTIFICATIONS
     * ------------------------------------------------------
     */    
    
    /* Set Notice
     * v 1.1
     *
     * Generic: Write a notification to $_SESSION.
     * CHANGELOG: 
     * 1.1 Can provide array of messages.
     */
    function setNotice($message, $type = 'notice')
    {
        global $Session;
        if ($message) $Session->update('notice', array('type' => $type, 'message' => $message));
        else $this->throw_error('GreyMatter: Missing string for '.$type);
    }

    /* Notice
     * v 1.1
     *
     * Generic: Display a notification from $_SESSION and clear.
     * CHANGELOG:
     * 1.1 Can intelligently display an array of messages.
     */
    function notice($tag = 'h4', $class = '')
    {
        global $Session;

        if (isset($Session->notice)) {

            $type = $Session->notice['type'];
            $message = '';

            //Array of messages, or single?
            if (is_array ($Session->notice['message'])) {
                $tag = 'div';
                foreach ( $Session->notice['message'] as $m ) {
                    $message .= "<p>$m</p>";
                }
            }else{
                $message = $Session->notice['message'];
            }
            
            echo "<$tag class='$type $class'>$message</$tag>";
            $Session->delete('notice');
        }
    }

    /* Redirect To
     * v 1.0
     *
     * Based on Controller/Action convention
     */
    function redirect_to($url)
    {
        header('Location: ' . ROOTURL . '/' . $url);
    }

    function setOptions($params, $userValues)
	{
		//First Get User Input
		$this->_getUserValues($userValues);
		//Now Determine whether to use user input or default
		foreach($params as $option => $default)
		$this->$option = (isset($this->userValues[$option])) ? $this->userValues[$option] : $default;
	}
	
	function setLocalOptions($params, $inputValues)
	{
		//Instantiate Return Object
		$returnObj = (object)NULL;
		//Determine whether to use user input or default
		foreach($params as $option => $default)
		{
			$returnObj->$option = (isset($inputValues[$option])) ? $inputValues[$option] : $default;
		}
		return $returnObj;
	}
	
	private function _getUserValues($userValues = NULL) 
	{
		//decode userValues (if JSON)
		if(is_string($userValues)) 
		{
			if( is_null(json_decode($userValues, true)) ) 
				$this->throw_error('WPHELPER ERROR: Invalid JSON');
			else 
				$userValues = json_decode($userValues, true);
		}
		$this->userValues = $userValues;
	}
	
    function throw_error($message)
	{
		trigger_error($message, E_USER_ERROR);
	}
}

?>
