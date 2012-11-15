<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table User SQL Server 2005 Table ngac_userinfo **/

class User extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('user')):
            is_message_loginErr();
            redirect('auth/user/index',301);
        endif;
        $this->load->module_model('employee','log'); //load model usergroup form user 
        $this->load->model('userinfo'); //load model userinfo form user   
        $this->load->model('usergroup'); //load model usergroup form user 
    }
    
    
	function index($offset=0)
	{
        $data['title']  = 'User';
        $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('user_paging'))
            $paging = $this->session->userdata('user_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('user_offset',$offset);
        $data['groups']	= $this->usergroup->getDataFromGroup();
        $data['pos']	= $this->usergroup->getDataFromPosition();
        $data['users']  = $this->userinfo->getAllRecords($offset,$paging,$this->session->userdata('user_search'),$this->session->userdata('user_group'),$this->session->userdata('user_pos'));
        $numrows = COUNT($this->userinfo->getAllRecords('','',$this->session->userdata('user_search'),$this->session->userdata('user_group'),$this->session->userdata('user_pos'))); 
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
    
    function paging($per_page)
    {
        $this->session->set_userdata('user_paging',$per_page);
        redirect('employee/user/index');
    }
    
    function search()
    {
        $search = array (   'user_search' =>  $this->input->post('table_search'),
                            'user_pos'  =>  $this->input->post('table_pos'),
                            'user_group'  =>  $this->input->post('table_group'));    
        $this->session->set_userdata($search);
        redirect('employee/user/index');
    }
    
    function edit($id){
        $data['title']  = 'User';
        $data['logs']   = $this->log->userLog();
        $data['value']  = $this->userinfo->getUserData($id);
        $data['groups']	= $this->usergroup->getDataFromGroup();
        $data['works']	= $this->usergroup->getDataFromWorkGroup();
        $data['friday']	= $this->usergroup->getDataFromFridayGroup();
        $data['page']	= 'user/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->userinfo->update();
        $this->session->set_flashdata('update_success',config_item('update_success'));
        redirect('employee/user/index/'.$this->session->userdata('user_offset'),301);
    }
    
}

/* End of file user.php */
/* Location: ./module/employee/user.php */
