<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Holidays_api extends CI_Controller
{

    private $key = 'cda706d8-d7cd-434f-92e8-cd4e1514e556';

    // Listas de países suportados!
    private $countrys = array('BE', 'BG', 'BR', 'CA', 'CZ', 'DE', 'ES', 'FR', 'GB', 'GT', 'HR', 'HU', 'ID', 'IN', 'IT', 'NL', 'NO', 'PL', 'PR', 'SI', 'SK', 'US');

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
        $this->CI->load->model('days_model');
    }

    public function verifyHoliday($date)
    {
        // Define o pais para buscar feriados
        $param['country'] = $this->countrys[2];

        // verificando se dia já exite no banco
        $check = $this->CI->days_model->where(['date' => $date, 'country' => $param['country']])->get();

        // se nao existir usa a api
        if (empty($check)) {
            $param = array();

            $param['key'] = $this->key;

            $param['country'] = $this->countrys[2];

            // Quebra a string em partes (em ano, mes e dia)
            list($param['year'], $param['month'], $param['day']) = explode('-', $date);

            // Converte a array em parâmetros de URL
            $param = http_build_query($param);

            // Conecta na API
            $curl = curl_init('https://holidayapi.com/v1/holidays?' . $param);

            // Permite retorno
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Obtem dados da API
            $response = json_decode(curl_exec($curl), true);

            // Encerra curl
            curl_close($curl);
            //return $response;
            if (isset($response['holidays']['0'])) {
                $this->CI->days_model->insert(['country' => $param['country'], 'is_holiday' => 1, 'name' => $response['holidays']['0']['name'], 'date' => $date]);
                return ['status' => true, 'name' => $response['holidays']['0']['name']];
            } else {
                $this->CI->days_model->insert(['is_holiday' => 0, 'name' => 'Dia comum', 'date' => $date]);
                return ['status' => false, 'name' => 'Dia comum'];
            }
        } else { // se existir usa os dados do banco
            return $check['is_holiday'] == 1 ? ['status' => true, 'name' => $check['name']] : ['status' => false, 'name' => $check['name']];
        }
    }
}
