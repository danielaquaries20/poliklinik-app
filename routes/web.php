<?php


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\PoliController;
use App\Http\Controllers\Pasien\DashboardController;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pasien\PembayaranController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\LaporanController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/dokter/export-excel', [DokterController::class, 'exportExcel'])->name('admin.dokter.export');
    Route::get('/pasien/export-excel', [PasienController::class, 'exportExcel'])->name('admin.pasien.export');
    Route::get('/obat/export-excel', [ObatController::class, 'exportExcel'])->name('admin.obat.export');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('admin.laporan.export');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
    Route::get('/verifikasi-pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('admin.verifikasi.index');
    Route::post('/verifikasi-pembayaran/{id}', [VerifikasiPembayaranController::class, 'verifikasi'])->name('admin.verifikasi.konfirmasi');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');

});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('pasien.dashboard');
    // })->name('pasien.dashboard');

    // Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');

    // Route::post('/daftar', [PoliController::class, 'submit'])
    //     ->name('pasien.daftar.submit');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pasien.dashboard');
    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');

    // Rute Riwayat Pendaftaran Poli
    Route::get('/riwayat-pendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('pasien.riwayat-pendaftaran.index');
    Route::get('/riwayat-pendaftaran/{id}', [RiwayatPendaftaranController::class, 'show'])->name('pasien.riwayat-pendaftaran.show');

    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pasien.pembayaran.store');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::get('/jadwal-periksa/export-excel', [JadwalPeriksaController::class, 'exportExcel'])->name('dokter.jadwal.export');
    Route::get('/riwayat-pasien/export-excel', [RiwayatPasienController::class, 'exportExcel'])->name('dokter.riwayat.export');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');

    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});

