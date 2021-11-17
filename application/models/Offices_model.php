<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Offices_model extends MY_Model {

	public function __construct() {
		$this->table = 'offices';
        $this->primary_key = 'office_id';
        $this->return_as = 'array';
        $this->timestamps = TRUE;
		
	    $this->cache_driver = 'file';
		$this->cache_prefix = 'mm';
        $this->delete_cache_on_save = true;

		
		parent::__construct();
	}
}