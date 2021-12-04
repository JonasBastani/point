<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_office_hour_model extends MY_Model {

	public function __construct() {
		$this->table = 'users_office_hour';
        $this->primary_key = 'user_office_hour_id';
        $this->return_as = 'array';
        $this->timestamps = TRUE;
		
	    $this->cache_driver = 'file';
		$this->cache_prefix = 'mm';
        $this->delete_cache_on_save = true;

        $this->has_one['user'] = array('Users_my_model', 'user_id', 'user_id');
		
		parent::__construct();
	}
}

