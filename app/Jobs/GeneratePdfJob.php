<?php

namespace App\Jobs;

use App\Models\PayrollDetail;
use App\Services\PdfGenerationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public PayrollDetail $payrollDetail,
        public string $periode,
    ) {}

    public function handle(PdfGenerationService $pdfService): void
    {
        $path = $pdfService->generate($this->payrollDetail, $this->periode, $this->payrollDetail->pdf_password);

        $this->payrollDetail->update(['pdf_path' => $path]);
    }
}
