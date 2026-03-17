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
            // Bangsa (6 items)
            ['jenis_kategori' => 'bangsa', 'nama_kategori' => 'Melayu', 'kod_kategori' => 'MLY', 'urutan' => 1],
            ['jenis_kategori' => 'bangsa', 'nama_kategori' => 'Cina', 'kod_kategori' => 'CHN', 'urutan' => 2],
            ['jenis_kategori' => 'bangsa', 'nama_kategori' => 'India', 'kod_kategori' => 'IND', 'urutan' => 3],
            ['jenis_kategori' => 'bangsa', 'nama_kategori' => 'Bumiputera Sabah', 'kod_kategori' => 'BSAB', 'urutan' => 4],
            ['jenis_kategori' => 'bangsa', 'nama_kategori' => 'Bumiputera Sarawak', 'kod_kategori' => 'BSRW', 'urutan' => 5],
            ['jenis_kategori' => 'bangsa', 'nama_kategori' => 'Lain-lain', 'kod_kategori' => 'OTH', 'urutan' => 6],
            
            // Agama (5 items)
            ['jenis_kategori' => 'agama', 'nama_kategori' => 'Islam', 'kod_kategori' => 'ISL', 'urutan' => 1],
            ['jenis_kategori' => 'agama', 'nama_kategori' => 'Buddha', 'kod_kategori' => 'BUD', 'urutan' => 2],
            ['jenis_kategori' => 'agama', 'nama_kategori' => 'Hindu', 'kod_kategori' => 'HIN', 'urutan' => 3],
            ['jenis_kategori' => 'agama', 'nama_kategori' => 'Kristian', 'kod_kategori' => 'KRS', 'urutan' => 4],
            ['jenis_kategori' => 'agama', 'nama_kategori' => 'Lain-lain', 'kod_kategori' => 'OTH', 'urutan' => 5],
            
            // Status Perkahwinan (4 items)
            ['jenis_kategori' => 'status_perkahwinan', 'nama_kategori' => 'Bujang', 'kod_kategori' => 'BUJ', 'urutan' => 1],
            ['jenis_kategori' => 'status_perkahwinan', 'nama_kategori' => 'Berkahwin', 'kod_kategori' => 'KWN', 'urutan' => 2],
            ['jenis_kategori' => 'status_perkahwinan', 'nama_kategori' => 'Janda', 'kod_kategori' => 'JND', 'urutan' => 3],
            ['jenis_kategori' => 'status_perkahwinan', 'nama_kategori' => 'Duda', 'kod_kategori' => 'DUD', 'urutan' => 4],
            
            // Negeri (16 items)
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Johor', 'kod_kategori' => 'JHR', 'urutan' => 1],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Kedah', 'kod_kategori' => 'KDH', 'urutan' => 2],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Kelantan', 'kod_kategori' => 'KTN', 'urutan' => 3],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Melaka', 'kod_kategori' => 'MLK', 'urutan' => 4],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Negeri Sembilan', 'kod_kategori' => 'NSN', 'urutan' => 5],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Pahang', 'kod_kategori' => 'PHG', 'urutan' => 6],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Pulau Pinang', 'kod_kategori' => 'PNG', 'urutan' => 7],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Perak', 'kod_kategori' => 'PRK', 'urutan' => 8],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Perlis', 'kod_kategori' => 'PLS', 'urutan' => 9],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Selangor', 'kod_kategori' => 'SGR', 'urutan' => 10],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Terengganu', 'kod_kategori' => 'TRG', 'urutan' => 11],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Sabah', 'kod_kategori' => 'SBH', 'urutan' => 12],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Sarawak', 'kod_kategori' => 'SWK', 'urutan' => 13],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Wilayah Persekutuan Kuala Lumpur', 'kod_kategori' => 'KUL', 'urutan' => 14],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Wilayah Persekutuan Labuan', 'kod_kategori' => 'LBN', 'urutan' => 15],
            ['jenis_kategori' => 'negeri', 'nama_kategori' => 'Wilayah Persekutuan Putrajaya', 'kod_kategori' => 'PJY', 'urutan' => 16],
            
            // Kategori Asnaf (8 items)
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Fakir', 'kod_kategori' => 'FKR', 'urutan' => 1],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Miskin', 'kod_kategori' => 'MSK', 'urutan' => 2],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Amil', 'kod_kategori' => 'AML', 'urutan' => 3],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Muallaf', 'kod_kategori' => 'MLF', 'urutan' => 4],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Riqab', 'kod_kategori' => 'RQB', 'urutan' => 5],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Gharimin', 'kod_kategori' => 'GRM', 'urutan' => 6],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Fisabilillah', 'kod_kategori' => 'FSB', 'urutan' => 7],
            ['jenis_kategori' => 'kategori_asnaf', 'nama_kategori' => 'Ibnu Sabil', 'kod_kategori' => 'IBS', 'urutan' => 8],
            
            // Status Pekerjaan (4 items)
            ['jenis_kategori' => 'status_pekerjaan', 'nama_kategori' => 'Bekerja', 'kod_kategori' => 'BKJ', 'urutan' => 1],
            ['jenis_kategori' => 'status_pekerjaan', 'nama_kategori' => 'Tidak Bekerja', 'kod_kategori' => 'TBK', 'urutan' => 2],
            ['jenis_kategori' => 'status_pekerjaan', 'nama_kategori' => 'Pesara', 'kod_kategori' => 'PSR', 'urutan' => 3],
            ['jenis_kategori' => 'status_pekerjaan', 'nama_kategori' => 'Pelajar', 'kod_kategori' => 'PLJ', 'urutan' => 4],
            
            // Status Kesihatan (3 items)
            ['jenis_kategori' => 'status_kesihatan', 'nama_kategori' => 'Sihat', 'kod_kategori' => 'SHT', 'urutan' => 1],
            ['jenis_kategori' => 'status_kesihatan', 'nama_kategori' => 'Sakit Kronik', 'kod_kategori' => 'SKR', 'urutan' => 2],
            ['jenis_kategori' => 'status_kesihatan', 'nama_kategori' => 'OKU', 'kod_kategori' => 'OKU', 'urutan' => 3],
            
            // Kewarganegaraan (3 items)
            ['jenis_kategori' => 'kewarganegaraan', 'nama_kategori' => 'Warganegara', 'kod_kategori' => 'WRG', 'urutan' => 1],
            ['jenis_kategori' => 'kewarganegaraan', 'nama_kategori' => 'Pemastautin Tetap', 'kod_kategori' => 'PMT', 'urutan' => 2],
            ['jenis_kategori' => 'kewarganegaraan', 'nama_kategori' => 'Bukan Warganegara', 'kod_kategori' => 'BWG', 'urutan' => 3],
        ];
        
        foreach ($masjids as $masjidId) {
            foreach ($defaultData as $data) {
                DB::table('kategori_asnaf')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => $data['jenis_kategori'],
                    'nama_kategori' => $data['nama_kategori'],
                    'kod_kategori' => $data['kod_kategori'],
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
        DB::table('kategori_asnaf')->truncate();
    }
};
