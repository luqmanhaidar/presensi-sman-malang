<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** PHPExcel */
/**
* 
*/
class Excel
{
    
    function __construct()
    {
        require_once APPPATH.'third_party/PHPExcel.php';
		require_once APPPATH."third_party/PHPExcel/IOFactory.php";//Require Loader Class n Config
    }
}  