<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    // Tampilkan daftar pending
    public function index()
    {
        $pendingDocuments = Document::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pages.verifikasi.index', compact('pendingDocuments'));
    }

    // Approve Dokumen -> Type: Success
    public function approve($id)
    {
        $document = Document::findOrFail($id);

        $document->update([
            'status' => 'approved',
            'rejection_reason' => null // Hapus alasan tolak jika ada
        ]);

        // Catat Log (Kata kunci 'Approve' akan dideteksi sebagai SUCCESS)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Approve Dokumen',
            'details' => "Menyetujui dokumen No: {$document->document_number} ({$document->category})",
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil disetujui (Approved).');
    }

    // Reject Dokumen -> Type: Danger
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $document = Document::findOrFail($id);

        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        // Catat Log (Kata kunci 'Reject' akan dideteksi sebagai DANGER)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Reject Dokumen',
            'details' => "Menolak dokumen No: {$document->document_number}. Alasan: {$request->reason}",
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil ditolak (Rejected).');
    }
}
