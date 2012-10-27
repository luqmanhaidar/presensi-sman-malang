<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

//class MY_Loader extends MX_Loader {}
class MY_Loader extends MX_Loader {
	
	/**
	 * Allows the loading of templates. Normaly you want pages with the same layout across your site.
	 * If you decide to load a template, then any of the views you load afterwards will be placed inside
	 * the template's code in the position with the $content variable
	 * 
	 * @param $template		The filename of the template to use, in the style of loading a view
	 * @param $data			Any data you wish to pass to the template, in a data array just like the views
	 * @param $return		If you want to just get the template contents set to true
	 */
	function theme($path='',$vars = array(),$return = FALSE)
	{
		return $this->_ci_load(array('_ci_path' => 'themes/'.$path.'/index'.EXT, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
		
	}
	
	
}

