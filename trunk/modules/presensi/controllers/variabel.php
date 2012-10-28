<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table Variabe Duration SQL Server 2005 Table ngac_variabel **/

class Variabel extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user 
        //$this->load->model('userinfo'); //load model userinfo form user   
        $this->load->model('presensi'); //load model usergroup form user 
    }
    
    function index($offset=0)
	{
        $data['title']  = 'Variabel';
        $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('variabel_paging'))
            $paging = $this->session->userdata('variabel_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('variabel_offset',$offset);
        $data['variabel']  = $this->presensi->getAllRecords($offset,$paging,$this->session->userdata('variabel_search'));
        $numrows = COUNT($this->presensi->getAllRecords('','',$this->session->userdata('variabel_search'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/variabel/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'variabel/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('variabel_paging',$per_page);
        redirect('presensi/variabel/index',301);
    }
    
    function search()
    {
        $search = array ('variabel_search' =>  $this->input->post('table_search'));    
        $this->session->set_userdata($search);
        redirect('presensi/variabel/index',301);
    }
    
    function add(){
        $data['title']  = 'Variabel';
        $data['action'] = 'presensi/variabel/save';
        $data['value']  = '';
        $data['logs']   =   $this->log->userLog();
        $data['page']	= 'variabel/vform';
		$this->load->theme('default',$data);
    }
    
    public function save()
    {
        $this->presensi->save();
        $this->session->set_flashdata('save_success',config_item('save_success'));
        redirect('presensi/variabel/index/'.$this->session->userdata('variabel_offset'),301);
    }
    
    function edit($id){
        $data['title']  = 'Variabel';
        $data['action'] = 'presensi/variabel/update';
        $data['logs']   = $this->log->userLog();
        $data['value']  = $this->presensi->getVariabelData($id);
        $data['page']	= 'variabel/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->presensi->update();
        $this->session->set_flashdata('message',config_item('update_success'));
        redirect('presensi/variabel/index/'.$this->session->userdata('variabel_offset'),301);
    }
    
    public function remove($id)
    {
		/*if($this->presensi->getCountVariabel($id) > 0 ):
            $this->session->set_flashdata('message',config_item('error_remove'));
            redirect('presensi/group/index/'.$this->session->userdata('group_offset'),301);
		endif;*/
		
        $this->presensi->remove($id);
		$this->session->set_flashdata('message',config_item('success_remove'));
		redirect('presensi/variabel/index/'.$this->session->userdata('variabel_offset'),301);
    }
    
}    
    