<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function ingredients()
	{
		$this->load->model('Test_model');
		
		$id = $this->Test_model->test_new_ingredients('Chicken', TRUE, 3, 0);
		$this->Test_model->test_mod_ingredients($id, 'Chips', TRUE, 1, 0);
		$this->Test_model->test_del_ingredients($id, 0);
		$this->load->view('head', ['page_title' => "Bootstrap Test"]);
		echo $id;
		$this->load->view('foot');
	}
	
	public function menu_ingredients()
	{
		$this->load->model('Test_model');
		
		$id_ing1 = $this->Test_model->test_new_ingredients('Chicken', TRUE, 3, 0);
		$id_ing2 = $this->Test_model->test_new_ingredients('Chicken', TRUE, 3, 0);
		$this->Test_model->test_mod_ingredients($id_ing2, 'Chips', TRUE, 1, 0);
		$id = $this->Test_model->test_new_menu('Chips & Chicken', 10.50, 0);
		$id_lk1 = $this->Test_model->test_new_menu_ingredient($id, $id_ing1, 0);
		$id_lk2 = $this->Test_model->test_new_menu_ingredient($id, $id_ing2, 0);
		$this->Test_model->test_del_menu_ingredient($id_lk1, 0);
		$this->Test_model->test_del_menu_ingredient($id_lk2, 0);
		$this->Test_model->test_del_menu($id, 0);
		$this->Test_model->test_del_ingredients($id_ing1, 0);
		$this->Test_model->test_del_ingredients($id_ing2, 0);
		$this->load->view('head', ['page_title' => "Bootstrap Test"]);
		echo "Chicken : $id_ing1 ~ Chips : $id_ing2 ~ Menu : $id";
		$this->load->view('foot');
	}
	
	public function menu_items()
	{
		$this->load->model('Test_model');
		
		$id = $this->Test_model->test_new_ingredients('Chicken', TRUE, 3, 0);
		$this->Test_model->test_mod_ingredients($id, 'Chips', TRUE, 1, 0);
		$this->Test_model->test_del_ingredients($id, 0);
		$this->load->view('head', ['page_title' => "Bootstrap Test"]);
		echo $id;
		$this->load->view('foot');
	}
}
