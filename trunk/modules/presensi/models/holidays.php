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
        $this->db->select('*');   
        $this->db->where("NGAC_Holiday.HolidayID",$id);
        $query    = $this->db->get('NGAC_Holiday',1);
        return $query->row_array();
    }
    
    function getHolidayDataByVar($var)
    {
        $this->db->select('HolidayValue');   
        $this->db->where("NGAC_Holiday.HolidayName",$var);
        $query    = $this->db->get('NGAC_Holiday',1);
        $val=$query->row_array();
        return $val['HolidayValue'];
    }
    
    function getAllRecords($offset='',$paging='',$name=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('HolidayName',$name);
    
        $this->db->select('*'); 
        $this->db->order_by('HolidayID','ASC');
        $Q = $this->db->get('NGAC_Holiday');
        return $Q->result_array();
    }
    
    function save()
    {
		$value = array(
                    'HolidayName'          =>  $this->input->post('name'),
                    'HolidayDescription'   =>  $this->input->post('description'),
                    'HolidayType'          =>  $this->input->post('type'),
                    'HolidayValue'         =>  $this->input->post('value'));
        $this->db->insert('NGAC_Holiday',$value);
    }
    
    function update()
    {
		$id = $this->input->post('ID');
        $value = array(
                    'HolidayName'          =>  $this->input->post('name'),
                    'HolidayDescription'   =>  $this->input->post('description'),
                    'HolidayType'          =>  $this->input->post('type'),
                    'HolidayValue'         =>  $this->input->post('value'));
        $this->db->where('HolidayID',$id);
        $this->db->update('NGAC_Holiday',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('HolidayID',$id);
        $this->db->delete('NGAC_Holiday');
    }

}
