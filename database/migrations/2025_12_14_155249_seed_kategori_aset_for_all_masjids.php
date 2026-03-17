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
        $masjids = DB::table('masjids')->pluck('id');
        
        $defaultData = [
            // Tanah & Bangunan (6 items)
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-001', 'nama_kategori' => 'Tanah Masjid', 'urutan' => 1],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-002', 'nama_kategori' => 'Bangunan Masjid', 'urutan' => 2],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-003', 'nama_kategori' => 'Surau', 'urutan' => 3],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-004', 'nama_kategori' => 'Pejabat', 'urutan' => 4],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-005', 'nama_kategori' => 'Rumah Imam', 'urutan' => 5],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-006', 'nama_kategori' => 'Dewan Serbaguna', 'urutan' => 6],
            
            // Kenderaan (4 items)
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-001', 'nama_kategori' => 'Kereta', 'urutan' => 1],
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-002', 'nama_kategori' => 'Van', 'urutan' => 2],
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-003', 'nama_kategori' => 'Bas', 'urutan' => 3],
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-004', 'nama_kategori' => 'Motosikal', 'urutan' => 4],
            
            // Peralatan (7 items)
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-001', 'nama_kategori' => 'Sistem PA', 'urutan' => 1],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-002', 'nama_kategori' => 'Projector', 'urutan' => 2],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-003', 'nama_kategori' => 'Air Conditioner', 'urutan' => 3],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-004', 'nama_kategori' => 'Kipas', 'urutan' => 4],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-005', 'nama_kategori' => 'Khemah', 'urutan' => 5],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-006', 'nama_kategori' => 'Kerusi Lipat', 'urutan' => 6],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-007', 'nama_kategori' => 'Meja Lipat', 'urutan' => 7],
            
            // Perabot (5 items)
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-001', 'nama_kategori' => 'Kabinet', 'urutan' => 1],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-002', 'nama_kategori' => 'Meja', 'urutan' => 2],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-003', 'nama_kategori' => 'Kerusi', 'urutan' => 3],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-004', 'nama_kategori' => 'Rak Buku', 'urutan' => 4],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-005', 'nama_kategori' => 'Almari', 'urutan' => 5],
            
            // Elektronik (5 items)
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-001', 'nama_kategori' => 'Komputer', 'urutan' => 1],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-002', 'nama_kategori' => 'Printer', 'urutan' => 2],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-003', 'nama_kategori' => 'Scanner', 'urutan' => 3],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-004', 'nama_kategori' => 'Kamera', 'urutan' => 4],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-005', 'nama_kategori' => 'TV/Monitor', 'urutan' => 5],
            
            // Lain-lain (1 item)
            ['jenis_kategori' => 'Lain-lain', 'kod_kategori' => 'LL-001', 'nama_kategori' => 'Lain-lain', 'urutan' => 1],
        ];
        
        foreach ($masjids as $masjidId) {
            foreach ($defaultData as $data) {
                DB::table('kategori_aset')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => $data['jenis_kategori'],
                    'kod_kategori' => $data['kod_kategori'],
                    'nama_kategori' => $data['nama_kategori'],
                    'urutan' => $data['urutan'],
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
        DB::table('kategori_aset')->truncate();
    }
};
