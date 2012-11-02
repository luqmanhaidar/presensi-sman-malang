<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Payroll Menu Press System **/

class Report extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->module_model('presensi','authlog'); //load model authlog form presensi
        $this->load->module_model('presensi','presensi'); //load model presensi form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
        $this->load->module_model('employee','usergroup'); //load model usergroup form user
		//$this->load->model('excelModel'); //load model authlog form presensi   
        $this->load->library('excel');
        $this->load->helper('download');
        $this->load->helper('date');    
	}
    
    function eat($offset=0){
        $data['title']  = 'Laporan Gaji Format 1';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('eat_paging'))
            $paging = $this->session->userdata('eat_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('eat_group')):
			$group = $this->session->userdata('eat_group');
            $start = $this->session->userdata('eat_start');
			$end   = $this->session->userdata('eat_finish');
        else:
			$group = '';
			$start = '';
			$end   = '';
        endif;                    
        $this->session->set_userdata('eat_offset',$offset);
        $data['checks']  = $this->authlog->getPerMonthRecords($offset,$paging,$start,$end,$group);
        $numrows = COUNT($this->authlog->getPerMonthRecords('','',$start,$end,$group)); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('payroll/report/eat/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;
		$data['var']	= $this->presensi->getVariabelDataByVar('UMK');
        $data['page']	= 'report/veat';
		$this->load->theme('default',$data);
    }
    
    function eat_paging($per_page)
    {
        $this->session->set_userdata('eat_paging',$per_page);
        redirect('payroll/report/eat');
    }
    
    function eat_search()
    {
        $search = array ('eat_group'  => $this->input->post('group'),
						 'eat_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'eat_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));  
        $this->session->set_userdata($search);
        redirect('payroll/report/eat',301);
    }
	
	function eat_preview(){
		$export = $this->input->post('export');
            
		switch($export):
			case 0 : $this->eat_print();
					 break;
			case 1 : $this->eat_pdf();
			         break;
			case 2 : $this->eat_excel();
			         break;
		endswitch;
	}
	
	function eat_print()
    {
		$data['title']		=	'Laporan Gaji Format 1';
        $data['name']       =   $this->session->userdata('personal_name');
        $data['checks']		=	$this->authlog->getAllRecords('','',$this->session->userdata('personal_search'),$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'));
		$data['user']		=	$this->session->userdata('personal_search');
        $this->load->vars($data);
        $this->load->theme('report/payroll-1',$data);
	}
	
	function eat_pdf()
    {
		$this->load->library('pdf');
		$data['title']		=	'Laporan Gaji Format-1 Bulan '.indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search');
        $data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_search'),$this->session->userdata('year_search'),$this->session->userdata('month_group'));
		$this->load->vars($data);
        $file = $this->load->theme('report/month',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
	}
    
}