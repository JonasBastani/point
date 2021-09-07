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

    //insere se user_id nao for passado e atualiza se for passado
    public function insertOrUpdate(){

        $this->api->allowedMethods(['POST']);

        $data = json_decode(file_get_contents("php://input"), true);

        if(!empty($data)){
            if(!empty($data['user_password'])){
                $data['user_password'] = md5($data['user_password']);
            }
            if(!empty($data['user_id'])){
                $user_id = $data["user_id"];
				unset($data["user_id"]);

                if($this->users_model->is_duplicated('user_cpf', $data['user_cpf'], $user_id)){
                    $this->api->response(405, array('status' => false, 'message' => "CPF de outra conta!"));
                }else if($this->users_model->is_duplicated('user_email', $data['user_email'], $user_id)){
                    $this->api->response(405, array('status' => false, 'message' => "E-mail de outra conta!"));
                }else{
                    $data['updated_at'] = date("Y-m-d H:i:s");
                    $this->users_my_model->update($data, $user_id);
                    $this->api->response(200, array('status' => true, 'data'=> $data, 'message' => "Dados alterados com sucesso!"));
                }

            }else{
                if($this->users_model->is_duplicated('user_cpf', $data['user_cpf'])){
                    $this->api->response(405, array('status' => false, 'message' => "Este CPF já possui cadastro!"));
                }else if($this->users_model->is_duplicated('user_email', $data['user_email'])){
                    $this->api->response(405, array('status' => false, 'message' => "Este E-mail já possui cadastro!"));
                }
                else{
                    $data['created_at'] = date("Y-m-d H:i:s");
                    $data['active'] = 1;
                    $this->users_my_model->insert($data);
                    $this->api->response(200, array('status' => true, 'data'=> $data, 'message' => "Usuário cadastrado com sucesso!"));
                }
            }
        }else{
            $this->api->response(400, array('status' => false, 'message' => "Dados insuficientes!"));
        }
    }
}
