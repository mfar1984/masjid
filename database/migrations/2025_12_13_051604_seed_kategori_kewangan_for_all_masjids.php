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
        $masjids = DB::table('masjids')->get();

        foreach ($masjids as $masjid) {
            $now = now();

            // Kategori Pendapatan
            $kategoriPendapatan = [
                ['nama' => 'Derma Umum', 'kod' => 'DERMA-01', 'urutan' => 1],
                ['nama' => 'Kutipan Jumaat', 'kod' => 'JUMAAT-01', 'urutan' => 2],
                ['nama' => 'Kutipan Subuh', 'kod' => 'SUBUH-01', 'urutan' => 3],
                ['nama' => 'Zakat Fitrah', 'kod' => 'ZAKAT-01', 'urutan' => 4],
                ['nama' => 'Zakat Harta', 'kod' => 'ZAKAT-02', 'urutan' => 5],
                ['nama' => 'Sewa Dewan', 'kod' => 'SEWA-01', 'urutan' => 6],
                ['nama' => 'Sewa Khemah', 'kod' => 'SEWA-02', 'urutan' => 7],
                ['nama' => 'Wakaf', 'kod' => 'WAKAF-01', 'urutan' => 8],
                ['nama' => 'Sedekah', 'kod' => 'SEDEKAH-01', 'urutan' => 9],
                ['nama' => 'Fidyah', 'kod' => 'FIDYAH-01', 'urutan' => 10],
                ['nama' => 'Nazar', 'kod' => 'NAZAR-01', 'urutan' => 11],
                ['nama' => 'Aqiqah', 'kod' => 'AQIQAH-01', 'urutan' => 12],
                ['nama' => 'Qurban', 'kod' => 'QURBAN-01', 'urutan' => 13],
                ['nama' => 'Yuran Kariah', 'kod' => 'KARIAH-01', 'urutan' => 14],
                ['nama' => 'Pendaftaran Perkahwinan', 'kod' => 'KAHWIN-01', 'urutan' => 15],
                ['nama' => 'Kursus Perkahwinan', 'kod' => 'KURSUS-01', 'urutan' => 16],
                ['nama' => 'Pendapatan Lain-lain', 'kod' => 'LAIN-01', 'urutan' => 17],
            ];

            foreach ($kategoriPendapatan as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'kategori_pendapatan',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Kaedah Bayaran
            $kaedahBayaran = [
                ['nama' => 'Tunai', 'kod' => 'TUNAI', 'urutan' => 1],
                ['nama' => 'Online Banking', 'kod' => 'ONLINE', 'urutan' => 2],
                ['nama' => 'FPX', 'kod' => 'FPX', 'urutan' => 3],
                ['nama' => 'Cek', 'kod' => 'CEK', 'urutan' => 4],
                ['nama' => 'Bank Draf', 'kod' => 'DRAF', 'urutan' => 5],
                ['nama' => 'Kad Kredit', 'kod' => 'KREDIT', 'urutan' => 6],
                ['nama' => 'Kad Debit', 'kod' => 'DEBIT', 'urutan' => 7],
                ['nama' => 'E-Wallet (Touch n Go)', 'kod' => 'TNG', 'urutan' => 8],
                ['nama' => 'E-Wallet (GrabPay)', 'kod' => 'GRAB', 'urutan' => 9],
                ['nama' => 'E-Wallet (Boost)', 'kod' => 'BOOST', 'urutan' => 10],
                ['nama' => 'E-Wallet (ShopeePay)', 'kod' => 'SHOPEE', 'urutan' => 11],
                ['nama' => 'QR Pay (DuitNow)', 'kod' => 'DUITNOW', 'urutan' => 12],
                ['nama' => 'Lain-lain', 'kod' => 'LAIN', 'urutan' => 13],
            ];

            foreach ($kaedahBayaran as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'kaedah_bayaran',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Jenis Akaun
            $jenisAkaun = [
                ['nama' => 'Akaun Semasa', 'kod' => 'SEMASA', 'urutan' => 1],
                ['nama' => 'Akaun Simpanan', 'kod' => 'SIMPANAN', 'urutan' => 2],
                ['nama' => 'Akaun Simpanan-i', 'kod' => 'SIMPANAN-I', 'urutan' => 3],
                ['nama' => 'Akaun Semasa-i', 'kod' => 'SEMASA-I', 'urutan' => 4],
                ['nama' => 'Akaun Pelaburan', 'kod' => 'PELABURAN', 'urutan' => 5],
                ['nama' => 'Akaun Deposit Tetap', 'kod' => 'DEPOSIT', 'urutan' => 6],
                ['nama' => 'Akaun Deposit-i', 'kod' => 'DEPOSIT-I', 'urutan' => 7],
                ['nama' => 'Akaun Mudharabah', 'kod' => 'MUDHARABAH', 'urutan' => 8],
                ['nama' => 'Akaun Wadiah', 'kod' => 'WADIAH', 'urutan' => 9],
            ];

            foreach ($jenisAkaun as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'jenis_akaun',
                    'nama_kategori' => $item['nama'],
                    'kod_kategori' => $item['kod'],
                    'urutan' => $item['urutan'],
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Nama Bank - Semua bank di Malaysia
            $namaBank = [
                // Commercial Banks
                ['nama' => 'Maybank (Malayan Banking Berhad)', 'kod' => 'MBB', 'urutan' => 1],
                ['nama' => 'CIMB Bank Berhad', 'kod' => 'CIMB', 'urutan' => 2],
                ['nama' => 'Public Bank Berhad', 'kod' => 'PBB', 'urutan' => 3],
                ['nama' => 'RHB Bank Berhad', 'kod' => 'RHB', 'urutan' => 4],
                ['nama' => 'Hong Leong Bank Berhad', 'kod' => 'HLB', 'urutan' => 5],
                ['nama' => 'AmBank (M) Berhad', 'kod' => 'AMB', 'urutan' => 6],
                ['nama' => 'United Overseas Bank (Malaysia) Bhd', 'kod' => 'UOB', 'urutan' => 7],
                ['nama' => 'OCBC Bank (Malaysia) Berhad', 'kod' => 'OCBC', 'urutan' => 8],
                ['nama' => 'HSBC Bank Malaysia Berhad', 'kod' => 'HSBC', 'urutan' => 9],
                ['nama' => 'Standard Chartered Bank Malaysia Berhad', 'kod' => 'SCB', 'urutan' => 10],
                ['nama' => 'Affin Bank Berhad', 'kod' => 'AFFIN', 'urutan' => 11],
                ['nama' => 'Alliance Bank Malaysia Berhad', 'kod' => 'ALLIANCE', 'urutan' => 12],
                
                // Islamic Banks
                ['nama' => 'Bank Islam Malaysia Berhad', 'kod' => 'BIMB', 'urutan' => 13],
                ['nama' => 'Bank Muamalat Malaysia Berhad', 'kod' => 'BMM', 'urutan' => 14],
                ['nama' => 'Bank Rakyat', 'kod' => 'BR', 'urutan' => 15],
                ['nama' => 'CIMB Islamic Bank Berhad', 'kod' => 'CIMB-I', 'urutan' => 16],
                ['nama' => 'Maybank Islamic Berhad', 'kod' => 'MBB-I', 'urutan' => 17],
                ['nama' => 'Public Islamic Bank Berhad', 'kod' => 'PBB-I', 'urutan' => 18],
                ['nama' => 'RHB Islamic Bank Berhad', 'kod' => 'RHB-I', 'urutan' => 19],
                ['nama' => 'Hong Leong Islamic Bank Berhad', 'kod' => 'HLB-I', 'urutan' => 20],
                ['nama' => 'AmBank Islamic Berhad', 'kod' => 'AMB-I', 'urutan' => 21],
                ['nama' => 'HSBC Amanah Malaysia Berhad', 'kod' => 'HSBC-I', 'urutan' => 22],
                ['nama' => 'Affin Islamic Bank Berhad', 'kod' => 'AFFIN-I', 'urutan' => 23],
                ['nama' => 'Alliance Islamic Bank Berhad', 'kod' => 'ALLIANCE-I', 'urutan' => 24],
                ['nama' => 'OCBC Al-Amin Bank Berhad', 'kod' => 'OCBC-I', 'urutan' => 25],
                ['nama' => 'Standard Chartered Saadiq Berhad', 'kod' => 'SCB-I', 'urutan' => 26],
                ['nama' => 'Kuwait Finance House (Malaysia) Berhad', 'kod' => 'KFH', 'urutan' => 27],
                ['nama' => 'Al Rajhi Banking & Investment Corporation (Malaysia) Berhad', 'kod' => 'ALRAJHI', 'urutan' => 28],
                
                // Development Financial Institutions
                ['nama' => 'Bank Pembangunan Malaysia Berhad', 'kod' => 'BPMB', 'urutan' => 29],
                ['nama' => 'Bank Simpanan Nasional (BSN)', 'kod' => 'BSN', 'urutan' => 30],
                ['nama' => 'Bank Pertanian Malaysia (Agrobank)', 'kod' => 'AGROBANK', 'urutan' => 31],
                ['nama' => 'Export-Import Bank of Malaysia Berhad (EXIM Bank)', 'kod' => 'EXIM', 'urutan' => 32],
                ['nama' => 'SME Bank', 'kod' => 'SME', 'urutan' => 33],
                
                // Foreign Banks
                ['nama' => 'Citibank Berhad', 'kod' => 'CITI', 'urutan' => 34],
                ['nama' => 'Bank of China (Malaysia) Berhad', 'kod' => 'BOC', 'urutan' => 35],
                ['nama' => 'Industrial and Commercial Bank of China (Malaysia) Berhad', 'kod' => 'ICBC', 'urutan' => 36],
                ['nama' => 'Bank of America Malaysia Berhad', 'kod' => 'BOA', 'urutan' => 37],
                ['nama' => 'J.P. Morgan Chase Bank Berhad', 'kod' => 'JPM', 'urutan' => 38],
                ['nama' => 'Sumitomo Mitsui Banking Corporation Malaysia Berhad', 'kod' => 'SMBC', 'urutan' => 39],
                ['nama' => 'MUFG Bank (Malaysia) Berhad', 'kod' => 'MUFG', 'urutan' => 40],
                ['nama' => 'Mizuho Bank (Malaysia) Berhad', 'kod' => 'MIZUHO', 'urutan' => 41],
                ['nama' => 'The Bank of Tokyo-Mitsubishi UFJ (Malaysia) Berhad', 'kod' => 'BTMU', 'urutan' => 42],
                ['nama' => 'BNP Paribas Malaysia Berhad', 'kod' => 'BNP', 'urutan' => 43],
                ['nama' => 'Deutsche Bank (Malaysia) Berhad', 'kod' => 'DB', 'urutan' => 44],
                
                // Others
                ['nama' => 'Lain-lain', 'kod' => 'LAIN', 'urutan' => 45],
            ];

            foreach ($namaBank as $item) {
                DB::table('kategori_kewangan')->insert([
                    'masjid_id' => $masjid->id,
                    'jenis_kategori' => 'nama_bank',
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
        // Delete seeded kategori kewangan
        DB::table('kategori_kewangan')
            ->whereIn('jenis_kategori', ['kategori_pendapatan', 'kaedah_bayaran', 'jenis_akaun', 'nama_bank'])
            ->delete();
    }
};
