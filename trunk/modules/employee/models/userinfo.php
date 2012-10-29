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
    
    function getDataFromUser(){
		$data = array();
		$query    = $this->db->get('NGAC_USERINFO');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['ID']] = $row['ID'].'-'.$row['Name'];
           endforeach;  
		endif;		
		return $data;
	}
    
    function getSingleDataFromUser(){
		$data = array();
		$query    = $this->db->get('NGAC_USERINFO');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['ID']] = $row['ID'];
           endforeach;  
		endif;		
		return $data;
	}
    
    
    function getUserData($id)
    {
        $this->db->select('NGAC_USERINFO.ID as ID,NGAC_USERINFO.Name as Name,NGAC_USERINFO.GroupDurationID,NGAC_GROUP_DURATION.GroupDurationName as GroupDurationName,PasswordWeb,GroupWork,GroupFriday');   
        $this->db->join('NGAC_GROUP','NGAC_GROUP.ID=NGAC_USERINFO.GroupID','LEFT');
        $this->db->join('NGAC_GROUP_DURATION','NGAC_GROUP_DURATION.ID=NGAC_USERINFO.GroupDurationID','LEFT');
        $this->db->where("NGAC_USERINFO.ID",$id);
        $query    = $this->db->get('NGAC_USERINFO');
        return $query->row_array();
    }
    
    function getAllRecords($offset='',$paging='',$name='',$group=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('U.Name',$name);
        if ($group <> 0)   
            $this->db->where('U.GroupDurationID',$group);
                
        $this->db->select('U.ID,U.Name,G.GroupDurationName,W.GroupWorkName,F.GroupFridayName');
        $this->db->join('NGAC_GROUP_DURATION G','G.ID=U.GroupDurationID','LEFT');   
        $this->db->join('NGAC_GROUP_WORK W','W.GroupWorkID=U.GroupWork','LEFT');  
        $this->db->join('NGAC_GROUP_FRIDAY F','F.GroupFridayID=U.GroupFriday','LEFT');
        $this->db->order_by('ID','ASC');
        $Q = $this->db->get('NGAC_USERINFO U');
        return $Q->result_array();
    }
	
	function getAdminRecords($offset='',$paging='',$name='',$group=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('U.Name',$name);
        if ($group <> 0)   
            $this->db->where('U.GroupDurationID',$group);
        
		$this->db->where('U.privilege',1);        
        $this->db->select('U.ID,U.Name,G.GroupDurationName,U.PasswordWeb');
        $this->db->join('NGAC_GROUP_DURATION G','G.ID=U.GroupDurationID','LEFT');    
        $this->db->order_by('ID','ASC');
        $Q = $this->db->get('NGAC_USERINFO U');
        return $Q->result_array();
    }
    
    function update()
    {
		$id = $this->input->post('ID');
        $data['GroupDurationID'] = $this->input->post('Group');
        $data['GroupWork'] = $this->input->post('Work');
        $data['GroupFriday'] = $this->input->post('Friday');
        $this->db->where('ID',$id);
        $this->db->update('NGAC_USERINFO',$data);
    }
	
	function updatePassword()
    {
		$id = $this->input->post('ID');
        $data['PasswordWeb'] = sha1($this->input->post('password'));
        $this->db->where('ID',$id);
        $this->db->update('NGAC_USERINFO',$data);
    }
    
}
