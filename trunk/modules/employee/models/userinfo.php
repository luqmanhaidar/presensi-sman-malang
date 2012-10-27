<?php

class Userinfo extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    /** Check User **/
    function checkUser($id,$password)
    {
        $this->db->where("ID",$id);
		$this->db->where("PasswordWeb",$password);
        $this->db->where('Privilege',1);
        $query    = $this->db->get('NGAC_USERINFO');
        return $query->num_rows();
    }
    
    function getUserData($id)
    {
        $this->db->select('NGAC_USERINFO.ID as ID,NGAC_USERINFO.Name as Name,NGAC_GROUP.Name as GroupName');   
		$this->db->where("NGAC_USERINFO.ID",$id);
        $this->db->join('NGAC_GROUP','NGAC_GROUP.ID=NGAC_USERINFO.GroupID','LEFT');
        $query    = $this->db->get('NGAC_USERINFO');
        if ($query->num_rows() > 0):
            $data = $query->row_array();
        endif;
        $query->free_result();
        return $data;
    }
    
    function getAllRecords($offset='',$paging='',$name=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('Name',$name);
        $this->db->order_by('ID','ASC');
        $Q = $this->db->get('NGAC_USERINFO');
        return $Q->result_array();
    }

    
}
