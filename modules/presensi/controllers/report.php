<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Dashboard Menu Press System **/

class Report extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('authlog'); //load model authlog form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
        if(!$this->session->userdata('user')):
            //is_message_loginErr();
            //redirect('auth/user/index',301);
        endif;    
	}
    
    public function index(){
		$data['title']		=	'Laporan';
        $data['logs']       =   $this->log->userLog();
        $data['page']	    =	'report/vreport';
        $this->load->vars($data);
        $this->load->theme('default',$data);
	}
    
    function personal($offset=0){
        $data['title']  = 'Laporan Individual';
        $data['logs']   =   $this->log->userLog();
        $data['users']	= $this->userinfo->getSingleDataFromUser();
        if($this->session->userdata('personal_paging'))
            $paging = $this->session->userdata('personal_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('personal_search'))
            $search = $this->session->userdata('personal_search');
        else
            $search = '55555';          
        $this->session->set_userdata('log_offset',$offset);
        $data['checks']  = $this->authlog->getAllRecords($offset,$paging,$search,$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'));
        $numrows = COUNT($this->authlog->getAllRecords('','',$search,$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/report/personal/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'report/vpersonal';
		$this->load->theme('default',$data);
    }
    
    function paging($per_page)
    {
        $this->session->set_userdata('personal_paging',$per_page);
        redirect('presensi/report/personal');
    }
    
    function search()
    {
        $search = array ('personal_search' => $this->input->post('user'),
                         'personal_key'    => $this->input->post('key'),
                         'personal_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'personal_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
        $this->session->set_userdata($search);
        redirect('presensi/report/personal',301);
    }
    
}