<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table User SQL Server 2005 Table ngac_userinfo **/

class User extends CI_Controller {
    
    public function __contruct()
    {
        parent::__construct();
        $this->load->model('userinfo'); //load model userinfo form user   
    }
    
	public function index($offset=0)
	{
        $data['title']  = 'User Group';
        if($this->session->userdata('user_paging'))
            $paging = $this->session->userdata('user_paging');
        else
            $paging = config_item('paging');
        
        $data['users']  = $this->userinfo->getAllRecords($offset,$paging);
        $numrows = COUNT($this->userinfo->getAllRecords()); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('employee/user/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'user/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page){
        $this->session->set_userdata('user_paging',$per_page);
        redirect('employee/user/index');
    }
    
}

/* End of file user.php */
/* Location: ./module/employee/user.php */
