<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $masjidId = 1;
        $akaunBankId = DB::table('akaun_bank')->where('masjid_id', $masjidId)->first()->id ?? 1;
        $userId = 1;

        // Get kategori IDs - Pendapatan
        $kategoriYuranKariah = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Yuran Kariah')->first()->id ?? null;
        $kategoriKutipanJumaat = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Kutipan Jumaat')->first()->id ?? null;
        $kategoriDermaUmum = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Derma Umum')->first()->id ?? null;
        $kategoriZakatFitrah = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Zakat Fitrah')->first()->id ?? null;
        $kategoriZakatHarta = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Zakat Harta')->first()->id ?? null;
        $kategoriSedekah = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Sedekah')->first()->id ?? null;
        
        // Get kategori IDs - Perbelanjaan
        $kategoriElektrik = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Elektrik (TNB)')->first()->id ?? null;
        $kategoriAir = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Air (PDAM)')->first()->id ?? null;
        $kategoriInternet = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Internet')->first()->id ?? null;
        $kategoriBangunan = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Bangunan')->first()->id ?? null;
        $kategoriPeralatan = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Peralatan')->first()->id ?? null;
        $kategoriLandskap = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Landskap')->first()->id ?? null;
        $kategoriImam = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Imam')->first()->id ?? null;
        $kategoriBilal = DB::table('kategori_kewangan')->where('masjid_id', $masjidId)->where('nama_kategori', 'Bilal')->first()->id ?? null;

        $now = Carbon::now();

        // ============================================
        // PENDAPATAN - Yuran Kariah (Monthly collection)
        // ============================================
        $yuranKariahData = [
            ['tarikh' => '2025-01-10', 'bulan' => 'Januari 2025', 'jumlah' => 850.00, 'kategori_id' => $kategoriYuranKariah],
            ['tarikh' => '2025-01-17', 'bulan' => 'Januari 2025', 'jumlah' => 920.00, 'kategori_id' => $kategoriYuranKariah],
            ['tarikh' => '2025-02-07', 'bulan' => 'Februari 2025', 'jumlah' => 1050.00, 'kategori_id' => $kategoriYuranKariah],
            ['tarikh' => '2025-02-14', 'bulan' => 'Februari 2025', 'jumlah' => 890.00, 'kategori_id' => $kategoriYuranKariah],
        ];

        foreach ($yuranKariahData as $data) {
            $noKutipan = 'YK-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            // Insert into kutipan_dana
            DB::table('kutipan_dana')->insert([
                'masjid_id' => $masjidId,
                'no_kutipan' => $noKutipan,
                'tarikh_kutipan' => $data['tarikh'],
                'jenis_kutipan' => 'Kutipan Kariah',
                'bulan_kutipan' => $data['bulan'],
                'kategori_kewangan_id' => $data['kategori_id'],
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $data['jumlah'],
                'kaedah_bayaran' => 'Tunai',
                'no_resit' => 'RES-' . date('Ymd', strtotime($data['tarikh'])) . '-' . rand(100, 999),
                'tujuan' => 'Yuran kariah bulanan ' . $data['bulan'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert into transaksi_kewangan
            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Pendapatan',
                'kategori_kewangan_id' => $data['kategori_id'],
                'jumlah' => $data['jumlah'],
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => 'Tunai',
                'no_rujukan' => $noKutipan,
                'keterangan' => 'Yuran kariah bulanan ' . $data['bulan'],
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ============================================
        // PENDAPATAN - Kutipan Jumaat
        // ============================================
        $kutipanJumaatData = [
            ['tarikh' => '2025-01-03', 'jumlah' => 450.00],
            ['tarikh' => '2025-01-10', 'jumlah' => 520.00],
            ['tarikh' => '2025-01-17', 'jumlah' => 480.00],
            ['tarikh' => '2025-01-24', 'jumlah' => 510.00],
            ['tarikh' => '2025-01-31', 'jumlah' => 495.00],
            ['tarikh' => '2025-02-07', 'jumlah' => 530.00],
            ['tarikh' => '2025-02-14', 'jumlah' => 475.00],
            ['tarikh' => '2025-02-21', 'jumlah' => 505.00],
            ['tarikh' => '2025-02-28', 'jumlah' => 490.00],
        ];

        foreach ($kutipanJumaatData as $data) {
            $noKutipan = 'KJ-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            DB::table('kutipan_dana')->insert([
                'masjid_id' => $masjidId,
                'no_kutipan' => $noKutipan,
                'tarikh_kutipan' => $data['tarikh'],
                'jenis_kutipan' => 'Kutipan Kariah',
                'kategori_kewangan_id' => $kategoriKutipanJumaat,
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $data['jumlah'],
                'kaedah_bayaran' => 'Tunai',
                'no_resit' => 'RES-' . date('Ymd', strtotime($data['tarikh'])) . '-' . rand(100, 999),
                'tujuan' => 'Kutipan Jumaat ' . date('d/m/Y', strtotime($data['tarikh'])),
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Pendapatan',
                'kategori_kewangan_id' => $kategoriKutipanJumaat,
                'jumlah' => $data['jumlah'],
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => 'Tunai',
                'no_rujukan' => $noKutipan,
                'keterangan' => 'Kutipan Jumaat ' . date('d/m/Y', strtotime($data['tarikh'])),
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ============================================
        // PENDAPATAN - Derma Umum & Sedekah
        // ============================================
        $dermaData = [
            ['tarikh' => '2025-01-12', 'nama' => 'Ahmad bin Abdullah', 'kategori' => 'Derma Umum', 'kategori_id' => $kategoriDermaUmum, 'jumlah' => 500.00, 'kaedah' => 'Tunai'],
            ['tarikh' => '2025-01-20', 'nama' => 'Siti Aminah binti Hassan', 'kategori' => 'Derma Umum', 'kategori_id' => $kategoriDermaUmum, 'jumlah' => 2000.00, 'kaedah' => 'Bank Transfer'],
            ['tarikh' => '2025-01-25', 'nama' => 'Hamba Allah', 'kategori' => 'Sedekah', 'kategori_id' => $kategoriSedekah, 'jumlah' => 300.00, 'kaedah' => 'Tunai'],
            ['tarikh' => '2025-02-03', 'nama' => 'Mohd Rizal bin Ismail', 'kategori' => 'Derma Umum', 'kategori_id' => $kategoriDermaUmum, 'jumlah' => 1500.00, 'kaedah' => 'Online Banking'],
            ['tarikh' => '2025-02-15', 'nama' => 'Fatimah binti Omar', 'kategori' => 'Sedekah', 'kategori_id' => $kategoriSedekah, 'jumlah' => 800.00, 'kaedah' => 'Tunai'],
            ['tarikh' => '2025-02-20', 'nama' => 'Hamba Allah', 'kategori' => 'Sedekah', 'kategori_id' => $kategoriSedekah, 'jumlah' => 250.00, 'kaedah' => 'Tunai'],
        ];

        foreach ($dermaData as $data) {
            $noKutipan = 'DRM-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            DB::table('kutipan_dana')->insert([
                'masjid_id' => $masjidId,
                'no_kutipan' => $noKutipan,
                'tarikh_kutipan' => $data['tarikh'],
                'jenis_kutipan' => 'Derma & Sumbangan',
                'nama_penderma' => $data['nama'],
                'kategori_kewangan_id' => $data['kategori_id'],
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $data['jumlah'],
                'kaedah_bayaran' => $data['kaedah'],
                'no_rujukan' => $data['kaedah'] !== 'Tunai' ? 'TRF-' . date('Ymd', strtotime($data['tarikh'])) . rand(1000, 9999) : null,
                'no_resit' => 'RES-' . date('Ymd', strtotime($data['tarikh'])) . '-' . rand(100, 999),
                'tujuan' => $data['kategori'] . ' dari ' . $data['nama'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Pendapatan',
                'kategori_kewangan_id' => $data['kategori_id'],
                'jumlah' => $data['jumlah'],
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => $data['kaedah'],
                'no_rujukan' => $noKutipan,
                'keterangan' => $data['kategori'] . ' dari ' . $data['nama'],
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ============================================
        // PENDAPATAN - Kutipan Zakat
        // ============================================
        $zakatData = [
            ['tarikh' => '2025-01-15', 'nama' => 'Hassan bin Ahmad', 'kategori' => 'Zakat Fitrah', 'kategori_id' => $kategoriZakatFitrah, 'jumlah' => 350.00],
            ['tarikh' => '2025-01-18', 'nama' => 'Aminah binti Yusof', 'kategori' => 'Zakat Fitrah', 'kategori_id' => $kategoriZakatFitrah, 'jumlah' => 280.00],
            ['tarikh' => '2025-01-22', 'nama' => 'Zainab binti Ibrahim', 'kategori' => 'Zakat Harta', 'kategori_id' => $kategoriZakatHarta, 'jumlah' => 1200.00],
            ['tarikh' => '2025-02-05', 'nama' => 'Abdullah bin Hassan', 'kategori' => 'Zakat Fitrah', 'kategori_id' => $kategoriZakatFitrah, 'jumlah' => 420.00],
            ['tarikh' => '2025-02-10', 'nama' => 'Kamal bin Yusof', 'kategori' => 'Zakat Harta', 'kategori_id' => $kategoriZakatHarta, 'jumlah' => 2500.00],
        ];

        foreach ($zakatData as $data) {
            $noKutipan = 'ZKT-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            DB::table('kutipan_dana')->insert([
                'masjid_id' => $masjidId,
                'no_kutipan' => $noKutipan,
                'tarikh_kutipan' => $data['tarikh'],
                'jenis_kutipan' => 'Kutipan Zakat',
                'nama_pembayar' => $data['nama'],
                'kategori_kewangan_id' => $data['kategori_id'],
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $data['jumlah'],
                'kaedah_bayaran' => 'Tunai',
                'no_resit' => 'RES-' . date('Ymd', strtotime($data['tarikh'])) . '-' . rand(100, 999),
                'tujuan' => $data['kategori'] . ' dari ' . $data['nama'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Pendapatan',
                'kategori_kewangan_id' => $data['kategori_id'],
                'jumlah' => $data['jumlah'],
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => 'Tunai',
                'no_rujukan' => $noKutipan,
                'keterangan' => $data['kategori'] . ' dari ' . $data['nama'],
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ============================================
        // PERBELANJAAN - Utiliti & Bil
        // ============================================
        $utilitiData = [
            ['tarikh' => '2025-01-08', 'kategori' => 'Elektrik (TNB)', 'kategori_id' => $kategoriElektrik, 'no_bil' => 'TNB-202501-001', 'jumlah' => 450.50],
            ['tarikh' => '2025-01-10', 'kategori' => 'Air (PDAM)', 'kategori_id' => $kategoriAir, 'no_bil' => 'SAJ-202501-001', 'jumlah' => 85.00],
            ['tarikh' => '2025-01-12', 'kategori' => 'Internet', 'kategori_id' => $kategoriInternet, 'no_bil' => 'TM-202501-001', 'jumlah' => 189.00],
            ['tarikh' => '2025-02-08', 'kategori' => 'Elektrik (TNB)', 'kategori_id' => $kategoriElektrik, 'no_bil' => 'TNB-202502-001', 'jumlah' => 520.75],
            ['tarikh' => '2025-02-10', 'kategori' => 'Air (PDAM)', 'kategori_id' => $kategoriAir, 'no_bil' => 'SAJ-202502-001', 'jumlah' => 92.50],
            ['tarikh' => '2025-02-12', 'kategori' => 'Internet', 'kategori_id' => $kategoriInternet, 'no_bil' => 'TM-202502-001', 'jumlah' => 189.00],
        ];

        foreach ($utilitiData as $data) {
            $noPerbelanjaan = 'UTL-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            DB::table('perbelanjaan')->insert([
                'masjid_id' => $masjidId,
                'no_perbelanjaan' => $noPerbelanjaan,
                'tarikh_perbelanjaan' => $data['tarikh'],
                'jenis_perbelanjaan' => 'Utiliti & Bil',
                'no_bil' => $data['no_bil'],
                'kategori_kewangan_id' => $data['kategori_id'],
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $data['jumlah'],
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'PAY-' . date('Ymd', strtotime($data['tarikh'])) . rand(1000, 9999),
                'keterangan' => 'Bayaran ' . $data['kategori'] . ' - ' . $data['no_bil'],
                'status_kelulusan' => 'Diluluskan',
                'diluluskan_oleh' => $userId,
                'tarikh_diluluskan' => $data['tarikh'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Perbelanjaan',
                'kategori_kewangan_id' => $data['kategori_id'],
                'jumlah' => $data['jumlah'],
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => $noPerbelanjaan,
                'keterangan' => 'Bayaran ' . $data['kategori'] . ' - ' . $data['no_bil'],
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ============================================
        // PERBELANJAAN - Penyelenggaraan
        // ============================================
        $penyelenggaraanData = [
            ['tarikh' => '2025-01-18', 'kategori' => 'Bangunan', 'kategori_id' => $kategoriBangunan, 'kerja' => 'Pembaikan bumbung bocor', 'kontraktor' => 'Syarikat Bina Jaya', 'jumlah' => 1500.00],
            ['tarikh' => '2025-01-25', 'kategori' => 'Peralatan', 'kategori_id' => $kategoriPeralatan, 'kerja' => 'Servis penghawa dingin', 'kontraktor' => 'Cool Air Services', 'jumlah' => 350.00],
            ['tarikh' => '2025-02-12', 'kategori' => 'Landskap', 'kategori_id' => $kategoriLandskap, 'kerja' => 'Potong rumput dan trim pokok', 'kontraktor' => 'Green Garden', 'jumlah' => 280.00],
            ['tarikh' => '2025-02-18', 'kategori' => 'Bangunan', 'kategori_id' => $kategoriBangunan, 'kerja' => 'Cat dinding dalam masjid', 'kontraktor' => 'Syarikat Cat Indah', 'jumlah' => 2200.00],
        ];

        foreach ($penyelenggaraanData as $data) {
            $noPerbelanjaan = 'PSG-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            DB::table('perbelanjaan')->insert([
                'masjid_id' => $masjidId,
                'no_perbelanjaan' => $noPerbelanjaan,
                'tarikh_perbelanjaan' => $data['tarikh'],
                'jenis_perbelanjaan' => 'Penyelenggaraan',
                'kontraktor' => $data['kontraktor'],
                'kerja_dilakukan' => $data['kerja'],
                'kategori_kewangan_id' => $data['kategori_id'],
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $data['jumlah'],
                'kaedah_bayaran' => 'Cek',
                'no_rujukan' => 'CHQ-' . date('Ymd', strtotime($data['tarikh'])) . rand(100, 999),
                'pembekal_vendor' => $data['kontraktor'],
                'keterangan' => $data['kerja'] . ' - ' . $data['kategori'],
                'status_kelulusan' => 'Diluluskan',
                'diluluskan_oleh' => $userId,
                'tarikh_diluluskan' => $data['tarikh'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Perbelanjaan',
                'kategori_kewangan_id' => $data['kategori_id'],
                'jumlah' => $data['jumlah'],
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => 'Cek',
                'no_rujukan' => $noPerbelanjaan,
                'keterangan' => $data['kerja'] . ' oleh ' . $data['kontraktor'],
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ============================================
        // PERBELANJAAN - Gaji & Elaun
        // ============================================
        $gajiData = [
            ['tarikh' => '2025-01-31', 'nama' => 'Ustaz Ahmad bin Hassan', 'kategori' => 'Imam', 'kategori_id' => $kategoriImam, 'gaji' => 2500.00, 'elaun' => 300.00],
            ['tarikh' => '2025-01-31', 'nama' => 'Encik Kamal bin Yusof', 'kategori' => 'Bilal', 'kategori_id' => $kategoriBilal, 'gaji' => 1500.00, 'elaun' => 200.00],
            ['tarikh' => '2025-02-28', 'nama' => 'Ustaz Ahmad bin Hassan', 'kategori' => 'Imam', 'kategori_id' => $kategoriImam, 'gaji' => 2500.00, 'elaun' => 300.00],
            ['tarikh' => '2025-02-28', 'nama' => 'Encik Kamal bin Yusof', 'kategori' => 'Bilal', 'kategori_id' => $kategoriBilal, 'gaji' => 1500.00, 'elaun' => 200.00],
        ];

        foreach ($gajiData as $data) {
            $noPerbelanjaan = 'GJI-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $jumlah = $data['gaji'] + $data['elaun'];
            
            DB::table('perbelanjaan')->insert([
                'masjid_id' => $masjidId,
                'no_perbelanjaan' => $noPerbelanjaan,
                'tarikh_perbelanjaan' => $data['tarikh'],
                'jenis_perbelanjaan' => 'Gaji & Elaun',
                'nama_kakitangan' => $data['nama'],
                'jawatan' => $data['kategori'],
                'gaji_pokok' => $data['gaji'],
                'elaun' => $data['elaun'],
                'potongan' => 0,
                'kategori_kewangan_id' => $data['kategori_id'],
                'akaun_bank_id' => $akaunBankId,
                'jumlah' => $jumlah,
                'kaedah_bayaran' => 'Bank Transfer',
                'no_rujukan' => 'SAL-' . date('Ymd', strtotime($data['tarikh'])) . rand(1000, 9999),
                'keterangan' => 'Gaji ' . date('F Y', strtotime($data['tarikh'])) . ' - ' . $data['kategori'] . ' (' . $data['nama'] . ')',
                'status_kelulusan' => 'Diluluskan',
                'diluluskan_oleh' => $userId,
                'tarikh_diluluskan' => $data['tarikh'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('transaksi_kewangan')->insert([
                'masjid_id' => $masjidId,
                'no_transaksi' => 'TXN-' . date('Ymd', strtotime($data['tarikh'])) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tarikh_transaksi' => $data['tarikh'],
                'jenis_transaksi' => 'Perbelanjaan',
                'kategori_kewangan_id' => $data['kategori_id'],
                'jumlah' => $jumlah,
                'akaun_bank_id' => $akaunBankId,
                'kaedah_bayaran' => 'Bank Transfer',
                'no_rujukan' => $noPerbelanjaan,
                'keterangan' => 'Gaji ' . date('F Y', strtotime($data['tarikh'])) . ' - ' . $data['kategori'] . ' (' . $data['nama'] . ')',
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $masjidId = 1;

        // Delete transaksi_kewangan
        DB::table('transaksi_kewangan')
            ->where('masjid_id', $masjidId)
            ->where('no_transaksi', 'like', 'TXN-2025%')
            ->delete();

        // Delete kutipan_dana
        DB::table('kutipan_dana')
            ->where('masjid_id', $masjidId)
            ->where(function ($query) {
                $query->where('no_kutipan', 'like', 'YK-2025%')
                    ->orWhere('no_kutipan', 'like', 'KJ-2025%')
                    ->orWhere('no_kutipan', 'like', 'DRM-2025%')
                    ->orWhere('no_kutipan', 'like', 'ZKT-2025%');
            })
            ->delete();

        // Delete perbelanjaan
        DB::table('perbelanjaan')
            ->where('masjid_id', $masjidId)
            ->where(function ($query) {
                $query->where('no_perbelanjaan', 'like', 'UTL-2025%')
                    ->orWhere('no_perbelanjaan', 'like', 'PSG-2025%')
                    ->orWhere('no_perbelanjaan', 'like', 'GJI-2025%');
            })
            ->delete();
    }
};
