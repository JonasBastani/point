<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('users_my_model');
    }

	public function index()
	{
	}

    

    public function login(){
        $this->api->allowedMethods(['POST']);
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['email']) && !empty($data['password'])) {
            $user = $this->users_my_model->where(['user_email'=> $data['email'], 'user_password'=> md5($data['password']), 'profile_id'=>2])->get();
            if(isset($user['user_id'])){
                unset($user['user_password']); // removendo senha dp usuário dos dados para sessão e storage
                $this->session->set_userdata('hr',$user);
                $this->api->response(200, array('status' => true,'data'=>$user, 'message' => "Tudo certo!"));
            }else{
                $this->api->response(500, array('status' => false, 'message' => "Usuário e/ou senha incorretos!"));
            }
        }else{
            $this->api->response(500, array('status' => false, 'message' => "Usuário e/ou senha incorretos!"));
        }
    }

    public function logoff(){
        $this->session->unset_userdata('hr');
    }


}
