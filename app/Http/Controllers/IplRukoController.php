<?php

namespace App\Http\Controllers;

use App\Models\IplRukoPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IplRukoController extends Controller
{
    public function index()
    {
        $totalTagihan = IplRukoPayment::count();
        $totalLunas = IplRukoPayment::where('status', 'lunas')->count();
        $totalTerlambat = IplRukoPayment::where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'menunggu')->whereDate('jatuh_tempo', '<', today());
            })->count();
        $totalMenunggu = IplRukoPayment::where('status', 'menunggu')
            ->whereDate('jatuh_tempo', '>=', today())->count();

        $payments = IplRukoPayment::with('creator')->latest()->paginate(20);

        return view('ipl.index', compact(
            'totalTagihan', 'totalLunas', 'totalTerlambat', 'totalMenunggu', 'payments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|string|max:50',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        IplRukoPayment::create($validated);

        return redirect()->route('ipl.index')->with('success', 'Tagihan IPL Ruko berhasil ditambahkan.');
    }

    public function update(Request $request, IplRukoPayment $iplRukoPayment)
    {
        $validated = $request->validate([
            'periode' => 'required|string|max:50',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $iplRukoPayment->update($validated);

        return redirect()->route('ipl.index')->with('success', 'Tagihan IPL Ruko berhasil diperbarui.');
    }

    public function destroy(IplRukoPayment $iplRukoPayment)
    {
        $iplRukoPayment->delete();
        return back()->with('success', 'Tagihan IPL Ruko berhasil dihapus.');
    }

    public function markPaid(IplRukoPayment $iplRukoPayment)
    {
        $iplRukoPayment->update([
            'status' => 'lunas',
            'tgl_bayar' => today(),
        ]);

        return back()->with('success', 'Tagihan IPL Ruko berhasil ditandai lunas.');
    }

    public function generateYear(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2100',
            'tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'jatuh_tempo_hari' => 'required|integer|min:1|max:28',
        ]);

        $tahun = $request->tahun;
        $createdBy = auth()->id();
        $count = 0;

        $namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $periode = $namaBulan[$bulan - 1] . ' ' . $tahun;
            $jatuhTempo = Carbon::create($tahun, $bulan, $request->jatuh_tempo_hari);

            $exists = IplRukoPayment::where('periode', $periode)->exists();
            if ($exists) {
                continue;
            }

            IplRukoPayment::create([
                'periode' => $periode,
                'tagihan' => $request->tagihan,
                'jatuh_tempo' => $jatuhTempo,
                'nominal' => $request->nominal,
                'status' => 'menunggu',
                'created_by' => $createdBy,
            ]);

            $count++;
        }

        return redirect()->route('ipl.index')->with('success', "Berhasil generate {$count} tagihan IPL Ruko untuk tahun {$tahun}.");
    }

    public function export(Request $request)
    {
        $query = IplRukoPayment::with('creator');
        $payments = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('IPL Ruko');

        $headers = ['No', 'Periode', 'Tagihan', 'Jatuh Tempo', 'Nominal', 'Status', 'Tgl Bayar', 'Oleh'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($payments as $idx => $p) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $p->periode);
            $sheet->setCellValue('C' . $row, $p->tagihan);
            $sheet->setCellValue('D' . $row, $p->jatuh_tempo->format('d/m/Y'));
            $sheet->setCellValue('E' . $row, $p->nominal);
            $sheet->setCellValue('F' . $row, $p->status);
            $sheet->setCellValue('G' . $row, $p->tgl_bayar?->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, $p->creator?->name);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'ipl_ruko_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function data(Request $request)
    {
        $query = IplRukoPayment::with('creator');

        if ($status = $request->status) {
            if ($status === 'terlambat') {
                $query->where(function ($q) {
                    $q->where('status', 'terlambat')
                        ->orWhere(function ($q2) {
                            $q2->where('status', 'menunggu')->whereDate('jatuh_tempo', '<', today());
                        });
                });
            } else {
                $query->where('status', $status);
            }
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('periode', 'like', "%{$search}%")
                    ->orWhere('tagihan', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate($request->per_page ?? 20);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $payments->items(),
                'meta' => [
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                    'total' => $payments->total(),
                ],
            ]);
        }

        return $payments;
    }
}
