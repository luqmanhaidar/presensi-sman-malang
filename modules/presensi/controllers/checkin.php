<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table Authlog SQL Server 2005 Table ngac_authlog **/

class Checkin extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('user')):
            is_message_loginErr();
            redirect('auth/user/index',301);
        endif;
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('authlog'); //load model authlog form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
    }
    
    function index($offset=0)
	{
        $data['title']  = 'Manual Checkin';
        $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('log_paging'))
            $paging = $this->session->userdata('log_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('log_offset',$offset);
        $data['checks']  = $this->authlog->getAllRecords($offset,$paging,$this->session->userdata('log_search'),$this->session->userdata('log_key'),$this->session->userdata('date_start'),$this->session->userdata('date_finish'));
        $numrows = COUNT($this->authlog->getAllRecords('','',$this->session->userdata('log_search'),$this->session->userdata('log_key'),$this->session->userdata('date_start'),$this->session->userdata('date_finish'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/checkin/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'checkin/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('log_paging',$per_page);
        redirect('presensi/checkin/index');
    }
    
    function search()
    {
        $search = array ('log_search' => $this->input->post('table_search'),
                         'log_key'    => $this->input->post('key'),
                         'date_start' => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'date_finish'=> $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
        $this->session->set_userdata($search);
        redirect('presensi/checkin/index',301);
    }
    
    function add($index=''){
        $data['title']  = 'Manual Checkin';
        $data['action'] = 'presensi/checkin/save';
		if(empty($index)):
			$data['value']  = '';
			$this->session->set_userdata('no_checkin','');
		else:	
			$data['value']  = $this->authlog->getPresentData($index);
			$this->session->set_userdata('no_checkin',$index);
		endif;	
		$data['logs']   =   $this->log->userLog();
        $data['users']	= $this->userinfo->getDataFromUser();
        $data['page']	= 'checkin/vform';
		$this->load->theme('default',$data);
    }
    
    public function save()
    {
        $user = $this->input->post('user');
        $key  = $this->input->post('key');
        $date = $this->input->post('day').'-'.$this->input->post('month').'-'.$this->input->post('year');
        if($this->authlog->getCountLog($user,$key,$date) > 0 ):
            $this->session->set_flashdata('message',config_item('save_error'));
            redirect('presensi/checkin/index/'.$this->session->userdata('group_offset'),301);
		endif;
            
        $this->authlog->save();
        $this->session->set_flashdata('message',config_item('save_success'));
		if(!empty($this->session->userdata('no_checkin'))):
			$this->authlog->removePresent($this->session->userdata('no_checkin'));
			redirect('presensi/report/present/'.$this->session->userdata('present_offset'),301);
		else
			redirect('presensi/checkin/index/'.$this->session->userdata('log_offset'),301);
		endif;	
			
        
    }
    
    function edit($id){
        $data['title']  = 'Manual Checkin';
        $data['action'] = 'presensi/checkin/update';
        $data['logs']   =   $this->log->userLog();
        $data['value']  = $this->authlog->getAuthlogData($id);
        $data['users']	= $this->userinfo->getDataFromUser();
        $data['page']	= 'checkin/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->authlog->update();
        $this->session->set_flashdata('message',config_item('update_success'));
        redirect('presensi/checkin/index/'.$this->session->userdata('log_offset'),301);
    }
    
    public function remove($id)
    {
		/*if($this->usergroup->getCountGroup($id) > 0 ):
            $this->session->set_flashdata('message',config_item('error_remove'));
            redirect('employee/group/index/'.$this->session->userdata('group_offset'),301);
		endif;*/
        $this->authlog->remove($id);
		$this->session->set_flashdata('message',config_item('success_remove'));
		redirect('presensi/checkin/index/'.$this->session->userdata('log_offset'),301);
    }
    
}    
    