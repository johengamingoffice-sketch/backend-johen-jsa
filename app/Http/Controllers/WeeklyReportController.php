<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeeklyReportController extends Controller
{
    public function index()
    {
        return view('operasional.weekly-report');
    }
}
