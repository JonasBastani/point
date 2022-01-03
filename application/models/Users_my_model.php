<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_my_model extends MY_Model {

	public function __construct() {
		$this->table = 'users';
        $this->primary_key = 'user_id';
        $this->return_as = 'array';
        $this->timestamps = TRUE;
		
	    $this->cache_driver = 'file';
		$this->cache_prefix = 'mm';
        $this->delete_cache_on_save = true;

		$this->has_one['profile'] = array('Profiles_model', 'profile_id', 'profile_id');
		$this->has_one['office'] = array('Offices_model', 'office_id', 'office_id');
		$this->has_one['remuneration'] = array('Remunerations_model', 'remuneration_id', 'remuneration_id');
		
		parent::__construct();
	}
}



