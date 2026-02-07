<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category',
        'document_number',
        'document_date',
        'source',
        'file_path',
        'cif',
        'cabinet',
        'shelf',
        'box',
        'description',
        'status',
        'rejection_reason',
        'is_active',
        'user_id',
    ];

    // Helper untuk mengecek umur dokumen
    public function checkLifecycle()
    {
        $docDate = Carbon::parse($this->document_date);
        $now = Carbon::now();
        $diffYears = $docDate->diffInYears($now);

        // Jika lebih dari 4 tahun, set non-active
        if ($diffYears >= 4 && $this->is_active) {
            $this->update(['is_active' => false]);
        }

        // Jika tidak aktif dan sudah lewat 1 tahun lagi (total 5 tahun dari tanggal dokumen), hapus
        // Catatan: Logika "1 tahun setelah non-active" bisa berarti 5 tahun total umur dokumen
        if (!$this->is_active && $diffYears >= 5) {
            $this->forceDelete(); // Hapus permanen
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
