<?php

namespace App\Http\Controllers;

use App\Models\DigitalAsset;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DigitalAssetController extends Controller
{
    public function index()
    {
        $totalAset = DigitalAsset::count();
        $totalLunas = DigitalAsset::where('status', 'lunas')->count();
        $totalJatuhTempo = DigitalAsset::where('status', 'menunggu')
            ->whereDate('jatuh_tempo', '>=', today())->count();
        $totalTerlambat = DigitalAsset::where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'menunggu')->whereDate('jatuh_tempo', '<', today());
            })->count();

        $totalNominal = DigitalAsset::sum('nominal');
        $nominalLunas = DigitalAsset::where('status', 'lunas')->sum('nominal');
        $nominalMenunggu = DigitalAsset::where('status', 'menunggu')
            ->whereDate('jatuh_tempo', '>=', today())->sum('nominal');
        $nominalTerlambat = DigitalAsset::whereIn('status', ['terlambat'])
            ->orWhere(function ($q) {
                $q->where('status', 'menunggu')->whereDate('jatuh_tempo', '<', today());
            })->sum('nominal');

        $assets = DigitalAsset::with('creator')->latest()->paginate(20);

        return view('digital.index', compact(
            'totalAset', 'totalLunas', 'totalJatuhTempo', 'totalTerlambat',
            'totalNominal', 'nominalLunas', 'nominalMenunggu', 'nominalTerlambat',
            'assets'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        DigitalAsset::create($validated);

        return redirect()->route('digital.index')->with('success', 'Tagihan aset digital berhasil ditambahkan.');
    }

    public function update(Request $request, DigitalAsset $digitalAsset)
    {
        $validated = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $digitalAsset->update($validated);

        return redirect()->route('digital.index')->with('success', 'Tagihan aset digital berhasil diperbarui.');
    }

    public function destroy(DigitalAsset $digitalAsset)
    {
        $digitalAsset->delete();
        return back()->with('success', 'Tagihan aset digital berhasil dihapus.');
    }

    public function markPaid(DigitalAsset $digitalAsset)
    {
        $digitalAsset->update([
            'status' => 'lunas',
            'tgl_bayar' => today(),
        ]);

        return back()->with('success', 'Tagihan aset digital berhasil ditandai lunas.');
    }

    public function export(Request $request)
    {
        $query = DigitalAsset::with('creator');
        $assets = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Aset Digital');

        $headers = ['No', 'Nama Aset', 'Tagihan', 'Jatuh Tempo', 'Nominal', 'Status', 'Tgl Bayar', 'Oleh'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($assets as $idx => $a) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $a->nama_aset);
            $sheet->setCellValue('C' . $row, $a->tagihan);
            $sheet->setCellValue('D' . $row, $a->jatuh_tempo->format('d/m/Y'));
            $sheet->setCellValue('E' . $row, $a->nominal);
            $sheet->setCellValue('F' . $row, $a->status);
            $sheet->setCellValue('G' . $row, $a->tgl_bayar?->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, $a->creator?->name);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'aset_digital_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function data(Request $request)
    {
        $query = DigitalAsset::with('creator');

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
                $q->where('nama_aset', 'like', "%{$search}%")
                    ->orWhere('tagihan', 'like', "%{$search}%");
            });
        }

        $assets = $query->latest()->paginate($request->per_page ?? 20);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $assets->items(),
                'meta' => [
                    'current_page' => $assets->currentPage(),
                    'last_page' => $assets->lastPage(),
                    'total' => $assets->total(),
                ],
            ]);
        }

        return $assets;
    }
}
