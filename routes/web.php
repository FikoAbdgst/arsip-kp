<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (Admin & Supervisor)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dokumen (Teller & CS)
    Route::get('/teller', [DocumentController::class, 'indexTeller'])->name('teller.index');
    Route::get('/cs', [DocumentController::class, 'indexCs'])->name('cs.index');

    // Create & Update (Admin Only logic handled in blade or middleware, but simple role check here)
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Verifikasi (Supervisor Only)
    // Sebaiknya bungkus dengan middleware role:supervisor jika ada
    Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification/{id}/approve', [VerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verification/{id}/reject', [VerificationController::class, 'reject'])->name('verification.reject');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');



    // Profile standard routes...
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::post('/activity/{id}/read', [ActivityLogController::class, 'markAsRead'])->name('activity.read');
    Route::post('/activity/read-all', [ActivityLogController::class, 'markAllRead'])->name('activity.readAll');
});

require __DIR__ . '/auth.php';
