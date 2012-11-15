<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holidays extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function getDataFromHoliday(){
		$data = array();
		$data[0] = 'None';
		$query    = $this->db->get('NGAC_HOLIDAY');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['IndexKey']] = $row['HolidayDescription'];
           endforeach;  
		endif;		
		return $data;
	}
    
    function getCountHoliday($id)
    {
        $this->db->where('HolidayName',$id);   
        $query  = $this->db->get('NGAC_Holiday');
        return $query->num_rows();
    }
    
    function getHolidayData($id)
    {
        $this->db->select('IndexKey,CONVERT(VARCHAR(10),HolidayDate, 105) AS MyDate,HolidayType,HolidayDescription');   
        $this->db->where("NGAC_Holiday.IndexKey",$id);
        $query    = $this->db->get('NGAC_Holiday',1);
        return $query->row_array();
    }
    
    function getHolidayDate($date='',$month='',$year='')
    {
        $this->db->select('HolidayDate');
        if($date)   
            $this->db->where("CONVERT(VARCHAR(10),HolidayDate, 105)='".$date."'");
        if($month):
            $this->db->where("DATEPART(MONTH,HolidayDate)='".$month."'");
            $this->db->where("DATEPART(YEAR,HolidayDate)='".$year."'");
        endif;    
        $query    = $this->db->get('NGAC_HOLIDAY');
        return $query->num_rows();
    }
    
    function getAllRecords($offset='',$paging='',$type='',$date_start='',$date_finish='',$month='',$year=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if($type)
            $this->db->where("NGAC_Holiday.HolidayType",$type);
        
        if($date_start)
            $this->db->where("(HolidayDate >='".$date_start."') AND (HolidayDate <=DATEADD(day,0,'".$date_finish."'))");  
        
        if($month):
            $this->db->where("DATEPART(MONTH,HolidayDate)='".$month."'");
            $this->db->where("DATEPART(YEAR,HolidayDate)='".$year."'");
        endif;
        
        $this->db->select('IndexKey,HolidayType,HolidayDescription,CONVERT(VARCHAR(10),HolidayDate, 105) AS HolidayDate'); 
        $this->db->order_by('IndexKey','ASC');
        $Q = $this->db->get('NGAC_HOLIDAY');
        return $Q->result_array();
    }
    
    function save()
    {
        $date = $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year');    
		$value = array(
                    'HolidayType'          =>  $this->input->post('type'),
                    'HolidayDescription'   =>  $this->input->post('description'),
                    'HolidayDate'          =>  $date);
        $this->db->insert('NGAC_HOLIDAY',$value);
    }
    
    function update()
    {
		$id = $this->input->post('ID');
        $date = $this->input->post('month').'/'.$this->input->post('day').'/'.$this->input->post('year');  
        $value = array(
                    'HolidayType'          =>  $this->input->post('type'),
                    'HolidayDescription'   =>  $this->input->post('description'),
                    'HolidayDate'          =>  $date);
        $this->db->where('IndexKey',$id);
        $this->db->update('NGAC_HOLIDAY',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('IndexKey',$id);
        $this->db->delete('NGAC_HOLIDAY');
    }

}
