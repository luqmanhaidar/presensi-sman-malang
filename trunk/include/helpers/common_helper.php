<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Auth UserInfo **/
function is_userInfo()
{
	$CI =& get_instance();
	$CI->load->library('session');
	return $CI->session->userdata('user');
}

function is_userID()
{
	$CI =& get_instance();
	$CI->load->library('session');
	return $CI->session->userdata('userID');
}

/** Auth GroupInfo **/
function is_groupInfo()
{
	$CI =& get_instance();
	$CI->load->library('session');
	return $CI->session->userdata('group');
}

function is_message_loginErr(){
    $CI =& get_instance();
    $CI->load->library('session');    
    return $CI->session->set_flashdata('error_login','<div class="alert alert-info alert-login">Anda tidak berhak mengakses ke dalam Sistem</div>');
}

function indonesian_longDate($date)
{  
     $day = substr($date,8,2);  
     $month = substr($date,5,2);	 
     $year = substr($date,0,4);

     return	$day." " .indonesian_monthName($month)." " .$year;        
}

function indonesian_shortDate($tgl,$format='')
{  
     $tanggal = substr($tgl,8,2);  
     $bulan = substr($tgl,5,2);	 
     $tahun = substr($tgl,0,4);
	 if($format)
		$data	= $tanggal.$format.$bulan.$format.$tahun;  
	 else 
		$data	= $tanggal.'-'.$bulan.'-'.$tahun;  
     return	$data;        
}

function indonesian_monthName($month)
{  
    switch ($month){  
        case '01':   
            return "Januari";  
            break;  
        case '02':  
            return "Februari";  
            break;  
        case '03':  
            return "Maret";  
            break;  
        case '04':  
            return "April";  
            break;  
        case '05':  
            return "Mei";  
            break;  
        case '06':  
            return "Juni";  
            break;  
        case '07':  
            return "Juli";  
            break;  
        case '08':  
            return "Agustus";  
            break;  
        case '09':  
            return "September";  
            break;  
        case '10':  
            return "Oktober";  
            break;  
        case '11':  
            return "November";  
            break;  
        case '12':  
            return "Desember";  
            break;  
    }  
}


function functionKey($key)
{  
    switch ($key){  
        case 1:   
            return "Jam Masuk";  
            break;  
        case 2:  
            return "Jam Pulang";  
            break;  
        case 3:  
            return "Mulai Lembur";  
            break;  
        case 4:  
            return "Selesai Lembur";  
            break;    
    }  
}