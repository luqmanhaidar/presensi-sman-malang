<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table UserGroup Duration SQL Server 2005 Table ngac_userinfo **/

class Group extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('userinfo'); //load model userinfo form user   
        $this->load->model('usergroup'); //load model usergroup form user 
    }
    
    function index($offset=0)
	{
        $data['title']  = 'Duration Group';
        if($this->session->userdata('group_paging'))
            $paging = $this->session->userdata('group_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('group_offset',$offset);
        $data['groups']  = $this->usergroup->getAllRecords($offset,$paging,$this->session->userdata('group_search'));
        $numrows = COUNT($this->usergroup->getAllRecords('','',$this->session->userdata('group_search'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('employee/group/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'group/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('group_paging',$per_page);
        redirect('employee/group/index');
    }
    
    function search()
    {
        $search = array ('group_search' =>  $this->input->post('table_search'));    
        $this->session->set_userdata($search);
        redirect('employee/group/index',301);
    }
    
    function add(){
        $data['title']  = 'Add Durasi Group';
        $data['action'] = 'employee/group/save';
        $data['value']  = '';
        $data['groups']	= $this->usergroup->getDataFromGroup();
        $data['page']	= 'group/vform';
		$this->load->theme('default',$data);
    }
    
    public function save()
    {
        $this->usergroup->save();
        $this->session->set_flashdata('save_success',config_item('save_success'));
        redirect('employee/group/index/'.$this->session->userdata('group_offset'),301);
    }
    
    function edit($id){
        $data['title']  = 'Update Durasi Group';
        $data['action'] = 'employee/group/update';
        $data['value']  = $this->usergroup->getGroupData($id);
        $data['groups']	= $this->usergroup->getDataFromGroup();
        $data['page']	= 'group/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->usergroup->update();
        $this->session->set_flashdata('message',config_item('update_success'));
        redirect('employee/group/index/'.$this->session->userdata('group_offset'),301);
    }
    
    public function remove($id)
    {
		if($this->usergroup->getCountGroup($id) > 0 ):
            $this->session->set_flashdata('message',config_item('error_remove'));
            redirect('employee/group/index/'.$this->session->userdata('group_offset'),301);
		endif;
		
        $this->usergroup->remove($id);
		$this->session->set_flashdata('message',config_item('success_remove'));
		redirect('employee/group/index/'.$this->session->userdata('group_offset'),301);
    }
    
}    
    