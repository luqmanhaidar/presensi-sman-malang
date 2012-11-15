<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table Overtime SQL Server 2005 Table ngac_overtime **/

class Overtime extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('user')):
            is_message_loginErr();
            redirect('auth/user/index',301);
        endif;
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('overtimes'); //load model overtimes form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
    }
    
    function index($offset=0)
	{
        $data['title']  = 'Daftar Lembur';
        $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('overtime_paging'))
            $paging = $this->session->userdata('overtime_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('overtime_offset',$offset);
        $data['overtime']  = $this->overtimes->getAllRecords($offset,$paging,$this->session->userdata('overtime_search'),$this->session->userdata('overtime_start'),$this->session->userdata('overtime_finish'));
        $numrows = COUNT($this->overtimes->getAllRecords('','',$this->session->userdata('overtime_search'),$this->session->userdata('overtime_start'),$this->session->userdata('overtime_finish'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/overtime/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'overtime/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('overtime_paging',$per_page);
        redirect('presensi/overtime/index');
    }
    
    function search()
    {
        $search = array ('overtime_search'=> $this->input->post('table_search'),
                         'overtime_start' => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'overtime_finish'=> $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
        $this->session->set_userdata($search);
        redirect('presensi/overtime/index',301);
    }
    
    function add(){
        $data['title']  = 'Daftar Lembur';
        $data['action'] = 'presensi/overtime/save';
        $data['value']  = '';
        $data['logs']   =   $this->log->userLog();
        $data['users']	= $this->userinfo->getDataFromUser();
        $data['page']	= 'overtime/vform';
		$this->load->theme('default',$data);
    }
    
    public function save()
    {
        $user = $this->input->post('user');
        $date = $this->input->post('day').'-'.$this->input->post('month').'-'.$this->input->post('year');
        
        if($this->overtimes->getCountOvertime($user,$date) > 0 ):
            $this->session->set_flashdata('message',config_item('save_error'));
            redirect('presensi/overtime/index/'.$this->session->userdata('overtime_offset'),301);
		endif;
            
        $this->overtimes->save();
        $this->session->set_flashdata('message',config_item('save_success'));
        redirect('presensi/overtime/index/'.$this->session->userdata('overtime_offset'),301);
    }
    
    function edit($id){
        $data['title']  = 'Daftar Lembur';
        $data['action'] = 'presensi/overtime/update';
        $data['logs']   =   $this->log->userLog();
        $data['value']  = $this->overtimes->getOvertimeData($id);
        $data['users']	= $this->userinfo->getDataFromUser();
        $data['page']	= 'overtime/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->overtimes->update();
        $this->session->set_flashdata('message',config_item('update_success'));
        redirect('presensi/overtime/index/'.$this->session->userdata('overtime_offset'),301);
    }
    
    public function remove($id)
    {
        $this->overtimes->remove($id);
		$this->session->set_flashdata('message',config_item('success_remove'));
		redirect('presensi/overtime/index/'.$this->session->userdata('overtime_offset'),301);
    }
    
}    
    