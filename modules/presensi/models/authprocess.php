<?php

class Authprocess extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function save($user,$date,$wmk,$late)
    {
		$value = array(
                    'UserID'              =>  $user,
                    'ProcessDateWorkStart'=>  $wmk,  
                    'ProcessDateStart'    =>  $date,
                    'ProcessDateLate'     =>  $late);
        $this->db->insert('NGAC_AUTHPROCESS',$value);
    }
    
    function update($user,$dateStart,$dateEnd,$wsk,$early)
    {
        $value = array(
                    'UserID'              =>  $user,
                    'ProcessDateWorkEnd'  =>  $wsk,  
                    'ProcessDateEnd'      =>  $dateEnd,
                    'ProcessDateEarly'    =>  $early);
        $this->db->where('UserID',$user);
        $this->db->where('CONVERT(VARCHAR(10),ProcessDateStart, 105)=',$dateStart);           
        $this->db->update('NGAC_AUTHPROCESS',$value);
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
            $this->db->where('NGAC_AUTHPROCESS.UserID',$user);
        if (!empty($day))   
            $this->db->where('CONVERT(VARCHAR(10),ProcessDateStart, 105)=',$day);    
                   
        $this->db->select('UserID,Name,datename(dw,ProcessDateStart) as DayName,NGAC_USERINFO.GroupID,GroupWork,GroupFriday,CONVERT(VARCHAR(10),ProcessDateStart, 105) as MyDate,CONVERT(VARCHAR(8),ProcessDateStart, 108) AS MyTimeStart,CONVERT(VARCHAR(8),ProcessDateEnd, 108) AS MyTimeEnd');
        $this->db->select('ProcessDateWorkStart,ProcessDateWorkEnd,ProcessDateLate,ProcessDateEarly');
        $this->db->select('DATEPART(WEEK,ProcessDateStart)-DATEPART(WEEK,(ProcessDateStart-DATEPART(day,ProcessDateStart)+1)) as W');
        $this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_AUTHPROCESS.UserID');
        $this->db->join('NGAC_GROUP_WORK','NGAC_GROUP_WORK.GroupWorkID=NGAC_USERINFO.GroupWork','LEFT');
        $this->db->join('NGAC_GROUP_FRIDAY','NGAC_GROUP_FRIDAY.GroupFridayID=NGAC_USERINFO.GroupFriday','LEFT');
        $this->db->where('DATEPART(WEEK,ProcessDateStart)-DATEPART(WEEK,(ProcessDateStart-DATEPART(day,ProcessDateStart)+1))>=1');
        $this->db->where('NGAC_USERINFO.privilege',2);
        $Q = $this->db->get('NGAC_AUTHPROCESS');
        if($type=='row')
            return $Q->row_array();
        else
            return $Q->result_array();
    }
    
    function removeAll()
    {
        $this->db->where('UserID>=0');
        $this->db->delete('NGAC_AUTHPROCESS');
    }
}    