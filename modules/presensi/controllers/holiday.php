<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table Holiday Duration SQL Server 2005 Table ngac_holiday **/

class Holiday extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user 
        $this->load->model('holidays'); //load model usergroup form user 
    }
    
    function index($offset=0)
	{
        $data['title']  = 'Libur Nasional & Cuti';
        $data['logs']   =   $this->log->userLog();
        if($this->session->userdata('holiday_paging'))
            $paging = $this->session->userdata('holiday_paging');
        else
            $paging = config_item('paging');
        $this->session->set_userdata('holiday_offset',$offset);
        
        if($this->session->userdata('holiday_start')):
			$type  = $this->session->userdata('holiday_type');
            $start = $this->session->userdata('holiday_start');
			$end   = $this->session->userdata('holiday_finish');
        else:
			$type  = '';
            $start = date('m/'.'01'.'/Y');
			$end   = date('m/'.days_in_month(date('m')).'/Y');
        endif; 
        
        $data['holiday']  = $this->holidays->getAllRecords($offset,$paging,$type,$start,$end);
        $numrows = COUNT($this->holidays->getAllRecords($type,$start,$end)); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/holiday/index/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'holiday/vindex';
		$this->load->theme('default',$data);
	}
    
    function paging($per_page)
    {
        $this->session->set_userdata('variabel_paging',$per_page);
        redirect('presensi/variabel/index',301);
    }
    
    function search()
    {
        $day   = $this->input->post('day');
        $month = $this->input->post('month');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('presensi/holiday/index/'.$this->session->userdata('holiday_offset'),301);
        endif; 
        
        $day   = $this->input->post('day2');
        $month = $this->input->post('month2');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('presensi/holiday/index/'.$this->session->userdata('holiday_offset'),301);
        endif;     
        
        $search = array ('holiday_type'   => $this->input->post('type'),   
						 'holiday_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'holiday_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));       
        $this->session->set_userdata($search);
        redirect('presensi/holiday/index',301);
    }
    
    function add(){
        $data['title']  = 'Holiday';
        $data['action'] = 'presensi/holiday/save';
        $data['value']  = '';
        $data['logs']   = $this->log->userLog();
        $data['page']	= 'holiday/vform';
		$this->load->theme('default',$data);
    }
    
    public function save()
    {
        $date = $this->input->post('day').'-'.$this->input->post('month').'-'.$this->input->post('year'); 
        if($this->holidays->getHolidayDate($date)>0):
            $this->session->set_flashdata('message',config_item('save_error'));
            redirect('presensi/holiday/index/'.$this->session->userdata('holiday_offset'),301);
        endif;
        $this->holidays->save();
        $this->session->set_flashdata('message',config_item('save_success'));
        redirect('presensi/holiday/index/'.$this->session->userdata('holiday_offset'),301);
    }
    
    function edit($id){
        $data['title']  = 'Holiday';
        $data['action'] = 'presensi/holiday/update';
        $data['logs']   = $this->log->userLog();
        $data['value']  = $this->holidays->getHolidayData($id);
        $data['page']	= 'holiday/vform';
		$this->load->theme('default',$data);
    }
    
    public function update()
    {
        $this->holidays->update();
        $this->session->set_flashdata('message',config_item('update_success'));
        redirect('presensi/holiday/index/'.$this->session->userdata('holiday_offset'),301);
    }
    
    public function remove($id)
    {
        $this->holidays->remove($id);
		$this->session->set_flashdata('message',config_item('success_remove'));
		redirect('presensi/holiday/index/'.$this->session->userdata('holiday_offset'),301);
    }
    
}    