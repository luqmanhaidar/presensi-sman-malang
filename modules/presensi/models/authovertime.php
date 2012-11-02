<?php

class Authovertime extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function save($user,$date,$tm1,$tm2)
    {
		$value = array(
                    'UserID'              =>  $user,
                    'OvertimeDate'		  =>  $date,  
                    'OvertimeStart'       =>  $tm1,
                    'OvertimeEnd'     	 =>  $tm2);
        $this->db->insert('NGAC_AUTHOVERTIME',$value);
    }
   
    function getAuthProcessData($user,$date)
    {
        $this->db->select('UserID');   
        $this->db->where('UserID',$user);
        $this->db->where('CONVERT(VARCHAR(10),ProcessDateStart, 105)=',indonesian_shortDate($date)); 
        $query = $this->db->get('NGAC_AUTHPROCESS');
        return $query->row_array();
    }
    
    function getAllRecords($offset='',$paging='',$user='',$type='',$day=''){
        if (!empty($offset))
            $this->db->offset($offset);
        if (!empty($paging))    
            $this->db->limit($paging); 
        if (!empty($user))   
            $this->db->where('NGAC_AUTHOVERTIME.UserID',$user);
        if (!empty($day))   
            $this->db->where('CONVERT(VARCHAR(10),OvertimeDate, 105)=',$day);    
			
        $this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_AUTHOVERTIME.UserID'); 
		$this->db->select('NGAC_AUTHOVERTIME.UserID,CONVERT(VARCHAR(10),OvertimeDate,105) as MyDate,NGAC_USERINFO.Name,OvertimeStart,OvertimeEnd,OvertimeDuration,OvertimeNominal');
        //$this->db->select('NGAC_AUTHOVERTIME.UserID,NGAC_USERINFO.Name,NGAC_USERINFO.GroupID,CONVERT(VARCHAR(8),NGAC_AUTHOVERTIME.OvertimeStart,108) AS MyTimeStart,CONVERT(VARCHAR(8),NGAC_AUTHOVERTIME.OvertimeEnd, 108) AS MyTimeEnd');
        //$this->db->select('DATEPART(WEEK,AUTHOVERTIME.OvertimeDate)-DATEPART(WEEK,(AUTHOVERTIME.OvertimeDate-DATEPART(day,AUTHOVERTIME.OvertimeDate)+1)) as W'); 
		$this->db->where('DATEPART(WEEK,NGAC_AUTHOVERTIME.OvertimeDate)-DATEPART(WEEK,(NGAC_AUTHOVERTIME.OvertimeDate-DATEPART(day,NGAC_AUTHOVERTIME.OvertimeDate)+1))>=1');
        //$this->db->where('NGAC_USERINFO.privilege',2);
        $Q = $this->db->get('NGAC_AUTHOVERTIME');
        $this->db->order_by('NGAC_USERINFO.UserOrder','ASC');
		if($type=='row')
            return $Q->row_array();
        else
            return $Q->result_array();
    }
    
    function removeAll()
    {
        $this->db->where('UserID>=0');
        $this->db->delete('NGAC_AUTHOVERTIME');
    }
}    