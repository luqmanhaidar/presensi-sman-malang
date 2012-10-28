<?php

class Log extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function userLog()
    {
        $val=array();
        $query=$this->db->query('SELECT TOP 10 LogName,CONVERT(VARCHAR(10),LogTransactionDate, 105) AS MyDate,CONVERT(VARCHAR(8),LogTransactionDate, 108) AS MyTime FROM NGAC_LOG ORDER BY LogTransactionDate DESC');
        /**
        $this->db->select('1TOP 1000 *');
        $this->db->limit(100);
        $query = $this->db->get('NGAC_LOG');
        **/
        if ($query->num_rows() > 0):
            foreach ($query->result_array() as $row):
                $val[] = $row;
            endforeach;
        endif;
        return $val;    
    }
	
	function getAllRecords($offset='',$paging='',$name=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('U.LogName',$name);
                
        $this->db->select('LogID,LogName,G.GroupDurationName,CONVERT(VARCHAR(10),LogTransactionDate, 105) AS LogDate,CONVERT(VARCHAR(8),LogTransactionDate, 108) AS LogTime,LogDescription');
        $this->db->order_by('LogID','DESC');
        $Q = $this->db->get('NGAC_LOG');
        return $Q->result_array();
    }
    
    function save($user,$log='')
    {
        $val = array(   'LogTransactionDate'=> date('Y-m-d H:i:s'), 
                        'LogName'           => $user,
				        'LogDescription' 	=> $log);
        $this->db->insert('NGAC_LOG',$val);         
    }
    
}
