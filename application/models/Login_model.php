<?php 

class Login_model extends CI_Model {


	public function tenantlogin($logindata){

		$this->db->where('username',$logindata['username']);
		$this->db->or_where('email',$logindata['username']);
		$this->db->where('password',$logindata['password']);
		$this->db->where('active','1');
		$result = $this->db->get('tenants');
		$this->db->reset_query();
		return $result->result_array();
		
	}


	public function adminlogin($logindata){

		$this->db->where('username',$logindata['username']);
		$this->db->or_where('email',$logindata['username']);
		$this->db->where('password',$logindata['password']);
		$this->db->where('active','1');
		$result = $this->db->get('admin');
		$this->db->reset_query();
		return $result->result_array();
	}





}


?>