<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $user = User::first();
        $userId = $user ? $user->id : 1;

        // 1. DOKUMEN AKTIF (0 - 3 TAHUN 11 BULAN)
        for ($i = 0; $i < 20; $i++) {
            $source = $faker->randomElement(['teller', 'cs']);
            // Random tanggal dari hari ini sampai mendekati 4 tahun lalu
            $date = Carbon::now()->subDays(rand(1, 1400));

            Document::create([
                'category' => $faker->randomElement(['Pembukaan Rekening', 'Setoran Tunai', 'Kredit']),
                'document_number' => 'DOC-AKTIF-' . $faker->unique()->numberBetween(1000, 9999),
                'document_date' => $date,
                'source' => $source,
                'file_path' => 'documents/dummy.pdf',
                'cif' => $source == 'cs' ? $faker->unique()->numerify('CIF#######') : null,
                'cabinet' => $faker->randomElement(['A', 'B']),
                'shelf' => 'Rak ' . $faker->numberBetween(1, 5),
                'box' => 'Box ' . $faker->numberBetween(1, 20),
                'description' => 'Dokumen operasional aktif.',
                'status' => 'approved',
                'is_active' => true,
                'user_id' => $userId,
            ]);
        }

        // 2. DOKUMEN MASA RETENSI / WARNING (4 - 5 TAHUN)
        // Ini adalah dokumen yang is_active = false, tapi belum dihapus (belum 5 tahun)
        for ($i = 0; $i < 10; $i++) {
            $source = $faker->randomElement(['teller', 'cs']);
            // Set tanggal spesifik antara 4 sampai 5 tahun lalu
            $date = Carbon::now()->subYears(4)->subMonths(rand(1, 11));

            Document::create([
                'category' => 'Arsip Lama ' . $faker->word,
                'document_number' => 'DOC-RETENSI-' . $faker->unique()->numberBetween(1000, 9999),
                'document_date' => $date,
                'source' => $source,
                'file_path' => 'documents/dummy_old.pdf',
                'cif' => $source == 'cs' ? $faker->unique()->numerify('CIF#######') : null,
                'cabinet' => 'D',
                'shelf' => 'Rak Arsip',
                'box' => 'Box Tahunan',
                'description' => 'Dokumen memasuki masa retensi. Akan dihapus otomatis saat mencapai 5 tahun.',
                'status' => 'approved',
                'is_active' => false, // Non-Aktif (Warning Mode)
                'user_id' => $userId,
            ]);
        }
    }
}
