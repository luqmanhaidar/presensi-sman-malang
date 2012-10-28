<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presensi extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function getDataFromVariabel(){
		$data = array();
		$data[0] = 'None';
		$query    = $this->db->get('NGAC_VARIABEL');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['VariabelID']] = $row['VariabelName'];
           endforeach;  
		endif;		
		return $data;
	}
    
    function getCountVariabel($id)
    {
        $this->db->where('VariabelName',$id);   
        $query  = $this->db->get('NGAC_VARIABEL');
        return $query->num_rows();
    }
    
    function getVariabelData($id)
    {
        $this->db->select('*');   
        $this->db->where("NGAC_VARIABEL.VariabelID",$id);
        $query    = $this->db->get('NGAC_VARIABEL',1);
        return $query->row_array();
    }
    
    function getAllRecords($offset='',$paging='',$name=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('VariabelName',$name);
    
        $this->db->select('*'); 
        $this->db->order_by('VariabelID','ASC');
        $Q = $this->db->get('NGAC_VARIABEL');
        return $Q->result_array();
    }
    
    function save()
    {
		$value = array(
                    'VariabelName'          =>  $this->input->post('name'),
                    'VariabelDescription'   =>  $this->input->post('description'),
                    'VariabelType'          =>  $this->input->post('type'),
                    'VariabelValue'         =>  $this->input->post('value'));
        $this->db->insert('NGAC_VARIABEL',$value);
    }
    
    function update()
    {
		$id = $this->input->post('ID');
        $value = array(
                    'VariabelName'          =>  $this->input->post('name'),
                    'VariabelDescription'   =>  $this->input->post('description'),
                    'VariabelType'          =>  $this->input->post('type'),
                    'VariabelValue'         =>  $this->input->post('value'));
        $this->db->where('VariabelID',$id);
        $this->db->update('NGAC_VARIABEL',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('VariabelID',$id);
        $this->db->delete('NGAC_VARIABEL');
    }

}
