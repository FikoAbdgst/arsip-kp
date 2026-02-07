<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Landing
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Pages (After Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard utama
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    // Dashboard per divisi (halaman filter, bukan role)
    Route::get('/teller', function () {
        return view('pages.teller.dashboard');
    })->name('teller.dashboard');

    Route::get('/cs', function () {
        return view('pages.cs.dashboard');
    })->name('cs.dashboard');

    // Notifications
    Route::get('/notifications', function () {
        return view('pages.notifications.index');
    })->name('notifications.index');

    // Reports
    Route::get('/reports', function () {
        return view('pages.reports.index');
    })->name('reports.index');
});

/*
|--------------------------------------------------------------------------
| Admin only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    // FORM CREATE harus DI ATAS route /dokumen/{no}
    Route::get('/dokumen/create', function () {
        return view('pages.dokumen.create');
    })->name('dokumen.create');

});

/*
|--------------------------------------------------------------------------
| Supervisor only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supervisor'])->group(function () {

    Route::get('/verifikasi', function () {
        return view('pages.verifikasi.index');
    })->name('verifikasi.index');

});

/*
|--------------------------------------------------------------------------
| Dokumen Detail (untuk semua user login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dokumen/{no}', function ($no) {
        return view('pages.dokumen.show', compact('no'));
    })
    ->where('no', '^(?!create$).+') // cegah "create" masuk ke {no}
    ->name('dokumen.show');

});

/*
|--------------------------------------------------------------------------
| Profile (Breeze default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
