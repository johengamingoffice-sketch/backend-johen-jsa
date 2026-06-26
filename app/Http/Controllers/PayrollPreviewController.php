<?php

namespace App\Http\Controllers;

use App\Models\PayrollImport;

class PayrollPreviewController extends Controller
{
    public function index(PayrollImport $import)
    {
        $import->load('payrollDetails');
        $totalPayroll = $import->payrollDetails->sum('take_home_pay');

        return view('payroll.preview', compact('import', 'totalPayroll'));
    }
}
