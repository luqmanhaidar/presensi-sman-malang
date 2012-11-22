<?php

class Others extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function getDataFromOther(){
		$data = array();
		$data[0] = 'None';
		$query    = $this->db->get('NGAC_OTHER');
		if ($query->num_rows() > 0):
           foreach ($query->result_array() as $row):
             $data[$row['OtherID']] = $row['UserID'].'-'.$row['OtherDateStart'];
           endforeach;  
		endif;		
		return $data;
	}
    
    function getUserTime($user,$month,$year,$type){
        $this->db->where('UserID',$user);
       if (!empty($month))   
            $this->db->where("DATEPART(MONTH,OtherDateStart)='".$month."'");  
            
        if (!empty($year))   
            $this->db->where("DATEPART(YEAR,OtherDateStart)='".$year."'"); 
        $this->db->where('OtherType',$type);
        $Q=$this->db->get('NGAC_OTHER'); 
        return $Q->num_rows(); 
    }
    
    function getCountOther($id,$date)
    {
        $this->db->where('UserID',$id);   
        $this->db->where("CONVERT(VARCHAR(10),OtherDateStart, 105)='".$date."'");
        $query  = $this->db->get('NGAC_OTHER');
        return $query->num_rows();
    }
    
    function getOtherData($id)
    {
        $this->db->select('OtherID,UserID,OtherType,CONVERT(VARCHAR(10),OtherDateStart, 105) AS OtherDateStart,CONVERT(VARCHAR(10),OtherDateFinish, 105) AS OtherDateFinish,OtherDescription');   
        $this->db->where("NGAC_OTHER.OtherID",$id);
        $query    = $this->db->get('NGAC_OTHER',1);
        return $query->row_array();
    }
    
    function getAllRecords($offset='',$paging='',$name='',$type='',$date_start='',$date_finish='',$user=''){
        if (!empty($offset))
            $this->db->offset($offset);
        
        if (!empty($paging))    
            $this->db->limit($paging);
        
        if (!empty($name))   
            $this->db->like('UserID',$name);
        
        if (!empty($user))   
            $this->db->where('UserID',$user);    
        
        if (!empty($type))   
            $this->db->where('OtherType',$type);    
        if (!empty($date_start))   
            $this->db->where("(OtherDateStart >='".$date_start."') AND (OtherDateStart <=DATEADD(day,1,'".$date_finish."'))");  
             
        $this->db->select('DATEPART(DAY,OtherDateStart) AS DAY,DATEPART(MONTH,OtherDateStart) AS MONTH,DATEPART(YEAR,OtherDateStart) AS YEAR,*');
        $this->db->select('DATEPART(WEEK,OtherDateStart)-DATEPART(WEEK,(OtherDateStart-DATEPART(DAY,OtherDateStart)+1)) as W');
		$this->db->join('NGAC_USERINFO','NGAC_USERINFO.ID=NGAC_OTHER.UserID');
        $this->db->order_by('OtherDateStart','ASC');
        $this->db->order_by('UserID','ASC');
        $this->db->where_not_in('UserID','');
        $Q = $this->db->get('NGAC_OTHER');
        return $Q->result_array();
    }
	
	function MinToMaxOtherData($week,$date_start,$date_finish,$user){
		$sql = "
				SELECT DAY(MIN(OtherDateStart)) AS MinDate,DAY(MAX(OtherDateStart)) as MaxDate,DATEPART(MONTH,OtherDateStart) AS MONTH
				FROM NGAC_OTHER
				WHERE (DAY(OtherDateStart) + (DATEPART(dw, DATEADD (MONTH, DATEDIFF (MONTH, 0, OtherDateStart), 0)) -1)-1)/7 + 1 = '".$week."'
				AND (OtherDateStart >='".$date_start."') AND (OtherDateStart <=DATEADD(day,1,'".$date_finish."'))
				AND UserID = ".$user."
				GROUP BY  (DAY(OtherDateStart) + (DATEPART(dw, DATEADD (MONTH, DATEDIFF (MONTH, 0, OtherDateStart), 0)) -1)-1)/7 + 1,DATEPART(MONTH,OtherDateStart)
			   ";
		$Q = $this->db->query($sql);
        $row = $Q->row_array();
		if ($row):
			if($row['MinDate']==$row['MaxDate'])
				$data = $row['MinDate'].'/'.$row['MONTH'];
			else	
				$data = $row['MinDate'].'-'.$row['MaxDate'].'/'.$row['MONTH'];		
		else:
			$data='';
		endif;	
		return $data;	
	}
    
    function save($date)
    {
        /**$date = date_create('20-02-2012');
        date_add($date, date_interval_create_from_date_string('7 days'));
        echo 'Tambahkan 7 hari: '.date_format($date,'d-m-Y');
        **/
        //'OtherDateFinish'     =>  $this->input->post('year2').'-'. $this->input->post('month2').'-'. $this->input->post('day2'),
  		$value = array(
                        'UserID'              =>  $this->input->post('user'),
                        'OtherType'           =>  $this->input->post('type'),
                        'OtherDateStart'      =>  $date,
                        'OtherDescription'    =>  $this->input->post('description'));
        $this->db->insert('NGAC_OTHER',$value);
    }

    function update()
    {
        //'OtherDateFinish'     =>  $this->input->post('year2').'-'. $this->input->post('month2').'-'. $this->input->post('day2'),
		$id = $this->input->post('ID');
        $value = array(
                    'UserID'              =>  $this->input->post('user'),
                    'OtherType'           =>  $this->input->post('type'),
                    'OtherDateStart'      =>  $this->input->post('year').'-'. $this->input->post('month').'-'. $this->input->post('day'),
                    'OtherDescription'    =>  $this->input->post('description'));
        $this->db->where('OtherID',$id);
        $this->db->update('NGAC_OTHER',$value);
    }
    
    function remove($id)
    {
        $id = trim(strtolower($id));
        $this->db->where('OtherID',$id);
        $this->db->delete('NGAC_OTHER');
    }
}