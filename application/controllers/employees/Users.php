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
        return $this->CI->session->userdata('employee');
    }

    // verifica se o usuário está logado
    public function checkLogin(){
        $user = $this->getUserData();
        if(!empty($user) && isset($user['profile_id'])){
            if($user['profile_id']==1){
                $this->api->response(200, array('status' => true, 'message' => "Acesso autorizado!")); 
            }else{
                $this->api->response(401, array('status' => false, 'message' => "Não autorizado!")); 
            }
        }else{
            $this->api->response(401, array('status' => false, 'message' => "Não autorizado!")); 
        }
    }

    //insere se user_id nao for passado e atualiza se for passado teste
    public function insertOrUpdate(){

        $this->api->allowedMethods(['POST']);

        $data = json_decode(file_get_contents("php://input"), true); // recebe valores passados pelo usuário em data

        if(!empty($data)){ // caso data não esteja vazio segue para o insert ou update
            if(!empty($data['user_password'])){ // se tiver sido passado um password(senha)
                $data['user_password'] = md5($data['user_password']); //criptografa password(senha)
            }
            if(!empty($data['user_id'])){ // se tiver passado um user_id segue para update
                $user_id = $data["user_id"];// iguala uma variavel user_id ao valor user_id que está em data[]
				unset($data["user_id"]); // retira user_id de data[]
                if($this->users_model->is_duplicated('user_cpf', $data['user_cpf'], $user_id)){ // verifica se o cpf passado já existe em outros usuarios
                    $this->api->response(405, array('status' => false, 'message' => "CPF de outra conta!")); // se existir, retorna mensagem de erro
                }else if($this->users_model->is_duplicated('user_email', $data['user_email'], $user_id)){ // verifica se o email passado já existe em outros usuarios
                    $this->api->response(405, array('status' => false, 'message' => "E-mail de outra conta!")); // se existir, retorna mensagem de erro
                }else{ // se não existe dados duplicados segue para o update
                    $data['updated_at'] = date("Y-m-d H:i:s"); //adiciona horario do update em data
                    $this->users_my_model->update($data, $user_id); // faz update
                    $this->api->response(200, array('status' => true, 'data'=> $data, 'message' => "Dados alterados com sucesso!")); // retorna sucesso
                }

            }else{ // se nao estiver passado um user_id segue para o insert
                if($this->users_model->is_duplicated('user_cpf', $data['user_cpf'])){ // verifica se o cpf ja existe em algum usuário
                    $this->api->response(405, array('status' => false, 'message' => "Este CPF já possui cadastro!")); // se existir, retorna mensagem de erro
                }else if($this->users_model->is_duplicated('user_email', $data['user_email'])){ // verifica se email já existe em algum usuário
                    $this->api->response(405, array('status' => false, 'message' => "Este E-mail já possui cadastro!")); // se existir, retorna mensagem de erro
                }
                else{ // se não existe dados duplicados segue para o insert
                    $data['created_at'] = date("Y-m-d H:i:s"); // adiciona horario do insert em data
                    $data['sratus'] = 1; // este atributo é 0 ou 1, 1 pra um usuario ativo e 0 para inativo, todo usuário inicia ativo
                    $this->users_my_model->insert($data); // faz o insert
                    $this->api->response(200, array('status' => true, 'data'=> $data, 'message' => "Usuário cadastrado com sucesso!")); //retorna sucesso
                }
            }
        }else{ // caso data esteja vazio
            $this->api->response(400, array('status' => false, 'message' => "Dados insuficientes!")); // retorna mensagem de erro
        }

    }

    


}
