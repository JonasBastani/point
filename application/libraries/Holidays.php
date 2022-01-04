<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Holidays extends CI_Controller
{

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
        $this->CI->load->model('days_model');
    }

    public function insertHolidaysAndDays($year_ = null)
    {


        $year_ = $year_ != null ? $year_ : date("Y");


        $year = intval(date('Y'));
        //$this->CI->api->response(200, [$year_]);

        for ($i = $year; $i <= 2037; $i++) {

            $pascoa     = easter_date($i); // Limite entre 1970 a 2037 conforme 
            //http: //www.php.net/manual/pt_BR/function.easter-date.php
            $dia_pascoa = date('j', $pascoa);
            $mes_pascoa = date('n', $pascoa);
            $ano_pascoa = date('Y', $pascoa);

            $holidays = array(
                // Datas Fixas dos feriados brasileiros
                'Ano Novo' => mktime(0, 0, 0, 1,  1,   $year), // Confraternização Universal - Lei nº 662, de 06/04/49
                'Tiradentes' => mktime(0, 0, 0, 4,  21,  $year), // Tiradentes - Lei nº 662, de 06/04/49
                'Dia do Trabalhador' => mktime(0, 0, 0, 5,  1,   $year), // Dia do Trabalhador - Lei nº 662, de 06/04/49
                'Independência do Brasil' => mktime(0, 0, 0, 9,  7,   $year), // Dia da Independência - Lei nº 662, de 06/04/49
                'Nossa Senhora Aparecida' => mktime(0, 0, 0, 10,  12, $year), // N. S. Aparecida - Lei nº 6802, de 30/06/80
                'Finados' => mktime(0, 0, 0, 11,  2,  $year), // Todos os santos - Lei nº 662, de 06/04/49
                'Proclamação da República' => mktime(0, 0, 0, 11, 15,  $year), // Proclamação da republica - Lei nº 662, de 06/04/49
                'Natal' => mktime(0, 0, 0, 12, 25,  $year), // Natal - Lei nº 662, de 06/04/49

                // Essas datas dependem da páscoa
                'Segunda de Carnaval' => mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa), //2ºferia Carnaval
                'Terça de Carnaval' => mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa), //3ºferia Carnaval	
                'Sexta-feira da Paixão' => mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2,  $ano_pascoa), //6ºfeira Santa  
                'Páscoa' => mktime(0, 0, 0, $mes_pascoa, $dia_pascoa,  $ano_pascoa), //Pascoa
                'Corpus Christi' => mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa), //Corpus Cirist
            );

            asort($holidays);

            foreach ($holidays as $name => $date) {
                $check = $this->CI->days_model->where('date', date("Y-m-d", $date))->get();
                if (empty($check)) {
                    $this->CI->days_model->insert(['country' => 'BR', 'is_holiday' => 1, 'name' => $name, 'date' => date("Y-m-d", $date), 'week_day'=> date("w", $date)]);
                }
            }

            /*
            $d = mktime( 0, 0, 0, 1, 1, $year ) cria um timestamp do primeiro dia do ano desejado

            date( 'Y', $d ) == $year faz com que o loop permaneça apenas no ano desejado

            $d += 86400 adiciona um dia ao timestamp (86400 = 24 * 60 * 60 segundos).

            adiciona a data à lista.

            se não, $d += 86400 já pula o domingo pra "economizar loop" (poderia deixar sem isso).

            */
            for ($d = mktime(0, 0, 0, 1, 1, $i); date('Y', $d) == $i; $d += 86400){
                $days[] = ['date'=> date('Y-m-d"', $d), 'day' => date('w', $d)];
            }

            foreach ($days as $day){
                set_time_limit(120);
                $check = $this->CI->days_model->where('date', $day)->get();
                if (empty($check)) {
                    $this->CI->days_model->insert(['country' => 'BR', 'is_holiday' => 0, 'name' => 'Dia comum', 'date' => $day['date'], 'week_day'=> $day['day']]);
                }
            }
        }
    }
}
