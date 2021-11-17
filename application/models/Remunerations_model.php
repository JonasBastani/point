<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Remunerations_model extends MY_Model {

	public function __construct() {
		$this->table = 'remunerations';
        $this->primary_key = 'remuneration_id';
        $this->return_as = 'array';
        $this->timestamps = TRUE;
		
	    $this->cache_driver = 'file';
		$this->cache_prefix = 'mm';
        $this->delete_cache_on_save = true;

		$this->has_one['user'] = array('Users_my_model', 'user_id', 'user_id');
		
		parent::__construct();
	}
}