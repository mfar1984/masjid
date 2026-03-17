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
        // Get all masjids
        $masjids = DB::table('masjids')->pluck('id');
        
        foreach ($masjids as $masjidId) {
            // Check if this masjid already has kategori data
            $existingCount = DB::table('kategori_kebajikan')
                ->where('masjid_id', $masjidId)
                ->whereIn('jenis_kategori', ['jenis_bantuan', 'keutamaan', 'jenis_program'])
                ->count();
            
            // Skip if already has data (like Masjid 1)
            if ($existingCount > 0) {
                continue;
            }
            
            // Seed Jenis Bantuan
            $jenisBantuan = [
                ['nama' => 'Tunai', 'kod' => 'TUN', 'urutan' => 1],
                ['nama' => 'Barangan', 'kod' => 'BAR', 'urutan' => 2],
                ['nama' => 'Perkhidmatan', 'kod' => 'PER', 'urutan' => 3],
                ['nama' => 'Campuran', 'kod' => 'CAM', 'urutan' => 4],
            ];
            
            foreach ($jenisBantuan as $jenis) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'jenis_bantuan',
                    'nama_kategori' => $jenis['nama'],
                    'kod_kategori' => $jenis['kod'],
                    'urutan' => $jenis['urutan'],
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Seed Keutamaan
            $keutamaan = [
                ['nama' => 'Sangat Tinggi', 'kod' => 'ST', 'urutan' => 1],
                ['nama' => 'Tinggi', 'kod' => 'T', 'urutan' => 2],
                ['nama' => 'Sederhana', 'kod' => 'S', 'urutan' => 3],
                ['nama' => 'Rendah', 'kod' => 'R', 'urutan' => 4],
            ];
            
            foreach ($keutamaan as $keut) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'keutamaan',
                    'nama_kategori' => $keut['nama'],
                    'kod_kategori' => $keut['kod'],
                    'urutan' => $keut['urutan'],
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Seed Jenis Program
            $jenisProgram = [
                ['nama' => 'Pendidikan', 'kod' => 'PEN', 'urutan' => 1],
                ['nama' => 'Kesihatan', 'kod' => 'KES', 'urutan' => 2],
                ['nama' => 'Kecemasan', 'kod' => 'KEC', 'urutan' => 3],
                ['nama' => 'Kebajikan Am', 'kod' => 'KEB', 'urutan' => 4],
                ['nama' => 'Anak Yatim', 'kod' => 'YAT', 'urutan' => 5],
                ['nama' => 'OKU', 'kod' => 'OKU', 'urutan' => 6],
                ['nama' => 'Warga Emas', 'kod' => 'WE', 'urutan' => 7],
                ['nama' => 'Ibu Tunggal', 'kod' => 'IT', 'urutan' => 8],
            ];
            
            foreach ($jenisProgram as $jenis) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'jenis_program',
                    'nama_kategori' => $jenis['nama'],
                    'kod_kategori' => $jenis['kod'],
                    'urutan' => $jenis['urutan'],
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
        // Delete seeded data for masjids that didn't have data before
        // Keep Masjid 1 data intact
        DB::table('kategori_kebajikan')
            ->where('masjid_id', '!=', 1)
            ->whereIn('jenis_kategori', ['jenis_bantuan', 'keutamaan', 'jenis_program'])
            ->delete();
    }
};
