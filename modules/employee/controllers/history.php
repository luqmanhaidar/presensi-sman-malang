<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table User SQL Server 2005 Table ngac_userinfo **/

class History extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user 
        $this->load->model('userinfo'); //load model userinfo form user   
        $this->load->model('usergroup'); //load model usergroup form user 
    }
    
    
	function index($offset=0)
	{
        $data['title']  = 'History';
        $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('logHistory_paging'))
            $paging = $this->session->userdata('admin_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('logHistory_offset',$offset);
        $data['groups']	= $this->usergroup->getDataFromGroup();
        $data['users']  = $this->log->getAllRecords($offset,$paging,$this->session->userdata('logHistory_search'));
        $numrows = COUNT($this->log->getAllRecords('','',$this->session->userdata('logHistory_search'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('employee/history/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'log/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('logHistory_paging',$per_page);
        redirect('employee/admin/index');
    }
    
    function search()
    {
        $search = array (   'logHistory_search' =>  $this->input->post('table_search'));    
        $this->session->set_userdata($search);
        redirect('employee/admin/index');
    }
    
    
}

/* End of file user.php */
/* Location: ./module/employee/user.php */
