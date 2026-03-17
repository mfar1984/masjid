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
        // Step 1: Add new columns
        Schema::table('kategori_kewangan', function (Blueprint $table) {
            $table->string('jenis_derma')->nullable()->after('nama_kategori');
            $table->string('jenis_bil')->nullable()->after('jenis_derma');
        });

        // Step 2: Modify enum to add new types
        DB::statement("ALTER TABLE kategori_kewangan MODIFY COLUMN jenis_kategori ENUM('Pendapatan','Perbelanjaan','kategori_pendapatan','kategori_perbelanjaan','kaedah_bayaran','jenis_akaun','nama_bank','jenis_derma','jenis_bil') NOT NULL");

        // Step 3: Seed Jenis Derma for all masjids
        $masjids = DB::table('masjids')->get();
        
        foreach ($masjids as $masjid) {
            $now = now();
            
            // Jenis Derma
            $jenisDerma = [
                ['nama' => 'Derma Umum', 'kod' => 'DERMA-UMUM', 'urutan' => 1],
                ['nama' => 'Derma Pembinaan', 'kod' => 'DERMA-BINA', 'urutan' => 2],
                ['nama' => 'Derma Penyelenggaraan', 'kod' => 'DERMA-SELENGGARA', 'urutan' => 3],
                ['nama' => 'Derma Pendidikan', 'kod' => 'DERMA-DIDIK', 'urutan' => 4],
                ['nama' => 'Derma Kebajikan', 'kod' => 'DERMA-KEBAJIKAN', 'urutan' => 5],
                ['nama' => 'Derma Khas', 'kod' => 'DERMA-KHAS', 'urutan' => 6],
            ];

            foreach ($jenisDerma as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'jenis_derma',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Jenis Bil
            $jenisBil = [
                ['nama' => 'Bil Air', 'kod' => 'BIL-AIR', 'urutan' => 1],
                ['nama' => 'Bil Elektrik', 'kod' => 'BIL-ELEKTRIK', 'urutan' => 2],
                ['nama' => 'Bil Telefon', 'kod' => 'BIL-TELEFON', 'urutan' => 3],
                ['nama' => 'Bil Internet', 'kod' => 'BIL-INTERNET', 'urutan' => 4],
                ['nama' => 'Bil Cukai Tanah', 'kod' => 'BIL-CUKAI', 'urutan' => 5],
                ['nama' => 'Bil Cukai Pintu', 'kod' => 'BIL-PINTU', 'urutan' => 6],
                ['nama' => 'Bil Insurans', 'kod' => 'BIL-INSURANS', 'urutan' => 7],
                ['nama' => 'Bil Gas', 'kod' => 'BIL-GAS', 'urutan' => 8],
                ['nama' => 'Bil Lain-lain', 'kod' => 'BIL-LAIN', 'urutan' => 9],
            ];

            foreach ($jenisBil as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'jenis_bil',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
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
        // Delete seeded data
        DB::table('kategori_kewangan')
            ->whereIn('jenis_kategori', ['jenis_derma', 'jenis_bil'])
            ->delete();

        // Revert enum
        DB::statement("ALTER TABLE kategori_kewangan MODIFY COLUMN jenis_kategori ENUM('Pendapatan','Perbelanjaan','kategori_pendapatan','kategori_perbelanjaan','kaedah_bayaran','jenis_akaun','nama_bank') NOT NULL");

        // Drop columns
        Schema::table('kategori_kewangan', function (Blueprint $table) {
            $table->dropColumn(['jenis_derma', 'jenis_bil']);
        });
    }
};
