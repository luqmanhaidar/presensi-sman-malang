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
        $data['groups']	= $this->usergroup->getDataFromPosition();
        if($this->session->userdata('month_paging'))
            $paging = $this->session->userdata('month_paging');
        else
            $paging = config_item('paging');
        if($this->session->userdata('month_group')):
			$group = $this->session->userdata('month_group');
            $start = $this->session->userdata('month_start');
			$end   = $this->session->userdata('month_finish');
        else:
			$group = '';
			$start = '';
			$end   = '';
        endif;           
        $this->session->set_userdata('month_offset',$offset);
        $data['checks']  = $this->authlog->getPerMonthRecords($offset,$paging,$start,$end,$group);
        $numrows = COUNT($this->authlog->getPerMonthRecords('','',$start,$end,$group)); 
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
						 'month_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'month_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));    
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
		$data['title']		=	'Laporan Presensi Bulanan Periode  '.$this->session->userdata('month_start').' s/d '.$this->session->userdata('month_finish');
		$data['position']	=	$this->usergroup->getPositionData($this->session->userdata('month_group'));
		$data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_start'),$this->session->userdata('month_finish'),$this->session->userdata('month_group'));
        $data['days']	    =  $this->authlog->getDay($this->session->userdata('month_start'),$this->session->userdata('month_finish'));
		$this->load->vars($data);
        $this->load->theme('report/month',$data);
	}
	
	function month_pdf()
    {
		$this->load->library('pdf');
		$data['title']		=	'Laporan Presensi Bulan '.indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search');
        $data['checks']		=	$this->authlog->getPerMonthRecords('','',$this->session->userdata('month_search'),$this->session->userdata('year_search'),$this->session->userdata('month_group'));
		$this->load->vars($data);
        $file = $this->load->theme('report/month',$data,TRUE);
		$this->pdf->pdf_create($file,$data['title']);
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
		
        $this->session->set_userdata('week_offset',$offset);
        $data['checks']  = $this->authprocess->getAllRecords($offset,$paging);
        $numrows = COUNT($this->authprocess->getAllRecords()); 
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
        $search = array ('week_group'  => $this->input->post('group'),
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
                if($row['GroupID']<=2):
                    $sk  =  $this->usergroup->getGroupWorkData($row['GroupWork']);
                    $jm  =  $this->usergroup->getGroupFridayData($row['GroupFriday']);
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
                
                $wm = (substr($row['MyTime'],0,2) * 3600) + (substr($row['MyTime'],3,2)*60) + (substr($row['MyTime'],6,2));
                
                if(($row['DayName']=='Saturday')):
                    $wmk = $sd1;
                    $dt  = $dt1;
                    $sk  = $sk1;
                    $wsk = $w1; // Wsk Senin-Kamis
                    $wsp = $wp1;
                elseif($row['DayName']=='Sunday'):
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
                elseif(($row['GroupID']>=3)):
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
                else:
                    $d = "-";
                    $late = "-";
                endif;          
                $this->authprocess->save($row['UserID'],$row['TransactionTime'],$wmk,$late);
            endif;   
        endforeach;
        
        /** Query Update ke AuthProcess **/
        $query = $this->authlog->getPerWeekRecords($start,$end,$group,2);
        foreach($query as $row):
            $ws = (substr($row['MyTime'],0,2) * 3600) + (substr($row['MyTime'],3,2)*60) + (substr($row['MyTime'],6,2));
            if($row['GroupID']<=2):
                $sk  =  $this->usergroup->getGroupWorkData($row['GroupWork']);
                $jm  =  $this->usergroup->getGroupFridayData($row['GroupFriday']);
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
                            
            if(($row['DayName']=='Saturday')):
                $wmk = $sd1;
                $dt  = $dt1;
                $sk  = $sk1;
                $wsk = $w1; // Wsk Senin-Kamis
                $wsp = $wp1;
            elseif($row['DayName']=='Sunday'):
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
            elseif(($row['GroupID']>=3)):
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
            $this->authprocess->update($row['UserID'],$row['MyDate'],$row['TransactionTime'],$wsk,$early);
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
		$data['title']		=	'DAFTAR CEK CLOCK';
		$data['days']	    =   $this->authlog->getDay($this->session->userdata('week_start'),$this->session->userdata('week_finish'));
		$data['periode']	=	'Periode '.$this->session->userdata('week_start'). ' s/d '. $this->session->userdata('week_finish');
		$data['users']	    =	$this->userinfo->getAllRecords('','','','',$this->session->userdata('week_group'));
		$data['var']	    =	$this->presensi->getVariabelDataByVar('DMK');
        $this->load->vars($data);
        $this->load->theme('report/week',$data);
	}
	
	function overtime($offset=0){
        $data['title']  = 'Laporan Lemburan';
        $data['logs']   =   $this->log->userLog();
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
        /** Query Insert ke AuthProcess **/
        $query = $this->overtimes->getAllRecords('','','',$start,$end,$group);
        /** masuk kerja senin-kamis & sabtu I**/
        //$sk1 = (6 * 3600) + (30*60) + (0);
        //$dt1 = (6 * 3600) + (45*60) + (0); // telat 15 menit
        //$sd1 = '06:30:00';
        /** masuk kerja senin-kamis II **/
        //$sk2 = (7 * 3600) + (0*60) + (0);
        //$dt2 = (7 * 3600) + (15*60) + (0); // telat 15 menit
        //$sd2 = '07:00:00';
        /** pulang kerja sabtu **/
        //$wp1 =  (12 * 3600) + (30*60) + (0);
        //$w1  =  '12:30:00';
        /** pulang kerja senin-kamis **/                    
        //$wp2 =  (14 * 3600) + (0*60) + (0);
        //$w2  =  '14:00:00';
        /** pulang kerja senin-kamis **/                      
        //$wp3 =  (14 * 3600) + (30*60) + (0);
        //$w3  =  '14:30:00'; 
        /** pulang kerja senin-kamis **/                      
        //$wp4 =  (15 * 3600) + (0*60) + (0);
        //$w4  =  '15:00:00';
        /** pulang kerja senin-kamis **/                      
        //$wp5 =  (15 * 3600) + (30*60) + (0);
        //$w5  =  '15:30:00';
                    
        foreach($query as $row):   
            //if(COUNT($this->authprocess->getAuthProcessData($row['UserID'],$row['TransactionTime']))== 0):
                //if($row['GroupID']<=2):
                  //  $sk  =  $this->usergroup->getGroupWorkData($row['GroupWork']);
                   // $jm  =  $this->usergroup->getGroupFridayData($row['GroupFriday']);
                   // if($sk):
                    //    $dbSkStart = $sk['GroupWorkStart'];
                    //    $dbSpStart = (substr($sk['GroupWorkStart'],0,2) * 3600) + (substr($sk['GroupWorkStart'],3,2)*60) + (substr($sk['GroupWorkStart'],6,2));
                    //    $dbSpWork  = (substr($sk['GroupWorkStart'],0,2) * 3600) + ((substr($sk['GroupWorkStart'],3,2)+15)*60) + (substr($sk['GroupWorkStart'],6,2));   
                    //    $dbSkEnd   = $sk['GroupWorkEnd'];
                    //    $dbSpEnd   = (substr($sk['GroupWorkEnd'],0,2) * 3600) + (substr($sk['GroupWorkEnd'],3,2)*60) + (substr($sk['GroupWorkEnd'],6,2));
                    //else:
                    //    $dbSkStart = $sd1;
                    //    $dbSpStart = $sk1;
                    //    $dbSpWork  = $dt1;
                    //    $dbSkEnd   = $w3;
                    //    $dbSpEnd   = $wp3; 
                    //endif;
                //endif;    
                
                //$wm = (substr($row['MyTime'],0,2) * 3600) + (substr($row['MyTime'],3,2)*60) + (substr($row['MyTime'],6,2));
                
                //if(($row['DayName']=='Saturday')):
                //    $wmk = $sd1;
                //    $dt  = $dt1;
                //    $sk  = $sk1;
                //    $wsk = $w1; // Wsk Senin-Kamis
                //    $wsp = $wp1;
                //elseif($row['DayName']=='Sunday'):
                //    $wmk = '';
                //    $dt  = 0;
                //    $sk  = 0;
                //    $wsk = ''; // Wsk Senin-Kamis
                //    $wsp = 0;
                //elseif(($row['DayName']<>'Saturday') && ($wm >= $sk1)  && ( ($row['GroupID']==1) || ($row['GroupID']==2) ) ):
                //    $wmk = $sd2;
                //    $dt  = $dt2;
                //    $sk  = $sk2;
                //    if($row['DayName']<>"Friday"):
                //        $wsk = $w5; // Wsk Senin-Kamis
                //        $wsp = $wp5;
                //    else:
                //        $wsk = $w4; // Wsk Jumat
                //        $wsp = $wp4;
                //    endif;
                //elseif(($row['DayName']<>'Saturday') && ($wm <= $sk1)  && ( ($row['GroupID']==1) || ($row['GroupID']==2) ) ):
               //     $wmk = $sd1;
                //    $dt  = $dt1;
                //    $sk  = $sk1;
                //    if($row['DayName']<>"Friday"):
                //        $wsk = $w3; // Wsk Senin-Kamis
                 //       $wsp = $wp3;
                 //   else:
                 //       $wsk = $w2; // Wsk Jumat
                 //       $wsp = $wp2;
                 //       $row['TransactionTime'] = substr($row['TransactionTime'],0,11)." 06:30:00";
                 //   endif;                  
               //elseif( ( ($row['GroupID']==1) || ($row['GroupID']==2) )):
                //    $wmk = $sd1;
                //    $dt  = $dt1;
                //    $sk  = $sk1;
                //    if($row['DayName']<>"Friday"):
                 //       $wsk = $w3;
                 //       $wsp = $wp3;
                 ///   else:
                 //       $wsk = $w2; 
                  //      $wsp = $wp2;
                  //  endif;
                //elseif(($row['GroupID']>=3)):
                //    $wmk = $dbSkStart;
                //    $dt  = $dbSpWork;
                //    $sk  = $dbSpStart;
                //    if($row['DayName']<>"Friday"):
                //        $wsk = $dbSkEnd;
                //        $wsp = $dbSpEnd;
                //    else:
                //        $wsk = $w2; 
                //        $wsp = $wp2;
                 //   endif;
                //endif; 
                
                //if (($wm > $dt) && ($row['DayName']<>'Sunday')):
                //    $d = $wm - $sk;
                //    $hours = code(floor($d / 3600));
                //    $mins = code(floor(($d - ($hours*3600)) / 60));
                //    $seconds = code($d % 60);
                //    $late = $hours.':'.$mins.':'.$seconds;     
               // else:
               //     $d = "-";
               //     $late = "-";
               // endif;          
			$tm1 = $this->authlog->getUserTime($row['MyDate'],$row['UserID'],'3');	
			$tm2 = $this->authlog->getUserTime($row['MyDate'],$row['UserID'],'4');
            $this->authovertime->save($row['UserID'],$row['OvertimeDate'],$tm1,$tm2);
            //endif;   
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
		$data['checks'] =  $this->authlog->getPerMonthRecords('','',$this->session->userdata('eat_start'),$this->session->userdata('eat_finish'),$this->session->userdata('eat_group'));
        $this->load->vars($data);
        $this->load->theme('report/payroll-1',$data);
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
            $start = $this->session->userdata('se_start');
			$end   = $this->session->userdata('se_finish');
        else:
			$group = '';
            $start = '';
			$end   = '';
        endif;           
        $this->session->set_userdata('se_offset',$offset);
        $data['checks']  = $this->authlog->getPerMonthRecords($offset,$paging,$start,$end,$group);
        $numrows = COUNT($this->authlog->getPerMonthRecords('','',$start,$end,$group)); 
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
        $search = array ('se_group'  => $this->input->post('group'),
						 'se_start'  => $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year'), 
                         'se_finish' => $this->input->post('month2').'/'.$this->input->post('day2').'/'.$this->input->post('year2'));     
        $this->session->set_userdata($search);
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
		$data['title']		=	'Laporan Presensi Pegawai Khusus Periode  '.$this->session->userdata('se_start').' s/d '.$this->session->userdata('se_finish');
		$data['position']	=  $this->usergroup->getPositionData($this->session->userdata('se_group'));
		$data['checks']		=  $this->authlog->getPerMonthRecords('','',$this->session->userdata('se_start'),$this->session->userdata('se_finish'),$this->session->userdata('se_group'));
        $data['days']	    =  $this->authlog->getDay($this->session->userdata('se_start'),$this->session->userdata('se_finish'));
        $this->load->vars($data);
        $this->load->theme('report/se',$data);
	}
}