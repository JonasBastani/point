<?php
defined('BASEPATH') or exit('No direct script access allowed');

function verifyHoliday($data, $boolean = false)
{

    $param = array();

    // Sua chave (exigida após 01/08/2016!), leia no final dessa postagem!
    $param['key'] = '';

    // Listas de países suportados!
    $paisesSuportados = array('BE', 'BG', 'BR', 'CA', 'CZ', 'DE', 'ES', 'FR', 'GB', 'GT', 'HR', 'HU', 'ID', 'IN', 'IT', 'NL', 'NO', 'PL', 'PR', 'SI', 'SK', 'US');

    // Define o pais para buscar feriados
    $param['country'] = $paisesSuportados[2];

    // Quebra a string em partes (em ano, mes e dia)
    list($param['year'], $param['month'], $param['day']) = explode('-', $data);

    // Converte a array em parâmetros de URL
    $param = http_build_query($param);

    // Conecta na API
    $curl = curl_init('https://holidayapi.com/v1/holidays?' . $param);

    // Permite retorno
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Obtem dados da API
    $dados = json_decode(curl_exec($curl), true);

    // Encerra curl
    curl_close($curl);

    // Retorna true/false se houver $boolean ou Nome_Do_Feriado/false se não houve $boolean
    return isset($dados['holidays']['0']) ? ['status'=>true, 'data'=>$dados['holidays']['0']['name']] : ['status'=> false];
}
