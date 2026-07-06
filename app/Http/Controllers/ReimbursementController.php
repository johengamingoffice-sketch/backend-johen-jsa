<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReimbursementController extends Controller
{
    public function index()
    {
        return view('reimbursement.index');
    }
}
