<?php 

class Inventory_model extends CI_Model {


	public function inventorysearch($searchdata){

		$this->db->like('item_id',$searchdata['searchkey'],'both');
		$this->db->or_like('itemname',$searchdata['searchkey'],'both');
		$this->db->where('active','1');
		$this->db->limit(5,$searchdata['offset']);
		$result = $this->db->get('inventory');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
		
	}
	public function inventory_pagination_count_search($searchdata){

		$this->db->like('item_id',$searchdata['searchkey'],'both');
		$this->db->or_like('itemname',$searchdata['searchkey'],'both');
		$this->db->where('active','1');
		$result = $this->db->get('inventory');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
		
	}
	public function inventorydisplayall($searchdata){

		$this->db->where('active','1');
		$this->db->limit(5,$searchdata['offset']);
		$result = $this->db->get('inventory');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function inventory_pagination_count_all($searchdata){

		$this->db->where('active','1');
		$result = $this->db->get('inventory');
		$this->db->reset_query();
		$results = array(
			'array' => $result->result_array(),
			'count' => $result->num_rows(),
		);
		return $results;
	}
	public function get_item_info($data){

		$this->db->where('item_id',$data);
		$this->db->where('active','1');
		$result = $this->db->get('inventory');
		$this->db->reset_query();

		return $result->result_array();
	}
	public function update_item($data,$id){

		$this->db->where('item_id', $id);
		$this->db->update('inventory',$data);
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}
	}
	public function delete_item($id){

		$this->db->set('active', '0');
		$this->db->where('item_id',$id);
		$this->db->update('inventory');
		if($this->db->affected_rows() != 0){
			return true; 
		}else{
			return false;
		}

	}
	public function add_item($data){
		
		$this->db->insert('inventory', $data);
			if($this->db->affected_rows() != 0){
				return true; 
			}else{
				return false;
			}

		}



}


?>