<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;

class UpdateDocumentStatus extends Command
{
    protected $signature = 'documents:update-status';
    protected $description = 'Update status dokumen (Active -> Non Active -> Delete) berdasarkan waktu';

    public function handle()
    {
        $documents = Document::withTrashed()->get(); // Ambil semua termasuk yang soft deleted jika perlu logika lain

        foreach ($documents as $doc) {
            $doc->checkLifecycle(); // Panggil fungsi helper yang ada di Model Document tadi
        }

        $this->info('Status dokumen telah diperbarui.');
    }
}
