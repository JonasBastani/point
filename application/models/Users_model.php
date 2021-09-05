<?php 

class Users_model extends CI_Model {

	public function __construct() {
		parent:: __construct();
		$this->load->database();
		
	}

	public function get_user_data($user_email) {
		$this->db
			->select("user_id, user_cpf, user_name, user_email, user_birthday, user_password_hash, active")
			->from("users")
			->where("user_email", $user_email);

		$result = $this->db->get();

		if ($result->num_rows() > 0)
		{
			return $result->row();
		}
		else
		{
			return NULL;	
		}
	}

    public function get_data($user_id, $select = NULL)
	{
		if (!empty($select))
		{
			$this->db->select($select);
		}
		$this->db->from("users");
		$this->db->where("user_id", $user_id);
		return $this->db->get();
	}

    public function insert($data)
	{
		$this->db->insert("users", $data);
	}

    public function update($user_id, $data)
	{
		$this->db->where("user_id", $user_id);
		$this->db->update("usuarios", $data);
	}

    public function dalete($user_id)
	{

		$this->db->where("user_id", $user_id);
		$this->db->delete("usuarios");
	}

    public function is_duplicated($field, $value, $user_id = NULL)
	{
		if (!empty($user_id))
		{
			$this->db->where("user_id <>", $user_id);			
		}
		$this->db->from("users");
		$this->db->where($field, $value);
		// return $this->db->get()->num_rows() > 0;
		if ($this->db->get()->num_rows() > 0) {
			return true;
		}
		else
		{
			return false;
		}
	}

}



