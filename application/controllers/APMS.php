<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class APMS extends CI_Controller {

	public function __construct()
	{
			parent::__construct();
			$this->load->model('Login_model');
			$this->load->model('Inventory_model');
			$this->load->model('Account_model');
			$this->load->model('Request_model');
			date_default_timezone_set('Asia/Manila');
			$this->data['test'] = 'test';

	}

	public function index()
	{
		//$this->load->view('welcome_message');
		$this->loginpage();
	}

	public function loginpage()
	{
		echo $this->session->userdata('ref_number');
		echo $this->session->userdata('admin_id');
		$this->load->view('loginform');
	}
	//START LOGIN FUNCTIONS
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
	//END LOGIN FUNCTIONS

	//START INVENTORY FUNCTIONS
	Public function inventory($offset = ''){

		//echo $offset;
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

	public function update_inventory(){
		
		$config = array(
			array(
					'field' => 'edit_id',
					'label' => 'Item ID',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
						'required' => 'You must provide a %s',
					),
			),
			array(
					'iname' => 'iname',
					'label' => 'Item Name',
					'rules' => 'required|strip_tags',
					'errors' => array(
						'required' => 'You must provide a %s',
					),
			),
			array(
					'field' => 'stock',
					'label' => 'Stock Number',
					'rules' => 'required|trim|strip_tags|numeric',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
				),
			array(
					'field' => 'price',
					'label' => 'Price',
					'rules' => 'required|trim|strip_tags|numeric',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
			)
		);
		$this->form_validation->set_rules($config);

		$edit = $this->input->post('itemedit');
		$del = $this->input->post('itemdelete');
		$item_id = $this->input->post('edit_id');
		$msg = '';

		if(isset($del)){
			echo 'deleted';

			if($this->Inventory_model->delete_item($item_id) == TRUE){
				$msg = 'Item Removed.';
			}else{
				$msg = 'Error in Removing Item';
			}
			$this->session->set_flashdata('inventory_message',$msg);
			redirect($this->router->fetch_class().'/inventory','refresh');
		}

		if($this->form_validation->run() == TRUE){

			$itemdata = array(
				'itemname' => $this->input->post('iname'),
				'stock' => $this->input->post('stock'),
				'individual_price' => $this->input->post('price')
			);
			if(isset($edit)){
				echo 'edited';
				
				if($this->Inventory_model->update_item($itemdata,$item_id) == TRUE){
					$msg = 'Item Updated!';
				}else{
					$msg = 'Error in Updating Item';
				}
			}

			$this->session->set_flashdata('inventory_message',$msg);
			redirect($this->router->fetch_class().'/inventory','refresh');
		}
		else{

			$this->session->set_flashdata('inventory_message',validation_errors());
			redirect($this->router->fetch_class().'/inventory','refresh');
		
		}

	}
	public function add_inventory(){
		
		$config = array(
			array(
					'field' => 'iname',
					'label' => 'Item Name',
					'rules' => 'required|strip_tags',
					'errors' => array(
						'required' => 'You must provide a %s',
					),
			),
			array(
					'field' => 'stock',
					'label' => 'Stock Number',
					'rules' => 'required|trim|strip_tags|numeric',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
				),
			array(
					'field' => 'price',
					'label' => 'Price',
					'rules' => 'required|trim|strip_tags|numeric',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
			)
		);
		$this->form_validation->set_rules($config);

		if($this->form_validation->run() == TRUE){

			$edit = $this->input->post('itemedit');
			$del = $this->input->post('itemdelete');
			$msg = '';

			$itemdata = array(
				'itemname' => $this->input->post('iname'),
				'stock' => $this->input->post('stock'),
				'individual_price' => $this->input->post('price')
			);
			if($this->Inventory_model->add_item($itemdata) == TRUE){
				$msg = 'Item Added!';
			}else{
				$msg = 'Error in Adding Item';
			}

			$this->session->set_flashdata('inventory_message',$msg);
			redirect($this->router->fetch_class().'/inventory','refresh');

		}else{

			$this->session->set_flashdata('inventory_message',validation_errors());
			redirect($this->router->fetch_class().'/inventory','refresh');

		}

	}
	//END INVENTORY FUNCTIONS

	//START TENANT ACCOUNT MANAGEMENT
	Public function accountmanagement($offset = ''){

		//echo $offset;
		$searchdata = array(
			'searchkey' => $this->input->get('search'),
			'offset' => $offset
		);

		$this->data['List'] =  $this->Account_model->accountdisplayall($searchdata);
		$this->data['pages'] = $this->Account_model->account_pagination_count_all($searchdata);
		if($this->input->get('search')){
			$this->data['List'] =  $this->Account_model->accountsearch($searchdata);
			$this->data['pages'] = $this->Account_model->account_pagination_count_search($searchdata);
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
		$this->edit_account();

		$this->load->view('tenants');
		

	}
	public function edit_account(){

		if($this->input->post('editbutton')){
			echo 'with id:'.$this->input->post('editbutton');
			$result = $this->Account_model->get_account_info($this->input->post('editbutton'));
			if($result){

				foreach($result as $row){

					$this->session->set_flashdata('edit_ref_number',$this->input->post('editbutton'));
					$this->session->set_flashdata('edit_fname',$row['fname']);
					$this->session->set_flashdata('edit_mname',$row['mname']);
					$this->session->set_flashdata('edit_lname',$row['lname']);
					$this->session->set_flashdata('edit_number',$row['cnumber']);
					$this->session->set_flashdata('edit_email',$row['email']);
					$this->session->set_flashdata('edit_username',$row['username']);

				}

			}else{
				$this->session->set_flashdata('tenant_account_message','Error, No data on id:'.$this->input->post('editbutton'));
			}
			
		}
		return;
	}

	public function update_account(){
		
		$config = array(
			array(
					'field' => 'ref_number',
					'label' => 'Reference Number',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
						'required' => 'You must provide a %s',
					),
			),
			array(
					'iname' => 'fname',
					'label' => 'First Name',
					'rules' => 'required|strip_tags',
					'errors' => array(
						'required' => 'You must provide a %s',
					),
			),
			array(
					'field' => 'mname',
					'label' => 'Middle Name',
					'rules' => 'trim|strip_tags',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
				),
			array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
				),
			array(
				'field' => 'cnumber',
				'label' => 'Contact Number',
				'rules' => 'required|trim|strip_tags',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'required|trim|strip_tags|min_length[5]',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'required|trim|strip_tags|valid_email',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required|trim|strip_tags|min_length[5]',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'repassword',
				'label' => 'Password',
				'rules' => 'required|matches[password]',
				'errors' => array(
						'required' => 'You didnt retype the Password.',
						'matches' => 'Password did not match'
				),
			)
		);
		$this->form_validation->set_rules($config);

		$edit = $this->input->post('edit');
		$del = $this->input->post('delete');
		$id = $this->input->post('ref_number');
		$msg = '';

		if(isset($del)){
			//echo 'deleted';
			if($this->input->post('ref_number')){
				if($this->Account_model->delete_account($id) == TRUE){
					
					$msg = 'Account Deleted.';
					
				}else{
					$msg = 'Error Encountered.';
				}
			}else{
				$msg = 'No Account Selected.';
			}
			$this->session->set_flashdata('tenant_account_message',$msg);
			redirect($this->router->fetch_class().'/accountmanagement','refresh');
		}

		if($this->form_validation->run() == TRUE){

			$data = array(
				'fname' => $this->input->post('fname'),
				'mname' => $this->input->post('mname'),
				'lname' => $this->input->post('lname'),
				'cnumber' => $this->input->post('cnumber'),
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password'),
			);
			if(isset($edit)){
				//echo 'edited';
				
				if($this->Account_model->update_account($data,$id) == TRUE){
					$msg = 'Account Updated!';
				}else{
					$msg = 'Error in Updating Item';
				}
			}

			$this->session->set_flashdata('tenant_account_message',$msg);
			redirect($this->router->fetch_class().'/accountmanagement','refresh');
			
		}
		else{

			$this->session->set_flashdata('tenant_account_message',validation_errors());
			redirect($this->router->fetch_class().'/accountmanagement','refresh');
		
		}

	}
	public function register_account(){
		
		$config = array(

			array(
					'iname' => 'fname',
					'label' => 'First Name',
					'rules' => 'required|strip_tags',
					'errors' => array(
						'required' => 'You must provide a %s',
					),
			),
			array(
					'field' => 'mname',
					'label' => 'Middle Name',
					'rules' => 'trim|strip_tags',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
				),
			array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'required|trim|strip_tags',
					'errors' => array(
							'required' => 'You must provide a %s',
					),
				),
			array(
				'field' => 'cnumber',
				'label' => 'Contact Number',
				'rules' => 'required|trim|strip_tags',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'required|trim|strip_tags|min_length[5]',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'required|trim|strip_tags|valid_email',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required|trim|strip_tags|min_length[5]',
				'errors' => array(
						'required' => 'You must provide a %s',
				),
			),
			array(
				'field' => 'repassword',
				'label' => 'Password',
				'rules' => 'required|matches[password]',
				'errors' => array(
						'required' => 'You didnt retype the Password.',
						'matches' => 'Password did not match'
				),
			)
		);
		$this->form_validation->set_rules($config);

		if($this->form_validation->run() == TRUE){

			$edit = $this->input->post('edit');
			$del = $this->input->post('delete');
			$msg = '';

			$data = array(
				'fname' => $this->input->post('fname'),
				'mname' => $this->input->post('mname'),
				'lname' => $this->input->post('lname'),
				'cnumber' => $this->input->post('cnumber'),
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password'),
			);
			if($this->Account_model->register_account($data) == TRUE){
				$msg = 'Account Added!';
			}else{
				$msg = 'Error in Registration';
			}

			$this->session->set_flashdata('tenant_account_message',$msg);
			redirect($this->router->fetch_class().'/accountmanagement','refresh');

		}else{

			$this->session->set_flashdata('tenant_account_message',validation_errors());
			redirect($this->router->fetch_class().'/accountmanagement','refresh');

		}

	}
	//END TENANT ACCOUNT MANAGEMENT

	//START REQUISITION MANAGEMENT
	Public function requisition($offset = ''){

		//echo $offset;
		$searchdata = array(
			'searchkey' => $this->input->get('search'),
			'remarks' => $this->input->get('remarks'),
			'offset' => $offset
		);

		//For Requisition form
		$this->data['itemlist'] =  $this->Request_model->items_list();

		//For Remarks List
		$this->data['Remarks'] =  $this->Request_model->remarks_list();

		if($searchdata['remarks']){
			$this->data['List'] =  $this->Request_model->request_search_remark($searchdata);
			$this->data['pages'] = $this->Request_model->requests_search_pagination_count_remark($searchdata);
		}
		else{
			$this->data['List'] =  $this->Request_model->request_search($searchdata);
			$this->data['pages'] = $this->Request_model->requests_search_pagination_count($searchdata);
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

		//Check if a request is selected
		$this->select_request();

		$this->load->view('requisition');
		
	}
	public function submit_request(){
		
		$config = array(
			
			array(
					'field' => 'item_id[]',
					'label' => 'Item',
					//'rules' => 'required',
					'rules' => 'trim',
					'errors' => array(
						'required' => 'Choose an %s',
					),
			),
			array(
					'field' => 'quantity[]',
					'label' => 'Quantity',
					//'rules' => 'required|trim|numeric',
					'rules' => 'trim',
					'errors' => array(
							'required' => 'Enter the %s',
					),
				),
				

		);
		$this->form_validation->set_rules($config);

		if($this->form_validation->run() == TRUE){

			//Check url jump
			if(!$this->session->userdata('tenant_login_token')){

				$this->session->set_flashdata('tenant_login_message','Access Denied');
				redirect($this->router->fetch_class().'/loginpage','refresh');

			}
			$add = $this->input->post('add');
			
			if(isset($add)){
				$data = array(
					'tenant_id' => $this->session->userdata('ref_number'),
					//'item_id' => $this->input->post('item_id'),
					//'quantity' => $this->input->post('quantity'),
					'comment' => $this->input->post('comment')
				);
				if($this->Request_model->add_request($data) == TRUE){

					$array['request_id'] = $this->db->insert_id();
					$items = $this->input->post('item_id');

					$count = 0;
					foreach($items as $i){
						$array['item_id'] = $i;
						$array['quantity'] = $this->input->post('quantity')[$count];
						
						if($this->Request_model->add_request_item($array) == FALSE){
							$msg = 'Error in Inserting Requisition.';
						}
						$count++;
					}
					$msg = 'Request Submitted!';
				}else{
					$msg = 'Error in Submission';
				}
			}
			else{
				$this->session->set_flashdata('tenant_login_message','Access Denied');
				redirect($this->router->fetch_class().'/loginpage','refresh');
			}
			$this->session->set_flashdata('message',$msg);
			redirect($this->router->fetch_class().'/requisition','refresh');

		}else{

			$this->session->set_flashdata('message',validation_errors());
			redirect($this->router->fetch_class().'/requisition','refresh');

		}

	}
	public function select_request(){

		if($this->input->post('editbutton')){
			//echo 'with id:'.$this->input->post('editbutton');
			$arraydata['id'] = $this->input->post('editbutton');
			$result = $this->Request_model->get_request_info($arraydata);
			if($result){

				$this->session->set_flashdata('request_info', $result['array']);
				$result2 = $this->Request_model->request_item_list($arraydata);
				$this->session->set_flashdata('request_items', $result2['array']);

			}else{
				$this->session->set_flashdata('inventory_message','Error, No data on id:'.$this->input->post('editbutton'));
			}
			
		}
		return;
	}
	public function respond_requisition(){
		
		$config = array(
			array(
				'field' => 'request_id',
				'label' => 'Request Number',
				'rules' => 'required|trim|strip_tags',
				'errors' => array(
					'required' => 'No Selection Found',
				),
			),
			array(
				'field' => 'reply',
				'label' => 'Reply',
				'rules' => 'strip_tags',
			),
			array(
				'field' => 'remarks',
				'label' => 'Remark',
				'rules' => 'required|trim|strip_tags|numeric',
				'errors' => array(
					'required' => 'You Must Choose A Remark',
				),
			)
		);
		$this->form_validation->set_rules($config);

		if($this->form_validation->run() == TRUE){

			$id = $this->input->post('request_id');
			$pressed = $this->input->post('update');
			$reply = $this->input->post('reply');
			$email = $this->input->post('email');

			$msg = '';

			$data = array(

				'admin_reply' => $this->input->post('reply'),
				'remarks_id' => $this->input->post('remarks')
			);

			if(isset($pressed)){
				//echo 'edited';
				if($this->Request_model->update_request($data,$id) == TRUE){
					$msg = 'Request Updated!';
				}else{
					$msg = 'Error in Updating Request';
				}
			}
			

			//CHECK AND RUNS EMAIL FUNCTIONALITY
			if($reply){
				if($email){
					$data = array(
						'reply' => $reply,
						'email' => $email
					);
					if($this->email_module($data) == TRUE){
						echo 'success';
						$msg .= '<br>Email Sent!';
					}else{
						show_error($this->email->print_debugger());
						$msg .= '<br>Reply Sending Failed: Error in Sending';
					}
				}else{
					$msg .= '<br>Reply Sending Failed: User has no Email';
				}
			}
			
			

			$this->session->set_flashdata('request_message',$msg);
			redirect($this->router->fetch_class().'/requisition','refresh');
			
		}
		else{

			$this->session->set_flashdata('request_message',validation_errors());
			redirect($this->router->fetch_class().'/requisition','refresh');
		
		}

	}
	//START REQUISITION MANAGEMENT

	//PAGINATION LINKS DESIGN
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

	//EMAIL FUNCTION
	public function email_module($data = ''){
		
		if(is_Array($data)){

			echo $data['email'].' : '.$data['reply'];
			$subject = 'TEST';
			$sender = 'gerarddilla@gmail.com';
			$sender_name = 'Gerhard Dilla';
			$password = 'owlman20';

			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => $sender,
				'smtp_pass' => $password,
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE
			);

			$this->load->library('email',$config);

			$this->email->from($sender, $sender_name);
			$this->email->to($data['email']);
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');
			
			$this->email->subject($subject);
			$this->email->message($data['reply']);
			
			if($this->email->send()){
				return TRUE;
			}else{
				return FALSE;
			}

			//echo 'Not bypassed';

		}else{
			echo 'Not Authorized to Access this Function';
		}

	}
	public function phpmailer_test(){

		$this->load->library('email');
		

		$subject = 'This is a test';
		$message = '<p>This message has been sent for testing purposes.</p>';

		// Get full html:
		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
			<title>' . html_escape($subject) . '</title>
			<style type="text/css">
				body {
					font-family: Arial, Verdana, Helvetica, sans-serif;
					font-size: 16px;
				}
			</style>
		</head>
		<body>
		' . $message . '
		</body>
		</html>';
		// Also, for getting full html you may use the following internal method:
		//$body = $this->email->full_html($subject, $message);

		$result = $this->email
			->from('gerarddilla@gmail.com')
			->reply_to('gerarddilla@gmail.com')    // Optional, an account where a human being reads.
			->to('gpdilla@sdca.edu.ph')
			->subject($subject)
			->message($body)
			->send();

		var_dump($result);
		echo '<br />';
		echo $this->email->print_debugger();

		exit;
	
	}
	
}
