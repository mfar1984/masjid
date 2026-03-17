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
        // This migration ensures existing permohonan_bantuan data is consistent with kategori_kebajikan
        // No actual changes needed as data is already correct, but we validate it
        
        $permohonan = DB::table('permohonan_bantuan')->get();
        
        foreach ($permohonan as $p) {
            // Validate jenis_bantuan exists in kategori_kebajikan
            $jenisExists = DB::table('kategori_kebajikan')
                ->where('masjid_id', $p->masjid_id)
                ->where('jenis_kategori', 'jenis_bantuan')
                ->where('nama_kategori', $p->jenis_bantuan)
                ->where('status', 'Aktif')
                ->exists();
            
            if (!$jenisExists) {
                // Create missing kategori
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $p->masjid_id,
                    'jenis_kategori' => 'jenis_bantuan',
                    'nama_kategori' => $p->jenis_bantuan,
                    'urutan' => 99,
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Validate keutamaan exists in kategori_kebajikan
            $keutamaanExists = DB::table('kategori_kebajikan')
                ->where('masjid_id', $p->masjid_id)
                ->where('jenis_kategori', 'keutamaan')
                ->where('nama_kategori', $p->keutamaan)
                ->where('status', 'Aktif')
                ->exists();
            
            if (!$keutamaanExists) {
                // Create missing kategori
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $p->masjid_id,
                    'jenis_kategori' => 'keutamaan',
                    'nama_kategori' => $p->keutamaan,
                    'urutan' => 99,
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Validate program_kebajikan kategori
        $programs = DB::table('program_kebajikan')->get();
        
        foreach ($programs as $prog) {
            $kategoriExists = DB::table('kategori_kebajikan')
                ->where('masjid_id', $prog->masjid_id)
                ->where('jenis_kategori', 'jenis_program')
                ->where('nama_kategori', $prog->kategori_program)
                ->where('status', 'Aktif')
                ->exists();
            
            if (!$kategoriExists) {
                // Create missing kategori
                DB::table('kategori_kebajikan')->insert([
                    'masjid_id' => $prog->masjid_id,
                    'jenis_kategori' => 'jenis_program',
                    'nama_kategori' => $prog->kategori_program,
                    'urutan' => 99,
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
        // No rollback needed - we only added missing categories
    }
};
