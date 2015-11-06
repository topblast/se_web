<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Login"]);
		$this->load->view('login/login_view');
		$this->load->view('foot');
	}
	
	public function verify()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('head', ['page_title' => "Login"]);
			$this->load->view('login/login_view');
			$this->load->view('foot');
		}
		else
		{
			redirect('admin', 'refresh');
		}
	}
	
	function check_database($password)
	{
		$this->session->unset_userdata('logged_in');
		$this->load->model("Staff");
   		$username = $this->input->post('username');
		$result = $this->Staff->login($username, $password);
		if ($result === FALSE)
		{
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return FALSE;
		}
			
		$this->session->set_userdata('logged_in', $result);
		
		return TRUE;
	}
}
