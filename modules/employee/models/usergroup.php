<?php

class Usergroup extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function getDataFromGroup(){
		$data = array();
		$data[0] = 'None';
		$query    = $this->db->get('NGAC_GROUP_DURATION');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['ID']] = $row['GroupDurationName'];
           endforeach;  
		endif;		
		return $data;
	}
    
    function getDataFromWorkGroup(){
		$data = array();
		$query    = $this->db->get('NGAC_GROUP_WORK');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['GroupWorkID']] = $row['GroupWorkName'].' ('.$row['GroupWorkStart'].' s/d '.$row['GroupWorkEnd'].')';
           endforeach;  
		endif;		
		return $data;
	}
    
    function getDataFromFridayGroup(){
		$data = array();
		$query    = $this->db->get('NGAC_GROUP_FRIDAY');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['GroupFridayID']] = $row['GroupFridayName'].' ('.$row['GroupFridayStart'].' s/d '.$row['GroupFridayEnd'].')';
           endforeach;  
		endif;		
		return $data;
	}
    
    function getCountGroup($id)
    {
        $this->db->join('NGAC_USERINFO U','U.GroupDurationID=G.ID','INNER'); 
        $this->db->where('G.ID',$id);   
        $query  = $this->db->get('NGAC_GROUP_DURATION G');
        return $query->num_rows();
    }
    
    function getGroupData($id)
    {
        $this->db->select('*');   
        $this->db->where("NGAC_GROUP_DURATION.ID",$id);
        $query    = $this->db->get('NGAC_GROUP_DURATION');
        return $query->row_array();
    }
    
    function getAllRecords($offset='',$paging='',$name=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('G.GroupDurationName',$name);
    
        $this->db->select('G.ID,G.GroupDurationName,G.Start,G.Finish'); 
        $this->db->order_by('ID','ASC');
        $Q = $this->db->get('NGAC_GROUP_DURATION G');
        return $Q->result_array();
    }
    
    function save()
    {
		$value = array(
                    'GroupDurationName'  =>  $this->input->post('name'),
                    'Start'              =>  $this->input->post('hour').':'. $this->input->post('minute'),
                    'Finish'             =>  $this->input->post('hour2').':'. $this->input->post('minute2'));
        $this->db->insert('NGAC_GROUP_DURATION',$value);
    }
    
    function update()
    {
		$id = $this->input->post('ID');
        $value = array(
                    'GroupDurationName'  =>  $this->input->post('name'),
                    'Start'              =>  $this->input->post('hour').':'. $this->input->post('minute'),
                    'Finish'             =>  $this->input->post('hour2').':'. $this->input->post('minute2'));
        $this->db->where('ID',$id);
        $this->db->update('NGAC_GROUP_DURATION',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('ID',$id);
        $this->db->delete('NGAC_GROUP_DURATION');
    }

}
