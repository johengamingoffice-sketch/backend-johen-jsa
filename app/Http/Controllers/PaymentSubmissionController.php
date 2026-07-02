<?php

namespace App\Http\Controllers;

use App\Models\PaymentSubmission;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PaymentSubmissionController extends Controller
{
    public function tagihan()
    {
        $submissions = PaymentSubmission::with('pengaju')
            ->whereIn('status', ['disetujui', 'jatuh_tempo'])
            ->latest()
            ->paginate(20);

        return view('payment-submissions.tagihan', compact('submissions'));
    }

    public function pengajuan()
    {
        $submissions = PaymentSubmission::with('pengaju', 'approver')
            ->where('pengaju_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('payment-submissions.pengajuan', compact('submissions'));
    }

    public function persetujuan()
    {
        $submissions = PaymentSubmission::with('pengaju')
            ->where('status', 'menunggu')
            ->latest()
            ->paginate(20);

        return view('payment-submissions.persetujuan', compact('submissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['pengaju_id'] = auth()->id();
        $validated['status'] = 'menunggu';
        PaymentSubmission::create($validated);

        return redirect()->route('payment-submissions.pengajuan')->with('success', 'Pengajuan pembayaran berhasil dikirim.');
    }

    public function approve(PaymentSubmission $paymentSubmission)
    {
        if ($paymentSubmission->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $paymentSubmission->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pengajuan pembayaran berhasil disetujui.');
    }

    public function reject(Request $request, PaymentSubmission $paymentSubmission)
    {
        if ($paymentSubmission->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        $paymentSubmission->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'keterangan' => $validated['keterangan'] ?? $paymentSubmission->keterangan,
        ]);

        return back()->with('success', 'Pengajuan pembayaran berhasil ditolak.');
    }

    public function uploadBukti(Request $request, PaymentSubmission $paymentSubmission)
    {
        $validated = $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');
        $paymentSubmission->update(['bukti' => $path]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    public function markPaid(PaymentSubmission $paymentSubmission)
    {
        $paymentSubmission->update([
            'status' => 'lunas',
            'tgl_bayar' => today(),
        ]);

        return back()->with('success', 'Pembayaran berhasil ditandai lunas.');
    }

    public function destroy(PaymentSubmission $paymentSubmission)
    {
        $paymentSubmission->delete();
        return back()->with('success', 'Pengajuan pembayaran berhasil dihapus.');
    }

    public function exportTagihan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju')
            ->whereIn('status', ['disetujui', 'jatuh_tempo']);

        if ($jenis = $request->jenis) {
            $query->where('jenis', $jenis);
        }

        $submissions = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tagihan Pembayaran');

        $headers = ['No', 'Jenis', 'Detail', 'Nominal', 'Status', 'Aksi'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($submissions as $idx => $s) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $s->jenis);
            $sheet->setCellValue('C' . $row, $s->detail);
            $sheet->setCellValue('D' . $row, $s->nominal);
            $sheet->setCellValue('E' . $row, $s->status);
            $sheet->setCellValue('F' . $row, '');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'tagihan_pembayaran_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function exportPengajuan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju', 'approver')
            ->where('pengaju_id', auth()->id());

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $submissions = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengajuan Saya');

        $headers = ['No', 'Jenis', 'Detail', 'Nominal', 'Tgl Bayar', 'Status', 'Bukti', 'Approval'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($submissions as $idx => $s) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $s->jenis);
            $sheet->setCellValue('C' . $row, $s->detail);
            $sheet->setCellValue('D' . $row, $s->nominal);
            $sheet->setCellValue('E' . $row, $s->tgl_bayar?->format('d/m/Y'));
            $sheet->setCellValue('F' . $row, $s->status);
            $sheet->setCellValue('G' . $row, $s->bukti ? 'Ada' : '-');
            $sheet->setCellValue('H' . $row, $s->approver?->name ?? '-');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'pengajuan_saya_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function exportPersetujuan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju')->where('status', 'menunggu');

        if ($jenis = $request->jenis) {
            $query->where('jenis', $jenis);
        }

        $submissions = $query->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Persetujuan');

        $headers = ['No', 'Tanggal', 'Pengaju', 'Jenis', 'Detail', 'Nominal', 'Tgl Bayar', 'Bukti', 'Aksi'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $row = 2;
        foreach ($submissions as $idx => $s) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $s->created_at->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $s->pengaju?->name);
            $sheet->setCellValue('D' . $row, $s->jenis);
            $sheet->setCellValue('E' . $row, $s->detail);
            $sheet->setCellValue('F' . $row, $s->nominal);
            $sheet->setCellValue('G' . $row, $s->tgl_bayar?->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, $s->bukti ? 'Ada' : '-');
            $sheet->setCellValue('I' . $row, '');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'persetujuan_' . date('Ymd') . '.xlsx';

        $temp = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    public function dataTagihan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju')
            ->whereIn('status', ['disetujui', 'jatuh_tempo']);

        if ($jenis = $request->jenis) {
            $query->where('jenis', $jenis);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $submissions->items(),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ],
        ]);
    }

    public function dataPengajuan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju', 'approver')
            ->where('pengaju_id', $request->user()?->id ?? auth()->id());

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $submissions->items(),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ],
        ]);
    }

    public function dataPersetujuan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju')->where('status', 'menunggu');

        if ($jenis = $request->jenis) {
            $query->where('jenis', $jenis);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $submissions->items(),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ],
        ]);
    }
}
