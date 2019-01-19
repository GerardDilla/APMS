<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class APMS extends CI_Controller {

	public function __construct()
	{
			parent::__construct();
			$this->load->model('Login_model');
			$this->load->model('Inventory_model');
			$this->data['test'] = 'test';
			//redirect($this->router->fetch_class().'/loginpage','refresh');
	}

	public function index()
	{
		$this->load->view('welcome_message');
		
	}

	public function loginpage()
	{
		echo $this->session->userdata('ref_number');
		echo $this->session->userdata('admin_id');
		$this->load->view('loginform');
	}

	public function tenant_login(){
		//LOGIN PROCESS FOR TENANT

		$config = array(
			array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
						'required' => 'You must provide a Username / Email',
					),
			),
			array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
			)
		);
		$this->form_validation->set_rules($config);

		if($this->form_validation->run() == TRUE){

			//initiate login check
			$logindata = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			);
			$result = $this->Login_model->tenantlogin($logindata);
			if($result){
				//echo 'Logged In';
				$this->session->set_flashdata('tenant_login_message','Logged In');
				//Saves login session
				$this->save_tenant_session($result);
				redirect($this->router->fetch_class().'/loginpage','refresh');
			}else{
				//echo 'Fail';
				$this->session->set_flashdata('tenant_login_message','Invalid Username or Password');
				redirect($this->router->fetch_class().'/loginpage','refresh');
			}
			//print_r($this->Login_model->tenantlogin($logindata));
		}
		else{
			$this->session->set_flashdata('tenant_login_message',validation_errors());
			redirect($this->router->fetch_class().'/loginpage','refresh');
		}
	}

	public function save_tenant_session($data){
		if($data){
			foreach($data as $row){
				$tenant_data['ref_number'] = $row['ref_number'];
				$tenant_data['fname'] = $row['fname'];
				$tenant_data['mname'] = $row['mname'];
				$tenant_data['lname'] = $row['lname'];
				$tenant_data['email'] = $row['email'];
				$tenant_data['cnumber'] = $row['cnumber'];
				$tenant_data['tenant_login_token'] = '1';
			}
			$this->session->set_userdata($tenant_data);
		}
		else{
			$this->session->set_flashdata('tenant_login_message','Error in saving session');
			redirect($this->router->fetch_class().'/loginpage','refresh');
		}

	}

	public function admin_login(){
		//LOGIN PROCESS FOR ADMIN

		$config = array(
			array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
						'required' => 'You must provide a Username / Email',
					),
			),
			array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
			)
		);
		$this->form_validation->set_rules($config);

		if($this->form_validation->run() == TRUE){

			//initiate login check
			$logindata = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			);
			$result = $this->Login_model->adminlogin($logindata);
			if($result){
				//echo 'Logged In';
				$this->session->set_flashdata('admin_login_message','Logged In');
				//Saves login session
				$this->save_admin_session($result);
				redirect($this->router->fetch_class().'/loginpage','refresh');
			}else{
				//echo 'Fail';
				$this->session->set_flashdata('admin_login_message','Invalid Username or Password');
				redirect($this->router->fetch_class().'/loginpage','refresh');
			}
		}
		else{
			$this->session->set_flashdata('admin_login_message',validation_errors());
			redirect($this->router->fetch_class().'/loginpage','refresh');
		}
	}

	public function save_admin_session($data){
		if($data){
			foreach($data as $row){
				$tenant_data['admin_id'] = $row['admin_id'];
				$tenant_data['fullname'] = $row['fullname'];
				$tenant_data['admin_email'] = $row['email'];
				$tenant_data['admin_login_token'] = '1';
			}
			$this->session->set_userdata($tenant_data);
		}
		else{
			$this->session->set_flashdata('tenant_login_message','Error in saving session');
			redirect($this->router->fetch_class().'/loginpage','refresh');
		}
	}

	public function admin_logout(){

		$this->session->unset_userdata('admin_login_token');
		redirect($this->router->fetch_class().'/loginpage','refresh');
	}

	public function tenant_logout(){

		$this->session->unset_userdata('tenant_login_token');
		redirect($this->router->fetch_class().'/loginpage','refresh');
	}

	Public function inventory($offset = 0){

		$searchdata = array(
			'searchkey' => $this->input->get('search'),
			'offset' => $offset
		);

		$this->data['Item_List'] =  $this->Inventory_model->inventorydisplayall($searchdata);
		$this->data['pages'] = $this->Inventory_model->inventory_pagination_count_all($searchdata);
		if($this->input->get('search')){
			$this->data['Item_List'] =  $this->Inventory_model->inventorysearch($searchdata);
			$this->data['pages'] = $this->Inventory_model->inventory_pagination_count_search($searchdata);
		}

		$config['base_url'] = base_url().'/index.php/'.$this->router->fetch_class().'/'.$this->router->fetch_method();
		$config['total_rows'] = $this->data['pages']['count'];
		$config['per_page'] = 5;
		$config["num_links"] = $config['total_rows']/$config['per_page'];
		$config['reuse_query_string'] = TRUE;
		// integrate bootstrap pagination
		$design = $this->pagination_design();

		$config = array_merge($config,$design);
		$this->pagination->initialize($config);

		//Initiates Item Edit if clicked
		$this->edit_item();

		$this->load->view('inventory');
		


	}
	public function edit_item(){

		if($this->input->post('editbutton')){
			echo 'with id:'.$this->input->post('editbutton');
			$result = $this->Inventory_model->get_item_info($this->input->post('editbutton'));
			if($result){

				foreach($result as $row){

					$this->session->set_flashdata('edit_item_id',$this->input->post('editbutton'));
					$this->session->set_flashdata('edit_item_name',$row['itemname']);
					$this->session->set_flashdata('edit_item_stock',$row['stock']);
					$this->session->set_flashdata('edit_item_price',$row['individual_price']);

				}

			}else{
				$this->session->set_flashdata('inventory_message','Error, No data on id:'.$this->input->post('editbutton'));
			}
			
		}
		return;
	}
	public function pagination_design(){

		// integrate bootstrap pagination
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		return $config;
	}
	
}
