<?php 

class Account_model extends CI_Model {


	public function accountsearch($searchdata){

		$this->db->like('ref_number',$searchdata['searchkey'],'both');
		$this->db->or_like('fname',$searchdata['searchkey'],'both');
		$this->db->or_like('mname',$searchdata['searchkey'],'both');
		$this->db->or_like('lname',$searchdata['searchkey'],'both');
		$this->db->or_like('email',$searchdata['searchkey'],'both');
		$this->db->or_like('cnumber',$searchdata['searchkey'],'both');
		$this->db->where('active','1');
		$this->db->limit(5,$searchdata['offset']);
		$result = $this->db->get('tenants');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
		
	}
	public function account_pagination_count_search($searchdata){

		$this->db->like('ref_number',$searchdata['searchkey'],'both');
		$this->db->or_like('fname',$searchdata['searchkey'],'both');
		$this->db->or_like('mname',$searchdata['searchkey'],'both');
		$this->db->or_like('lname',$searchdata['searchkey'],'both');
		$this->db->or_like('email',$searchdata['searchkey'],'both');
		$this->db->or_like('cnumber',$searchdata['searchkey'],'both');
		$this->db->where('active','1');
		$result = $this->db->get('tenants');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
		
	}
	public function accountdisplayall($searchdata){

		$this->db->where('active','1');
		$this->db->limit(5,$searchdata['offset']);
		$result = $this->db->get('tenants');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function account_pagination_count_all($searchdata){

		$this->db->where('active','1');
		$result = $this->db->get('tenants');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function get_account_info($data){

		$this->db->where('ref_number',$data);
		$this->db->where('active','1');
		$result = $this->db->get('tenants');
		$this->db->reset_query();

		return $result->result_array();
	}
	public function update_account($data,$id){

		$this->db->where('ref_number', $id);
		$this->db->update('tenants',$data);
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}

	}
	public function delete_account($id){

		$this->db->set('active', '0');
		$this->db->where('ref_number',$id);
		$this->db->update('tenants');
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}
		
	}
	public function register_account($data){
		
		$this->db->insert('tenants', $data);
			if($this->db->affected_rows() != 0){
				return true; 
			}else{
				return false;
			}

		}



}


?>