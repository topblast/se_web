<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function get_menu($id)
	{
		$id = (int)$id;
		
		$query = 'SELECT "menu_id" as id, "menu_name" as name, "menu_price" as price, "menu_image" as image FROM "menu_items" where "menu_id"=? LIMIT 1';
		$result = $this->db->query($query, array($id));
		
		if ($result->num_rows() == 0)
		{
			return NULL;
		}
		$row = $result->row();
		$row->ingredients = $this->list_menu_ingredients($row->id);
		
		return $row;
	}
	
	public function get_image_path($id)
	{
		$id = (int)$id;
		
		$query = 'SELECT "menu_image" from "menu_items" WHERE "menu_id"=? LIMIT 1;';
		$result = $this->db->query($query, array($id));
		
		if ($result->num_rows() == 0)
		{
			return NULL;
		}
		
		return $result->row()->menu_image;
	}
	
	public function set_image_path($id, $path, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$id = (int)$id;
		$path = "$path";
		$staff = (int)$staff;
		
		$exist_ing = 'SELECT "menu_image" from "menu_items" WHERE "menu_id"=?;';
		$res = $this->db->query($exist_ing, array($id));
		if ($res->num_rows() == 0)
			return FALSE;
		$img = $res->row()->menu_image;
		if (!is_null($img))
			unlink("./images/".$res->row()->menu_image);
		$query_ing = 'UPDATE "menu_items" SET "menu_image"=? WHERE "menu_id"=?;';
		$log_ing = 'INSERT INTO "log_menu_items" ("log_staff_id","log_type","menu_id","menu_name","menu_price", "menu_image") SELECT ? as s , \'modify\' as t, i."menu_id", i."menu_name", i."menu_price", ? as img FROM "menu_items" AS i WHERE i."menu_id" = ?;';
		
		$this->db->query($query_ing, array($path, $id));
		$this->db->query($log_ing, array($staff, $path, $id));
		return $id;
	}
	
	public function list_menu($limit=-1)
	{
		$limit = (int)$limit;
		
		$query = 'SELECT "menu_id" as id, "menu_name" as name, "menu_price" as price, "menu_image" as image FROM "menu_items" LIMIT ?';
		
		$result = $this->db->query($query, array($limit));
		
		if ($result->num_rows() == 0)
		{
			return array();
		}
		
		$menus = $result->result();
		$data = array();
		foreach ($menus as $row) {
			$row->ingredients = $this->list_menu_ingredients($row->id);
			$data[] = $row;
		}
		return $data;
	}
	
	public function new_menu($menu_name, $price, $staff, $image=NULL)
	{
		if (!isset($staff))
			return FALSE;
		$menu_name = "$menu_name";
		$price = (float)$price;
		$staff = (int)$staff;
		if ($image !== NULL)
			$image = "$image";
		
		$query_ing = 'INSERT INTO "menu_items" ("menu_id", "menu_name", "menu_price","menu_image") VALUES (NULL,?,?,?);';
		$log_ing = 'INSERT INTO "log_menu_items" ("log_staff_id","log_type","menu_id","menu_name","menu_price", "menu_image") VALUES (?, \'insert\',?,?,?,?);';
		
		$this->db->query($query_ing, array($menu_name, $price, $image));
		$id = $this->db->insert_id();
		$this->db->query($log_ing, array($staff, $id, $menu_name, $price, $image));
		return $id;
	}
	
	public function mod_menu_and_image($id, $menu_name, $price, $path, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$id = (int)$id;
		$menu_name = "$menu_name";
		$price = (float)$price;
		$staff = (int)$staff;
		$path = "$path";
		
		$exist_ing = 'SELECT "menu_image" from "menu_items" WHERE "menu_id"=?;';
		$res = $this->db->query($exist_ing, array($id));
		
		if ($res->num_rows() == 0)
			return FALSE;
		
		$img = $res->row()->menu_image;
		
		if (!is_null($img))
			unlink("./images/".$res->row()->menu_image);
		
		$query_ing = 'UPDATE "menu_items" SET "menu_name"=?, "menu_price"=?, "menu_image"=? WHERE "menu_id" = ?;';
		$log_ing = 'INSERT INTO "log_menu_items" ("log_staff_id","log_type","menu_id","menu_name","menu_price", "menu_image") VALUES (?, \'modify\',?,?,?,?);';
		
		$this->db->query($query_ing, array($menu_name, $price, $path, $id));
		$this->db->query($log_ing, array($staff, $id, $menu_name, $price, $path));
		return $id;
	}
	
	public function mod_menu($id, $menu_name, $price, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$id = (int)$id;
		$menu_name = "$menu_name";
		$price = (float)$price;
		$staff = (int)$staff;
		
		$query_ing = 'UPDATE "menu_items" SET "menu_name"=?, "menu_price"=? WHERE "menu_id" = ?;';
		$log_ing = 'INSERT INTO "log_menu_items" ("log_staff_id","log_type","menu_id","menu_name","menu_price", "menu_image") VALUES (?, \'modify\',?,?,?,?);';
		
		$this->db->query($query_ing, array($menu_name, $price,$id));
		$this->db->query($log_ing, array($staff, $id, $menu_name, $price, $this->get_image_path($id)));
		return $id;
	}
	
	public function del_menu($id, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$id = (int)$id;
		$staff = (int)$staff;
		
		$exist_ing = 'SELECT "menu_image" from "menu_items" WHERE "menu_id"=?;';
		$res = $this->db->query($exist_ing, array($id));
		if ($res->num_rows() == 0)
			return FALSE;
		$img = $res->row()->menu_image;
		if (!is_null($img))
			unlink("./images/".$res->row()->menu_image);
		$query_ing = 'DELETE FROM "menu_items" WHERE "menu_id" = ?;';
		$log_ing = 'INSERT INTO "log_menu_items" ("log_staff_id","log_type","menu_id","menu_name","menu_price", "menu_image") SELECT ? as s , \'delete\' as t, i."menu_id", i."menu_name", i."menu_price", i."menu_image" FROM "menu_items" AS i WHERE i."menu_id" = ?;';
		
		$this->db->query($log_ing, array($staff, $id));
		$this->db->query($query_ing, array($id));
	}
	
	public function new_menu_ingredient($menu_id, $ing_id, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$menu_id = (int)$menu_id;
		$ing_id = (int)$ing_id;
		$staff = (int)$staff;
		
		$exist_ing = 'SELECT "rel_id" FROM "menu_ingredients" WHERE "menu_id"=? AND "ing_id"=? LIMIT 1';
		$res = $this->db->query($exist_ing, array($menu_id, $ing_id));
		if ($res->num_rows() != 0)
			return $res->row()->rel_id;
			
		$query_ing = 'INSERT INTO menu_ingredients (menu_id, ing_id) VALUES (?, ?);';
		$log_ing = 'INSERT INTO log_menu_ingredients (log_staff_id,log_type,rel_id,menu_id,ing_id) VALUES (?, \'insert\', ?, ?, ?);';

		$this->db->query($query_ing, array($menu_id, $ing_id));
		$id = $this->db->insert_id();
		$this->db->query($log_ing, array($staff, $id, $menu_id, $ing_id));
		return $id;
	}
	
	public function del_menu_ingredient($menu_id, $ing_id, $staff)
	{
		if (!isset($staff))
			return FALSE;
		$menu_id = (int)$menu_id;
		$ing_id = (int)$ing_id;
		$staff = (int)$staff;
		
		$exist_ing = 'SELECT "rel_id" FROM "menu_ingredients" WHERE "menu_id"=? AND "ing_id"=? LIMIT 1';
		$res = $this->db->query($exist_ing, array($menu_id, $ing_id));
		if ($res->num_rows() == 0)
			return FALSE;
		
		$id = (int)$res->row()->rel_id;
		
		$query_ing = 'DELETE FROM menu_ingredients WHERE rel_id = ?;';
		$log_ing = 'INSERT INTO log_menu_ingredients (log_staff_id,log_type,rel_id,menu_id,ing_id) SELECT ? as s , \'delete\' as t, i.rel_id, i.menu_id, i.ing_id FROM menu_ingredients AS i WHERE i.rel_id = ?;';
		
		$this->db->query($log_ing, array($staff, $id));
		$this->db->query($query_ing, array($id));
		return TRUE;
	}
	
	public function list_menu_ingredients($menu_id)
	{
		$menu_id = (int)$menu_id;
		
		$query = 'SELECT i."ing_id" as id, i."ing_name" as name, i."ing_available" as available, i."ing_stock" as stock FROM "ingredients" as i INNER JOIN "menu_ingredients" as mi ON mi."ing_id" = i."ing_id" WHERE mi."menu_id" = ?;';
		
		$result = $this->db->query($query, array($menu_id));
		
		if ($result->num_rows() == 0)
		{
			return array();
		}
		
		return $result->result();
	}
}
?>