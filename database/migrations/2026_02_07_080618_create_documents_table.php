<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Kategori
            $table->string('document_number'); // Nomor Dokumen
            $table->date('document_date'); // Tanggal Dokumen
            $table->enum('source', ['teller', 'cs']); // Sumber (Otomatis by sistem nanti)
            $table->string('file_path'); // Path Upload File

            // Khusus CS
            $table->string('cif')->unique()->nullable(); // CIF (Unik, Nullable karena Teller tidak pakai)

            // Lokasi Arsip
            $table->enum('cabinet', ['A', 'B', 'C', 'D']); // Lemari A-D
            $table->string('shelf'); // Rak
            $table->string('box'); // Kotak
            $table->text('description')->nullable(); // Keterangan

            // Status Approval (Supervisor)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Alasan jika ditolak

            // Status Lifecycle (4 Tahun / 1 Tahun)
            $table->boolean('is_active')->default(true);

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang upload
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur "sampah" sebelum dihapus permanen (opsional)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
