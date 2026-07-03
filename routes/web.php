<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ManualBookController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayrollImportController;
use App\Http\Controllers\PayrollPreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Livewire\AbsensiTable;
use App\Livewire\CutiIzinTable;
use App\Livewire\KontrakKerjaTable;
use App\Livewire\UserTable;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\DigitalAssetController;
use App\Http\Controllers\ElectricityController;
use App\Http\Controllers\InternetController;
use App\Http\Controllers\IplRukoController;
use App\Http\Controllers\PaymentSubmissionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AssetViewController;
use App\Http\Controllers\JobdeskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('hris')->name('hris.')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::post('/employees/{employee}/photo', [EmployeeController::class, 'uploadPhoto'])->name('employees.upload-photo');
        Route::post('/employees/{employee}/documents', [EmployeeController::class, 'storeDocument'])->name('employees.store-document');
        Route::get('/employees/{employee}/documents/{document}/download', [EmployeeController::class, 'downloadDocument'])->name('employees.download-document');
        Route::delete('/employees/{employee}/documents/{document}', [EmployeeController::class, 'destroyDocument'])->name('employees.destroy-document');

        Route::post('/employees/{employee}/contracts', [EmployeeController::class, 'storeContract'])->name('employees.store-contract');
        Route::get('/employees/{employee}/contracts/{contract}', [EmployeeController::class, 'getContract'])->name('employees.get-contract');
        Route::delete('/employees/{employee}/contracts/{contract}', [EmployeeController::class, 'destroyContract'])->name('employees.destroy-contract');
        Route::put('/employees/{employee}/contracts/{contract}', [EmployeeController::class, 'updateContract'])->name('employees.update-contract');

        Route::post('/employees/{employee}/position-histories', [EmployeeController::class, 'storePositionHistory'])->name('employees.store-position-history');
        Route::delete('/employees/{employee}/position-histories/{positionHistory}', [EmployeeController::class, 'destroyPositionHistory'])->name('employees.destroy-position-history');

        Route::post('/employees/{employee}/promotions', [PromotionController::class, 'store'])->name('employees.store-promotion');
        Route::delete('/employees/{employee}/promotions/{promotion}', [PromotionController::class, 'destroy'])->name('employees.destroy-promotion');
        Route::get('/employees/{employee}/promotions/{promotion}/download', [PromotionController::class, 'downloadPdf'])->name('employees.download-promotion-pdf');

        Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
        Route::get('/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])->name('struktur-organisasi');
        Route::get('/absensi', AbsensiTable::class)->name('absensi');
        Route::get('/cuti-izin', CutiIzinTable::class)->name('cuti-izin');
        Route::get('/kontrak-kerja', KontrakKerjaTable::class)->name('kontrak-kerja');
        Route::get('/manual-book', [ManualBookController::class, 'index'])->name('manual-book');
        Route::get('/jobdesk', [JobdeskController::class, 'index'])->name('jobdesk');

        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/employees', [ExportController::class, 'employees'])->name('employees');
            Route::get('/divisions', [ExportController::class, 'divisions'])->name('divisions');
            Route::get('/kontrak-kerja', [ExportController::class, 'kontrakKerja'])->name('kontrak-kerja');
        });
    });

    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/upload', [PayrollImportController::class, 'create'])->name('upload');
        Route::post('/upload', [PayrollImportController::class, 'store'])->name('store');
        Route::delete('/{import}', [PayrollImportController::class, 'destroy'])->name('destroy');
        Route::get('/{import}/preview', [PayrollPreviewController::class, 'index'])->name('preview');
        Route::post('/{import}/generate', [PayrollController::class, 'generate'])->name('generate');
        Route::get('/{import}', [PayrollController::class, 'show'])->name('show');
        Route::get('/detail/{detail}/download', [PayrollController::class, 'downloadPdf'])->name('download-pdf');

        Route::get('/{import}/progress-json', [PayrollController::class, 'progressJson'])->name('progress-json');
        Route::get('/{import}/email-logs', [EmailLogController::class, 'index'])->name('email-logs');
        Route::post('/email-logs/{detail}/retry', [EmailLogController::class, 'retry'])->name('email-retry');
        Route::post('/{import}/retry-all', [EmailLogController::class, 'retryAll'])->name('email-retry-all');
    });

    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/{import}', [HistoryController::class, 'show'])->name('show');
    });

    Route::prefix('meeting')->name('meeting.')->group(function () {
        Route::get('/jadwal', [MeetingController::class, 'jadwal'])->name('jadwal');
        Route::get('/permintaan', [MeetingController::class, 'permintaan'])->name('permintaan');
        Route::post('/permintaan', [MeetingController::class, 'storePermintaan'])->name('permintaan.store');
        Route::put('/permintaan/{meetingRequest}/setujui', [MeetingController::class, 'setujui'])->name('permintaan.setujui');
        Route::put('/permintaan/{meetingRequest}/tolak', [MeetingController::class, 'tolak'])->name('permintaan.tolak');
    });

    Route::prefix('bonus')->name('bonus.')->group(function () {
        Route::get('/', [BonusController::class, 'index'])->name('index');
    });

    Route::prefix('electricity')->name('electricity.')->group(function () {
        Route::get('/', [ElectricityController::class, 'index'])->name('index');
        Route::get('/topups-data', [ElectricityController::class, 'topupsData'])->name('topups.data');
        Route::get('/checks-data', [ElectricityController::class, 'checksData'])->name('checks.data');
        Route::get('/stats', [ElectricityController::class, 'stats'])->name('stats');
        Route::post('/topups', [ElectricityController::class, 'storeTopup'])->name('store.topup');
        Route::delete('/topups/{electricityTopup}', [ElectricityController::class, 'destroyTopup'])->name('destroy.topup');
        Route::post('/checks', [ElectricityController::class, 'storeCheck'])->name('store.check');
        Route::delete('/checks/{electricityTokenCheck}', [ElectricityController::class, 'destroyCheck'])->name('destroy.check');
        Route::put('/settings', [ElectricityController::class, 'updateSettings'])->name('update.settings');
        Route::get('/export/topups', [ElectricityController::class, 'exportTopups'])->name('export.topups');
        Route::get('/export/checks', [ElectricityController::class, 'exportChecks'])->name('export.checks');
    });

    Route::prefix('internet')->name('internet.')->group(function () {
        Route::get('/', [InternetController::class, 'index'])->name('index');
        Route::get('/payments-data', [InternetController::class, 'paymentsData'])->name('payments.data');
        Route::get('/checks-data', [InternetController::class, 'checksData'])->name('checks.data');
        Route::post('/', [InternetController::class, 'storePayment'])->name('store.payment');
        Route::put('/{internetPayment}', [InternetController::class, 'updatePayment'])->name('update.payment');
        Route::delete('/{internetPayment}', [InternetController::class, 'destroyPayment'])->name('destroy.payment');
        Route::post('/checks', [InternetController::class, 'storeCheck'])->name('store.check');
        Route::delete('/checks/{internetUsageCheck}', [InternetController::class, 'destroyCheck'])->name('destroy.check');
        Route::get('/export/payments', [InternetController::class, 'exportPayments'])->name('export.payments');
        Route::get('/export/checks', [InternetController::class, 'exportChecks'])->name('export.checks');
    });

    Route::prefix('digital')->name('digital.')->group(function () {
        Route::get('/', [DigitalAssetController::class, 'index'])->name('index');
        Route::get('/data', [DigitalAssetController::class, 'data'])->name('data');
        Route::post('/', [DigitalAssetController::class, 'store'])->name('store');
        Route::put('/{digitalAsset}', [DigitalAssetController::class, 'update'])->name('update');
        Route::delete('/{digitalAsset}', [DigitalAssetController::class, 'destroy'])->name('destroy');
        Route::patch('/{digitalAsset}/mark-paid', [DigitalAssetController::class, 'markPaid'])->name('mark-paid');
        Route::get('/export', [DigitalAssetController::class, 'export'])->name('export');
    });

    Route::prefix('ipl')->name('ipl.')->group(function () {
        Route::get('/', [IplRukoController::class, 'index'])->name('index');
        Route::get('/data', [IplRukoController::class, 'data'])->name('data');
        Route::post('/', [IplRukoController::class, 'store'])->name('store');
        Route::put('/{iplRukoPayment}', [IplRukoController::class, 'update'])->name('update');
        Route::delete('/{iplRukoPayment}', [IplRukoController::class, 'destroy'])->name('destroy');
        Route::patch('/{iplRukoPayment}/mark-paid', [IplRukoController::class, 'markPaid'])->name('mark-paid');
        Route::post('/generate', [IplRukoController::class, 'generateYear'])->name('generate');
        Route::get('/export', [IplRukoController::class, 'export'])->name('export');
    });

    Route::prefix('payment-submissions')->name('payment-submissions.')->group(function () {
        Route::get('/tagihan', [PaymentSubmissionController::class, 'tagihan'])->name('tagihan');
        Route::get('/pengajuan', [PaymentSubmissionController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/persetujuan', [PaymentSubmissionController::class, 'persetujuan'])->name('persetujuan');
        Route::post('/', [PaymentSubmissionController::class, 'store'])->name('store');
        Route::patch('/{paymentSubmission}/approve', [PaymentSubmissionController::class, 'approve'])->name('approve');
        Route::patch('/{paymentSubmission}/reject', [PaymentSubmissionController::class, 'reject'])->name('reject');
        Route::post('/{paymentSubmission}/upload-bukti', [PaymentSubmissionController::class, 'uploadBukti'])->name('upload-bukti');
        Route::patch('/{paymentSubmission}/mark-paid', [PaymentSubmissionController::class, 'markPaid'])->name('mark-paid');
        Route::delete('/{paymentSubmission}', [PaymentSubmissionController::class, 'destroy'])->name('destroy');
        Route::get('/export/tagihan', [PaymentSubmissionController::class, 'exportTagihan'])->name('export.tagihan');
        Route::get('/export/pengajuan', [PaymentSubmissionController::class, 'exportPengajuan'])->name('export.pengajuan');
        Route::get('/export/persetujuan', [PaymentSubmissionController::class, 'exportPersetujuan'])->name('export.persetujuan');
        Route::get('/data/tagihan', [PaymentSubmissionController::class, 'dataTagihan'])->name('data.tagihan');
        Route::get('/data/pengajuan', [PaymentSubmissionController::class, 'dataPengajuan'])->name('data.pengajuan');
        Route::get('/data/persetujuan', [PaymentSubmissionController::class, 'dataPersetujuan'])->name('data.persetujuan');
    });

    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [AssetViewController::class, 'index'])->name('index');
        Route::get('/{category}', [AssetViewController::class, 'index'])->name('category');
    });

    Route::get('/reimbursement', function () {
        return redirect()->route('payment-submissions.pengajuan');
    })->name('reimbursement');

    Route::get('/kelola-akun', UserTable::class)->name('kelola-akun');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/pin', [ProfileController::class, 'pin'])->name('profile.pin');
    Route::post('/profile/pin', [ProfileController::class, 'updatePin'])->name('profile.pin.update');

});

require __DIR__.'/auth.php';
