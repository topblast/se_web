<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {
	var $User;
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('logged_in'))
		{
			redirect('login', 'refresh');
		}
		
		$this->load->model("Staff");
		
		$this->User = $this->Staff->get_updated_info($this->session->userdata('logged_in')->id);
		if ($this->User === FALSE)
		{
			redirect('login', 'refresh');
		}
	}
	
	public function index()
	{
		if (!$this->User->admin)
		{
			redirect('admin', 'refresh');
		}
		
		$this->load->view('head', ['page_title' => "Account Management"]);
		$this->load->view('manage/head', ['firstname' => $this->User->firstname,'lastname' => $this->User->lastname]);
		$this->load->view('manage/list', ['list'=>$this->Staff->list_staff()]);
		$this->load->view('foot');
	}
	
	public function add($error=NULL)
	{
		if (!$this->User->admin)
			redirect('admin', 'refresh');
			
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Account Management"]);
		$this->load->view('manage/head', ['firstname' => $this->User->firstname,'lastname' => $this->User->lastname]);
		$this->load->view('manage/add', ['error'=> ($error !== NULL)]);
		$this->load->view('foot');
	}
	
	public function verify_add()
	{
		if (!$this->User->admin)
			redirect('admin', 'refresh');
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length[5]|callback_is_unique_username');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|xss_clean');
		$this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('admin', 'Manager', 'trim|xss_clean');
		
		
		if ($this->form_validation->run() == FALSE || $this->check_add() == FALSE)
		{
			$this->add(true);
		}
		else
			redirect('manage', 'refresh');
	}
	
	public function is_unique_username($username)
	{
		$username = "$username";
		
		if (!$this->Staff->is_unique_username($username))
		{
			$this->form_validation->set_message('is_unique_username', 'Username has been taken.');
			return FALSE;
		}
		return TRUE;
	}
	
	function check_add()
	{	
   		$uname = $this->input->post('username');
   		$password = $this->input->post('password');
		$confirmpassword = $this->input->post('confirmpassword');
		$fname = $this->input->post('fname');
		$lname = $this->input->post('lname');
		$admin = (bool)$this->input->post('admin');
		
		$result = $this->Staff->new_staff($uname, $password, $fname, $lname, $admin);
		
		if ($result == FALSE)
		{
			$this->form_validation->set_message('check_add', 'Database Error, failed to add new staff.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function modify($id,$error=NULL)
	{
		if (!$this->User->admin && $id != $this->ser->id)
		{
			redirect('manage/modify/'.$this->User->id, 'refresh');
		}
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Account Modification"]);
		$this->load->view('manage/head', ['firstname' => $this->User->firstname,'lastname' => $this->User->lastname]);
		$this->load->view('manage/modify', ['error'=> ($error !== NULL), 'isadmin' => $this->Staff->is_admin($id),'id'=>$id,'firstname' => $this->User->firstname,'lastname' => $this->User->lastname, 'hidepass' => ($this->User->id != $id && $this->User->admin)]);
		$this->load->view('foot');
	}
	
	public function verify_modify($id)
	{
		if (!$this->User->admin && $id != $this->User->id)
		{
			redirect('manage/modify/'.$this->User->id, 'refresh');
		}
		
		$this->load->library('form_validation');
		$_POST['id'] = $id;
		$this->form_validation->set_rules('password', 'Old Password', 'trim'. (($this->User->id != $id && $this->User->admin) ? '':'|required|callback_check_password').'|xss_clean');
		$this->form_validation->set_rules('newpassword', 'New Password', 'trim|min_length[6]|xss_clean|callback_check_new_password');
		$this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'trim|matches[newpassword]|xss_clean');
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('admin', 'Manager', 'trim|xss_clean');
		
		if ($this->form_validation->run() == FALSE || $this->check_modify($id) == FALSE)
		{
			$this->modify($id, true);
		}
		else
			redirect('manage', 'refresh');
	}
	
	function check_password($password)
	{
   		$id = (int)$this->input->post('id');
		   
		$result = $this->Staff->verify_pass_with_id($id, $password);
		if ($result === FALSE)
		{
			$this->form_validation->set_message('check_password', 'Your Password is incorrect!');
			return FALSE;
		}
	}
	
	function check_new_password($password)
	{
		if (!empty($password))
		{
			if (empty($this->input->post('confirmpassword')))
			{
				$this->form_validation->set_message('check_new_password', 'The Confirm Password field is required..');
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	function check_modify($id)
	{
		$id = (int)$id;
		
   		$password = $this->input->post('password');
		$newpassword = $this->input->post('newpassword');
		$oldpassword = $this->input->post('oldpassword');
		$fname = $this->input->post('fname');
		$lname = $this->input->post('lname');
		$admin = $this->input->post('lname');
		
		if ($this->User->id != $id && $this->User->admin)
		{
			$this->Staff->set_admin($id, $admin);
		}
		
		if (empty($newpassword))
			return $this->Staff->mod_staff($id, $fname, $lname);
		else
		{	
			return $this->Staff->mod_staff_and_pass($id, $newpassword, $fname, $lname);
		}
	}
	
	public function remove($id)
	{
		if (!$this->User->admin)
			redirect('admin', 'refresh');
			
		$this->Staff->del_staff((int)$id);
		
		redirect('manage', 'refresh');
	}
}
