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
		//$data['username'] = $session_data['username'];
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Admin"]);
		
		$this->load->view('foot');
	}
	
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}
}
