<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Dashboard Menu Press System **/

class Report extends CI_Controller {
    
    public function __construct()
    {
		parent::__construct();
        if(!$this->session->userdata('user')):
            is_message_loginErr();
            redirect('auth/user/index',301);
        endif;
        $this->load->module_model('employee','log'); //load model usergroup form user
        $this->load->model('authlog'); //load model authlog form presensi
        $this->load->model('others'); //load model others form others
        $this->load->model('presensi'); //load model presensi form presensi   
        $this->load->model('holidays'); //load model presensi form holiday
        $this->load->model('authprocess'); //load model authprocess form presensi
		$this->load->model('overtimes'); //load model presensi form presensi  
		$this->load->model('authovertime'); //load model authprocess form presensi  		
        $this->load->module_model('employee','userinfo'); //load model usergroup form user 
        $this->load->module_model('employee','usergroup'); //load model usergroup form user
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
        $day   = $this->input->post('day');
        $month = $this->input->post('month');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('presensi/report/personal/'.$this->session->userdata('personal_offset'),301);
        endif; 
        
        $day   = $this->input->post('day2');
        $month = $this->input->post('month2');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('presensi/report/personal/'.$this->session->userdata('personal_offset'),301);
        endif;     
            
        $search = array ('personal_search' => $this->input->post('user'),
                         'personal_key'    => $this->input->post('key'),
                         'personal_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'personal_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
        $this->session->set_userdata($search);
        redirect('presensi/report/personal/'.$this->session->userdata('personal_offset'),301);
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
    
    function present($offset=0){
        $data['title']  = 'Laporan Ketidakhadiran';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('week_paging'))
            $paging = $this->session->userdata('week_paging');
        else
            $paging = config_item('paging');
		
        if($this->session->userdata('week_group')):
			$group = $this->session->userdata('week_group');
            $start = $this->session->userdata('week_start');
			$end   = $this->session->userdata('week_finish');
        else:
			$group = 100;
            $start = '';
			$end   = '';
        endif; 
        $data['var']	    =	$this->presensi->getVariabelDataByVar('DMK');
        
        if($this->session->userdata('week_type')=='M2'):
            $record  = $this->authprocess->getAllWeekRecord($offset,$paging);
            $num_rec = COUNT($this->authprocess->getAllWeekRecord());
        else:
            $record  = $this->authprocess->getAllRecords($offset,$paging);
            $num_rec = COUNT($this->authprocess->getAllRecords());
        endif;    
		
        $this->session->set_userdata('week_offset',$offset);
        $data['checks']  = $record;
        $numrows = $num_rec; 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/report/weekly/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'report/vpresent';
		$this->load->theme('default',$data);
    }
    
    function monthly($offset=0){
        $data['title']  = 'Laporan Bulanan';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('month_paging'))
            $paging = $this->session->userdata('month_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('month_group')):
			$group = $this->session->userdata('month_group');
            $month = $this->session->userdata('month_month');
			$year  = $this->session->userdata('month_year');
        else:
			$group = '100';
			$month = date('m');
			$year  = date('Y');
        endif;           
        $this->session->set_userdata('month_offset',$offset);
        $data['checks']  = $this->authlog->getMonthRecords($offset,$paging,$month,$year,$group);
        $numrows = COUNT($this->authlog->getMonthRecords('','',$month,$year,$group)); 
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
        $search = array ('month_group'  => $this->input->post('group'),
                         'month_month'  => $this->input->post('month'),
                         'month_year'   => $this->input->post('year')
                        );
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
        $group = $this->session->userdata('month_group');
        $month = $this->session->userdata('month_month');
		$year  = $this->session->userdata('month_year');
		$data['position']	=	$this->usergroup->getPositionData($group);
        $data['holidays']   =   $this->holidays->getAllRecords('','','','','',$month,$year);
		$data['checks']		=	$this->authlog->getMonthRecords('','',$month,$year,$group);
		$data['days']       =   days_in_month($this->session->userdata('month_month'));
        $data['month']      =   $this->session->userdata('month_month');
        $data['year']       =   $this->session->userdata('month_year');  
		$data['title']		=	'DAFTAR HADIR '.$data['position']['Name'];
        $this->load->vars($data);
        $this->load->theme('report/month',$data);
	}
	
    function month_pdf(){
        $this->load->helper('tcpdf');    
        $group = $this->session->userdata('month_group');
        $month = $this->session->userdata('month_month');
		$year  = $this->session->userdata('month_year');
        $data['holidays']   =   $this->holidays->getAllRecords('','','','','',$month,$year);
		$data['position']	=	$this->usergroup->getPositionData($group);
		$data['checks']		=	$this->authlog->getMonthRecords('','',$month,$year,$group);
		$data['days']       =   days_in_month($this->session->userdata('month_month'));
        $data['month']      =   $this->session->userdata('month_month');
        $data['year']       =   $this->session->userdata('month_year');  
		$data['title']		=	'DAFTAR HADIR '.$data['position']['Name'];
        $this->load->vars($data);
        $content=$this->load->theme('report/month',$data,TRUE);
        $html2pdf = html2pdf('L','Letter');
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('Laporan-Bulanan.pdf','D');
        
    }
    
    function month_excel()
    {
        $group = $this->session->userdata('month_group');
        $month = $this->session->userdata('month_month');
		$year  = $this->session->userdata('month_year');
        $pos   = $this->usergroup->getPositionData($group);
        $recs  = $this->authlog->getMonthRecords('','',$month,$year,$group);
        $days  =   days_in_month($month);
		$excel = $this->excelModel->month_excel($recs,$month,$year,$group,$days,$pos,'Tanggal');
        $data = file_get_contents("assets/Lap-Bulanan.xlsx"); // Read the file's contents
        force_download("Lap-Bulanan",$data); 
    }
    
    function weekly($offset=0){
        $data['title']  = 'Laporan Mingguan';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('week_paging'))
            $paging = $this->session->userdata('week_paging');
        else
            $paging = config_item('paging');
		
        if($this->session->userdata('week_group')):
			$group = $this->session->userdata('week_group');
            $start = $this->session->userdata('week_start');
			$end   = $this->session->userdata('week_finish');
        else:
			$group = 100;
            $start = '';
			$end   = '';
        endif; 
        $data['var']	    =	$this->presensi->getVariabelDataByVar('DMK');
        
        if($this->session->userdata('week_type')=='M2'):
            $record  = $this->authprocess->getAllWeekRecord($offset,$paging);
            $num_rec = COUNT($this->authprocess->getAllWeekRecord());
        else:
            $record  = $this->authprocess->getAllRecords($offset,$paging);
            $num_rec = COUNT($this->authprocess->getAllRecords());
        endif;    
		
        $this->session->set_userdata('week_offset',$offset);
        $data['checks']  = $record;
        $numrows = $num_rec; 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/report/weekly/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'report/vweek';
		$this->load->theme('default',$data);
    }
    
    function week_search()
    {
        $day   = $this->input->post('day');
        $month = $this->input->post('month');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('presensi/report/weekly/'.$this->session->userdata('week_offset'),301);
        endif; 
        
        $day   = $this->input->post('day2');
        $month = $this->input->post('month2');
        if(!validateDate($day,$month)):
            $this->session->set_flashdata('message',config_item('range_error'));
            redirect('presensi/report/weekly/'.$this->session->userdata('week_offset'),301);
        endif;     
            
        $search = array ('week_group'  => $this->input->post('group'),
                         'week_type'   => $this->input->post('type'),   
						 'week_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'week_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));   
        $start =  $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year');
		$end   =  $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2');
		$group =  $this->input->post('group');
		
		$this->session->set_userdata($search);
        $this->authprocess->removeAll();
        
        /** Query Insert ke AuthProcess **/
        $query = $this->authlog->getPerWeekRecords($start,$end,$group,1);
        /** masuk kerja senin-kamis & sabtu I**/
        $sk1 = (6 * 3600) + (30*60) + (0);
        $dt1 = (6 * 3600) + (45*60) + (0); // telat 15 menit
        $sd1 = '06:30:00';
        /** masuk kerja senin-kamis II **/
        $sk2 = (7 * 3600) + (0*60) + (0);
        $dt2 = (7 * 3600) + (15*60) + (0); // telat 15 menit
        $sd2 = '07:00:00';
        /** pulang kerja sabtu **/
        $wp1 =  (12 * 3600) + (30*60) + (0);
        $w1  =  '12:30:00';
        /** pulang kerja senin-kamis **/                    
        $wp2 =  (14 * 3600) + (0*60) + (0);
        $w2  =  '14:00:00';
        /** pulang kerja senin-kamis **/                      
        $wp3 =  (14 * 3600) + (30*60) + (0);
        $w3  =  '14:30:00'; 
        /** pulang kerja senin-kamis **/                      
        $wp4 =  (15 * 3600) + (0*60) + (0);
        $w4  =  '15:00:00';
        /** pulang kerja senin-kamis **/                      
        $wp5 =  (15 * 3600) + (30*60) + (0);
        $w5  =  '15:30:00';
                    
        foreach($query as $row):   
            if(COUNT($this->authprocess->getAuthProcessData($row['UserID'],$row['TransactionTime']))== 0):
                $wm = (substr($row['MyTime'],0,2) * 3600) + (substr($row['MyTime'],3,2)*60) + (substr($row['MyTime'],6,2));
                
                if($row['GroupID']>2):
                    $sk  =  $this->usergroup->getGroupWorkData($row['GroupWork']);
                    $jm  =  $this->usergroup->getGroupFridayData($row['GroupFriday']);
                    
                    if(TRIM($row['GroupID'])==8):
                        $a_in = TRIM($this->presensi->getVariabelDataByVar('PA1'));
                        $a_out= TRIM($this->presensi->getVariabelDataByVar('PA2'));
                        $b_in = TRIM($this->presensi->getVariabelDataByVar('PB1'));
                        $b_out= TRIM($this->presensi->getVariabelDataByVar('PB2'));
                        $c_in = TRIM($this->presensi->getVariabelDataByVar('PC1'));
                        $c_out= TRIM($this->presensi->getVariabelDataByVar('PC2'));
                        
                        $a1 =  (substr($a_in,0,2) * 3600) + (substr($a_in,3,2) * 60);
                        $a2 =  (substr($a_out,0,2) * 3600) + (substr($a_out,3,2) * 60);
                        $b1 =  (substr($b_in,0,2) * 3600) + (substr($b_in,3,2) * 60);
                        $b2 =  (substr(($b_out),0,2) * 3600) + (substr(($b_out),3,2) * 60);
                        $c1 =  (substr($c_in,0,2) * 3600) + (substr($c_in,3,2) * 60);
                        $c2 =  (substr(($c_out),0,2) * 3600) + (24 * 3600);
                        
                        //10
                        $a_max = $a1 + (4*3600);
                        //18
                        $b_max = $b1 + (4*3600);
                        
                        //if 12:00 > 18:00
                        if($wm>$b_max):
                            $dbSkStart = $c_in;
                            $dbSpStart = (substr($c_in,0,2) * 3600) + (substr($c_in,3,2)*60);
                            $dbSpWork  = (substr($c_in,0,2) * 3600) + ((substr($c_in,3,2) + 15)*60);   
                            $dbSkEnd   = $c_out;
                            $dbSpEnd   = (substr($c_out,0,2) * 3600) + (substr($c_out,3,2)*60);
                            //$mytime    = $c_in.':00';
                            $mytime = $row['TransactionTime'];
							$wm        = $c1;   
                        //if 12:00 > 10:00
						elseif($wm>$a_max):
                            $dbSkStart = $b_in;
                            $dbSpStart = (substr($b_in,0,2) * 3600) + (substr($b_in,3,2)*60);
                            $dbSpWork  = (substr($b_in,0,2) * 3600) + ((substr($b_in,3,2) + 15)*60);   
                            $dbSkEnd   = $b_out;
                            $dbSpEnd   = (substr($b_out,0,2) * 3600) + (substr($b_out,3,2)*60);
                            //$mytime    = $b_in.':00';
                            $mytime = $row['TransactionTime'];
							$wm        = $b1;     
                        elseif(($wm>=$a1) && ($wm<=$b1) && ($wm<$a_max) && ($wm<$b_max)):
                            $dbSkStart = $a_in;
                            $dbSpStart = (substr($a_in,0,2) * 3600) + (substr($a_in,3,2)*60);
                            $dbSpWork  = (substr($a_in,0,2) * 3600) + ((substr($a_in,3,2) + 15)*60);   
                            $dbSkEnd   = $a_out;
                            $dbSpEnd   = (substr($a_out,0,2) * 3600) + (substr($a_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];
                        elseif(($wm>=$b1) && ($wm<=$c1)):
                            $dbSkStart = $b_in;
                            $dbSpStart = (substr($b_in,0,2) * 3600) + (substr($b_in,3,2)*60);
                            $dbSpWork  = (substr($b_in,0,2) * 3600) + ((substr($b_in,3,2) + 15)*60);   
                            $dbSkEnd   = $b_out;
                            $dbSpEnd   = (substr($b_out,0,2) * 3600) + (substr($b_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];
                        elseif(($wm>=$c1)):
                            $dbSkStart = $c_in;
                            $dbSpStart = (substr($c_in,0,2) * 3600) + (substr($c_in,3,2)*60);
                            $dbSpWork  = (substr($c_in,0,2) * 3600) + ((substr($c_in,3,2) + 15)*60);   
                            $dbSkEnd   = $c_out;
                            $dbSpEnd   = (substr($c_out,0,2) * 3600) + (substr($c_out,3,2)*60) + (24 * 3600);
                            $mytime    = $row['TransactionTime'];    
                        endif;    
                        
                    elseif((TRIM($row['GroupID'])==6) || (TRIM($row['GroupID'])==7) ):
                        $a_in = $this->presensi->getVariabelDataByVar('SA1');
                        $a_out= $this->presensi->getVariabelDataByVar('SA2');
                        $b_in = $this->presensi->getVariabelDataByVar('SB1');
                        $b_out= $this->presensi->getVariabelDataByVar('SB2');
                        
                        $a1 =  (substr($a_in,0,2) * 3600) + (substr($a_in,3,2) * 60);
                        $a2 =  (substr($a_out,0,2) * 3600) + (substr($a_out,3,2) * 60);
                        $b1 =  (substr($b_in,0,2) * 3600) + (substr($b_in,3,2) * 60);
                        $b2 =  (substr(($b_out+24),0,2) * 3600) + (substr(($b_out+24),3,2) * 60);
                        
                        $a_max = (substr(12,0,2) * 3600) + (substr(0,3,2) * 60);
                        
                        if($wm>$a_max):
                            $dbSkStart = $b_in;
                            $dbSpStart = (substr($b_in,0,2) * 3600) + (substr($b_in,3,2)*60);
                            $dbSpWork  = (substr($b_in,0,2) * 3600) + ((substr($b_in,3,2) + 15)*60);   
                            $dbSkEnd   = $b_out;
                            $dbSpEnd   = (substr($b_out,0,2) * 3600) + (substr($b_out,3,2)*60);
                            //$mytime    = $b_in.':00';
                            $mytime = $row['TransactionTime'];
							$wm        = $b1; 
                        elseif($wm<$b1):
                            $dbSkStart = $a_in;
                            $dbSpStart = (substr($a_in,0,2) * 3600) + (substr($a_in,3,2)*60);
                            $dbSpWork  = (substr($a_in,0,2) * 3600) + ((substr($a_in,3,2) + 15)*60);   
                            $dbSkEnd   = $a_out;
                            $dbSpEnd   = (substr($a_out,0,2) * 3600) + (substr($a_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];
                        else:
                            $dbSkStart = $b_in;
                            $dbSpStart = (substr($b_in,0,2) * 3600) + (substr($b_in,3,2)*60);
                            $dbSpWork  = (substr($b_in,0,2) * 3600) + ((substr($b_in,3,2) + 15)*60);   
                            $dbSkEnd   = $b_out;
                            $dbSpEnd   = (substr($b_out,0,2) * 3600) + (substr($b_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];
                        endif;
                        
                    else:
                        
                        if($sk1>=$wm):
                            $wm   = $sk1;
                            //$mytime = $sd1;
							$mytime = $row['TransactionTime'];
                        else:
                            $wm   = $wm;
                            $mytime = $row['TransactionTime'];     
                        endif;
                        
                        if($row['DayName']=='Friday'):
                            if($jm):
                                $dbSkStart = $jm['GroupFridayStart'];
                                $dbSpStart = (substr($jm['GroupFridayStart'],0,2) * 3600) + (substr($jm['GroupFridayStart'],3,2)*60) + (substr($jm['GroupFridayStart'],6,2));
                                $dbSpWork  = (substr($jm['GroupFridayStart'],0,2) * 3600) + ((substr($jm['GroupFridayStart'],3,2)+15)*60) + (substr($jm['GroupFridayStart'],6,2));   
                                $dbSkEnd   = $jm['GroupFridayEnd'];
                                $dbSpEnd   = (substr($jm['GroupFridayEnd'],0,2) * 3600) + (substr($jm['GroupFridayEnd'],3,2)*60) + (substr($jm['GroupFridayEnd'],6,2));
                            else:
                                $dbSkStart = $sd1;
                                $dbSpStart = $sk1;
                                $dbSpWork  = $dt1;
                                $dbSkEnd   = $w3;
                                $dbSpEnd   = $wp3; 
                            endif;
                        else:
                            if($sk):
                                $dbSkStart = $sk['GroupWorkStart'];
                                $dbSpStart = (substr($sk['GroupWorkStart'],0,2) * 3600) + (substr($sk['GroupWorkStart'],3,2)*60) + (substr($sk['GroupWorkStart'],6,2));
                                $dbSpWork  = (substr($sk['GroupWorkStart'],0,2) * 3600) + ((substr($sk['GroupWorkStart'],3,2)+15)*60) + (substr($sk['GroupWorkStart'],6,2));   
                                $dbSkEnd   = $sk['GroupWorkEnd'];
                                $dbSpEnd   = (substr($sk['GroupWorkEnd'],0,2) * 3600) + (substr($sk['GroupWorkEnd'],3,2)*60) + (substr($sk['GroupWorkEnd'],6,2));
                            else:
                                $dbSkStart = $sd1;
                                $dbSpStart = $sk1;
                                $dbSpWork  = $dt1;
                                $dbSkEnd   = $w3;
                                $dbSpEnd   = $wp3; 
                            endif;
                         endif;    
                       endif;   
                    
                    else:
                        if($sk1>=$wm):
                            $wm   = $sk1;
                            //$mytime = $sd1;
							$mytime = $row['TransactionTime'];
                        else:
                            $wm   = $wm;
                            $mytime = $row['TransactionTime'];     
                        endif;       
                    endif;
            
                if(($row['DayName']=='Saturday') && ($row['GroupID']<=5)):
                    $wmk = $sd1;
                    $dt  = $dt1;
                    $sk  = $sk1;
                    $wsk = $w1; // Wsk Senin-Kamis
                    $wsp = $wp1;
                elseif(($row['DayName']=='Sunday') && ($row['GroupID']<=5)):
                    $wmk = '';
                    $dt  = 0;
                    $sk  = 0;
                    $wsk = ''; // Wsk Senin-Kamis
                    $wsp = 0;
                elseif(($row['DayName']<>'Saturday') && ($wm >= $sk1)  && ( ($row['GroupID']==1) || ($row['GroupID']==2) ) ):
                    $wmk = $sd2;
                    $dt  = $dt2;
                    $sk  = $sk2;
                    if($row['DayName']<>"Friday"):
                        $wsk = $w5; // Wsk Senin-Kamis
                        $wsp = $wp5;
                    else:
                        $wsk = $w4; // Wsk Jumat
                        $wsp = $wp4;
                    endif;
                elseif(($row['DayName']<>'Saturday') && ($wm <= $sk1)  && ( ($row['GroupID']==1) || ($row['GroupID']==2) ) ):
                    $wmk = $sd1;
                    $dt  = $dt1;
                    $sk  = $sk1;
                    if($row['DayName']<>"Friday"):
                        $wsk = $w3; // Wsk Senin-Kamis
                        $wsp = $wp3;
                    else:
                        $wsk = $w2; // Wsk Jumat
                        $wsp = $wp2;
                        $row['TransactionTime'] = substr($row['TransactionTime'],0,11)." 06:30:00";
                    endif;                  
                elseif( ( ($row['GroupID']==1) || ($row['GroupID']==2) )):
                    $wmk = $sd1;
                    $dt  = $dt1;
                    $sk  = $sk1;
                    if($row['DayName']<>"Friday"):
                        $wsk = $w3;
                        $wsp = $wp3;
                    else:
                        $wsk = $w2; 
                        $wsp = $wp2;
                    endif;
                elseif( ( ($row['GroupID']==6) || ($row['GroupID']==7) )):
                    $wmk = $dbSkStart;
                    $dt  = $dbSpWork;
                    $sk  = $dbSpStart;
                    $wsk = $dbSkEnd;
                    $wsp = $dbSpEnd; 
                elseif((($row['GroupID']==8))):
                    $wmk = $dbSkStart;
                    $dt  = $dbSpWork;
                    $sk  = $dbSpStart;
                    $wsk = $dbSkEnd;
                    $wsp = $dbSpEnd;           
                elseif(($row['GroupID']>2)):
                    $wmk = $dbSkStart;
                    $dt  = $dbSpWork;
                    $sk  = $dbSpStart;
                    if($row['DayName']<>"Friday"):
                        $wsk = $dbSkEnd;
                        $wsp = $dbSpEnd;
                    else:
                        $wsk = $w2; 
                        $wsp = $wp2;
                    endif;
                endif; 
                
                if (($wm > $dt) && ($row['DayName']<>'Sunday')):
                    $d = $wm - $sk;
                    $hours = code(floor($d / 3600));
                    $mins = code(floor(($d - ($hours*3600)) / 60));
                    $seconds = code($d % 60);
                    $late = $hours.':'.$mins.':'.$seconds;
                elseif (($wm > $dt) && ($row['GroupID'] >=5 )):
                    $d = $wm - $sk;
                    $hours = code(floor($d / 3600));
                    $mins = code(floor(($d - ($hours*3600)) / 60));
                    $seconds = code($d % 60);
                    $late = $hours.':'.$mins.':'.$seconds;             
                else:
                    $d = "-";
                    $late = "-";
                endif;
                //mytime = $row['TransactionTime'];          
                $this->authprocess->save($row['UserID'],$mytime,$wmk,$wsk,$late);
            endif;   
        endforeach;
        
        /** Query Update ke AuthProcess **/
        $query = $this->authlog->getPerWeekRecords($start,$end,$group,2);
        //$query = $this->auhtprocess->getAllRecords();
        foreach($query as $row):
            $ws = (substr($row['MyTime'],0,2) * 3600) + (substr($row['MyTime'],3,2)*60) + (substr($row['MyTime'],6,2));
            if($row['GroupID']>2):
                $sk  =  $this->usergroup->getGroupWorkData($row['GroupWork']);
                $jm  =  $this->usergroup->getGroupFridayData($row['GroupFriday']);
                
                if(TRIM($row['GroupID'])==8):
                        $a_in = $this->presensi->getVariabelDataByVar('PA1');
                        $a_out= $this->presensi->getVariabelDataByVar('PA2');
                        $b_in = $this->presensi->getVariabelDataByVar('PB1');
                        $b_out= $this->presensi->getVariabelDataByVar('PB2');
                        $c_in = $this->presensi->getVariabelDataByVar('PC1');
                        $c_out= $this->presensi->getVariabelDataByVar('PC2');
                        
                        $a1 =  (substr($a_in,0,2) * 3600) + (substr($a_in,3,2) * 60);
                        $a2 =  (substr($a_out,0,2) * 3600) + (substr($a_out,3,2) * 60);
                        $b1 =  (substr($b_in,0,2) * 3600) + (substr($b_in,3,2) * 60);
                        $b2 =  (substr(($b_out),0,2) * 3600) + (substr(($b_out),3,2) * 60);
                        $c1 =  (substr($c_in,0,2) * 3600) + (substr($c_in,3,2) * 60);
                        $c2 =  (substr(($c_out+24),0,2) * 3600) + (substr(($c_out),3,2) * 60);
                        
                        //10
                        //18
                        //26
                        
                        if(($ws>=$a2) && ($ws<=$b2) ):
                            $dbSkStart = $a_in;
                            $dbSpStart = (substr($a_in,0,2) * 3600) + (substr($a_in,3,2)*60);
                            $dbSpWork  = (substr($a_in,0,2) * 3600) + ((substr($a_in,3,2) + 15)*60);   
                            $dbSkEnd   = $a_out;
                            $dbSpEnd   = (substr($a_out,0,2) * 3600) + (substr($a_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];
                        elseif(($ws>=$b2) && ($wm<=$c2)):
                            $dbSkStart = $b_in;
                            $dbSpStart = (substr($b_in,0,2) * 3600) + (substr($b_in,3,2)*60);
                            $dbSpWork  = (substr($b_in,0,2) * 3600) + ((substr($b_in,3,2) + 15)*60);   
                            $dbSkEnd   = $b_out;
                            $dbSpEnd   = (substr($b_out,0,2) * 3600) + (substr($b_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];
                        elseif(($ws>=$c2)):
                            $dbSkStart = $c_in;
                            $dbSpStart = (substr($c_in,0,2) * 3600) + (substr($c_in,3,2)*60);
                            $dbSpWork  = (substr($c_in,0,2) * 3600) + ((substr($c_in,3,2) + 15)*60);   
                            $dbSkEnd   = $c_out;
                            $dbSpEnd   = (substr($c_out + 24 ,0,2) * 3600) + (substr($c_out,3,2)*60);
                            $mytime    = $row['TransactionTime'];    
                        endif;    
                        
                        
                elseif((TRIM($row['GroupID'])==6) || (TRIM($row['GroupID'])==7) ):
                        $a_in = $this->presensi->getVariabelDataByVar('SA1');
                        $a_out= $this->presensi->getVariabelDataByVar('SA2');
                        $b_in = $this->presensi->getVariabelDataByVar('SB1');
                        $b_out= $this->presensi->getVariabelDataByVar('SB2');
                        
                        $a1 =  (substr($a_in,0,2) * 3600) + (substr($a_in,3,2) * 60);
                        $a2 =  (substr($a_out,0,2) * 3600) + (substr($a_out,3,2) * 60);
                        $b1 =  (substr($b_in,0,2) * 3600) + (substr($b_in,3,2) * 60);
                        $b2 =  (substr(($b_out+24),0,2) * 3600) + (substr(($b_out+24),3,2) * 60);
                        
                        if($ws>$b2):
                            $dbSkStart = $a_in;
                            $dbSpStart = (substr($a_in,0,2) * 3600) + (substr($a_in,3,2)*60);
                            $dbSpWork  = (substr($a_in,0,2) * 3600) + ((substr($a_in,3,2) + 15)*60);   
                            $dbSkEnd   = $a_out;
                            $dbSpEnd   = (substr($a_out,0,2) * 3600) + (substr($a_out,3,2)*60);
                        else:
                            $dbSkStart = $b_in;
                            $dbSpStart = (substr($b_in,0,2) * 3600) + (substr($b_in,3,2)*60);
                            $dbSpWork  = (substr($b_in,0,2) * 3600) + ((substr($b_in,3,2) + 15)*60);   
                            $dbSkEnd   = $b_out;
                            $dbSpEnd   = (substr($b_out,0,2) * 3600) + (substr($b_out,3,2)*60);
                        endif;
         
                else:
                
                    if($row['DayName']=='Friday'):
                        if($jm):
                            $dbSkStart = $jm['GroupFridayStart'];
                            $dbSpStart = (substr($jm['GroupFridayStart'],0,2) * 3600) + (substr($jm['GroupFridayStart'],3,2)*60) + (substr($jm['GroupFridayStart'],6,2));
                            $dbSpWork  = (substr($jm['GroupFridayStart'],0,2) * 3600) + ((substr($jm['GroupFridayStart'],3,2)+15)*60) + (substr($jm['GroupFridayStart'],6,2));   
                            $dbSkEnd   = $jm['GroupFridayEnd'];
                            $dbSpEnd   = (substr($jm['GroupFridayEnd'],0,2) * 3600) + (substr($jm['GroupFridayEnd'],3,2)*60) + (substr($jm['GroupFridayEnd'],6,2));
                        else:
                            $dbSkStart = $sd1;
                            $dbSpStart = $sk1;
                            $dbSpWork  = $dt1;
                            $dbSkEnd   = $w3;
                            $dbSpEnd   = $wp3; 
                        endif;
                    else:
                        if($sk):
                            $dbSkStart = $sk['GroupWorkStart'];
                            $dbSpStart = (substr($sk['GroupWorkStart'],0,2) * 3600) + (substr($sk['GroupWorkStart'],3,2)*60) + (substr($sk['GroupWorkStart'],6,2));
                            $dbSpWork  = (substr($sk['GroupWorkStart'],0,2) * 3600) + ((substr($sk['GroupWorkStart'],3,2)+15)*60) + (substr($sk['GroupWorkStart'],6,2));   
                            $dbSkEnd   = $sk['GroupWorkEnd'];
                            $dbSpEnd   = (substr($sk['GroupWorkEnd'],0,2) * 3600) + (substr($sk['GroupWorkEnd'],3,2)*60) + (substr($sk['GroupWorkEnd'],6,2));
                        else:
                            $dbSkStart = $sd1;
                            $dbSpStart = $sk1;
                            $dbSpWork  = $dt1;
                            $dbSkEnd   = $w3;
                            $dbSpEnd   = $wp3; 
                        endif;
                    endif;    
                endif;
                 
            endif;     
                            
            if(($row['DayName']=='Saturday') && ($row['GroupID']>=5)):
                $wmk = $sd1;
                $dt  = $dt1;
                $sk  = $sk1;
                $wsk = $w1; // Wsk Senin-Kamis
                $wsp = $wp1;
            elseif(($row['DayName']=='Sunday') && ($row['GroupID']>=5)):
                $wmk = '';
                $dt  = 0;
                $sk  = 0;
                $wsk = ''; // Wsk Senin-Kamis
                $wsp = 0;
            elseif(($row['DayName']<>'Saturday') && ($wm >= $sk1)  && ( ($row['GroupID']==1) || ($row['GroupID']==2) ) ):
                $wmk = $sd2;
                $dt  = $dt2;
                $sk  = $sk2;
                if($row['DayName']<>"Friday"):
                    $wsk = $w5; // Wsk Senin-Kamis
                    $wsp = $wp5;
                else:
                    $wsk = $w4; // Wsk Jumat
                    $wsp = $wp4;
                endif;       
            elseif(( ($row['GroupID']==1) || ($row['GroupID']==2) )):
                $wmk = $sd1;
                $dt  = $dt1;
                $sk  = $sk1;
                if($row['DayName']<>"Friday"):
                    $wsk = $w3;
                    $wsp = $wp3;
                else:
                    $wsk = $w2; 
                    $wsp = $wp2;
                endif;
            elseif( ( ($row['GroupID']==6) || ($row['GroupID']==7) )):
                $wmk = $dbSkStart;
                $dt  = $dbSpWork;
                $sk  = $dbSpStart;
                $wsk = $dbSkEnd;
                $wsp = $dbSpEnd; 
            elseif(($row['GroupID']==8)):
                $wmk = $dbSkStart;
                $dt  = $dbSpWork;
                $sk  = $dbSpStart;
                $wsk = $dbSkEnd;
                $wsp = $dbSpEnd;           
            elseif(($row['GroupID']>2)):
                $wmk = $dbSkStart;
                $dt  = $dbSpWork;
                $sk  = $dbSpStart;
                if($row['DayName']<>"Friday"):
                    $wsk = $dbSkEnd;
                    $wsp = $dbSpEnd;
                else:
                    $wsk = $w2; 
                    $wsp = $wp2;
                endif;
            endif; 
            
            if ($ws >= $wsp):
                $early = "-";  
            else:
                $e = $wsp - $ws;
                $hours = code(floor($e / 3600));
                $mins = code(floor(($e - ($hours*3600)) / 60));
                $seconds = code($e % 60);
                $early = $hours.':'.$mins.':'.$seconds;   
            endif;
            
            if($ws>0)
                $ws = $ws;
            else
                $ws = 0;
             
            if($wm>0)
                $wm = $wm;
            else
                $wm = 0;            
            
            $duration = $ws - $wm;
            
            if($duration<0)
                $duration = 0;
                       
            $this->authprocess->update($row['UserID'],$row['MyDate'],$row['TransactionTime'],$early,$duration);
        endforeach;
        redirect('presensi/report/weekly',301);
        
    }
    
    function week_paging($per_page)
    {
        $this->session->set_userdata('week_paging',$per_page);
        redirect('presensi/report/weekly');
    }
    
    function week_preview(){
		$export = $this->input->post('export');
            
		switch($export):
			case 0 : $this->week_print();
					 break;
			case 1 : $this->week_pdf();
			         break;
			case 2 : $this->week_excel();
			         break;
		endswitch;
	}
    
    function week_print()
    {
		if($this->session->userdata('week_type')=='M2'):
			$data['position']	=	$this->usergroup->getPositionData($this->session->userdata('week_group'));
			$data['title']		=	'REKAPITULASI PEMENUHAN JAM '.$data['position']['Name'].' MAN 3 MALANG';
			$data['periode']	=	'LAPORAN PERIODE '.$this->session->userdata('week_start').' s/d '.$this->session->userdata('week_finish');
			$data['start']      =   $this->session->userdata('week_start');
            $data['end']        =   $this->session->userdata('week_finish'); 
            $data['users']	    =	$this->userinfo->getAllRecords('','','','',$this->session->userdata('week_group'));
			$data['var']	    =	$this->presensi->getVariabelDataByVar('DMK');
			$this->load->theme('report/week2',$data);
		else:
			$data['title']		=	'DAFTAR CEK CLOCK';
			$data['days']	    =   $this->authlog->getDay($this->session->userdata('week_start'),$this->session->userdata('week_finish'));
			$data['periode']	=	'Periode '.$this->session->userdata('week_start'). ' s/d '. $this->session->userdata('week_finish');
			$data['users']	    =	$this->userinfo->getAllRecords('','','','',$this->session->userdata('week_group'));
			$data['var']	    =	$this->presensi->getVariabelDataByVar('DMK');
			$this->load->vars($data);
			$this->load->theme('report/week',$data);	
		endif;	
	}
	
	function week_pdf()
    {
		
		if($this->session->userdata('week_type')=='M2'):
            $this->load->library('pdf');
			$data['position']	=	$this->usergroup->getPositionData($this->session->userdata('week_group'));
			$data['title']		=	'REKAPITULASI PEMENUHAN JAM '.$data['position']['Name'].' MAN 3 MALANG';
			$data['periode']	=	'LAPORAN PERIODE '.$this->session->userdata('week_start').' s/d '.$this->session->userdata('week_finish');
			$data['start']      =   $this->session->userdata('week_start');
            $data['end']        =   $this->session->userdata('week_finish'); 
            $data['users']	    =	$this->userinfo->getAllRecords('','','','',$this->session->userdata('week_group'));
			$data['var']	    =	$this->presensi->getVariabelDataByVar('DMK');
			$file = $this->load->theme('report/week2',$data,TRUE);
			$this->pdf->pdf_create($file,$data['title'],$stream=TRUE,'A4',"Landscape");
		else:
            $title  = 'DAFTAR CEK CLOCK';
            $periode= 'Periode '.$this->session->userdata('week_start').' s/d '.$this->session->userdata('week_finish');
            $users  = $this->userinfo->getAllRecords('','','','',$this->session->userdata('week_group'));
            $days   = $this->authlog->getDay($this->session->userdata('week_start'),$this->session->userdata('week_finish'));
            $var	= $this->presensi->getVariabelDataByVar('DMK');
            $this->load->helper('tcpdf');  
            $pdf = tcpdf();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(5,5,5);
            $pdf->AddPage("L","Letter");
            
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('helvetica', '', 7);
            
            $y = 5;
            $x = 7;
            $row=1;
            $total = COUNT($users);
                   
            foreach($users as $user):
                
                if($row%3==1):
                    $x=7;
                elseif($row%3==2):
                    $x=97;
				elseif($row%3==0):
					$x=187;
				endif;	
                    
                $ID = $user['ID'];
                $x=$x;$y=$y;
                $pdf->SetY($y);$pdf->SetX($x); 	
                $pdf->Cell(85,4,$title, 0, 0, 'C', 1); 
                

                $x=$x;$y=$y+4;
                $pdf->SetY($y);$pdf->SetX($x); 	
                $pdf->Cell(85, 4,$periode, 0, 0, 'C', 1); 
                
                $pdf->SetFillColor(205, 201, 201);
                
                 
    
                $x=$x;$y=$y+5;
                $pdf->MultiCell(85,4,$row.'-'.$ID.' '.$user['Name'],1,'L', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x;$y=$y+4;
                $pdf->MultiCell(6,4,"M",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+6;$y=$y;
                $pdf->MultiCell(15,4,"Tanggal",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+15;$y=$y;
                $pdf->MultiCell(12,4,"Datang",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+12;$y=$y;
                $pdf->MultiCell(12,4,"Pulang",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+12;$y=$y;
                $pdf->MultiCell(12,4,"Durasi",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+12;$y=$y;
                $pdf->MultiCell(14,4,"Dtg.Telat",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+14;$y=$y;
                $pdf->MultiCell(14,4,"Plg.Cepat",1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $pdf->SetFillColor(255, 255, 255);
                
                $y=$y+4;
                $i=1;
                $ww[1]=0;$ww[2]=0;$ww[3]=0;$ww[4]=0;$ww[5]=0;
                $wl[1]=0;$wl[2]=0;$wl[3]=0;$wl[4]=0;$wl[5]=0;
                $we[1]=0;$we[2]=0;$we[3]=0;$we[4]=0;$we[5]=0;
                
                foreach($days as $rec):
                    $val = $this->authprocess->getAllRecords('','',$user['ID'],'row',$rec['DAY']);
                    
                    if($val):
                        $w = $val['W'];
                        $d = $val['MyDate'];
                        $s = $val['MyTimeStart'];
                        $e = $val['MyTimeEnd'];
                        $l = $val['ProcessDateLate'];
                        $el= $val['ProcessDateEarly'];
                        /** Call**/
                        if(empty($e))
                            $end=0;
                        else
                            $end   = (substr($e,0,2) * 3600) + (substr($e,3,2)*60) + (substr($e,6,2));
                                
                        if(empty($s))
                            $start=0;
                        else
                            $start = (substr($s,0,2) * 3600) + (substr($s,3,2)*60) + (substr($s,6,2));
                                        
                        $range = $end - $start;
                        if($range<0)
                            $range = 0;
                        else
                            $range = $range;    
                                
                        $hours = code(floor($range / 3600));
                        $mins  = code(floor(($range - ($hours*3600)) / 60));
                        $seconds = code($range % 60);
                        if($range>0)
                            $dr  = $hours.':'.$mins.':'.$seconds; 
                        else
                            $dr='';
                        
                    else:
                        $w = '-';
                        $d = '-';
                        $s = '-';
                        $e = '-';
                        $dr= '-';
                        $l = '-';
                        $el= '-';
                        $range=0;
                    endif;
                    
                    
                    if($row%3==1):
						$x=7;
					elseif($row%3==2):
						$x=97;
					elseif($row%3==0):
						$x=187;
					endif;	
                        
                    $pdf->MultiCell(6,4,$w,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    $x=$x+6;
                    $pdf->MultiCell(15,4,$d,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    $x=$x+15;
                    $pdf->MultiCell(12,4,$s,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    $x=$x+12;
                    $pdf->MultiCell(12,4,$e,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    $x=$x+12;
                    $pdf->MultiCell(12,4,$dr,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    $x=$x+12;
                    $pdf->MultiCell(14,4,$l,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    $x=$x+14;
                    $pdf->MultiCell(14,4,$el,1,'C', 1, 2, $x,$y,true,0,false,true,0);
                    
                    
                    $sl = (substr($l,0,2) * 3600) + (substr($l,3,2) *60) + (substr($l, 6,2));
                    $sel= (substr($el,0,2)* 3600) + (substr($el,3,2)*60) + (substr($el,6,2));
                    if($w==1):
                        $ww[1] = $ww[1] + $range;
                        $wl[1] = $wl[1] + $sl;
                        $we[1] = $we[1] + $sel;
                    elseif($w==2):
                        $ww[2] = $ww[2] + $range;
                        $wl[2] = $wl[2]+ $sl;
                        $we[2] = $we[2]+ $sel;        
                    elseif($w==3):
                        $ww[3] = $ww[3] + $range;
                        $wl[3] = $wl[3]+ $sl;
                        $we[3] = $we[3]+ $sel;
                    elseif($w==4):
                        $ww[4] = $ww[4] + $range;
                        $wl[4] = $wl[4]+ $sl;
                        $we[4] = $we[4]+ $sel;
                    elseif($w==5):
                        $ww[5] = $ww[5] + $range;  
                        $wl[5] = $wl[5]+ $sl; 
                        $we[5] = $we[5]+ $sel;
                    endif;                            
                            
                    $y=$y+4;
                    $i++;
                endforeach;
                
                if($row%3==1):
					$x=7;
				elseif($row%3==2):
					$x=97;
				elseif($row%3==0):
					$x=187;
				endif;	
                        
                $pdf->MultiCell(85,4,'Total Jam Kehadiran',1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $pdf->SetFillColor(205, 201, 201);
                
                $y=$y+4; 
                $pdf->MultiCell(15,4,'Minggu Ke',1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+15;
                $pdf->MultiCell(15,4,'Work Time',1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+15;
                $pdf->MultiCell(25,4,'Keterangan',1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+25;
                $pdf->MultiCell(15,4,'Dtg.Telat',1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $x=$x+15;
                $pdf->MultiCell(15,4,'Plg.Cepat',1,'C', 1, 2, $x,$y,true,0,false,true,0);
                
                $pdf->SetFillColor(255, 255, 255);
                $y=$y+4;
				$tm='';
                for($m=1;$m<=5;$m++):
                    
                    if($row%3==1):
						$x=7;
					elseif($row%3==2):
						$x=97;
					elseif($row%3==0):
						$x=187;
					endif;	
                    
                    $whours = code(floor($ww[$m] / 3600));
                    $wmins  = code(floor(($ww[$m] - ($whours*3600)) / 60));
                    $wseconds = code($ww[$m] % 60);
                            
                    $lhours = code(floor($wl[$m] / 3600));
                    $lmins  = code(floor(($wl[$m] - ($lhours*3600)) / 60));
                    $lseconds = code($wl[$m] % 60);
                            
                    $ehours = code(floor($we[$m] / 3600));
                    $emins  = code(floor(($we[$m] - ($ehours*3600)) / 60));
                    $eseconds = code($we[$m] % 60);
                            
                    $week[$m] = $whours.':'.$wmins.':'.$wseconds; 
                    $late[$m] = $lhours.':'.$lmins.':'.$lseconds;
                    $early[$m]= $ehours.':'.$emins.':'.$eseconds;
                    
                    if($week[$m]):
				        $v = (substr($var,0,2) * 3600) + (substr($var,3,2)*60) + (substr($var,6,2));
    				    if($ww[$m]>=$v):
    				        $desc = "Memenuhi";
							$tm = $tm . '';
    				    else:
    				        $desc = "Tidak Memenuhi"; 
							$tm = $tm.'M'.$m.': TM ';
						endif;	
				    endif;	
                            
                    $pdf->MultiCell(15,4,'Minggu '.$m,1,'C', 1, 0, $x,$y,true,0,false,true,0);
                    
                    $x=$x+15;
                    $pdf->MultiCell(15,4,$week[$m],1,'C', 1, 0, $x,$y,true,0,false,true,0);
                    
                    $x=$x+15;
                    $pdf->MultiCell(25,4,$desc,1,'C', 1, 0, $x,$y,true,0,false,true,0);
                    
                    $x=$x+25;
                    $pdf->MultiCell(15,4,$late[$m],1,'C', 1, 0, $x,$y,true,0,false,true,0);
                    
                    $x=$x+15;
                    $pdf->MultiCell(15,4,$early[$m],1,'C', 1, 0,$x,$y,true,0,false,true,0);
                
                    $y=$y+4;
                endfor;
                
                if($row%3==1):
					$x=7;
				elseif($row%3==2):
					$x=97;
				elseif($row%3==0):
					$x=187;
				endif;	
                        
                $pdf->MultiCell(85,4,$tm,1,'C', 1, 2, $x,$y,true,0,false,true,0);
            
                if($row%3==0):
                    $y=$y+5;
                else:
                    $x= $x;
                    $y=$y-(5+4+(0*7)+($i*4)+($m*4)+(1*7)+1);    
                endif;
                
                if(($row%3==0) && ($row!=$total)):
                    $pdf->AddPage();
                    $y = 5;
					
                    if($row%3==1):
						$x=7;
					elseif($row%3==2):
						$x=97;
					elseif($row%3==0):
						$x=187;
					endif;	
						
                endif;    
                $row++;
                
            endforeach;
            $pdf->Output("Laporan-Mingguan-1.pdf","I"); 
		endif;	
        
	}
    
    function week_excel()
    {
		if($this->session->userdata('week_type')=='M2'):
			$start = $this->session->userdata('week_start'); 
			$end   = $this->session->userdata('week_finish');
			$group = $this->session->userdata('week_group'); 
			$pos   = $this->usergroup->getPositionData($this->session->userdata('week_group'));
			$recs  = $this->userinfo->getAllRecords('','','','',$group);
			$days  = $this->authlog->getDay($start,$end);  
			$var   = $this->presensi->getVariabelDataByVar('DMK');
			$excel = $this->excelModel->week2_excel($recs,$start,$end,$group,$days,$pos,$var);
			$data = file_get_contents("assets/Lap-Mingguan-2.xlsx"); // Read the file's contents
			force_download("Lap-Mingguan-2",$data);
		else:
			$start = $this->session->userdata('week_start'); 
			$end   = $this->session->userdata('week_finish');
			$group = $this->session->userdata('week_group'); 
			$pos   = $this->usergroup->getPositionData($this->session->userdata('week_group'));
			$recs  = $this->userinfo->getAllRecords('','','','',$group);
			$days  = $this->authlog->getDay($start,$end);  
			$var   = $this->presensi->getVariabelDataByVar('DMK');
			$excel = $this->excelModel->week_excel($recs,$start,$end,$group,$days,$pos,$var);
			$data = file_get_contents("assets/Lap-Mingguan.xlsx"); // Read the file's contents
			force_download("Lap-Mingguan",$data); 
		endif;	
    }
	
	function overtime($offset=0){
        $data['title']  = 'Laporan Lemburan';
        $data['logs']   =   $this->log->userLog();
        $data['overtime']= $this->presensi->getVariabelDataByVar('ULB');
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('overtime_paging'))
            $paging = $this->session->userdata('overtime_paging');
        else
            $paging = config_item('paging');
			
        if($this->session->userdata('overtime_group')):
			$group = $this->session->userdata('overtime_group');
            $start = $this->session->userdata('overtime_start');
			$end   = $this->session->userdata('overtime_finish');
        else:
			$group = 100;
            $start = '';
			$end   = '';
        endif;          
        $this->session->set_userdata('overtime_offset',$offset);
        $data['checks']  = $this->authovertime->getAllRecords();
        
        $numrows = COUNT($this->authovertime->getAllRecords()); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/report/overtime/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'report/vovertime';
		$this->load->theme('default',$data);
    }
	
	function overtime_search()
    {
        $search = array ('overtime_group'  => $this->input->post('group'),
						 'overtime_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'overtime_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));   
        $start =  $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year');
		$end   =  $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2');
		$group =  $this->input->post('group');
		
		$this->session->set_userdata($search);
        $this->authovertime->removeAll();
        /** Query Insert ke AuthOvertime **/
        $query = $this->overtimes->getAllRecords('','','',$start,$end,$group);
        foreach($query as $row):       
            $min = number_format($this->presensi->getVariabelDataByVar('LMN'),0);
            $max = number_format($this->presensi->getVariabelDataByVar('LMX'));              
			$tm1 = $this->authlog->getUserTime($row['MyDate'],$row['UserID'],'3');	
			$tm2 = $this->authlog->getUserTime($row['MyDate'],$row['UserID'],'4');
            //$tm2 = '22:30:00';
            $tm1_value = (substr($tm1,0,2) * 3600) + ((substr($tm1,3,2)*60)) + (substr($tm1,6,2));
            $tm2_value = (substr($tm2,0,2) * 3600) + ((substr($tm2,3,2)*60)) + (substr($tm2,6,2));
            $tm1_value = $tm1_value/60; 
            $tm2_value = $tm2_value/60;
            $duration  =  number_format($tm2_value - $tm1_value,0);
            $meal      =  120; 
            
            if(($duration >= $min)):
                if(($duration >= $max))
                    $over = $max/60;
                else
                    $over = $duration/60;
            else:
                $over = 0;
            endif;    
                
            if($duration>=$meal)
                $meal = $this->presensi->getVariabelDataByVar('ULM');
             else
                $meal = 0;           
            $over = substr($over,0,1);            
            $this->authovertime->save($row['UserID'],$row['OvertimeDate'],$tm1,$tm2,$over,$meal); 
        endforeach;
        redirect('presensi/report/overtime',301);
        
    }
    
    function overtime_paging($per_page)
    {
        $this->session->set_userdata('overtime_paging',$per_page);
        redirect('presensi/report/overtime');
    }
    
    function overtime_preview(){
		$export = $this->input->post('export');
            
		switch($export):
			case 0 : $this->overtime_print();
					 break;
			case 1 : $this->overtime_pdf();
			         break;
			case 2 : $this->overtime_excel();
			         break;
		endswitch;
	}
    
    function overtime_print()
    {
		$data['title']  =  'Laporan Lembur';
		$data['var']	=  $this->presensi->getVariabelDataByVar('ULB');
		$data['checks'] =  $this->authovertime->getAllRecords();
        $this->load->vars($data);
        $this->load->theme('report/overtime',$data);
	}
    
    function overtime_pdf()
    {
        $this->load->library('pdf');
		$data['title']  =  'Laporan Lembur';
		$data['var']	=  $this->presensi->getVariabelDataByVar('ULB');
		$data['checks'] =  $this->authovertime->getAllRecords();
        $this->load->vars($data);
        $file = $this->load->theme('report/overtime',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
	}
    
    function overtime_excel()
    {
        $start = $this->session->userdata('overtime_start'); 
        $end   = $this->session->userdata('overtime_finish');
        $group = $this->session->userdata('overtime_group'); 
        $recs  = $this->authovertime->getAllRecords();
        $var   = $this->presensi->getVariabelDataByVar('ULB');   
		$excel = $this->excelModel->overtime_excel($recs,$var);
        $data = file_get_contents("assets/Lap-Lembur.xlsx"); // Read the file's contents
        force_download("Lap-Lembur",$data); 
	}
	
	function special_employee($offset=0){
        $data['title']  = 'Laporan Pegawai Khusus';
        $data['logs']   =   $this->log->userLog();
        $data['groups']	= $this->usergroup->getDataFromPosition(6);
        if($this->session->userdata('se_paging'))
            $paging = $this->session->userdata('se_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('se_group')):
			$group = $this->session->userdata('se_group');
            $month = $this->session->userdata('se_month');
			$year  = $this->session->userdata('se_year');
        else:
			$group = '100';
            $month = date('m');
			$year  = date('Y');
        endif;           
        $this->session->set_userdata('se_offset',$offset);
        $data['checks']  = $this->authlog->getMonthRecords($offset,$paging,$month,$year,$group);
        $numrows = COUNT($this->authlog->getMonthRecords('','',$month,$year,$group)); 
        if ($numrows > $paging):
            $config['base_url']   = site_url('presensi/report/special_employee/');
            $config['total_rows'] = $numrows;
            $config['per_page']   = $paging;
            $config['uri_segment']= 4;
            $this->pagination->initialize($config);	 
            $data['pagination']   = $this->pagination->create_links();
        endif;    
        $data['page']	= 'report/vse';
		$this->load->theme('default',$data);
    }
    
    function se_paging($per_page)
    {
        $this->session->set_userdata('se_paging',$per_page);
        redirect('presensi/report/special_employee');
    }
    
    function se_search()
    {
        /*$search = array ('se_group'  => $this->input->post('group'),
						 'se_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'se_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));     
        */
        $search = array ('se_group'  => $this->input->post('group'),
                         'se_month'  => $this->input->post('month'),
                         'se_year'   => $this->input->post('year')
                        );
        $this->session->set_userdata($search);
        //echo $this->session->userdata('se_group');
        redirect('presensi/report/special_employee',301);
    }
	
	function se_preview(){
		$export = $this->input->post('export');
            
		switch($export):
			case 0 : $this->se_print();
					 break;
			case 1 : $this->se_pdf();
			         break;
			case 2 : $this->se_excel();
			         break;
		endswitch;
	}
	
	function se_print()
    {
		$data['title']		=	'DAFTAR HADIR PEGAWAI';
        $group = $this->session->userdata('se_group');
        $month = $this->session->userdata('se_month');
		$year  = $this->session->userdata('se_year');
		$data['position']	=	$this->usergroup->getPositionData($group);
		$data['checks']		=	$this->authlog->getMonthRecords('','',$month,$year,$group);
		$data['days']       =   days_in_month($month);  
        $data['month']      =   $this->session->userdata('se_month');
        $data['year']       =   $this->session->userdata('se_year');   
        $this->load->vars($data);
        $this->load->theme('report/se',$data);
	}
	
    function se_pdf(){
        $this->load->helper('tcpdf');    
        $data['title']		=	'DAFTAR HADIR PEGAWAI';
        $group = $this->session->userdata('se_group');
        $month = $this->session->userdata('se_month');
		$year  = $this->session->userdata('se_year');
		$data['position']	=	$this->usergroup->getPositionData($group);
		$data['checks']		=	$this->authlog->getMonthRecords('','',$month,$year,$group);
		$data['days']       =   days_in_month($this->session->userdata('se_month'));
        $data['month']      =   $this->session->userdata('se_month');
        $data['year']       =   $this->session->userdata('se_year');    
        $this->load->vars($data);
        $content=$this->load->theme('report/month',$data,TRUE);
        $html2pdf = html2pdf('L','A3');
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('Laporan-Pegawai-Khusus.pdf');
    }
    
	function se_pdf2()
    {
		/**$this->load->library('pdf');
        $data['title']		=	'DAFTAR HADIR PEGAWAI';
        $group = $this->session->userdata('se_group');
        $month = $this->session->userdata('se_month');
		$year  = $this->session->userdata('se_year');
		$data['position']	=	$this->usergroup->getPositionData($group);
		$data['checks']		=	$this->authlog->getMonthRecords('','',$month,$year,$group);
        //$data['days']	    =   $this->authlog->getDay($this->session->userdata('month_start'),$this->session->userdata('month_finish'));
		$data['days']       =   days_in_month($this->session->userdata('month_month'));
        $data['month']      =   $this->session->userdata('se_month');
        $data['year']       =   $this->session->userdata('se_year');    
        $this->load->vars($data);
        $file = $this->load->theme('report/month',$data,TRUE);
        $this->pdf->pdf_create($file,$data['title']);**/
        $group = $this->session->userdata('se_group');
        $month =   $this->session->userdata('se_month');
        $year  =   $this->session->userdata('se_year'); 
        $days  =   days_in_month($month);
        $records=   $this->authlog->getMonthRecords('','',$month,$year,$group);  
        
        $this->load->helper('tcpdf');
        $pdf = tcpdf();
        $pdf->setPageOrientation ('L','Letter',8); 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        $pdf->AddPage("L","A3");
  
        /** initialization of x & y axis **/
        $x = 7;
        $y = 7;
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.1);
        
        /** Cel Title **/
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(275, 4,'KEMENTRIAN AGAMA', 0, 0, 'L', 1); 
        
         /** Cel Title **/
        $y=$y+6;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x);
        $pdf->Cell(275, 4,'MADRASAH ALIYAH NEGERI 3 MALANG', 0, 0, 'L', 1);
        
         /** Cel Title **/
        $y=$y+6;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x);
        $pdf->Cell(275, 4,'JL.BANDUNG NO.7 Telp.0341-551357,588333  ', 0, 0, 'L', 1);
        
        $x2=$x+405;
        $y=$y+8;
        $x=$x+0;
        $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line($x,$y,$x2,$y,$style);
        
        $y=$y+2;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x);
        $pdf->Cell(350, 4,'DAFTAR HADIR PEGAWAI', 0, 0, 'C', 1);
        
        $y=$y+5;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x);
        $pdf->Cell(350, 4,'Bulan : '.indonesian_monthName($month).' '.$year, 0, 0, 'C', 1);
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetFillColor(205, 201, 201);
        $pdf->SetLineWidth(0.1);
        $y=$y+7;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(10, 12,'No', 1, 1, 'C', 1, '', 0, false, 'T', 'C');
        
        $y=$y+0;
        $x=$x+10;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(45,12,'Nama', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $y=$y+0;
        $x=$x+45;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(12,12,'Paraf', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $y=$y+0;
        $x=$x+12;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(($days * 10),6,'Tanggal', 1, 1, 'C', 1, '', 0, false, 'T','C');
         
        $y = $y + 6;
        $x = $x + 0;
        //$line= $x;
        for($i=1;$i<=$days;$i++):
            $y=$y + 0;
            $x = $x;
            $pdf->SetY($y);$pdf->SetX($x); 	
            $pdf->Cell(10,6,code($i), 1, 1, 'C', 1, '', 0, false, 'T','C');
            $x = $x + 10;
        endfor;
        
        $y=$y-6;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell((4 * 7) ,6,'Keterangan', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $y=$y+6;
        $x=$x+0;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(7,6,'S', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $y=$y+0;
        $x=$x+7;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(7,6,'I', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $y=$y+0;
        $x=$x+7;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(7,6,'C', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $y=$y+0;
        $x=$x+7;
        $pdf->SetY($y);$pdf->SetX($x); 	
        $pdf->Cell(7,6,'DL', 1, 1, 'C', 1, '', 0, false, 'T','C');
        
        $pdf->SetFillColor(255, 255, 255);
        
        $i=1;
        //$x=7;
        $y=$y+6;
        $row_height=40;	
        foreach($records as $rec):
            $x=7;
            $pdf->SetY($y);
		    $pdf->SetX($x); 
            $pdf->MultiCell(10,$row_height,"\n\n".$i, 1, 'C', 0, 0, '', '', true); 
            $pdf->MultiCell(45,$row_height,"\n"."\n".$rec['Name']."\n".$rec['Description']."\n".$rec['Department'],1, 'L', 0, 0, '', '', true); 
            $pdf->MultiCell(12,10,"\n".'Paraf', 1, 'C', 0, 0, '', '', true); 
            
            $pdf->SetY($y+10);
            $pdf->SetX($x+55);  
            $pdf->MultiCell(12,10,"\n".'Dtg.PK', 1, 'C', 0, 0, '', '', true);
            
            $pdf->SetY($y+20);
            $pdf->SetX($x+55);  
            $pdf->MultiCell(12,10,"\n".'Paraf', 1, 'C', 0, 0, '', '', true);
            
            $pdf->SetY($y+30);
            $pdf->SetX($x+55);  
            $pdf->MultiCell(12,10,"\n".'Plg.PK', 1, 'C', 0, 0, '', '', true);
            
            /*Paraf Dtg PK*/
            $x=64;
            for($j=1;$j<=$days;$j++):
                $pdf->SetFillColor(205, 201, 201);
                if(getSunday($year,$month,code($j)))
                    $colour=1;
                else    
                    $colour=0;
                $pdf->SetY($y);
    		    $pdf->SetX($x+($j*10)); 
                $pdf->MultiCell(10,10,"\n"."", 1, 'C', $colour, 0, '', '', true);
            endfor;
            
            $pdf->MultiCell(7,40,"\n".$this->others->getUserTime($rec['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Sakit"), 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(7,40,"\n".$this->others->getUserTime($rec['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Ijin"), 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(7,40,"\n".$this->others->getUserTime($rec['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Cuti"), 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(7,40,"\n".$this->others->getUserTime($rec['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Tugas"), 1, 'C', 0, 0, '', '', true);
            
            /*Dtg PK*/
            $y=$y+10;
            $x=64;
            for($j=1;$j<=$days;$j++):
                $pdf->SetFillColor(205, 201, 201);
                if(getSunday($year,$month,code($j)))
                    $colour=1;
                else    
                    $colour=0;
                $pdf->SetY($y);
    		    $pdf->SetX($x+($j*10)); 
                $pdf->MultiCell(10,10,"\n".substr($this->authlog->getUserTime(code($j).'-'.$month.'-'.$year,$rec['UserID'],1),0,5), 1, 'C', $colour, 0, '', '', true);
            endfor;
            
            /* Paraf Plg PK*/
            $y=$y+10;
            $x=64;
            for($j=1;$j<=$days;$j++):
                $pdf->SetFillColor(205, 201, 201);
                if(getSunday($year,$month,code($j)))
                    $colour=1;
                else    
                    $colour=0;
                
                $pdf->SetY($y);
    		    $pdf->SetX($x+($j*10)); 
                $pdf->MultiCell(10,10,"\n",1, 'C',$colour,0, '', '', true);
            endfor;
            
            /* Paraf Plg PK*/
            $y=$y+10;
            $x=64;
            for($j=1;$j<=$days;$j++):
                $pdf->SetFillColor(205, 201, 201);
                if(getSunday($year,$month,code($j)))
                    $colour=1;
                else    
                    $colour=0;
                $pdf->SetY($y);
    		    $pdf->SetX($x+($j*10)); 
                $pdf->MultiCell(10,10,"\n".substr($this->authlog->getUserTime(code($j).'-'.$month.'-'.$year,$rec['UserID'],2),0,5), 1, 'C', $colour, 0, '', '', true);
            endfor;
           
            $y=$y+10;
            $i++;
      
            $this_y=$pdf->getY();
            if($this_y >= 260):
	               $pdf->AddPage();
                   $y=7;
                   $x=7;
            endif;          
        endforeach;
 
        $pdf->Output("Laporan-Bulanan.pdf","I"); 
	}
    
    function se_excel()
    {
        $group = $this->session->userdata('se_group');
        $month = $this->session->userdata('se_month');
		$year  = $this->session->userdata('se_year');
        $pos   = $this->usergroup->getPositionData($group);
        $recs  = $this->authlog->getMonthRecords('','',$month,$year,$group);
        $days  = days_in_month($month);
		$excel = $this->excelModel->month_excel($recs,$month,$year,$group,$days,$pos,'Tanggal');
        $data = file_get_contents("assets/Lap-Pegawai-Khusus.xlsx"); // Read the file's contents
        force_download("Lap-Pegawai-Khusus",$data); 
    }
}