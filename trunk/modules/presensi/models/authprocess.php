<?php

class Authprocess extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function save($user,$date)
    {
		$value = array(
                    'UserID'              =>  $user,
                    'ProcessDateStart'    =>  $date);
        $this->db->insert('NGAC_AUTHPROCESS',$value);
    }
    
    function update($user,$dateStart,$dateEnd)
    {
        $value['ProcessDateEnd'] = $dateEnd;
        $this->db->where('UserID',$user);
        $this->db->where('CONVERT(VARCHAR(10),ProcessDateStart, 105)=',$dateStart);           
        $this->db->update('NGAC_AUTHPROCESS',$value);
    }
    
    function getAllRecords($offset='',$paging=''){
        if (!empty($offset))
            $this->db->offset($offset);
        if (!empty($paging))    
            $this->db->limit($paging);    
        $this->db->select('UserID,Name,datename(dw,ProcessDateStart) as DayName,NGAC_USERINFO.GroupID,GroupWork,GroupFriday,CONVERT(VARCHAR(10),ProcessDateStart, 105) as MyDate,CONVERT(VARCHAR(8),ProcessDateStart, 108) AS MyTimeStart,CONVERT(VARCHAR(8),ProcessDateEnd, 108) AS MyTimeEnd');
        $this->db->select('DATEPART(WEEK,ProcessDateStart)-DATEPART(WEEK,(ProcessDateStart-DATEPART(day,ProcessDateStart)+1)) as W');
        $this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_AUTHPROCESS.UserID');
        $this->db->join('NGAC_GROUP_WORK','NGAC_GROUP_WORK.GroupWorkID=NGAC_USERINFO.GroupWork','LEFT');
        $this->db->join('NGAC_GROUP_FRIDAY','NGAC_GROUP_FRIDAY.GroupFridayID=NGAC_USERINFO.GroupFriday','LEFT');
        $this->db->where('DATEPART(WEEK,ProcessDateStart)-DATEPART(WEEK,(ProcessDateStart-DATEPART(day,ProcessDateStart)+1))>=1');
        $Q = $this->db->get('NGAC_AUTHPROCESS');
        return $Q->result_array();
    }
    
    function removeAll()
    {
        $this->db->where('UserID>=0');
        $this->db->delete('NGAC_AUTHPROCESS');
    }
}    