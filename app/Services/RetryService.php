<?php

namespace App\Services;

use App\Models\PayrollDetail;
use App\Models\PayrollImport;

class RetryService
{
    public function __construct(
        private EmailService $emailService,
    ) {}

    public function retrySingle(int $detailId): void
    {
        $detail = PayrollDetail::findOrFail($detailId);
        $periode = $detail->payrollImport->periode;
        $this->emailService->retry($detail, $periode);
    }

    public function retryAllFailed(int $importId): int
    {
        $import = PayrollImport::findOrFail($importId);
        $failedDetails = $import->payrollDetails()->where('status', 'failed')->get();

        foreach ($failedDetails as $detail) {
            $this->emailService->retry($detail, $import->periode);
        }

        return $failedDetails->count();
    }
}
