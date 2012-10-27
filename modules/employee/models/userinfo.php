<?php

class Userinfo extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    /** Check User **/
    function checkUser($username,$password)
    {
        $this->db->where("Name",$username);
		$this->db->where("PasswordWeb",$password);
        $this->db->where('Privilege',1);
        $query    = $this->db->get('NGAC_USERINFO');
        return $query->num_rows();
    }
    
    function getUserData($username)
    {
        $this->db->select('NGAC_USERINFO.ID as ID,NGAC_USERINFO.Name as Name,NGAC_GROUP.Name as GroupName');   
		$this->db->where("NGAC_USERINFO.Name",$username);
        $this->db->join('NGAC_GROUP','NGAC_GROUP.ID=NGAC_USERINFO.GroupID','LEFT');
        $query    = $this->db->get('NGAC_USERINFO');
        if ($query->num_rows() > 0):
            $data = $query->row_array();
        endif;
        $query->free_result();
        return $data;
    }
    
    function getAllRecords(){
        $this->db->select('*');
        $Q = $this->db->get('NGAC_USERINFO',10);
        return $Q->result_array();
    }

    
}
