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
        // First, update the enum to include kategori_perbelanjaan
        DB::statement("ALTER TABLE kategori_kewangan MODIFY COLUMN jenis_kategori ENUM('Pendapatan', 'Perbelanjaan', 'kategori_pendapatan', 'kategori_perbelanjaan', 'kaedah_bayaran', 'jenis_akaun', 'nama_bank') NOT NULL");

        // Get all masjids
        $masjids = DB::table('masjids')->get();

        foreach ($masjids as $masjid) {
            $now = now();

            // Kategori Perbelanjaan - Utiliti & Bil
            $kategoriUtiliti = [
                ['nama' => 'Elektrik (TNB)', 'kod' => 'UTIL-01', 'parent' => 'Utiliti & Bil', 'urutan' => 1],
                ['nama' => 'Air (PDAM)', 'kod' => 'UTIL-02', 'parent' => 'Utiliti & Bil', 'urutan' => 2],
                ['nama' => 'Telefon', 'kod' => 'UTIL-03', 'parent' => 'Utiliti & Bil', 'urutan' => 3],
                ['nama' => 'Internet', 'kod' => 'UTIL-04', 'parent' => 'Utiliti & Bil', 'urutan' => 4],
                ['nama' => 'Gas', 'kod' => 'UTIL-05', 'parent' => 'Utiliti & Bil', 'urutan' => 5],
            ];

            foreach ($kategoriUtiliti as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'kategori_perbelanjaan',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'keterangan' => $item['parent'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Kategori Perbelanjaan - Penyelenggaraan
            $kategoriPenyelenggaraan = [
                ['nama' => 'Bangunan', 'kod' => 'MAINT-01', 'parent' => 'Penyelenggaraan', 'urutan' => 6],
                ['nama' => 'Peralatan', 'kod' => 'MAINT-02', 'parent' => 'Penyelenggaraan', 'urutan' => 7],
                ['nama' => 'Landskap', 'kod' => 'MAINT-03', 'parent' => 'Penyelenggaraan', 'urutan' => 8],
                ['nama' => 'Lain-lain', 'kod' => 'MAINT-04', 'parent' => 'Penyelenggaraan', 'urutan' => 9],
            ];

            foreach ($kategoriPenyelenggaraan as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'kategori_perbelanjaan',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'keterangan' => $item['parent'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Kategori Perbelanjaan - Gaji & Elaun
            $kategoriGaji = [
                ['nama' => 'Imam', 'kod' => 'GAJI-01', 'parent' => 'Gaji & Elaun', 'urutan' => 10],
                ['nama' => 'Bilal', 'kod' => 'GAJI-02', 'parent' => 'Gaji & Elaun', 'urutan' => 11],
                ['nama' => 'Pekerja', 'kod' => 'GAJI-03', 'parent' => 'Gaji & Elaun', 'urutan' => 12],
                ['nama' => 'Lain-lain', 'kod' => 'GAJI-04', 'parent' => 'Gaji & Elaun', 'urutan' => 13],
            ];

            foreach ($kategoriGaji as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'kategori_perbelanjaan',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'keterangan' => $item['parent'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Kategori Perbelanjaan - Perbelanjaan Lain
            $kategoriLain = [
                ['nama' => 'Alat Tulis', 'kod' => 'LAIN-01', 'parent' => 'Perbelanjaan Lain', 'urutan' => 14],
                ['nama' => 'Makanan', 'kod' => 'LAIN-02', 'parent' => 'Perbelanjaan Lain', 'urutan' => 15],
                ['nama' => 'Pengangkutan', 'kod' => 'LAIN-03', 'parent' => 'Perbelanjaan Lain', 'urutan' => 16],
                ['nama' => 'Lain-lain', 'kod' => 'LAIN-04', 'parent' => 'Perbelanjaan Lain', 'urutan' => 17],
            ];

            foreach ($kategoriLain as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'kategori_perbelanjaan',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'keterangan' => $item['parent'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete kategori perbelanjaan
        DB::table('kategori_kewangan')
            ->where('jenis_kategori', 'kategori_perbelanjaan')
            ->delete();

        // Revert enum back
        DB::statement("ALTER TABLE kategori_kewangan MODIFY COLUMN jenis_kategori ENUM('Pendapatan', 'Perbelanjaan', 'kategori_pendapatan', 'kaedah_bayaran', 'jenis_akaun', 'nama_bank') NOT NULL");
    }
};
