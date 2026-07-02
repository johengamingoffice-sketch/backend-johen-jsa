<?php

namespace App\Http\Controllers;

use App\Models\ElectricitySetting;
use App\Models\ElectricityTokenCheck;
use App\Models\ElectricityTopup;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ElectricityController extends Controller
{
    public function index()
    {
        $setting = ElectricitySetting::firstOrCreate(
            ['id' => 1],
            ['kapasitas_kwh' => 3000, 'updated_by' => auth()->id()]
        );

        $lastTopup = ElectricityTopup::with('creator')->latest()->first();
        $lastCheck = ElectricityTokenCheck::with('checker')->latest()->first();

        $totalTerpakai = ElectricityTokenCheck::sum('terpakai');
        $sisaToken = $lastCheck?->sisa_kwh ?? 0;

        $topups = ElectricityTopup::with('creator')->latest()->paginate(20);
        $checks = ElectricityTokenCheck::with('checker')->latest()->paginate(20);

        return view('electricity.index', compact(
            'setting', 'lastTopup', 'lastCheck', 'totalTerpakai', 'sisaToken', 'topups', 'checks'
        ));
    }

    public function topupsData(Request $request)
    {
        $query = ElectricityTopup::with('creator');

        $filter = $request->filter ?? 'bulanan';
        $query = $this->applyFilter($query, $filter);

        $topups = $query->latest()->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $topups->items(),
                'meta' => [
                    'current_page' => $topups->currentPage(),
                    'last_page' => $topups->lastPage(),
                    'total' => $topups->total(),
                ],
            ]);
        }

        return $topups;
    }

    public function storeTopup(Request $request)
    {
        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'periode' => 'required|string|max:50',
            'jumlah_kwh' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        ElectricityTopup::create($validated);

        return redirect()->route('electricity.index')->with('success', 'Top up token berhasil dicatat.');
    }

    public function destroyTopup(ElectricityTopup $electricityTopup)
    {
        $electricityTopup->delete();

        return back()->with('success', 'Riwayat top up berhasil dihapus.');
    }

    public function checksData(Request $request)
    {
        $query = ElectricityTokenCheck::with('checker');

        $filter = $request->filter ?? 'bulanan';
        $query = $this->applyFilter($query, $filter);

        $checks = $query->latest()->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $checks->items(),
                'meta' => [
                    'current_page' => $checks->currentPage(),
                    'last_page' => $checks->lastPage(),
                    'total' => $checks->total(),
                ],
            ]);
        }

        return $checks;
    }

    public function storeCheck(Request $request)
    {
        $validated = $request->validate([
            'tanggal_check' => 'required|date',
            'sisa_kwh' => 'required|numeric|min:0',
            'terpakai' => 'required|numeric|min:0',
            'status' => 'nullable|in:normal,rendah,habis',
            'catatan' => 'nullable|string',
        ]);

        $validated['checked_by'] = auth()->id();

        if (!isset($validated['status'])) {
            if ($validated['sisa_kwh'] <= 0) {
                $validated['status'] = 'habis';
            } elseif ($validated['sisa_kwh'] <= 500) {
                $validated['status'] = 'rendah';
            } else {
                $validated['status'] = 'normal';
            }
        }

        ElectricityTokenCheck::create($validated);

        return redirect()->route('electricity.index')->with('success', 'Pengecekan token berhasil dicatat.');
    }

    public function destroyCheck(ElectricityTokenCheck $electricityTokenCheck)
    {
        $electricityTokenCheck->delete();

        return back()->with('success', 'Data pengecekan berhasil dihapus.');
    }

    public function stats()
    {
        $setting = ElectricitySetting::firstOrCreate(
            ['id' => 1],
            ['kapasitas_kwh' => 3000, 'updated_by' => auth()->id()]
        );

        $lastTopup = ElectricityTopup::with('creator')->latest()->first();
        $lastCheck = ElectricityTokenCheck::with('checker')->latest()->first();

        $totalTopupKwh = ElectricityTopup::sum('jumlah_kwh');
        $totalTerpakai = ElectricityTokenCheck::sum('terpakai');
        $totalNominal = ElectricityTopup::sum('nominal');
        $sisaToken = $lastCheck?->sisa_kwh ?? 0;

        return response()->json([
            'setting' => $setting,
            'last_topup' => $lastTopup,
            'last_check' => $lastCheck,
            'total_topup_kwh' => $totalTopupKwh,
            'total_terpakai' => $totalTerpakai,
            'total_nominal' => $totalNominal,
            'sisa_token' => $sisaToken,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'kapasitas_kwh' => 'required|numeric|min:0',
        ]);

        $setting = ElectricitySetting::firstOrCreate(
            ['id' => 1],
            ['kapasitas_kwh' => 3000, 'updated_by' => auth()->id()]
        );

        $setting->update([
            'kapasitas_kwh' => $validated['kapasitas_kwh'],
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pengaturan kapasitas token berhasil diupdate.');
    }

    public function exportTopups(Request $request)
    {
        $query = ElectricityTopup::with('creator');

        $filter = $request->filter ?? 'bulanan';
        $query = $this->applyFilter($query, $filter);

        $topups = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Top Up');

        $headers = ['No', 'Tanggal Bayar', 'Periode', 'Jumlah KWH', 'Nominal', 'Oleh', 'Catatan'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($topups as $idx => $t) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $t->tanggal_bayar->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $t->periode);
            $sheet->setCellValue('D' . $row, $t->jumlah_kwh);
            $sheet->setCellValue('E' . $row, $t->nominal);
            $sheet->setCellValue('F' . $row, $t->creator?->name);
            $sheet->setCellValue('G' . $row, $t->catatan);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'riwayat_topup_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function exportChecks(Request $request)
    {
        $query = ElectricityTokenCheck::with('checker');

        $filter = $request->filter ?? 'bulanan';
        $query = $this->applyFilter($query, $filter);

        $checks = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengecekan Token');

        $headers = ['No', 'Tanggal Check', 'Sisa KWH', 'Terpakai', 'Status', 'Pengecek', 'Catatan'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($checks as $idx => $c) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $c->tanggal_check->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $c->sisa_kwh);
            $sheet->setCellValue('D' . $row, $c->terpakai);
            $sheet->setCellValue('E' . $row, $c->status);
            $sheet->setCellValue('F' . $row, $c->checker?->name);
            $sheet->setCellValue('G' . $row, $c->catatan);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'pengecekan_token_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    private function applyFilter($query, $filter)
    {
        return match ($filter) {
            'harian' => $query->whereDate('created_at', today()),
            'mingguan' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            default => $query,
        };
    }
}
