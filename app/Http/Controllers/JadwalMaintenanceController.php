<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalMaintenanceController extends Controller
{
    public function index()
    {
        return view('it.maintenance');
    }
}
