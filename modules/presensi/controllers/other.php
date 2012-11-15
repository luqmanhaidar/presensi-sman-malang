<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table Other SQL Server 2005 Table ngac_other **/

class Other extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('user')):
            is_message_loginErr();
            redirect('auth/user/index',301);
        endif;
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('others'); //load model other form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
    }
    
    function index($offset=0)
	{
        $data['title']  = 'Ijin Kerja';
         $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('other_paging'))
            $paging = $this->session->userdata('other_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('other_offset',$offset);
        $data['others']  = $this->others->getAllRecords($offset,$paging,$this->session->userdata('other_search'),$this->session->userdata('other_type'),$this->session->userdata('other_start'),$this->session->userdata('other_finish'));
        $numrows = COUNT($this->others->getAllRecords('','',$this->session->userdata('other_search'),$this->session->userdata('other_type'),$this->session->userdata('other_start'),$this->session->userdata('other_finish'))); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/other/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'other/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('other_paging',$per_page);
        redirect('presensi/other/index');
    }
    
    function search()
    {
        $search = array ('other_search'=> $this->input->post('table_search'),
                         'other_type'=> $this->input->post('table_type'),
                         'other_start' => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'other_finish'=> $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
        $this->session->set_userdata($search);
        redirect('presensi/other/index',301);
    }
    
    function add(){
        $data['title']  = 'Ijin Kerja';
        $data['action'] = 'presensi/other/save';
        $data['value']  = '';
        $data['logs']   =   $this->log->userLog();
        $data['users']	= $this->userinfo->getDataFromUser();
        $data['page']	= 'other/vform';
		$this->load->theme('default',$data);
    }
    
    public function save()
    {
        /*$d2=GregorianToJD($this->input->post('month2'),$this->input->post('day2'),$this->input->post('year2'));
        $d1=GregorianToJD($this->input->post('month'),$this->input->post('day'),$this->input->post('year'));
        $d = $d2 - $d1;
        
        if($d < 0 ):
            $this->session->set_flashdata('message',config_item('date_error'));
            redirect('presensi/other/index/'.$this->session->userdata('other_offset'),301);
		endif;*/
        
        $user = $this->input->post('user');
        $date = $this->input->post('day').'-'.$this->input->post('month').'-'.$this->input->post('year');
        
        if($this->others->getCountOther($user,$date) > 0 ):
            $this->session->set_flashdata('message',config_item('save_error'));
            redirect('presensi/other/index/'.$this->session->userdata('other_offset'),301);
		endif;
        
        /**echo GregorianToJD($this->input->post('month'),$this->input->post('day'),$this->input->post('year'));
        echo "<br/>";
        echo GregorianToJD($this->input->post('month2'),$this->input->post('day2'),$this->input->post('year2'));     
        $date = $this->input->post('year').'-'.$this->input->post('month').'-'.$this->input->post('day');
        $d = $d + 1;**/
        $this->others->save();
        $this->session->set_flashdata('message',config_item('save_success'));
        redirect('presensi/other/index/'.$this->session->userdata('other_offset'),301);
    }
    
    function edit($id){
        $data['title']  = 'Ijin Kerja';
        $data['action'] = 'presensi/other/update';
        $data['logs']   =   $this->log->userLog();
        $data['value']  = $this->others->getOtherData($id);
        $data['users']	= $this->userinfo->getDataFromUser();
        $data['page']	= 'other/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->others->update();
        $this->session->set_flashdata('message',config_item('update_success'));
        redirect('presensi/other/index/'.$this->session->userdata('other_offset'),301);
    }
    
    public function remove($id)
    {
        $this->others->remove($id);
		$this->session->set_flashdata('message',config_item('success_remove'));
		redirect('presensi/other/index/'.$this->session->userdata('other_offset'),301);
    }
    
}    
    