<?php

namespace App\Http\Controllers;

use App\Models\InternetPayment;
use App\Models\InternetUsageCheck;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InternetController extends Controller
{
    public function index()
    {
        $totalWifi = InternetPayment::count();
        $sudahDibayar = InternetPayment::where('status', 'lunas')->count();
        $jatuhTempo = InternetPayment::where('status', 'menunggu')
            ->where('masa_tenggang', '>=', now())
            ->count();
        $terlambat = InternetPayment::where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'menunggu')
                  ->where('masa_tenggang', '<', now());
            })
            ->count();

        $payments = InternetPayment::with('creator')->latest()->paginate(20);
        $checks = InternetUsageCheck::with('checker')->latest()->paginate(20);

        return view('internet.index', compact(
            'totalWifi', 'sudahDibayar', 'jatuhTempo', 'terlambat',
            'payments', 'checks'
        ));
    }

    public function paymentsData(Request $request)
    {
        $query = InternetPayment::with('creator');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_internet', 'like', "%{$request->search}%")
                  ->orWhere('provider', 'like', "%{$request->search}%")
                  ->orWhere('pic', 'like', "%{$request->search}%");
            });
        }

        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(20);

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

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'nama_internet' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_tenggang' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'status' => 'nullable|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        InternetPayment::create($validated);

        return redirect()->route('internet.index')->with('success', 'Tagihan internet berhasil ditambahkan.');
    }

    public function updatePayment(Request $request, InternetPayment $internetPayment)
    {
        $validated = $request->validate([
            'nama_internet' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_tenggang' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'status' => 'nullable|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $internetPayment->update($validated);

        return redirect()->route('internet.index')->with('success', 'Tagihan internet berhasil diupdate.');
    }

    public function destroyPayment(InternetPayment $internetPayment)
    {
        $internetPayment->delete();
        return back()->with('success', 'Tagihan internet berhasil dihapus.');
    }

    public function checksData(Request $request)
    {
        $query = InternetUsageCheck::with('checker');

        if ($request->month && $request->year) {
            $query->whereMonth('tanggal', $request->month)
                  ->whereYear('tanggal', $request->year);
        }

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
            'ruangan' => 'required|string|max:255',
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'penggunaan_wifi' => 'required|numeric|min:0',
            'penggunaan_ethernet' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['checked_by'] = auth()->id();
        InternetUsageCheck::create($validated);

        return redirect()->route('internet.index')->with('success', 'Pengecekan usage internet berhasil dicatat.');
    }

    public function destroyCheck(InternetUsageCheck $internetUsageCheck)
    {
        $internetUsageCheck->delete();
        return back()->with('success', 'Data pengecekan berhasil dihapus.');
    }

    public function exportPayments(Request $request)
    {
        $query = InternetPayment::with('creator');

        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pembayaran Internet');

        $headers = ['No', 'Nama Internet', 'Provider', 'PIC', 'Jabatan', 'Masa Tenggang', 'Biaya', 'Status', 'Tgl Bayar', 'Keterangan'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($payments as $idx => $p) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $p->nama_internet);
            $sheet->setCellValue('C' . $row, $p->provider);
            $sheet->setCellValue('D' . $row, $p->pic);
            $sheet->setCellValue('E' . $row, $p->jabatan);
            $sheet->setCellValue('F' . $row, $p->masa_tenggang->format('d/m/Y'));
            $sheet->setCellValue('G' . $row, $p->biaya);
            $sheet->setCellValue('H' . $row, $p->status);
            $sheet->setCellValue('I' . $row, $p->tgl_bayar?->format('d/m/Y') ?? '-');
            $sheet->setCellValue('J' . $row, $p->keterangan);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'pembayaran_internet_' . date('Ymd') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function exportChecks(Request $request)
    {
        $query = InternetUsageCheck::with('checker');

        if ($request->month && $request->year) {
            $query->whereMonth('tanggal', $request->month)
                  ->whereYear('tanggal', $request->year);
        }

        $checks = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Usage Internet');

        $headers = ['No', 'Ruangan', 'Hari', 'Tanggal', 'Penggunaan Wifi', 'Penggunaan Ethernet', 'Pengecek', 'Keterangan'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($checks as $idx => $c) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $c->ruangan);
            $sheet->setCellValue('C' . $row, $c->hari);
            $sheet->setCellValue('D' . $row, $c->tanggal->format('d/m/Y'));
            $sheet->setCellValue('E' . $row, $c->penggunaan_wifi);
            $sheet->setCellValue('F' . $row, $c->penggunaan_ethernet);
            $sheet->setCellValue('G' . $row, $c->checker?->name);
            $sheet->setCellValue('H' . $row, $c->keterangan);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'usage_internet_' . date('Ymd') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }
}
