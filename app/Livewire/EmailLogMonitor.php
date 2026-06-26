<?php

namespace App\Livewire;

use App\Models\PayrollImport;
use Livewire\Component;

class EmailLogMonitor extends Component
{
    public PayrollImport $import;
    public string $filter = '';

    protected function getListeners(): array
    {
        return ['$refresh'];
    }

    public function render()
    {
        $details = $this->import->payrollDetails()
            ->with('emailLog')
            ->when($this->filter, function ($query) {
                $query->where('status', $this->filter);
            })
            ->get();

        $stats = [
            'total' => $details->count(),
            'sent' => $details->where('status', 'sent')->count(),
            'failed' => $details->where('status', 'failed')->count(),
            'pending' => $details->where('status', 'pending')->count(),
        ];

        return view('livewire.email-log-monitor', compact('details', 'stats'));
    }
}
