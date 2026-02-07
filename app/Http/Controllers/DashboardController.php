<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Statistik Global (Tetap hitung semua agar angka kartu stabil)
        $stats = [
            'total'    => Document::count(),
            'approved' => Document::where('status', 'approved')->count(),
            'rejected' => Document::where('status', 'rejected')->count(),
            'today'    => Document::whereDate('created_at', Carbon::today())->count(),
        ];

        // 2. Query Dasar
        $query = Document::with('user'); // Eager loading user

        // 3. Filter Lifecycle (Aktif/Tidak Aktif)
        if ($request->has('lifecycle') && $request->lifecycle !== 'all') {
            $isActive = $request->lifecycle === 'active';
            $query->where('is_active', $isActive);
        }

        // 4. Filter Approval (Status)
        if ($request->has('approval') && $request->approval !== 'all') {
            if ($request->approval === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } else {
                $query->where('status', $request->approval);
            }
        }

        // 5. Ambil Data dengan Pagination & Append Query String
        $documents = $query->latest()
            ->paginate(10)
            ->appends($request->all());

        return view('pages.dashboard', compact('documents', 'stats'));
    }
}
