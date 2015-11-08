<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function list_staff()
	{
		$query = 'SELECT "staff_id" as id,"staff_username" as username,"staff_firstname" as firstname,"staff_lastname" as lastname,"staff_admin" as admin, "staff_lastlogged" as lastlogged FROM "staff";';
		
		$result = $this->db->query($query);
		if ($result->num_rows() == 0)
		{
			return array();
		}
		
		return $result->result();
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
	
	public function verify_pass_with_id($id, $password)
	{
		$id = (int)$id;
		$query_salt = 'SELECT "staff_salt" FROM "staff" WHERE "staff_id"=? LIMIT 1;';
		$result = $this->db->query($query_salt, array($id));
		
		if ($result->num_rows() == 0)
			return FALSE;
		$query = 'SELECT "staff_id" as id,"staff_firstname" as firstname,"staff_lastname" as lastname,"staff_admin" as admin FROM "staff" WHERE "staff_id"=? AND "staff_passwordhash"=? LIMIT 1;';
		
		$result = $this->db->query($query, array($id, (string)MD5("$password".$result->row()->staff_salt)));
		
		if ($result->num_rows() == 0)
		{
			return FALSE;
		}
		
		return $result->row();
	}
	
	public function mod_staff($id, $fname, $lname)
	{
		$id = (int)$id;
		$fname = "$fname";
		$lname = "$lname";
		
		$query = 'UPDATE "staff" SET "staff_firstname"=?, "staff_lastname"=? WHERE "staff_id"=?;';
		
		$result = $this->db->query($query, array($fname, $lname, $id));
		
		return ($this->db->affected_rows() != 0);
	}
	
	public function mod_staff_and_pass($id, $password, $fname, $lname)
	{
		$id = (int)$id;
		$fname = "$fname";
		$password = "$password";
		$lname = "$lname";
		$salt = $this->create_salt();
		
		$query = 'UPDATE "staff" SET "staff_passwordhash"=?, "staff_salt"=?, "staff_firstname"=?, "staff_lastname"=? WHERE "staff_id"=?;';
		
		$result = $this->db->query($query, array(MD5($password.$salt), $salt, $fname, $lname, $id));
		
		return ($this->db->affected_rows() != 0);
	}
	
	public function is_admin($id)
	{
		$query = 'SELECT "staff_id" FROM "staff" WHERE "staff_id"=? AND "staff_admin"=1;';
		$result = $this->db->query($query, array((int)$id));
		return $result->num_rows() != 0;
	}
	
	public function set_admin($id, $admin)
	{
		$id = (int)$id;
		$admin = (bool)$admin;
		
		$query = 'UPDATE "staff" SET "staff_admin"=? WHERE "staff_id"=?;';
		
		$result = $this->db->query($query, array($admin, $id));
		
		return ($this->db->affected_rows() != 0);
	}
	
	public function new_staff($uname, $password, $fname, $lname, $admin)
	{
		$fname = "$fname";
		$lname = "$lname";
		$uname = "$uname";
		$password = "$password";
		$admin = (bool)$admin;
		$salt = $this->create_salt();
		if (!$this->is_unique_username($uname))
			return -1;
		
		$query = 'INSERT INTO "staff" VALUES(NULL,?,?,?,?,?,?,NULL);';
		
		$result = $this->db->query($query, array($uname, $fname, $lname, MD5($password.$salt), $salt, $admin));
		
		return ($this->db->affected_rows() != 0);
	}
	
	public function is_unique_username($uname)
	{
		$query = 'SELECT "staff_id" FROM "staff" WHERE "staff_username"=?;';
		$result = $this->db->query($query, array($uname));
		return $result->num_rows() == 0;
	}
	
	public function get_updated_info($id)
	{
		$query = 'SELECT "staff_id" as id,"staff_firstname" as firstname,"staff_lastname" as lastname,"staff_admin" as admin FROM "staff" WHERE "staff_id"=? LIMIT 1;';
		
		$result = $this->db->query($query, array($id));
		
		if ($result->num_rows() == 0)
		{
			return FALSE;
		}
		
		return $result->row();
	}
	
	protected function create_salt()
	{
		$this->load->helper('string');
    	return sha1(random_string('alnum', 40));
	}
	
	public function del_staff($id)
	{
		$query_ing = 'DELETE FROM "staff" WHERE "staff_id" = ?;';
		
		$this->db->query($query_ing, array((int)$id));
	}
}
?>