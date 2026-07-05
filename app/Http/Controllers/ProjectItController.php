<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectItController extends Controller
{
    public function index()
    {
        return view('it.project');
    }
}
