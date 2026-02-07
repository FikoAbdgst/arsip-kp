<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Menampilkan halaman Teller
    public function indexTeller()
    {
        $documents = Document::where('source', 'teller')
            ->latest()
            ->get();

        // Pastikan view dirender sesuai struktur folder Anda
        return view('pages.teller.dashboard', compact('documents'));
    }

    // Menampilkan halaman CS
    public function indexCs()
    {
        $documents = Document::where('source', 'cs')
            ->latest()
            ->get();
        return view('pages.cs.dashboard', compact('documents'));
    }

    // Simpan Dokumen Baru (Upload)
    public function store(Request $request)
    {
        // Validasi
        $rules = [
            'category' => 'required|string',
            'document_number' => 'required|string',
            'document_date' => 'required|date',
            'file_path' => 'required|file|mimes:pdf,jpg,png|max:2048', // Max 2MB
            'cabinet' => 'required|in:A,B,C,D',
            'shelf' => 'required|string',
            'box' => 'required|string',
            'source' => 'required|in:teller,cs', // Hidden field di form
        ];

        // Jika CS, CIF wajib dan unik
        if ($request->source == 'cs') {
            $rules['cif'] = 'required|string|unique:documents,cif';
        }

        $request->validate($rules);

        // Upload File
        $path = $request->file('file_path')->store('documents', 'public');

        // Simpan ke DB
        Document::create([
            'category' => $request->category,
            'document_number' => $request->document_number,
            'document_date' => $request->document_date,
            'source' => $request->source,
            'file_path' => $path,
            'cif' => $request->cif ?? null,
            'cabinet' => $request->cabinet,
            'shelf' => $request->shelf,
            'box' => $request->box,
            'description' => $request->description,
            'status' => 'pending', // Default Pending
            'user_id' => Auth::id(),
        ]);

        // Catat Activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Upload Dokumen',
            'details' => 'Mengupload dokumen ' . $request->source . ' No: ' . $request->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diupload dan menunggu persetujuan Supervisor.');
    }

    // Update Dokumen (Edit / Re-upload jika rejected)
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $rules = [
            'category' => 'required|string',
            'document_number' => 'required|string',
            'document_date' => 'required|date',
            'cabinet' => 'required|in:A,B,C,D',
            'shelf' => 'required|string',
            'box' => 'required|string',
        ];

        if ($document->source == 'cs') {
            $rules['cif'] = 'required|string|unique:documents,cif,' . $document->id;
        }

        $request->validate($rules);

        $data = $request->except(['file_path', 'status']); // Status tidak boleh diubah user lewat edit biasa kecuali re-submit

        // Jika upload file baru (misal revisi rejected)
        if ($request->hasFile('file_path')) {
            // Hapus file lama
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('documents', 'public');
        }

        // Jika dokumen sebelumnya rejected dan diedit, kembalikan ke pending agar dicek ulang
        if ($document->status == 'rejected') {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        $document->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit Dokumen',
            'details' => 'Memperbarui dokumen No: ' . $document->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diperbarui.');
    }

    // Hapus Dokumen (Hanya untuk Admin di menu Rejected atau Supervisor)
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Hapus file fisik
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->forceDelete(); // Hapus permanen dari DB

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Dokumen',
            'details' => 'Menghapus dokumen No: ' . $document->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen dihapus permanen.');
    }
}
