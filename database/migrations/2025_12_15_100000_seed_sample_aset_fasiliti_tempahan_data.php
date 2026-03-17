<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();
        
        // Get masjid_id = 1 (Super Admin's masjid)
        $masjidId = 1;
        
        // Get kategori_aset_id for "Kerusi"
        $kategoriKerusi = DB::table('kategori_aset')
            ->where('masjid_id', $masjidId)
            ->where('nama_kategori', 'Kerusi')
            ->first();
            
        // Get kategori_aset_id for "Meja"
        $kategoriMeja = DB::table('kategori_aset')
            ->where('masjid_id', $masjidId)
            ->where('nama_kategori', 'Meja')
            ->first();
            
        // Get kategori_aset_id for "Sistem PA"
        $kategoriPA = DB::table('kategori_aset')
            ->where('masjid_id', $masjidId)
            ->where('nama_kategori', 'Sistem PA')
            ->first();
            
        // Get kategori_aset_id for "Projector"
        $kategoriProjector = DB::table('kategori_aset')
            ->where('masjid_id', $masjidId)
            ->where('nama_kategori', 'Projector')
            ->first();

        if (!$kategoriKerusi || !$kategoriMeja || !$kategoriPA || !$kategoriProjector) {
            return; // Skip if categories don't exist
        }

        // 1. SEED SENARAI ASET
        $asetData = [
            [
                'masjid_id' => $masjidId,
                'no_aset' => 'AST-2025-0001',
                'kategori_aset_id' => $kategoriKerusi->id,
                'nama_aset' => 'Kerusi Plastik',
                'kod_aset' => 'KRS-001',
                'jenis_aset' => 'Perabot',
                'tarikh_perolehan' => '2024-01-15',
                'cara_perolehan' => 'Pembelian',
                'pembekal' => 'Syarikat Perabot Sdn Bhd',
                'harga_perolehan' => 15000.00,
                'lokasi_semasa' => 'Stor Utama',
                'status_aset' => 'Aktif',
                'kondisi_aset' => 'Baik',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'no_aset' => 'AST-2025-0002',
                'kategori_aset_id' => $kategoriMeja->id,
                'nama_aset' => 'Meja Lipat',
                'kod_aset' => 'MJA-001',
                'jenis_aset' => 'Perabot',
                'tarikh_perolehan' => '2024-02-20',
                'cara_perolehan' => 'Pembelian',
                'pembekal' => 'Syarikat Perabot Sdn Bhd',
                'harga_perolehan' => 8000.00,
                'lokasi_semasa' => 'Stor Utama',
                'status_aset' => 'Aktif',
                'kondisi_aset' => 'Baik',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'no_aset' => 'AST-2025-0003',
                'kategori_aset_id' => $kategoriPA->id,
                'nama_aset' => 'Sistem PA (Public Address)',
                'kod_aset' => 'PA-001',
                'jenis_aset' => 'Elektronik',
                'tarikh_perolehan' => '2023-06-10',
                'cara_perolehan' => 'Pembelian',
                'pembekal' => 'Audio Tech Sdn Bhd',
                'harga_perolehan' => 12000.00,
                'jenama' => 'TOA',
                'model' => 'PA-2000',
                'lokasi_semasa' => 'Dewan Utama',
                'status_aset' => 'Aktif',
                'kondisi_aset' => 'Baik',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'no_aset' => 'AST-2025-0004',
                'kategori_aset_id' => $kategoriProjector->id,
                'nama_aset' => 'Projektor LCD',
                'kod_aset' => 'PRJ-001',
                'jenis_aset' => 'Elektronik',
                'tarikh_perolehan' => '2024-03-15',
                'cara_perolehan' => 'Pembelian',
                'pembekal' => 'IT Solutions Sdn Bhd',
                'harga_perolehan' => 3500.00,
                'jenama' => 'Epson',
                'model' => 'EB-X41',
                'lokasi_semasa' => 'Bilik Mesyuarat',
                'status_aset' => 'Aktif',
                'kondisi_aset' => 'Baik',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($asetData as $aset) {
            DB::table('senarai_aset')->insert($aset);
        }

        // Get inserted aset IDs
        $kerusiAset = DB::table('senarai_aset')->where('kod_aset', 'KRS-001')->first();
        $mejaAset = DB::table('senarai_aset')->where('kod_aset', 'MJA-001')->first();
        $paAset = DB::table('senarai_aset')->where('kod_aset', 'PA-001')->first();
        $projektorAset = DB::table('senarai_aset')->where('kod_aset', 'PRJ-001')->first();

        // 2. SEED SENARAI FASILITI
        $fasilitiData = [
            [
                'masjid_id' => $masjidId,
                'kod_fasiliti' => 'FS-2025-0001',
                'nama_fasiliti' => 'Dewan Serbaguna',
                'jenis_fasiliti' => 'Dewan',
                'kategori_fasiliti' => 'Dewan Utama',
                'senarai_aset_id' => null,
                'kuantiti_total' => 1,
                'is_countable' => false,
                'kapasiti_maksimum' => 500,
                'luas_kawasan' => '1000 kaki persegi',
                'kemudahan' => 'Penghawa dingin, Sistem PA, Tempat letak kereta',
                'harga_sewa_sejam' => 100.00,
                'harga_sewa_separuh_hari' => 400.00,
                'harga_sewa_sehari' => 700.00,
                'deposit_diperlukan' => 500.00,
                'status_fasiliti' => 'Tersedia',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'kod_fasiliti' => 'FS-2025-0002',
                'nama_fasiliti' => 'Bilik Mesyuarat',
                'jenis_fasiliti' => 'Bilik',
                'kategori_fasiliti' => 'Bilik Kecil',
                'senarai_aset_id' => null,
                'kuantiti_total' => 1,
                'is_countable' => false,
                'kapasiti_maksimum' => 30,
                'luas_kawasan' => '300 kaki persegi',
                'kemudahan' => 'Penghawa dingin, Projektor, Papan putih',
                'harga_sewa_sejam' => 50.00,
                'harga_sewa_separuh_hari' => 200.00,
                'harga_sewa_sehari' => 350.00,
                'deposit_diperlukan' => 200.00,
                'status_fasiliti' => 'Tersedia',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'kod_fasiliti' => 'FS-2025-0003',
                'nama_fasiliti' => 'Kerusi Plastik',
                'jenis_fasiliti' => 'Aset',
                'kategori_fasiliti' => 'Perabot',
                'senarai_aset_id' => $kerusiAset->id,
                'kuantiti_total' => 200,
                'is_countable' => true,
                'harga_sewa_sejam' => 1.00,
                'harga_sewa_separuh_hari' => 4.00,
                'harga_sewa_sehari' => 7.00,
                'deposit_diperlukan' => 0.00,
                'status_fasiliti' => 'Tersedia',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'kod_fasiliti' => 'FS-2025-0004',
                'nama_fasiliti' => 'Meja Lipat',
                'jenis_fasiliti' => 'Aset',
                'kategori_fasiliti' => 'Perabot',
                'senarai_aset_id' => $mejaAset->id,
                'kuantiti_total' => 50,
                'is_countable' => true,
                'harga_sewa_sejam' => 2.00,
                'harga_sewa_separuh_hari' => 8.00,
                'harga_sewa_sehari' => 14.00,
                'deposit_diperlukan' => 0.00,
                'status_fasiliti' => 'Tersedia',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'kod_fasiliti' => 'FS-2025-0005',
                'nama_fasiliti' => 'Sistem PA',
                'jenis_fasiliti' => 'Aset',
                'kategori_fasiliti' => 'Elektronik',
                'senarai_aset_id' => $paAset->id,
                'kuantiti_total' => 1,
                'is_countable' => true,
                'harga_sewa_sejam' => 50.00,
                'harga_sewa_separuh_hari' => 200.00,
                'harga_sewa_sehari' => 350.00,
                'deposit_diperlukan' => 500.00,
                'status_fasiliti' => 'Tersedia',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'masjid_id' => $masjidId,
                'kod_fasiliti' => 'FS-2025-0006',
                'nama_fasiliti' => 'Projektor LCD',
                'jenis_fasiliti' => 'Aset',
                'kategori_fasiliti' => 'Elektronik',
                'senarai_aset_id' => $projektorAset->id,
                'kuantiti_total' => 1,
                'is_countable' => true,
                'harga_sewa_sejam' => 30.00,
                'harga_sewa_separuh_hari' => 120.00,
                'harga_sewa_sehari' => 200.00,
                'deposit_diperlukan' => 300.00,
                'status_fasiliti' => 'Tersedia',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($fasilitiData as $fasiliti) {
            DB::table('senarai_fasiliti')->insert($fasiliti);
        }

        // Get inserted fasiliti IDs
        $dewanFasiliti = DB::table('senarai_fasiliti')->where('kod_fasiliti', 'FS-2025-0001')->first();
        $bilikFasiliti = DB::table('senarai_fasiliti')->where('kod_fasiliti', 'FS-2025-0002')->first();
        $kerusiFasiliti = DB::table('senarai_fasiliti')->where('kod_fasiliti', 'FS-2025-0003')->first();
        $mejaFasiliti = DB::table('senarai_fasiliti')->where('kod_fasiliti', 'FS-2025-0004')->first();
        $paFasiliti = DB::table('senarai_fasiliti')->where('kod_fasiliti', 'FS-2025-0005')->first();
        $projektorFasiliti = DB::table('senarai_fasiliti')->where('kod_fasiliti', 'FS-2025-0006')->first();

        // 3. SEED TEMPAHAN FASILITI
        // Tempahan 1: Majlis Perkahwinan (Past - Selesai)
        $tempahan1Id = DB::table('tempahan_fasiliti')->insertGetId([
            'masjid_id' => $masjidId,
            'no_tempahan' => 'TP-2025-0001',
            'senarai_fasiliti_id' => $dewanFasiliti->id,
            'nama_penyewa' => 'Ahmad bin Abdullah',
            'no_ic_penyewa' => '850101011234',
            'no_telefon_penyewa' => '0123456789',
            'emel_penyewa' => 'ahmad@email.com',
            'alamat_penyewa_1' => 'No 123, Jalan Masjid',
            'poskod_penyewa' => '50000',
            'bandar_penyewa' => 'Kuala Lumpur',
            'negeri_penyewa' => 'Wilayah Persekutuan',
            'tarikh_tempahan' => '2024-11-01',
            'tarikh_mula' => '2024-12-01 08:00:00',
            'tarikh_tamat' => '2024-12-01 23:00:00',
            'tempoh_sewa' => 1,
            'unit_tempoh' => 'Hari',
            'tujuan_tempahan' => 'Majlis Perkahwinan',
            'jenis_acara' => 'Perkahwinan',
            'bilangan_jangka_peserta' => 400,
            'harga_sewa' => 1700.00,
            'deposit' => 500.00,
            'jumlah_bayaran' => 2200.00,
            'status_tempahan' => 'Selesai',
            'diluluskan_oleh' => 1,
            'tarikh_diluluskan' => '2024-11-05 10:00:00',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => '2024-11-01 09:00:00',
            'updated_at' => '2024-12-02 09:00:00',
        ]);

        // Items for Tempahan 1
        DB::table('tempahan_fasiliti_items')->insert([
            [
                'tempahan_fasiliti_id' => $tempahan1Id,
                'senarai_fasiliti_id' => $dewanFasiliti->id,
                'quantity' => 1,
                'harga_per_unit' => 700.00,
                'subtotal' => 700.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan1Id,
                'senarai_fasiliti_id' => $kerusiFasiliti->id,
                'quantity' => 100,
                'harga_per_unit' => 7.00,
                'subtotal' => 700.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan1Id,
                'senarai_fasiliti_id' => $mejaFasiliti->id,
                'quantity' => 20,
                'harga_per_unit' => 14.00,
                'subtotal' => 280.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan1Id,
                'senarai_fasiliti_id' => $paFasiliti->id,
                'quantity' => 1,
                'harga_per_unit' => 350.00,
                'subtotal' => 350.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Tempahan 2: Seminar (Future - Lulus)
        $tempahan2Id = DB::table('tempahan_fasiliti')->insertGetId([
            'masjid_id' => $masjidId,
            'no_tempahan' => 'TP-2025-0002',
            'senarai_fasiliti_id' => $dewanFasiliti->id,
            'nama_penyewa' => 'Siti binti Hassan',
            'no_ic_penyewa' => '900202025678',
            'no_telefon_penyewa' => '0198765432',
            'emel_penyewa' => 'siti@email.com',
            'alamat_penyewa_1' => 'No 456, Jalan Perdana',
            'poskod_penyewa' => '46000',
            'bandar_penyewa' => 'Petaling Jaya',
            'negeri_penyewa' => 'Selangor',
            'organisasi_penyewa' => 'Persatuan Wanita Islam',
            'tarikh_tempahan' => '2025-12-10',
            'tarikh_mula' => '2025-12-25 08:00:00',
            'tarikh_tamat' => '2025-12-25 17:00:00',
            'tempoh_sewa' => 1,
            'unit_tempoh' => 'Hari',
            'tujuan_tempahan' => 'Seminar Keusahawanan Wanita',
            'jenis_acara' => 'Seminar',
            'bilangan_jangka_peserta' => 200,
            'harga_sewa' => 1350.00,
            'deposit' => 500.00,
            'jumlah_bayaran' => 1850.00,
            'status_tempahan' => 'Lulus',
            'diluluskan_oleh' => 1,
            'tarikh_diluluskan' => '2025-12-12 14:00:00',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => '2025-12-10 10:00:00',
            'updated_at' => '2025-12-12 14:00:00',
        ]);

        // Items for Tempahan 2
        DB::table('tempahan_fasiliti_items')->insert([
            [
                'tempahan_fasiliti_id' => $tempahan2Id,
                'senarai_fasiliti_id' => $dewanFasiliti->id,
                'quantity' => 1,
                'harga_per_unit' => 700.00,
                'subtotal' => 700.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan2Id,
                'senarai_fasiliti_id' => $kerusiFasiliti->id,
                'quantity' => 80,
                'harga_per_unit' => 7.00,
                'subtotal' => 560.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan2Id,
                'senarai_fasiliti_id' => $projektorFasiliti->id,
                'quantity' => 1,
                'harga_per_unit' => 200.00,
                'subtotal' => 200.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Tempahan 3: Mesyuarat (Future - Lulus) - OVERLAPPING with Tempahan 2 but different items
        $tempahan3Id = DB::table('tempahan_fasiliti')->insertGetId([
            'masjid_id' => $masjidId,
            'no_tempahan' => 'TP-2025-0003',
            'senarai_fasiliti_id' => $bilikFasiliti->id,
            'nama_penyewa' => 'Mohd Rizal bin Ismail',
            'no_ic_penyewa' => '880303039012',
            'no_telefon_penyewa' => '0176543210',
            'emel_penyewa' => 'rizal@email.com',
            'alamat_penyewa_1' => 'No 789, Jalan Harmoni',
            'poskod_penyewa' => '40000',
            'bandar_penyewa' => 'Shah Alam',
            'negeri_penyewa' => 'Selangor',
            'organisasi_penyewa' => 'Syarikat ABC Sdn Bhd',
            'tarikh_tempahan' => '2025-12-11',
            'tarikh_mula' => '2025-12-25 09:00:00',
            'tarikh_tamat' => '2025-12-25 13:00:00',
            'tempoh_sewa' => 4,
            'unit_tempoh' => 'Jam',
            'tujuan_tempahan' => 'Mesyuarat Tahunan',
            'jenis_acara' => 'Mesyuarat',
            'bilangan_jangka_peserta' => 25,
            'harga_sewa' => 200.00,
            'deposit' => 200.00,
            'jumlah_bayaran' => 400.00,
            'status_tempahan' => 'Lulus',
            'diluluskan_oleh' => 1,
            'tarikh_diluluskan' => '2025-12-12 15:00:00',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => '2025-12-11 11:00:00',
            'updated_at' => '2025-12-12 15:00:00',
        ]);

        // Items for Tempahan 3
        DB::table('tempahan_fasiliti_items')->insert([
            [
                'tempahan_fasiliti_id' => $tempahan3Id,
                'senarai_fasiliti_id' => $bilikFasiliti->id,
                'quantity' => 1,
                'harga_per_unit' => 50.00,
                'subtotal' => 200.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Tempahan 4: Kursus (Future - Baharu) - Pending approval
        $tempahan4Id = DB::table('tempahan_fasiliti')->insertGetId([
            'masjid_id' => $masjidId,
            'no_tempahan' => 'TP-2025-0004',
            'senarai_fasiliti_id' => $dewanFasiliti->id,
            'nama_penyewa' => 'Fatimah binti Ahmad',
            'no_ic_penyewa' => '920404043456',
            'no_telefon_penyewa' => '0134567890',
            'emel_penyewa' => 'fatimah@email.com',
            'alamat_penyewa_1' => 'No 321, Jalan Sejahtera',
            'poskod_penyewa' => '68000',
            'bandar_penyewa' => 'Ampang',
            'negeri_penyewa' => 'Selangor',
            'organisasi_penyewa' => 'Institut Pendidikan XYZ',
            'tarikh_tempahan' => '2025-12-14',
            'tarikh_mula' => '2026-01-15 08:00:00',
            'tarikh_tamat' => '2026-01-15 17:00:00',
            'tempoh_sewa' => 1,
            'unit_tempoh' => 'Hari',
            'tujuan_tempahan' => 'Kursus Kepimpinan Belia',
            'jenis_acara' => 'Kursus',
            'bilangan_jangka_peserta' => 150,
            'harga_sewa' => 1260.00,
            'deposit' => 500.00,
            'jumlah_bayaran' => 1760.00,
            'status_tempahan' => 'Baharu',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => '2025-12-14 09:00:00',
            'updated_at' => '2025-12-14 09:00:00',
        ]);

        // Items for Tempahan 4
        DB::table('tempahan_fasiliti_items')->insert([
            [
                'tempahan_fasiliti_id' => $tempahan4Id,
                'senarai_fasiliti_id' => $dewanFasiliti->id,
                'quantity' => 1,
                'harga_per_unit' => 700.00,
                'subtotal' => 700.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan4Id,
                'senarai_fasiliti_id' => $kerusiFasiliti->id,
                'quantity' => 60,
                'harga_per_unit' => 7.00,
                'subtotal' => 420.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tempahan_fasiliti_id' => $tempahan4Id,
                'senarai_fasiliti_id' => $mejaFasiliti->id,
                'quantity' => 10,
                'harga_per_unit' => 14.00,
                'subtotal' => 140.00,
                'status_item' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        // Delete in reverse order due to foreign keys
        DB::table('tempahan_fasiliti_items')->whereIn('tempahan_fasiliti_id', function($query) {
            $query->select('id')->from('tempahan_fasiliti')
                ->where('no_tempahan', 'like', 'TP-2025-%');
        })->delete();

        DB::table('tempahan_fasiliti')->where('no_tempahan', 'like', 'TP-2025-%')->delete();
        DB::table('senarai_fasiliti')->where('kod_fasiliti', 'like', 'FS-2025-%')->delete();
        DB::table('senarai_aset')->where('no_aset', 'like', 'AST-2025-%')->delete();
    }
};
