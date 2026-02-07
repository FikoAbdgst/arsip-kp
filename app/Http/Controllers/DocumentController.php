<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Menampilkan halaman Teller
    // Menampilkan halaman Teller
    public function indexTeller(Request $request)
    {
        // 1. Hitung Statistik Global (Tetap hitung semua agar angka kartu stabil)
        $stats = [
            'total'    => Document::where('source', 'teller')->count(),
            'approved' => Document::where('source', 'teller')->where('status', 'approved')->count(),
            'rejected' => Document::where('source', 'teller')->where('status', 'rejected')->count(),
            'today'    => Document::where('source', 'teller')->whereDate('created_at', \Carbon\Carbon::today())->count(),
        ];

        // 2. Query Dasar
        $query = Document::where('source', 'teller');

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

        return view('pages.teller.dashboard', compact('documents', 'stats'));
    }

    public function indexCs(Request $request)
    {
        // 1. Statistik Global (Tetap hitung semua agar angka di kartu tidak berubah-ubah)
        $stats = [
            'total'    => Document::where('source', 'cs')->count(),
            'approved' => Document::where('source', 'cs')->where('status', 'approved')->count(),
            'rejected' => Document::where('source', 'cs')->where('status', 'rejected')->count(),
            'today'    => Document::where('source', 'cs')->whereDate('created_at', Carbon::today())->count(),
        ];

        // 2. Query Dasar
        $query = Document::where('source', 'cs');

        // 3. Filter Server-side: Lifecycle (Aktif/Tidak Aktif)
        if ($request->has('lifecycle') && $request->lifecycle !== 'all') {
            $isActive = $request->lifecycle === 'active';
            $query->where('is_active', $isActive);
        }

        // 4. Filter Server-side: Approval (Status)
        if ($request->has('approval') && $request->approval !== 'all') {
            if ($request->approval === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } else {
                $query->where('status', $request->approval);
            }
        }

        // 5. Ambil Data dengan Pagination & Pertahankan Query String (agar filter tidak hilang saat ganti halaman)
        $documents = $query->latest()
            ->paginate(10)
            ->appends($request->all());

        return view('pages.cs.dashboard', compact('documents', 'stats'));
    }
    // Simpan Dokumen Baru
    public function store(Request $request)
    {
        $rules = [
            'category' => 'required|string',
            'document_number' => 'required|string',
            'document_date' => 'required|date',
            'file_path' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'cabinet' => 'required|in:A,B,C,D',
            'shelf' => 'required|string',
            'box' => 'required|string',
            'source' => 'required|in:teller,cs',
        ];

        if ($request->source == 'cs') {
            $rules['cif'] = 'required|string|unique:documents,cif';
        }

        $request->validate($rules);

        $path = $request->file('file_path')->store('documents', 'public');

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
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Upload Dokumen',
            'details' => 'Mengupload dokumen ' . $request->source . ' No: ' . $request->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }

    // Update Dokumen (Revisi / Re-upload)
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
            'file_path' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // Nullable karena edit
        ];

        if ($document->source == 'cs') {
            $rules['cif'] = 'required|string|unique:documents,cif,' . $document->id;
        }

        $request->validate($rules);

        $data = $request->except(['file_path', 'status', 'rejection_reason']);

        // Jika ada file baru (Re-upload)
        if ($request->hasFile('file_path')) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('documents', 'public');
        }

        // LOGIKA RE-UPLOAD: Jika status rejected, kembalikan ke pending
        if ($document->status == 'rejected') {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null; // Hapus alasan penolakan
        }

        $document->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit Dokumen',
            'details' => 'Revisi/Edit dokumen No: ' . $document->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diperbarui dan diajukan ulang ke Supervisor.');
    }

    // Hapus Dokumen
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->forceDelete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Dokumen',
            'details' => 'Menghapus dokumen No: ' . $document->document_number
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus permanen.');
    }
}
