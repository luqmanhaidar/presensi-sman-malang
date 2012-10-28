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

    
}
