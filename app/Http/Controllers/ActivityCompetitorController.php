<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityCompetitorController extends Controller
{
    public function index()
    {
        return view('operasional.activity-competitor');
    }
}
