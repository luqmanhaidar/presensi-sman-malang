<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Dashboard Menu Press System **/

class Report extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('authlog'); //load model authlog form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
        $this->load->model('excelModel'); //load model authlog form presensi   
        $this->load->library('excel');
        $this->load->helper('download');
        $this->load->helper('date');
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
        $data['users']	= $this->userinfo->getDataFromUser();
        if($this->session->userdata('personal_paging'))
            $paging = $this->session->userdata('personal_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('personal_search'))
            $search = $this->session->userdata('personal_search');
        else
            $search = 'xxxx';          
        $this->session->set_userdata('personal_offset',$offset);
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
    
    function personal_paging($per_page)
    {
        $this->session->set_userdata('personal_paging',$per_page);
        redirect('presensi/report/personal');
    }
    
    function personal_search()
    {
        $search = array ('personal_search' => $this->input->post('user'),
                         'personal_key'    => $this->input->post('key'),
                         'personal_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'personal_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
        $this->session->set_userdata($search);
        redirect('presensi/report/personal',301);
    }
	
	function personal_preview(){
		$export = $this->input->post('export');
        $row = $this->userinfo->getUserData($this->session->userdata('personal_search'));
        if(COUNT($row)>0)
            $this->session->set_userdata('personal_name',$row['Name']);
        else
            $this->session->set_userdata('personal_name','-');
            
		switch($export):
			case 0 : $this->personal_print();
					 break;
			case 1 : $this->personal_pdf();
			         break;
			case 2 : $this->personal_excel();
			         break;
		endswitch;
	}
	
	function personal_print()
    {
		$data['title']		=	'Laporan Individu';
        $data['name']       =   $this->session->userdata('personal_name');
        $data['checks']		=	$this->authlog->getAllRecords('','',$this->session->userdata('personal_search'),$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'));
		$data['user']		=	$this->session->userdata('personal_search');
        $this->load->vars($data);
        $this->load->theme('report/personal',$data);
	}
	
	function personal_pdf()
    {
		$this->load->library('pdf');
		$data['title']		=	'laporan Individu';
        $data['name']       =   $this->session->userdata('personal_name');
		$data['checks']		=	$this->authlog->getAllRecords('','',$this->session->userdata('personal_search'),$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'));
		$data['user']		=	$this->session->userdata('personal_search');
        $this->load->vars($data);
        $file = $this->load->theme('report/personal',$data,TRUE);
		$this->pdf->pdf_create($file,'Laporan Individu');
	}
    
    function personal_excel()
    {
        $user = $this->session->userdata('personal_search'); 
        $name = $this->session->userdata('personal_name');    
		$excel = $this->excelModel->personal_excel($user,$name);
        $data = file_get_contents("assets/Personal.xlsx"); // Read the file's contents
        force_download("Laporan-Individu",$data); 
	}
    
    function monthly($offset=0){
        $data['title']  = 'Laporan Bulanan';
        $data['logs']   =   $this->log->userLog();
        $data['users']	= $this->userinfo->getDataFromUser();
        if($this->session->userdata('month_paging'))
            $paging = $this->session->userdata('month_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('month_search')):
            $month = $this->session->userdata('month_search');
            $year  = $this->session->userdata('year_search');
        else:
            $month = '00';   
            $year  = '2020';
        endif;           
        $this->session->set_userdata('month_offset',$offset);
        $data['checks']  = $this->authlog->getPerMonthRecords($offset,$paging,$month,$year);
        $numrows = COUNT($this->authlog->getPerMonthRecords('','',$month,$year)); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/report/monthly/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'report/vmonth';
		$this->load->theme('default',$data);
    }
    
    function month_paging($per_page)
    {
        $this->session->set_userdata('month_paging',$per_page);
        redirect('presensi/report/monthly');
    }
    
    function month_search()
    {
        $search = array ('month_search' => $this->input->post('month'),
                         'year_search'  => $this->input->post('year'));    
        $this->session->set_userdata($search);
        redirect('presensi/report/monthly',301);
    }
	
	function month_preview(){
		$export = $this->input->post('export');
            
		switch($export):
			case 0 : $this->month_print();
					 break;
			case 1 : $this->month_pdf();
			         break;
			case 2 : $this->month_excel();
			         break;
		endswitch;
	}
	
	function month_print()
    {
		$data['title']		=	'Laporan Presensi Bulan  '.indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search');
		$data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_search'),$this->session->userdata('year_search'));
        $this->load->vars($data);
        $this->load->theme('report/month',$data);
	}
	
	function month_pdf()
    {
		$this->load->library('pdf');
		$data['title']		=	'Laporan Presensi Bulan '.indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search');
        $data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_search'),$this->session->userdata('year_search'));
		$this->load->vars($data);
        $file = $this->load->theme('report/month',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
	}
}