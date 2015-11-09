<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingredients extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->db->query('PRAGMA foreign_keys = ON;');
	}
	
	public function get_ingredient($id)
	{
		$id = (int)$id;
		
		$query = 'SELECT "ing_id" as id, "ing_name" as name, "ing_available" as available, "ing_stock" as stock FROM "ingredients" WHERE "ing_id"=?;';
		$result = $this->db->query($query, array($id));
		
		if ($result->num_rows() == 0)
		{
			return NULL;
		}
		
		return $result->row();
	}
	
	public function list_ingredients()
	{
		$query = 'SELECT "ing_id" as id, "ing_name" as name, "ing_available" as available, "ing_stock" as stock FROM "ingredients";';
		
		$result = $this->db->query($query);
		
		if ($result->num_rows() == 0)
		{
			return array();
		}
		
		return $result->result();
	}
	
	public function new_ingredients($ig_name, $available, $stock, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$ig_name = "$ig_name";
		$available = (bool)$available;
		$stock = (int)$stock;
		$staff = (int)$staff;
		
		$query_ing = 'INSERT INTO "ingredients" ("ing_id", "ing_name", "ing_available", "ing_stock") VALUES (NULL,?, ?, ?);';
		$log_ing = 'INSERT INTO "log_ingredients" ("log_staff_id","log_type","ing_id","ing_name","ing_available","ing_stock") VALUES (?, \'insert\', ?, ?, ?, ?);';
		
		$this->db->query($query_ing, array($ig_name, $available, $stock));
		$id = $this->db->insert_id();
		$this->db->query($log_ing, array($staff, $id, $ig_name, $available, $stock));
		return $id;
	}
	
	public function mod_ingredients($id, $ing_name, $available, $stock, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$id = (int)$id;
		$ing_name = "$ing_name";
		$available = (bool)$available;
		$stock = (int)$stock;
		$staff = (int)$staff;
		
		$query_ing = 'UPDATE "ingredients" SET "ing_name"=?, "ing_available"=?, ing_stock=? WHERE ing_id = ?;';
		$log_ing = 'INSERT INTO "log_ingredients" ("log_staff_id","log_type","ing_id","ing_name","ing_available","ing_stock") VALUES (?, \'modify\', ?, ?, ?, ?);';
		
		$this->db->query($query_ing, array($ing_name, $available, $stock, $id));
		$this->db->query($log_ing, array($staff, $id, $ing_name, $available, $stock));
		return $id;
	}
	
	public function del_ingredients($id, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$id = (int)$id;
		$staff = (int)$staff;
		
		$query_ing = 'DELETE FROM ingredients WHERE ing_id = ?;';
		$log_ing = 'INSERT INTO log_ingredients (log_staff_id,log_type,ing_id,ing_name,ing_available,ing_stock) SELECT ? as s , \'delete\' as t, ? as ident, i.ing_name, i.ing_available, i.ing_stock FROM ingredients AS i WHERE i.ing_id = ?;';
		
		$this->db->query($log_ing, array($staff, $id, $id));
		$this->db->query($query_ing, array($id));
	}
}
?>