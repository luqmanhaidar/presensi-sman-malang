<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Dashboard Menu Press System **/

class Report extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('authlog'); //load model authlog form presensi
        $this->load->model('presensi'); //load model presensi form presensi   
        $this->load->model('authprocess'); //load model authprocess form presensi   
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
        $this->load->module_model('employee','usergroup'); //load model usergroup form user
		$this->load->model('excelModel'); //load model authlog form presensi   
        $this->load->library('excel');
        $this->load->helper('download');
        $this->load->helper('date');    
	}
    
    function eat($offset=0){
        $data['title']  = 'Laporan Gaji Format 1';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('month_paging'))
            $paging = $this->session->userdata('month_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('month_search')):
			$group = $this->session->userdata('month_group');
            $month = $this->session->userdata('month_search');
            $year  = $this->session->userdata('year_search');
        else:
			$group = 100;
            $month = '09';   
            $year  = '2012';
        endif;           
        $this->session->set_userdata('month_offset',$offset);
        $data['checks']  = $this->authlog->getPerMonthRecords($offset,$paging,$month,$year,$group);
        $numrows = COUNT($this->authlog->getPerMonthRecords('','',$month,$year,$group)); 
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
    
    function eat_paging($per_page)
    {
        $this->session->set_userdata('month_paging',$per_page);
        redirect('presensi/report/monthly');
    }
    
    function eat_search()
    {
        $search = array ('month_search' => $this->input->post('month'),
						 'month_group'  => $this->input->post('group'),
                         'year_search'  => $this->input->post('year'));    
        $this->session->set_userdata($search);
        redirect('presensi/report/monthly',301);
    }
	
	function eat_preview(){
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
	
	function eat_print()
    {
		$data['title']		=	'Laporan Presensi Bulan  '.indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search');
		$data['position']	=	$this->usergroup->getPositionData($this->session->userdata('month_group'));
		$data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_search'),$this->session->userdata('year_search'),$this->session->userdata('month_group'));
        $this->load->vars($data);
        $this->load->theme('report/month',$data);
	}
	
	function eat_pdf()
    {
		$this->load->library('pdf');
		$data['title']		=	'Laporan Presensi Bulan '.indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search');
        $data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_search'),$this->session->userdata('year_search'),$this->session->userdata('month_group'));
		$this->load->vars($data);
        $file = $this->load->theme('report/month',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
	}
    
}