<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index()
    {
        // Hanya tampilkan yang pending
        $pendingDocuments = Document::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('pages.verifikasi.index', compact('pendingDocuments'));
    }

    public function approve($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['status' => 'approved']);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Verifikasi Dokumen',
            'details' => 'Menyetujui dokumen No: ' . $document->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);

        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Verifikasi Dokumen',
            'details' => 'Menolak dokumen No: ' . $document->document_number . '. Alasan: ' . $request->reason
        ]);

        return redirect()->back()->with('warning', 'Dokumen ditolak.');
    }
}
