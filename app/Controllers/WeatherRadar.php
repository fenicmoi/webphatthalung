<?php

namespace App\Controllers;

class WeatherRadar extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index()
    {
        $alert = function_exists('get_emergency_alert') ? get_emergency_alert() : [];
        
        $data = [
            'title'       => 'ระบบติดตามสถานการณ์น้ำ กล้องสดริมคลอง และเรดาร์พยากรณ์ฝน จังหวัดพัทลุง | กอปภ.จ.พัทลุง',
            'alertData'   => $alert,
            'externalUrl' => 'https://ddpmpt.rescuephatthalung.com/#forecast'
        ];

        return view('weather_radar/index', $data);
    }
}

