<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Dashboard Menu Press System **/

class User extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        if(!is_userInfo()):
            is_message_loginErr();
            redirect('auth/user/index');
        endif;    
	}
    
    public function index(){
		$data['title']		=	'Dashboard';
        $data['page']	    =	'dashboard';
        $this->load->vars($data);
        $this->load->theme('default',$data);
	}
    
}