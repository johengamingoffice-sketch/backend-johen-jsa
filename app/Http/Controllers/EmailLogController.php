<?php

namespace App\Http\Controllers;

use App\Models\PayrollImport;
use App\Services\RetryService;

class EmailLogController extends Controller
{
    public function __construct(
        private RetryService $retryService,
    ) {}

    public function index(PayrollImport $import)
    {
        $import->load(['payrollDetails.emailLog']);

        return view('email-log.index', compact('import'));
    }

    public function retry(int $detailId)
    {
        $this->retryService->retrySingle($detailId);

        return redirect()->back()->with('success', 'Email berhasil dikirim ulang.');
    }

    public function retryAll(PayrollImport $import)
    {
        $count = $this->retryService->retryAllFailed($import->id);

        return redirect()->back()->with('success', "{$count} email gagal sedang diproses ulang.");
    }
}
