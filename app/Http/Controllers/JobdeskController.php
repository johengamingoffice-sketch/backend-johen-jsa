<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobdeskController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;

        return view('hris.jobdesk', compact('employee'));
    }
}
