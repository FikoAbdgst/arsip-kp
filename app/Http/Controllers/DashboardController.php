<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil SEMUA dokumen (Teller & CS), urutkan dari yang terbaru
        $documents = Document::with('user')->latest()->get();

        return view('pages.dashboard', compact('documents'));
    }
}
