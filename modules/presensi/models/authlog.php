<?php

class Authlog extends CI_Model
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
    
    function getCountLog($id,$key,$date)
    {
        $this->db->where('UserID',$id); 
        $this->db->where('FunctionKey',$key);   
        $this->db->where("CONVERT(VARCHAR(10),TransactionTime, 105)='".$date."'");
        $query  = $this->db->get('NGAC_AUTHLOG');
        return $query->num_rows();
    }
    
    function getAuthlogData($id)
    {
        $this->db->select('IndexKey,CONVERT(VARCHAR(10),TransactionTime, 105) AS MyDate,CONVERT(VARCHAR(8),TransactionTime, 108) AS MyTime,FunctionKey,UserID');   
        $this->db->where("NGAC_AUTHLOG.IndexKey",$id);
        $query    = $this->db->get('NGAC_AUTHLOG',1);
        return $query->row_array();
    }
    
	function getDay($date_start='',$date_finish=''){
		$this->db->select('CONVERT(VARCHAR(10),TransactionTime, 105) as DAY');
        //$this->db->select('DATEPART(WEEK,CONVERT(VARCHAR(10),TransactionTime,105))-DATEPART(WEEK,(TransactionTime-DATEPART(day,TransactionTime)+1)) as W');
		$this->db->where("(TransactionTime >='".$date_start."') AND (TransactionTime <=DATEADD(day,1,'".$date_finish."'))");  
		$this->db->group_by('CONVERT(VARCHAR(10),TransactionTime,105)');
		$this->db->order_by('CONVERT(VARCHAR(10),TransactionTime,105)','ASC');
		$Q = $this->db->get('NGAC_AUTHLOG');
		return $Q->result_array();
	}
	
    function getAllRecords($offset='',$paging='',$name='',$key='',$date_start='',$date_finish=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('NGAC_AUTHLOG.UserID',$name ."");
            
            
        if (!empty($key))   
            $this->db->where('NGAC_AUTHLOG.FunctionKey',$key);   
        
        if (!empty($date_start))   
            $this->db->where("(TransactionTime >='".$date_start."') AND (TransactionTime <=DATEADD(day,1,'".$date_finish."'))");  
        
        $this->db->select('NGAC_AUTHLOG.IndexKey,NGAC_AUTHLOG.UserID,NGAC_USERINFO.Name,NGAC_AUTHLOG.FunctionKey,MIN(NGAC_AUTHLOG.TransactionTime) as TransactionTime');
        $this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_AUTHLOG.UserID');
        $this->db->where_not_in('NGAC_AUTHLOG.FunctionKey',0);
        $this->db->group_by('NGAC_AUTHLOG.IndexKey,NGAC_AUTHLOG.UserID,NGAC_USERINFO.UserOrder,NGAC_USERINFO.Name,NGAC_AUTHLOG.FunctionKey,CONVERT(VARCHAR(10),TransactionTime,105)');
        $Q = $this->db->get('NGAC_AUTHLOG');
        $this->db->order_by('ABS(NGAC_USERINFO.UserOrder)','ASC');
        return $Q->result_array();
    }
    
    function getPerMonthRecords($offset='',$paging='',$date_start='',$date_finish='',$group=''){
        if (empty($paging))
			$this->db->order_by('NGAC_USERINFO.UserOrder','ASC');	
		
		if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
			
		if (!empty($date_start))   
            $this->db->where("(TransactionTime >='".$date_start."') AND (TransactionTime <=DATEADD(day,1,'".$date_finish."'))");    
			
		if (!empty($group))   
            $this->db->where("NGAC_USERINFO.GroupID=".$group);
		$this->db->select('NGAC_AUTHLOG.UserID,NGAC_USERINFO.Name,NGAC_USERINFO.Department,NGAC_GROUP.Name as GroupName,MIN(NGAC_AUTHLOG.TransactionTime) as TransactionTime,MAX(NGAC_AUTHLOG.TransactionTime) as TransactionTimeMax,COUNT(DISTINCT(CONVERT(VARCHAR(10),TransactionTime, 105))) as Total ');
        $this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_AUTHLOG.UserID');
		$this->db->join('NGAC_GROUP','NGAC_GROUP.ID=NGAC_USERINFO.GroupID');
        $this->db->where_not_in('NGAC_USERINFO.Privilege',1);
        $this->db->where_not_in('datename(dw,TransactionTime)','Sunday');
        $this->db->where('AuthResult','0');
        $this->db->group_by('NGAC_AUTHLOG.UserID,NGAC_USERINFO.UserOrder,NGAC_USERINFO.Name,NGAC_USERINFO.Department,NGAC_GROUP.Name');
		$Q = $this->db->get('NGAC_AUTHLOG');
        return $Q->result_array();
    }
	
	function getUserTime($day,$user,$key){
		$this->db->select('CONVERT(VARCHAR(8),TransactionTime, 108) AS MyTime');
		$this->db->where("CONVERT(VARCHAR(10),TransactionTime,105)='".$day."'");
		$this->db->where('NGAC_AUTHLOG.FunctionKey',$key);
        $this->db->where('AuthResult','0');
		$this->db->where('NGAC_AUTHLOG.UserID',$user);
		$this->db->order_by('CONVERT(VARCHAR(10),TransactionTime,105)','ASC');
		$Q = $this->db->get('NGAC_AUTHLOG');
        $row = $Q->row_array();
		if ($row)
			$value = $row['MyTime'];
		else
			$value = '-';
		return $value;
	}
    
    function getPerWeekRecords($start='',$end='',$group='',$key=''){
        $this->db->select('NGAC_AUTHLOG.UserID,NGAC_USERINFO.GroupID,GroupWork,GroupFriday,NGAC_USERINFO.Name,datename(dw,TransactionTime) as DayName,NGAC_AUTHLOG.TransactionTime,CONVERT(VARCHAR(10),TransactionTime, 105) as MyDate,CONVERT(VARCHAR(8),TransactionTime, 108) AS MyTime');
        if (!empty($start))   
            $this->db->where("(TransactionTime >='".$start."') AND (TransactionTime <=DATEADD(day,1,'".$end."'))");  
           
		if (!empty($group))   
            $this->db->where("GroupID=".$group);
            
        if (!empty($key))   
            $this->db->where("FunctionKey=".$key);
        $this->db->where_not_in('NGAC_USERINFO.Privilege',1);
        $this->db->where('AuthResult','0');        	
        $this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_AUTHLOG.UserID');
        $this->db->order_by('UserID','ASC');
        $this->db->order_by('TransactionTime','ASC');
		$Q = $this->db->get('NGAC_AUTHLOG');
        return $Q->result_array();
    }
    
    function save()
    {
        $this->db->select('IndexKey');   
        $this->db->where("NGAC_USERINFO.ID",$this->input->post('user'));
        $query = $this->db->get('NGAC_USERINFO',1);
        $row = $query->row_array();
            
		$value = array(
                    'UserID'         =>  $this->input->post('user'),
                    'UserIDIndex'    =>  $row['IndexKey'], 
                    'FunctionKey'    =>  $this->input->post('key'),
                    'TerminalID'     =>  1,
                    'AuthResult'     =>  0,   
                    'TransactionTime'=>  $this->input->post('year').'-'. $this->input->post('month').'-'. $this->input->post('day').' '.$this->input->post('hour').':'. $this->input->post('minute').':'.$this->input->post('second'));
        $this->db->insert('NGAC_AUTHLOG',$value);
    }
    
    function update()
    {
        $this->db->select('IndexKey');   
        $this->db->where("NGAC_USERINFO.ID",$this->input->post('user'));
        $query = $this->db->get('NGAC_USERINFO',1);
        $row = $query->row_array();
            
		$id = $this->input->post('ID');
        $value = array(
                    'UserID'         =>  $this->input->post('user'),
                    'UserIDIndex'    =>  $row['IndexKey'], 
                    'FunctionKey'    =>  $this->input->post('key'),
                    'TerminalID'     =>  1,
                    'AuthResult'     =>  0,   
                    'TransactionTime'=>  $this->input->post('year').'-'. $this->input->post('month').'-'. $this->input->post('day').' '.$this->input->post('hour').':'. $this->input->post('minute').':'.$this->input->post('second'));
        $this->db->where('IndexKey',$id);
        $this->db->update('NGAC_AUTHLOG',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('IndexKey',$id);
        $this->db->delete('NGAC_AUTHLOG');
    }

}
