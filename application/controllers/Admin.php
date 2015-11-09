<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		
		if (!$this->session->userdata('logged_in'))
		{
			redirect('login', 'refresh');
		}
		
		$this->load->model("Staff");
		
		$user = $this->Staff->get_updated_info($this->session->userdata('logged_in')->id);
		
		if ($user === FALSE)
		{
			$this->session->unset_userdata('logged_in');
		}
		else
		{
			$this->session->set_userdata('logged_in', $user);
		}
	}
	
	public function index()
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Menu");
		
		$menu = $this->Menu->list_menu();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/menu/list', ['list' => $menu]);
		$this->load->view('foot');
	}
	
	public function menu()
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Menu");
		
		$menu = $this->Menu->list_menu();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/menu/list', ['list' => $menu]);
		$this->load->view('foot');
	}
	
	public function menu_add($error=NULL)
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Ingredients");
		
		$ingredients = $this->Ingredients->list_ingredients();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/menu/add', ['error'=> ($error !== NULL),'list' => $ingredients]);
		$this->load->view('foot');
	}
	
	public function verify_menu_add()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('check[]', 'Ingredients', 'trim|required|xss_clean');
		$this->form_validation->set_rules('image', 'Image', 'trim|xss_clean|callback_do_upload');
		//$this->form_validation->set_rules('blank', 'ERROR', 'callback_check_menu_add');
		
		if ($this->form_validation->run() == FALSE || $this->check_menu_add() == FALSE)
		{
			$this->menu_add(TRUE);
		}
		else
		{
			redirect('admin/menu', 'refresh');
		}
	}
	
	public function do_upload($image)
	{		
		$config['upload_path'] = './images/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2048';
		$config['encrypt_name'] = TRUE;
		$config['max_width']  = '256';
		$config['max_height']  = '256';

		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('image') && $_FILES['image']['error'] != 4)
        {
			$this->form_validation->set_message('do_upload', $this->upload->display_errors());
			
			return FALSE;
		}
		return TRUE;
	}
	
	public function check_menu_add()
	{
   		$name = $this->input->post('name');
   		$price = $this->input->post('price');
		$price = round((float)$price, 2, PHP_ROUND_HALF_UP);
		$check = $this->input->post('check[]');
		
		$this->load->model("Menu");
		   
		$session_data = $this->session->userdata('logged_in');
		
		if ($_FILES['image']['error'] != 4)
			$result = $this->Menu->new_menu($name, $price, $session_data->id, $this->upload->data('file_name'));
		else
			$result = $this->Menu->new_menu($name, $price, $session_data->id);
		if ($result === FALSE)
		{
			$this->form_validation->set_message('check_menu_add', 'Database Error, failed to add menu item.');
			return FALSE;
		}
		
		foreach($check as $id => $val)
		{
			$this->Menu->new_menu_ingredient($result, $id, $session_data->id);
		}
		
		return TRUE;
	}
	
	public function menu_modify($id, $error=NULL)
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model('Menu');
		$this->load->model("Ingredients");
		
		$ingredients = $this->Ingredients->list_ingredients();
		$menu = $this->Menu->get_menu($id);
		
		if (is_null($menu))
		{
			redirect('admin/menu');
		}
		
		$ids = array();
		foreach($menu->ingredients as $ing)
		{
			$ids[] = $ing->id;
		}
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/menu/modify', ['error'=> ($error !== NULL),'id'=>$id, 'ids' =>$ids, 'list' => $ingredients, 'name' => $menu->name, 'price' => number_format($menu->price, 2)]);
		$this->load->view('foot');
	}
	
	public function verify_menu_modify($id)
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('check[]', 'Ingredients', 'trim|required|xss_clean');
		$this->form_validation->set_rules('image', 'Image', 'trim|xss_clean|callback_do_upload');
		//$_POST['id'] = $id;
		//$this->form_validation->set_rules('id', 'ERROR', 'callback_check_menu_modify');
		
		if ($this->form_validation->run() == FALSE || $this->check_menu_modify($id) == FALSE)
		{
			$this->menu_modify($id, TRUE);
		}
		else
			redirect('admin/menu', 'refresh');
	}
	
	public function check_menu_modify($id)
	{
   		$name = $this->input->post('name');
   		$price = $this->input->post('price');
		$price = round((float)$price, 2, PHP_ROUND_HALF_UP);
		$check = $this->input->post('check[]');
		
		$this->load->model("Menu");
		   
		$session_data = $this->session->userdata('logged_in');
		
		if ($_FILES['image']['error'] != 4)
			$result = $this->Menu->mod_menu_and_image($id, $name, $price, $this->upload->data('file_name'), $session_data->id);
		else
			$result = $this->Menu->mod_menu($id, $name, $price, $session_data->id);
			
		if ($result === FALSE)
		{
			$this->form_validation->set_message('check_menu_add', 'Database Error, failed to modify menu item.');
			return FALSE;
		}
		
		$ings = $this->Menu->list_menu_ingredients($result);
		foreach($ings as $ing)
		{
			if (!array_key_exists($ing->id, $check))
			{
				$this->Menu->del_menu_ingredient($result, $ing->id, $session_data->id);
			}
			else
			{
				unset($check[$ing->id]);
			}
		}
		foreach($check as $id => $val)
		{
			$this->Menu->new_menu_ingredient($result, $id, $session_data->id);
		}
		
		return TRUE;
	}
	
	
	public function menu_remove($id)
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Menu");
		
		$this->Menu->del_menu((int)$id, $session_data->id);
		
		redirect('admin/menu', 'refresh');
	}
	
	public function ingredients()
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Ingredients");
		
		$menu = $this->Ingredients->list_ingredients();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/ingredient/list', ['list' => $menu]);
		$this->load->view('foot');
	}
	
	public function ingredients_add($error=NULL)
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['error'=> ($error !== NULL),'firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/ingredient/add');
		$this->load->view('foot');
	}
	
	public function ingredients_modify($id, $error=NULL)
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Ingredients");
		
		$ing = $this->Ingredients->get_ingredient($id);
		
		if (is_null($ing))
		{
			redirect('admin/ingredients');
		}
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname, 'isadmin' => $session_data->admin]);
		$this->load->view('admin/ingredient/modify', ['error'=> ($error !== NULL),'id'=>$id, 'name' => $ing->name, 'available' => (bool)$ing->available, 'stock' => (int)$ing->stock]);
		$this->load->view('foot');
	}
	
	
	public function verify_ingredients_add()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('stock', 'Stock', 'trim|required|xss_clean');
		$this->form_validation->set_rules('available', 'Available', 'trim|xss_clean');
		
		if ($this->form_validation->run() == FALSE || $this->check_ingredients_add() == FALSE)
		{
			$this->ingredients_add(TRUE);
		}
		else
		{
			redirect('admin/ingredients', 'refresh');
		}
	}
	
	
	public function verify_ingredients_modify($id)
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('stock', 'Stock', 'trim|required|xss_clean');
		$this->form_validation->set_rules('available', 'Available', 'trim|xss_clean');
		$_POST['id'] = $id;
		$this->form_validation->set_rules('id', 'ERROR', 'callback_check_ingredients_modify');
		
		if ($this->form_validation->run() == FALSE || $this->check_ingredients_modify($id) == FALSE)
		{
			$this->ingredients_modify($id, TRUE);
		}
		else
		{
			redirect('admin/ingredients', 'refresh');
		}
	}
	
	public function check_ingredients_add()
	{
   		$name = $this->input->post('name');
   		$stock = (int)$this->input->post('stock');
		$available = (bool)$this->input->post('available');
		
		$this->load->model("Ingredients");
		   
		$session_data = $this->session->userdata('logged_in');
		
		$result = $this->Ingredients->new_ingredients($name, $available, $stock, $session_data->id);
			
		if ($result === FALSE)
		{
			$this->form_validation->set_message('check_ingredients_add', 'Database Error, failed to modify menu item.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function check_ingredients_modify($id)
	{
		$id = (int)$id;
   		$name = $this->input->post('name');
   		$stock = (int)$this->input->post('stock');
		$available = (bool)$this->input->post('available');
		
		$this->load->model("Ingredients");
		   
		$session_data = $this->session->userdata('logged_in');
		
		$result = $this->Ingredients->mod_ingredients($id, $name, $available, $stock, $session_data->id);
			
		if ($result === FALSE)
		{
			$this->form_validation->set_message('check_ingredients_add', 'Database Error, failed to modify menu item.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function ingredients_remove($id)
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Ingredients");
		
		$this->Ingredients->del_ingredients((int)$id, $session_data->id);
		
		redirect('admin/ingredients', 'refresh');
	}
	
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}
}
