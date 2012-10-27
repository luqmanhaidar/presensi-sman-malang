<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Auth User SQL Server 2005 Table ngac_userinfo **/

class User extends CI_Controller {
    
    public function __contruct()
    {
        parent::__construct();
    }
    
	public function index()
	{
		$data['content'] = "Testing";
		$this->load->theme('login',$data);
	}
    
    function do_login() 
    {	
        $this->load->module_model('employee','userinfo'); //load model userinfo form user   
        $this->load->module_model('employee','log'); //load model log form user  
		$password   = $this->input->post("password");
        $username   = $this->input->post("username");  
		
        if($this->userinfo->checkUser($username,$password)>0): //check jumlah user terdaftar
            $user = $this->userinfo->getUserData($username); // get userinfo name and group
            if($user['GroupName']=='')
                $user['GroupName']='Administrator';
                
            $auth = array('user'    =>  $user['Name'],
                          'userID'  =>  $user['ID'],
                          'group'   =>  $user['GroupName']  
                    );
            $this->log->save($username,'Login');        
            $this->session->set_userdata($auth);
            redirect('dashboard/user/index',301);
        else:
            $this->log->save('Error Login');
            $this->session->set_flashdata('error_login','<div class="alert alert-info alert-login">Anda Salah Memasukan Username atau Password</div>');
            redirect('auth/user/index',301);
        endif;        
	}
    
    function do_logout()
    {
		$this->session->sess_destroy();
		redirect('/',301);
	}
}

/* End of file user.php */
/* Location: ./module/auth/user.php */
