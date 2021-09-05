<?php 

class Users_my_model extends MY_Model {

	public function __construct() {
		$this->table = 'users';
        $this->primary_key = 'user_id';
        $this->return_as = 'array';
        $this->timestamps = TRUE;
		
	    $this->cache_driver = 'file';
		$this->cache_prefix = 'mm';
        $this->delete_cache_on_save = true;
		parent::__construct();
	}
}



