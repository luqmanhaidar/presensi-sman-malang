<?php

class Overtimes extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function getDataFromOvertime(){
		$data = array();
		$data[0] = 'None';
		$query    = $this->db->get('NGAC_OVERTIME');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['OvertimeID']] = $row['UserID'].'-'.$row['OvertimeDate'];
           endforeach;  
		endif;		
		return $data;
	}
    
    function getCountOvertime($id,$date)
    {
        $this->db->where('UserID',$id);   
        $this->db->where("CONVERT(VARCHAR(10),OvertimeDate, 105)='".$date."'");
        $query  = $this->db->get('NGAC_OVERTIME');
        return $query->num_rows();
    }
    
    function getOvertimeData($id)
    {
        $this->db->select('OvertimeID,UserID,CONVERT(VARCHAR(10),OvertimeDate, 105) AS MyDate,OvertimeDescription');   
        $this->db->where("NGAC_OVERTIME.OvertimeID",$id);
        $query    = $this->db->get('NGAC_OVERTIME',1);
        return $query->row_array();
    }
    
    function getAllRecords($offset='',$paging='',$name='',$date_start='',$date_finish=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('UserID',$name);
            
        if (!empty($date_start))   
            //$this->db->where('CONVERT(VARCHAR(10),TransactionTime, 105)>=',$date_start);
            $this->db->where("OvertimeDate >='".$date_start."' AND OvertimeDate <='".$date_finish."' ");  
        
        //if (!empty($date_finish))   
            //$this->db->where('CONVERT(VARCHAR(10),TransactionTime, 105)<=',$date_finish);          
        $this->db->order_by('OvertimeDate','DESC');
        $this->db->order_by('UserID','ASC');
        $this->db->where_not_in('UserID','');
        $Q = $this->db->get('NGAC_OVERTIME');
        return $Q->result_array();
    }
    
    function save()
    {
		$value = array(
                    'UserID'              =>  $this->input->post('user'),
                    'OvertimeDate'        =>  $this->input->post('year').'-'. $this->input->post('month').'-'. $this->input->post('day'),
                    'OvertimeDescription' =>  $this->input->post('description'));
        $this->db->insert('NGAC_OVERTIME',$value);
    }

    
    function update()
    {
		$id = $this->input->post('ID');
        $value = array(
                    'UserID'              =>  $this->input->post('user'),
                    'OvertimeDate'        =>  $this->input->post('year').'-'. $this->input->post('month').'-'. $this->input->post('day'),
                    'OvertimeDescription' =>  $this->input->post('description'));
        $this->db->where('OvertimeID',$id);
        $this->db->update('NGAC_OVERTIME',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('OvertimeID',$id);
        $this->db->delete('NGAC_OVERTIME');
    }

}
