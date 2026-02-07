<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Jalankan pengecekan lifecycle otomatis setiap buka dashboard (opsional, lebih baik pakai Scheduler)
        // Document::all()->each->checkLifecycle();

        $totalDocuments = Document::count();
        $pendingDocuments = Document::where('status', 'pending')->count();
        $approvedDocuments = Document::where('status', 'approved')->count();
        $rejectedDocuments = Document::where('status', 'rejected')->count();

        // Ambil data rejected untuk ditampilkan di list jika user mengklik card rejected
        $rejectedList = Document::where('status', 'rejected')->latest()->get();

        return view('pages.dashboard', compact(
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'rejectedDocuments',
            'rejectedList'
        ));
    }
}
