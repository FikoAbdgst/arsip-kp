<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel; // Pastikan install package maatwebsite/excel
// use Barryvdh\DomPDF\Facade\Pdf; // Pastikan install package barryvdh/laravel-dompdf

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query();

        // Filter: Jalankan Semua Fungsi Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('document_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->get();

        return view('pages.reports.index', compact('documents'));
    }

    public function exportPdf(Request $request)
    {
        // Logika query sama dengan index, lalu load view PDF
        // $pdf = Pdf::loadView('exports.documents_pdf', ['documents' => $data]);
        // return $pdf->download('laporan.pdf');
    }

    public function exportExcel(Request $request)
    {
        // return Excel::download(new DocumentsExport($request), 'laporan.xlsx');
    }
}
