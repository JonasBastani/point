<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('users_my_model');
    }

	public function index()
	{
	}

    public function insertOrUpdate(){
        $this->load->library('i4proApi');
        $this->api->allowedMethods(['POST']);

        $data = json_decode(file_get_contents("php://input"), true);

        if(!empty($data)){

        }else{
            $this->api->response(400, array('status' => false, 'message' => "Dadps insuficientes!"));
        }
    }
}
