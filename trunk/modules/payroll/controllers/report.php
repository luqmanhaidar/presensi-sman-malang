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
        $this->load->model('excelModel'); //load model excelModel
        $this->load->module_model('presensi','holidays'); //load model presensi form holiday
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
			$group = '100';
			$start = date('m/d/y');
			$end   = date('m/d/y');
        endif;                 
        $data['holidays']=   COUNT($this->holidays->getAllRecords('','','',$start,$end,'',''));   
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
        $day   = $this->input->post('day');
        $month = $this->input->post('month');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('payroll/report/eat/'.$this->session->userdata('eat_offset'),301);
        endif; 
        
        $day   = $this->input->post('day2');
        $month = $this->input->post('month2');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('payroll/report/eat/'.$this->session->userdata('eat_offset'),301);
        endif;     
        
        $search = array ('eat_group'  => $this->input->post('group'),
                         'eat_holiday'=> $this->input->post('holiday'),
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
		$data['title']  =  'DAFTAR OPERASIONAL KEHADIRAN DAN PENAMBAHAN GIZI';
        $data['holidays']=   COUNT($this->holidays->getAllRecords('','','',$this->session->userdata('eat_start'),$this->session->userdata('eat_finish'),'',''));  
        $data['group']  =  $this->usergroup->getPositionData($this->session->userdata('eat_group')); 
		$data['eat']	=  $this->presensi->getVariabelDataByVar('UMK');
        $data['trp']	=  $this->presensi->getVariabelDataByVar('UTR');
		$data['checks'] =  $this->authlog->getPerMonthRecords('','',$this->session->userdata('eat_start'),$this->session->userdata('eat_finish'),$this->session->userdata('eat_group'));
        $this->load->vars($data);
        $this->load->theme('report/payroll-1',$data);
	}
	
	function eat_pdf()
    {
		$this->load->library('pdf');
		$data['title']  =  'DAFTAR OPERASIONAL KEHADIRAN DAN PENAMBAHAN GIZI ';
        $data['holidays']=   COUNT($this->holidays->getAllRecords('','','',$this->session->userdata('eat_start'),$this->session->userdata('eat_finish'),'',''));  
        $data['group']  =  $this->usergroup->getPositionData($this->session->userdata('eat_group')); 
		$data['eat']	=  $this->presensi->getVariabelDataByVar('UMK');
        $data['trp']	=  $this->presensi->getVariabelDataByVar('UTR');
		$data['checks'] =  $this->authlog->getPerMonthRecords('','',$this->session->userdata('eat_start'),$this->session->userdata('eat_finish'),$this->session->userdata('eat_group'));
        $this->load->vars($data);
        $file=$this->load->theme('report/payroll-1',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
	}
    
    function eat_excel()
    {
        $start = $this->session->userdata('eat_start'); 
        $end   = $this->session->userdata('eat_finish');
        $id    = $this->session->userdata('eat_group'); 
        $holidays=   COUNT($this->holidays->getAllRecords('','','',$start,$end,'',''));  
        $group =  $this->usergroup->getPositionData($id);
        $recs  = $this->authlog->getPerMonthRecords('','',$start,$end,$id);
        $eat   =  $this->presensi->getVariabelDataByVar('UMK');
        $trp   =  $this->presensi->getVariabelDataByVar('UTR');
		$excel = $this->excelModel->eat_excel($recs,$trp,$eat,$group,$holidays);
        $data = file_get_contents("assets/Lap-Gaji-Format-1.xlsx"); // Read the file's contents
        force_download("Lap-Gaji-Format-1",$data); 
	}
	
	function transport($offset=0){
        $data['title']  = 'Laporan Gaji Format 2';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('transport_paging'))
            $paging = $this->session->userdata('transport_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('transport_group')):
			$group = $this->session->userdata('transport_group');
            $start = $this->session->userdata('transport_start');
			$end   = $this->session->userdata('transport_finish');
        else:
			$group = '1000';
			$start = date('m/d/y');
			$end   = date('m/d/y');
        endif;                    
        $this->session->set_userdata('transport_offset',$offset);
        $data['holidays']=   COUNT($this->holidays->getAllRecords('','','',$start,$end,'',''));  
        $data['checks']  = $this->authlog->getPerMonthRecords($offset,$paging,$start,$end,$group);
        $numrows = COUNT($this->authlog->getPerMonthRecords('','',$start,$end,$group)); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('payroll/report/transport/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;
		$data['var']	= $this->presensi->getVariabelDataByVar('UTR');
        $data['page']	= 'report/vtransport';
		$this->load->theme('default',$data);
    }
    
    function transport_paging($per_page)
    {
        $this->session->set_userdata('transport_paging',$per_page);
        redirect('payroll/report/transport');
    }
    
    function transport_search()
    {
        $day   = $this->input->post('day');
        $month = $this->input->post('month');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('payroll/report/transport/'.$this->session->userdata('transport_offset'),301);
        endif; 
        
        $day   = $this->input->post('day2');
        $month = $this->input->post('month2');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('payroll/report/transport/'.$this->session->userdata('transport_offset'),301);
        endif;     
        
        $search = array ('transport_group'  => $this->input->post('group'),
						 'transport_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'transport_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));  
        $this->session->set_userdata($search);
        redirect('payroll/report/transport',301);
    }
	
	function transport_preview(){
		$export = $this->input->post('export');
            
		switch($export):
			case 0 : $this->transport_print();
					 break;
			case 1 : $this->transport_pdf();
			         break;
			case 2 : $this->transport_excel();
			         break;
		endswitch;
	}
	
	function transport_print()
    {
		$data['title']  =  'Laporan Gaji Format 2';
		$data['eat']	=  $this->presensi->getVariabelDataByVar('UMK');
        $data['trp']	=  $this->presensi->getVariabelDataByVar('UTR');
        //$data['var']	=  $this->presensi->getVariabelDataByVar('UTR');
		$data['group']  =  $this->usergroup->getPositionData($this->session->userdata('transport_group')); 
        $data['holidays']=   COUNT($this->holidays->getAllRecords('','','',$this->session->userdata('transport_start'),$this->session->userdata('transport_finish'),'',''));  
        $data['checks'] =  $this->authlog->getPerMonthRecords('','',$this->session->userdata('transport_start'),$this->session->userdata('transport_finish'),$this->session->userdata('transport_group'));
        $this->load->vars($data);
        $this->load->theme('report/payroll-2',$data);
	}
    
    function transport_pdf()
    {
		$this->load->library('pdf');
		$data['title']  =  'Laporan Gaji Format 2';
		$data['eat']	=  $this->presensi->getVariabelDataByVar('UMK');
        $data['trp']	=  $this->presensi->getVariabelDataByVar('UTR');
        //$data['var']	=  $this->presensi->getVariabelDataByVar('UTR');
        $data['group']  =  $this->usergroup->getPositionData($this->session->userdata('transport_group')); 
        $data['holidays']=   COUNT($this->holidays->getAllRecords('','','',$this->session->userdata('transport_start'),$this->session->userdata('transport_finish'),'',''));  
		$data['checks'] =  $this->authlog->getPerMonthRecords('','',$this->session->userdata('transport_start'),$this->session->userdata('transport_finish'),$this->session->userdata('transport_group'));
        $this->load->vars($data);
        $file=$this->load->theme('report/payroll-2',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
	}
    
    function transport_excel()
    {
        $start = $this->session->userdata('transport_start'); 
        $end   = $this->session->userdata('transport_finish');
        $group = $this->session->userdata('transport_group'); 
        $holidays= COUNT($this->holidays->getAllRecords('','','',$this->session->userdata('transport_start'),$this->session->userdata('transport_finish'),'',''));  
        $recs  = $this->authlog->getPerMonthRecords('','',$start,$end,$group);
        $var   = $this->presensi->getVariabelDataByVar('UTR');   
		$excel = $this->excelModel->transport_excel($recs,$var,$holidays);
        $data = file_get_contents("assets/Lap-Gaji-Format-2.xlsx"); // Read the file's contents
        force_download("Lap-Gaji-Format-2",$data); 
	}
    
}