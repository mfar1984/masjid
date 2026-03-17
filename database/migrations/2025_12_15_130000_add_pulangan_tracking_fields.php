<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Phase 1: Add pulangan tracking fields for inventory integration
     * Note: Columns already added by previous partial migration, only adding kategori
     */
    public function up(): void
    {
        // 1-3. Columns already exist from previous migration attempt
        // Only add kategori_kewangan entries

        // 4. Add ganti rugi categories to kategori_kewangan for all masjids
        $masjids = DB::table('masjids')->pluck('id');
        
        foreach ($masjids as $masjidId) {
            // Get max urutan for kategori_pendapatan
            $maxUrutan = DB::table('kategori_kewangan')
                ->where('masjid_id', $masjidId)
                ->where('jenis_kategori', 'kategori_pendapatan')
                ->max('urutan') ?? 0;

            // Check if category already exists
            $existsHilang = DB::table('kategori_kewangan')
                ->where('masjid_id', $masjidId)
                ->where('kod_kategori', 'KL-ASET-HILANG')
                ->exists();
            
            if (!$existsHilang) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'kategori_pendapatan',
                    'nama_kategori' => 'Ganti Rugi Aset Hilang',
                    'kod_kategori' => 'KL-ASET-HILANG',
                    'keterangan' => 'Kutipan ganti rugi untuk aset yang hilang semasa pinjaman/sewa',
                    'urutan' => $maxUrutan + 1,
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $existsRosak = DB::table('kategori_kewangan')
                ->where('masjid_id', $masjidId)
                ->where('kod_kategori', 'KL-ASET-ROSAK')
                ->exists();
            
            if (!$existsRosak) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => 'kategori_pendapatan',
                    'nama_kategori' => 'Ganti Rugi Aset Rosak',
                    'kod_kategori' => 'KL-ASET-ROSAK',
                    'keterangan' => 'Kutipan ganti rugi untuk aset yang rosak semasa pinjaman/sewa',
                    'urutan' => $maxUrutan + 2,
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
        // Remove ganti rugi categories only (columns managed by other migration)
        DB::table('kategori_kewangan')
            ->whereIn('kod_kategori', ['KL-ASET-HILANG', 'KL-ASET-ROSAK'])
            ->delete();
    }
};
