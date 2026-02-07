<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // 1. Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%$search%")
                    ->orWhere('details', 'like', "%$search%");
            });
        }

        // 2. Filter Tabs (Status/Tipe)
        if ($request->filled('filter')) {
            $filter = $request->filter;

            if ($filter == 'unread') {
                $query->where('is_read', false);
            }
            // Filter manual berdasarkan string action untuk tipe lainnya
            elseif ($filter == 'danger') {
                $query->where(function ($q) {
                    $q->where('action', 'like', '%hapus%')
                        ->orWhere('action', 'like', '%reject%')
                        ->orWhere('action', 'like', '%tolak%');
                });
            } elseif ($filter == 'success') {
                $query->where(function ($q) {
                    $q->where('action', 'like', '%approve%')
                        ->orWhere('action', 'like', '%setujui%');
                });
            } elseif ($filter == 'warning') {
                $query->where(function ($q) {
                    $q->where('action', 'like', '%edit%')
                        ->orWhere('action', 'like', '%revisi%');
                });
            } elseif ($filter == 'info') {
                // Info adalah sisanya (Upload dll)
                $query->where(function ($q) {
                    $q->where('action', 'like', '%upload%')
                        ->orWhere('action', 'like', '%login%');
                });
            }
        }

        $activities = $query->paginate(10)->withQueryString();

        return view('pages.activities.index', compact('activities'));
    }

    // Tandai satu notifikasi sudah dibaca
    public function markAsRead($id)
    {
        $activity = ActivityLog::findOrFail($id);
        $activity->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    // Tandai SEMUA dibaca
    public function markAllRead()
    {
        ActivityLog::where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
