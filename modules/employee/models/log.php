<?php

class Log extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function log()
    {
        $val=array();
        $this->db->select('CONVERT(VARCHAR(10),LogTransactionDate,103) AS LogDate,logName');
        $query = $this->db->get('NGAC_LOG');
        $Q = $this->db->query($sql);
        if ($Q->num_rows() > 0):
            foreach ($Q->result_array() as $row):
                $val[] = $row;
            endforeach;
        endif;
        return $val;    
    }
    
    function save($user,$log='')
    {
        $val = array(   'LogTransactionDate'=> date('Y-m-d H:i:s'), 
                        'LogName'           => $user,
				        'LogDescription' 	=> $log);
        $this->db->insert('NGAC_LOG',$val);         
    }
    
}
