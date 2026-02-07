<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'details', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // LOGIKA PENENTUAN WARNA / TIPE NOTIFIKASI
    public function getTypeAttribute()
    {
        $action = strtolower($this->action); // Ubah ke huruf kecil biar aman

        // Jika ada kata 'hapus', 'reject', atau 'tolak' -> Merah (Danger)
        if (str_contains($action, 'hapus') || str_contains($action, 'reject') || str_contains($action, 'tolak')) {
            return 'danger';
        }

        // Jika ada kata 'approve' atau 'setujui' -> Hijau (Success)
        if (str_contains($action, 'approve') || str_contains($action, 'setujui')) {
            return 'success';
        }

        // Jika ada kata 'edit', 'revisi', atau 'update' -> Kuning (Warning)
        if (str_contains($action, 'edit') || str_contains($action, 'revisi') || str_contains($action, 'update')) {
            return 'warning';
        }

        // Sisanya -> Biru (Info)
        return 'info';
    }
}
