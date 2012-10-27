<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Table User SQL Server 2005 Table ngac_userinfo **/

class User extends CI_Controller {
    
    public function __contruct()
    {
        parent::__construct();
        $this->load->module_model('employee','userinfo'); //load model userinfo form user   
    }
    
	public function index()
	{
        $this->db->select('*');
        $this->db->order_by('ID','ASC');
        $Q = $this->db->get('NGAC_USERINFO');
        $val= $Q->result_array();
        $data['users']  = $val;   
        $data['page']	= 'user/vindex';
		$this->load->theme('default',$data);
	}
    
    
}

/* End of file user.php */
/* Location: ./module/auth/user.php */
