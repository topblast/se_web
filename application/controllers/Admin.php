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
	}
	
	public function index()
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Menu");
		
		$menu = $this->Menu->list_menu();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname]);
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
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname]);
		$this->load->view('admin/menu/list', ['list' => $menu]);
		$this->load->view('foot');
	}
	
	public function menu_add()
	{
		$session_data = $this->session->userdata('logged_in');
		
		$this->load->model("Ingredients");
		
		$ingredients = $this->Ingredients->list_ingredients();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname]);
		$this->load->view('admin/menu/add', ['list' => $ingredients]);
		$this->load->view('foot');
	}
	
	public function verify_menu_add()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('check[]', 'Ingredients', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE || $this->check_menu_add() == FALSE)
		{
			$this->menu_add();
		}
		else
		{
			redirect('admin/menu', 'refresh');
		}
	}
	
	public function check_menu_add()
	{
   		$name = $this->input->post('name');
   		$price = $this->input->post('price');
		$price = round((float)$price, 2, PHP_ROUND_HALF_UP);
		$check = $this->input->post('check[]');
		
		$this->load->model("Menu");
   		$username = $this->input->post('username');
		   
		$session_data = $this->session->userdata('logged_in');
		
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
	
	public function menu_modify($id)
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
		$this->load->view('admin/head', ['firstname' => $session_data->firstname,'lastname' => $session_data->lastname]);
		$this->load->view('admin/menu/modify', ['id'=>$id, 'ids' =>$ids, 'list' => $ingredients, 'name' => $menu->name, 'price' => number_format($menu->price, 2)]);
		$this->load->view('foot');
	}
	
	public function verify_menu_modify($id)
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('check[]', 'Ingredients', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE 
			|| $this->check_menu_modify($this->input->post('check[]'), $id) == FALSE)
		{
			$this->menu_modify($id);
		}
		else
			redirect('admin/menu', 'refresh');
	}
	
	public function check_menu_modify($check, $id)
	{
   		$name = $this->input->post('name');
   		$price = $this->input->post('price');
		$price = round((float)$price, 2, PHP_ROUND_HALF_UP);
		
		$this->load->model("Menu");
   		$username = $this->input->post('username');
		   
		$session_data = $this->session->userdata('logged_in');
		
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
	
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}
}
