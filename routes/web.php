<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\WaliKelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\QrAbsensiController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } elseif (auth()->user()->role === 'kepala_sekolah') {
            return redirect()->route('kepsek.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
//     Route::get('/dashboard', fn () => view('admin.dashboard'));
// });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/dashboard/chart-kehadiran', [DashboardController::class, 'getChartData'])
            ->name('dashboard.chart');

        // SISWA CRUD (FULL)
        Route::get('/siswa', [SiswaController::class, 'index'])
            ->name('siswa.index');
        Route::post('/siswa/clean-graduated', [SiswaController::class, 'cleanGraduated'])
            ->name('siswa.clean-graduated');



        Route::post('/siswa', [SiswaController::class, 'store'])
            ->name('siswa.store');



        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])
            ->name('siswa.update');

        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])
            ->name('siswa.destroy');

        // GURU CRUD (FULL)
        Route::get('/guru', [GuruController::class, 'index'])
            ->name('guru.index');



        Route::post('/guru', [GuruController::class, 'store'])
            ->name('guru.store');



        Route::put('/guru/{guru}', [GuruController::class, 'update'])
            ->name('guru.update');

        Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])
            ->name('guru.destroy');

        Route::get('/guru-export', [GuruController::class, 'export'])->name('guru.export');
        Route::post('/guru-import', [GuruController::class, 'import'])->name('guru.import');
        Route::get('/guru-template', [GuruController::class, 'downloadTemplate'])->name('guru.template');

        // KELAS MANAGEMENT (EXTENDED)
        Route::get('/kelas-template', [KelasController::class, 'downloadTemplate'])->name('kelas.template');
        Route::post('/bulk-import-kelas', [KelasController::class, 'bulkImport'])->name('kelas.bulk-import');
        Route::post('/kelas/reset-tahun-ajaran', [KelasController::class, 'resetTahunAjaran'])->name('kelas.reset');

        // Manajemen Admin
        Route::resource('manajemen-admin', AdminController::class)->except(['create', 'edit'])->names('admins');

        // Manajemen Tahun Ajaran
        Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
        Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
        Route::put('/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
        Route::post('/tahun-ajaran/{tahunAjaran}/set-aktif', [TahunAjaranController::class, 'setAktif'])->name('tahun-ajaran.set-aktif');
        Route::get('/tahun-ajaran/{tahunAjaran}/archive', [TahunAjaranController::class, 'exportArchive'])->name('tahun-ajaran.archive');
        Route::post('/tahun-ajaran/{tahunAjaran}/freeze', [TahunAjaranController::class, 'freezeArchive'])->name('tahun-ajaran.freeze');
        Route::delete('/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');

        Route::resource('kelas', KelasController::class)->except(['create', 'edit'])->names([
            'index'   => 'kelas.index',
            'store'   => 'kelas.store',
            'show'    => 'kelas.show',
            'update'  => 'kelas.update',
            'destroy' => 'kelas.destroy',
        ]);

        Route::post('/kelas/{kela}/assign', [KelasController::class, 'assignSiswa'])->name('kelas.assign');
        Route::delete('/kelas/{kela}/remove-siswa/{siswa}', [KelasController::class, 'removeSiswa'])->name('kelas.remove-siswa');
        Route::post('/kelas/{kela}/import', [KelasController::class, 'importSiswa'])->name('kelas.import');

        // MATA PELAJARAN CRUD (FULL)
        Route::get('/mapel', [MataPelajaranController::class, 'index'])
            ->name('mapel.index');



        Route::post('/mapel', [MataPelajaranController::class, 'store'])
            ->name('mapel.store');


        Route::put('/mapel/{mapel}', [MataPelajaranController::class, 'update'])
            ->name('mapel.update');

        Route::delete('/mapel/{mapel}', [MataPelajaranController::class, 'destroy'])
            ->name('mapel.destroy');

        Route::get('/mapel-export', [MataPelajaranController::class, 'export'])->name('mapel.export');
        Route::post('/mapel-import', [MataPelajaranController::class, 'import'])->name('mapel.import');
        Route::get('/mapel-template', [MataPelajaranController::class, 'downloadTemplate'])->name('mapel.template');

        // WALI KELAS CRUD
        Route::get('/walikelas', [WaliKelasController::class, 'index'])
            ->name('walikelas.index');



        Route::put('/walikelas/{walikelas}', [WaliKelasController::class, 'update'])
            ->name('walikelas.update');

        Route::post('/walikelas', [WaliKelasController::class, 'store'])
            ->name('walikelas.store');

        Route::delete('/walikelas/{walikelas}', [WaliKelasController::class, 'destroy'])
            ->name('walikelas.destroy');

        // LAPORAN SISWA
        Route::get('/laporan/siswa', [LaporanController::class, 'siswaIndex'])
            ->name('laporan.siswa.index');

        Route::get('/laporan/siswa/export', [LaporanController::class, 'siswaExport'])
            ->name('laporan.siswa.export');

        // LAPORAN GURU
        Route::get('/laporan/guru', [LaporanController::class, 'guruIndex'])
            ->name('laporan.guru.index');

        Route::get('/laporan/guru/export', [LaporanController::class, 'guruExport'])
            ->name('laporan.guru.export');

        // QR PRESENSI
        Route::get('/qr-presensi', [QrAbsensiController::class, 'index'])
            ->name('qr-absensi.index');

        Route::post('/qr-presensi/generate', [QrAbsensiController::class, 'generate'])
            ->name('qr-absensi.generate');

        Route::post('/qr-presensi/settings', [QrAbsensiController::class, 'updateSettings'])
            ->name('qr-absensi.settings.update');

        Route::post('/qr-presensi/status/toggle', [QrAbsensiController::class, 'toggleStatus'])
            ->name('qr-absensi.status.toggle');

        Route::post('/qr-presensi/mark-manual', [QrAbsensiController::class, 'markManual'])
            ->name('qr-absensi.mark-manual');

        Route::get('/qr-presensi/refresh', [QrAbsensiController::class, 'refreshQr'])
            ->name('qr-absensi.refresh');

        Route::get('/qr-presensi/full', [QrAbsensiController::class, 'fullView'])
            ->name('qr-absensi.full');

        Route::get('/qr-presensi/stats', [QrAbsensiController::class, 'getStats'])
            ->name('qr-absensi.stats');

    });

    // GURU ROUTES
    Route::middleware(['auth', 'guru'])
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');
            
            Route::get('/scan-absensi', [\App\Http\Controllers\Guru\AbsensiController::class, 'scan'])->name('scan');
            
            Route::get('/riwayat-absensi', [\App\Http\Controllers\Guru\RiwayatAbsensiController::class, 'index'])->name('riwayat-absensi');
            
            Route::get('/input-presensi', [\App\Http\Controllers\Guru\AbsensiSiswaController::class, 'index'])->name('absensi-siswa.index');
            Route::post('/input-presensi', [\App\Http\Controllers\Guru\AbsensiSiswaController::class, 'store'])->name('absensi-siswa.store');
            
            Route::get('/password', [\App\Http\Controllers\Guru\PasswordController::class, 'edit'])->name('password.edit');
            Route::put('/password', [\App\Http\Controllers\Guru\PasswordController::class, 'update'])->name('password.update');

            Route::get('/laporan-kelas', [\App\Http\Controllers\Guru\LaporanKelasController::class, 'index'])->name('laporan-kelas');
            Route::get('/laporan-kelas/export', [\App\Http\Controllers\Guru\LaporanKelasController::class, 'export'])->name('laporan-kelas.export');
            
            Route::get('/rekap-absensi', [\App\Http\Controllers\Guru\RekapAbsensiController::class, 'index'])->name('rekap-absensi.index');
            Route::get('/rekap-absensi/detail', [\App\Http\Controllers\Guru\RekapAbsensiController::class, 'show'])->name('rekap-absensi.show');
            Route::get('/rekap-absensi/export', [\App\Http\Controllers\Guru\RekapAbsensiController::class, 'export'])->name('rekap-absensi.export');
        });

    // SCAN QR — route publik (guru scan dari HP, hanya perlu login)
    Route::middleware(['auth'])->group(function () {
        Route::get('/presensi/scan/{token}', [QrAbsensiController::class, 'scanPage'])
            ->name('absensi.scan');

        Route::post('/presensi/scan/{token}', [QrAbsensiController::class, 'scanProcess'])
            ->name('absensi.scan.process');
    });

    // KEPALA SEKOLAH ROUTES
    Route::middleware(['auth', 'kepala_sekolah'])
        ->prefix('kepala-sekolah')
        ->name('kepsek.')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\KepalaSekolah\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/chart-kehadiran', [\App\Http\Controllers\KepalaSekolah\DashboardController::class, 'getChartData'])->name('dashboard.chart');
            
            // LAPORAN SISWA
            Route::get('/laporan/siswa', [\App\Http\Controllers\KepalaSekolah\LaporanController::class, 'siswaIndex'])->name('laporan.siswa.index');
            Route::get('/laporan/siswa/export', [\App\Http\Controllers\KepalaSekolah\LaporanController::class, 'siswaExport'])->name('laporan.siswa.export');

            // LAPORAN GURU
            Route::get('/laporan/guru', [\App\Http\Controllers\KepalaSekolah\LaporanController::class, 'guruIndex'])->name('laporan.guru.index');
            Route::get('/laporan/guru/export', [\App\Http\Controllers\KepalaSekolah\LaporanController::class, 'guruExport'])->name('laporan.guru.export');
        });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
