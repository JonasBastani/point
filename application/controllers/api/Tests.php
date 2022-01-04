<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tests extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('holidays_api');
        $this->load->library('holidays');
    }

	public function index()
	{
	}

    public function holidays($date = null){
        $date = $date == null ? date("Y-m-d") : $date;
        $data = $this->holidays_api->verifyHoliday($date);

        $this->api->response(200, ['status'=> true, 'data'=> $data]);
    }


    public function insertHolidays($date = null){
        $data = $this->holidays->insertHolidaysAndDays();

    }


}
