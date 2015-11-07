<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function login($username, $password)
	{
		$query_salt = 'SELECT "staff_salt" FROM "staff" WHERE "staff_username"=? LIMIT 1;';
		$result = $this->db->query($query_salt, array($username));
		
		if ($result->num_rows() == 0)
			return FALSE;
		$query = 'SELECT "staff_id" as id,"staff_firstname" as firstname,"staff_lastname" as lastname,"staff_admin" as admin FROM "staff" WHERE "staff_username"=? AND "staff_passwordhash"=? LIMIT 1;';
		
		$result = $this->db->query($query, array("$username", (string)MD5("$password".$result->row()->staff_salt)));
		
		if ($result->num_rows() == 0)
		{
			return FALSE;
		}
		$staff = $result->row();
		
		$query = 'UPDATE "staff" SET "staff_lastlogged"=DATETIME(\'now\') WHERE "staff_id"=?;';
		$this->db->query($query, array($staff->id));
		
		return $staff;
	}
}
?>