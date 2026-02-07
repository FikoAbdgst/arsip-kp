<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Fungsi bantuan untuk menerapkan filter agar tidak menulis ulang kode 3x
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('start_date')) {
            $query->whereDate('document_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('document_date', '<=', $request->end_date);
        }

        // Filter Baru
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('cabinet')) {
            $query->where('cabinet', $request->cabinet);
        }
        if ($request->filled('shelf')) {
            $query->where('shelf', $request->shelf);
        }
        if ($request->filled('box')) {
            $query->where('box', $request->box);
        }

        // Filter Status (Tetap ada untuk mendukung klik pada Card Statistik)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = Document::query();

        // Terapkan semua filter
        $this->applyFilters($query, $request);

        // Ambil Data untuk Tabel
        $documents = $query->latest()->get();

        // Hitung Statistik (Query terpisah agar Card Statistik tidak terpengaruh filter statusnya sendiri, tapi terpengaruh filter tanggal/kategori)
        // Kita buat base query baru untuk stats
        $statsBase = Document::query();

        // Terapkan filter SELAIN status ke stats (agar stats berubah sesuai tanggal/kategori/lokasi)
        if ($request->filled('start_date')) $statsBase->whereDate('document_date', '>=', $request->start_date);
        if ($request->filled('end_date')) $statsBase->whereDate('document_date', '<=', $request->end_date);
        if ($request->filled('category')) $statsBase->where('category', $request->category);
        if ($request->filled('source')) $statsBase->where('source', $request->source);
        if ($request->filled('cabinet')) $statsBase->where('cabinet', $request->cabinet);

        $stats = [
            'total' => (clone $statsBase)->count(),
            'approved' => (clone $statsBase)->where('status', 'approved')->count(),
            'pending' => (clone $statsBase)->where('status', 'pending')->count(),
            'rejected' => (clone $statsBase)->where('status', 'rejected')->count(),
        ];

        return view('pages.reports.index', compact('documents', 'stats'));
    }

    public function exportExcel(Request $request)
    {
        $query = Document::query();
        $this->applyFilters($query, $request); // Pakai fungsi helper filter yang sama

        $documents = $query->latest()->get();
        $filename = "Laporan-Arsip-" . date('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No Dokumen', 'Kategori', 'Sumber', 'CIF', 'Tanggal Dokumen', 'Lemari', 'Rak', 'Box', 'Status', 'Pengupload'];

        $callback = function () use ($documents, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($documents as $doc) {
                fputcsv($file, [
                    $doc->document_number,
                    $doc->category,
                    strtoupper($doc->source),
                    $doc->cif ?? '-',
                    $doc->document_date,
                    $doc->cabinet, // Pisahkan lokasi agar lebih jelas di excel
                    $doc->shelf,
                    $doc->box,
                    ucfirst($doc->status),
                    $doc->user->name ?? 'Deleted User'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = Document::query();
        $this->applyFilters($query, $request);

        $documents = $query->latest()->get();

        $pdf = Pdf::loadView('pages.reports.pdf', compact('documents'));
        return $pdf->download("Laporan-Arsip-" . date('Y-m-d') . ".pdf");
    }
}
