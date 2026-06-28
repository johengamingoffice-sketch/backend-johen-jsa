<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function jadwal()
    {
        return view('meeting.jadwal');
    }

    public function permintaan()
    {
        return view('meeting.permintaan');
    }
}
