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
        // Alter enum to add 'tempoh_bantuan'
        DB::statement("ALTER TABLE kategori_kebajikan MODIFY COLUMN jenis_kategori ENUM('jenis_bantuan', 'keutamaan', 'jenis_program', 'tempoh_bantuan') NOT NULL");
        
        // Seed default Tempoh Bantuan data
        $masjids = DB::table('masjids')->pluck('id');
        
        foreach ($masjids as $masjidId) {
            $tempohBantuan = [
                ['nama' => 'Sekali', 'urutan' => 1],
                ['nama' => 'Bulanan', 'urutan' => 2],
                ['nama' => 'Tahunan', 'urutan' => 3],
                ['nama' => 'Mengikut Keperluan', 'urutan' => 4],
            ];
            
            foreach ($tempohBantuan as $tempoh) {
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'tempoh_bantuan',
                    'nama_kategori' => $tempoh['nama'],
                    'kod_kategori' => strtoupper(substr($tempoh['nama'], 0, 3)),
                    'urutan' => $tempoh['urutan'],
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
        // Delete tempoh_bantuan records
        DB::table('kategori_kebajikan')->where('jenis_kategori', 'tempoh_bantuan')->delete();
        
        // Revert enum
        DB::statement("ALTER TABLE kategori_kebajikan MODIFY COLUMN jenis_kategori ENUM('jenis_bantuan', 'keutamaan', 'jenis_program') NOT NULL");
    }
};
