<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter enum to add bangsa, agama, jenis_kediaman
        DB::statement("ALTER TABLE kategori_kebajikan MODIFY COLUMN jenis_kategori ENUM('jenis_bantuan', 'keutamaan', 'jenis_program', 'tempoh_bantuan', 'bangsa', 'agama', 'jenis_kediaman') NOT NULL");
        
        // Seed default data for all masjids
        $masjids = DB::table('masjids')->pluck('id');
        
        foreach ($masjids as $masjidId) {
            // Seed Bangsa
            $bangsa = [
                ['nama' => 'Melayu', 'kod' => 'MEL', 'urutan' => 1],
                ['nama' => 'Cina', 'kod' => 'CHN', 'urutan' => 2],
                ['nama' => 'India', 'kod' => 'IND', 'urutan' => 3],
                ['nama' => 'Bumiputera Sabah', 'kod' => 'BSB', 'urutan' => 4],
                ['nama' => 'Bumiputera Sarawak', 'kod' => 'BSW', 'urutan' => 5],
                ['nama' => 'Lain-lain', 'kod' => 'LN', 'urutan' => 6],
            ];
            
            foreach ($bangsa as $item) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'bangsa',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Seed Agama
            $agama = [
                ['nama' => 'Islam', 'kod' => 'ISL', 'urutan' => 1],
                ['nama' => 'Buddha', 'kod' => 'BUD', 'urutan' => 2],
                ['nama' => 'Hindu', 'kod' => 'HIN', 'urutan' => 3],
                ['nama' => 'Kristian', 'kod' => 'KRI', 'urutan' => 4],
                ['nama' => 'Lain-lain', 'kod' => 'LN', 'urutan' => 5],
            ];
            
            foreach ($agama as $item) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'agama',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Seed Jenis Kediaman
            $jenisKediaman = [
                ['nama' => 'Rumah Sendiri', 'kod' => 'RS', 'urutan' => 1],
                ['nama' => 'Rumah Sewa', 'kod' => 'RW', 'urutan' => 2],
                ['nama' => 'Rumah Keluarga', 'kod' => 'RK', 'urutan' => 3],
                ['nama' => 'Rumah Pangsa', 'kod' => 'RP', 'urutan' => 4],
                ['nama' => 'Rumah Setinggan', 'kod' => 'RT', 'urutan' => 5],
                ['nama' => 'Lain-lain', 'kod' => 'LN', 'urutan' => 6],
            ];
            
            foreach ($jenisKediaman as $item) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'jenis_kediaman',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete seeded data
        DB::table('kategori_kebajikan')
            ->whereIn('jenis_kategori', ['bangsa', 'agama', 'jenis_kediaman'])
            ->delete();
        
        // Revert enum
        DB::statement("ALTER TABLE kategori_kebajikan MODIFY COLUMN jenis_kategori ENUM('jenis_bantuan', 'keutamaan', 'jenis_program', 'tempoh_bantuan') NOT NULL");
    }
};
