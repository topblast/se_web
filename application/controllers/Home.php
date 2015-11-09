<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->model("Menu");
		
		$menu = $this->Menu->list_menu();
		
		$this->load->helper(array('form'));
		$this->load->view('head', ['page_title' => "Welcome to the Kiosk"]);
		$this->load->view('main/view', ['list' => $menu]);
		$this->load->view('foot');
	}
}