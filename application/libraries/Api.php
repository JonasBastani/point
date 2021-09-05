<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct(){
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        $this->CI->load->model('users_model');
        $this->CI->load->model('users_my_model');
    }

    public function allowedMethods($methods){
        if(array_search($_SERVER['REQUEST_METHOD'],$methods)===false){
            $this->response(405,array('status'=>'false','message'=>'Metodo "'.$_SERVER['REQUEST_METHOD'].'" não permitido'));
        }
    }

    public function response($code,$response){
        http_response_code($code);
        header("Content-Type: application/json; charset=UTF-8");

        echo json_encode($response);
        die;
    }

}