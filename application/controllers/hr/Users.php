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

    // retorna dados do usuário logado
    public function getUserData(){
        return $this->CI->session->userdata('hr');
    }

    // verifica se o usuário está logado
    public function checkLogin(){
        $user = $this->getUserData();
        if(!empty($user) && isset($user['profile_id'])){
            if($user['profile_id']==2){
                $this->api->response(200, array('status' => true, 'message' => "Acesso autorizado!")); 
            }else{
                $this->api->response(401, array('status' => false, 'message' => "Não autorizado!")); 
            }
        }else{
            $this->api->response(401, array('status' => false, 'message' => "Não autorizado!")); 
        }
    }


    


}
