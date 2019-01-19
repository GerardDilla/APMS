<?php 

class Request_model extends CI_Model {


	public function request_search($searchdata){

		$this->db->like('re.request_id',$searchdata['searchkey'],'both');
		$this->db->or_like('t.fname',$searchdata['searchkey'],'both');
		$this->db->or_like('t.mname',$searchdata['searchkey'],'both');
		$this->db->or_like('t.lname',$searchdata['searchkey'],'both');
		//$this->db->or_like('i.itemname',$searchdata['searchkey'],'both');
		$this->db->join('tenants as t','t.ref_number = re.tenant_id','inner');
		$this->db->join('remarks as rm','rm.remarks_id = re.remarks_id','inner');
		//$this->db->join('inventory i','i.item_id = re.item_id','inner');
		$this->db->where('re.active','1');
		$this->db->where('t.active','1');
		//$this->db->where('i.active','1');
		$this->db->limit(5,$searchdata['offset']);
		$result = $this->db->get('request as re');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}

	public function requests_search_pagination_count($searchdata){

		$this->db->like('re.request_id',$searchdata['searchkey'],'both');
		$this->db->or_like('t.fname',$searchdata['searchkey'],'both');
		$this->db->or_like('t.mname',$searchdata['searchkey'],'both');
		$this->db->or_like('t.lname',$searchdata['searchkey'],'both');
		//$this->db->or_like('i.itemname',$searchdata['searchkey'],'both');
		$this->db->join('tenants as t','t.ref_number = re.tenant_id','inner');
		$this->db->join('remarks as rm','rm.remarks_id = re.remarks_id','inner');
		//$this->db->join('inventory i','i.item_id = re.item_id','inner');
		$this->db->where('re.active','1');
		$this->db->where('t.active','1');
		//$this->db->where('i.active','1');
		$result = $this->db->get('request as re');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function request_search_remark($searchdata){

		$this->db->group_start();
		$this->db->where('rm.remarks_id',$searchdata['remarks']);
		$this->db->where('re.active','1');
		$this->db->where('t.active','1');
		//$this->db->where('i.active','1');
		$this->db->group_end();
		$this->db->group_start();
		$this->db->like('re.request_id',$searchdata['searchkey'],'both');
		$this->db->or_like('t.fname',$searchdata['searchkey'],'both');
		$this->db->or_like('t.mname',$searchdata['searchkey'],'both');
		$this->db->group_end();
		$this->db->join('tenants as t','t.ref_number = re.tenant_id','inner');
		$this->db->join('remarks as rm','rm.remarks_id = re.remarks_id','inner');
		//$this->db->join('inventory i','i.item_id = re.item_id','inner');
		$this->db->limit(5,$searchdata['offset']);
		$result = $this->db->get('request as re');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}

	public function requests_search_pagination_count_remark($searchdata){

		$this->db->group_start();
		$this->db->where('rm.remarks_id',$searchdata['remarks']);
		$this->db->where('re.active','1');
		$this->db->where('t.active','1');
		//$this->db->where('i.active','1');
		$this->db->group_end();
		$this->db->group_start();
		$this->db->like('re.request_id',$searchdata['searchkey'],'both');
		$this->db->or_like('t.fname',$searchdata['searchkey'],'both');
		$this->db->or_like('t.mname',$searchdata['searchkey'],'both');
		$this->db->group_end();
		$this->db->join('tenants as t','t.ref_number = re.tenant_id','inner');
		$this->db->join('remarks as rm','rm.remarks_id = re.remarks_id','inner');
		//$this->db->join('inventory i','i.item_id = re.item_id','inner');
		$result = $this->db->get('request as re');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function request_item_list($data){

		$this->db->join('inventory as i','i.item_id = ri.item_id','inner');
		$this->db->where('ri.request_id',$data['id']);
		$this->db->where('ri.active','1');
		$result = $this->db->get('request_items as ri');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;

	}
	public function remarks_list(){

		$this->db->where('active','1');
		$result = $this->db->get('remarks');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function items_list(){

		$this->db->where('active','1');
		$result = $this->db->get('inventory');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function get_request_info($searchdata){

		
		$this->db->where('re.request_id',$searchdata['id']);
		$this->db->where('re.active','1');
		$this->db->where('t.active','1');
		//$this->db->where('i.active','1');
		$this->db->join('tenants as t','t.ref_number = re.tenant_id','inner');
		$this->db->join('remarks as rm','rm.remarks_id = re.remarks_id','inner');
		//$this->db->join('inventory i','i.item_id = re.item_id','inner');
		$result = $this->db->get('request as re');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function add_request($data){
		
		$this->db->insert('request', $data);
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}

	}
	public function add_request_item($data){
		
		$this->db->insert('request_items', $data);
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}

	}
	public function update_request($data,$id){

		$this->db->where('request_id', $id);
		$this->db->update('request',$data);
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}
	}



}


?>