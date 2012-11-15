<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Dashboard Menu Press System **/

class User extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user 
        if(!$this->session->userdata('user')):
            is_message_loginErr();
            redirect('auth/user/index',301);
        endif;    
	}
    
    public function index(){
		$data['title']		=	'Dashboard';
        $data['logs']       =   $this->log->userLog();
        $data['page']	    =	'dashboard';
        $this->load->vars($data);
        $this->load->theme('default',$data);
	}
    
}