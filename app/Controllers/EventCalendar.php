<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class EventCalendar extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        helper('settings');
        $events = get_site_events(true);

        return view('calendar_portal', [
            'events' => $events,
            'pageTitle' => 'ปฏิทินกิจกรรมและตารางปฏิบัติงานจังหวัดพัทลุง'
        ]);
    }

    public function getJson()
    {
        helper('settings');
        $events = get_site_events(true);
        return $this->respond([
            'status' => 'success',
            'data' => $events
        ]);
    }
}
