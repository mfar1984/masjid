<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $masjidId = 1; // Assuming masjid_id = 1
        $userId = 1; // Assuming user_id = 1
        $now = now();

        // Get kategori IDs
        $kategoriPendapatan = DB::table('kategori_kewangan')
            ->where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'kategori_pendapatan')
            ->where('status', 'Aktif')
            ->first();

        $kategoriPerbelanjaan = DB::table('kategori_kewangan')
            ->where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'kategori_perbelanjaan')
            ->where('status', 'Aktif')
            ->first();

        $jenisDerma = DB::table('kategori_kewangan')
            ->where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'jenis_derma')
            ->where('status', 'Aktif')
            ->first();

        $jenisBil = DB::table('kategori_kewangan')
            ->where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'jenis_bil')
            ->where('status', 'Aktif')
            ->first();

        $akaunBank = DB::table('akaun_bank')
            ->where('masjid_id', $masjidId)
            ->where('status', 'Aktif')
            ->first();

        // 1. KUTIPAN KARIAH
        $kutipanKariah = [
            [
                'no_kutipan' => 'KUT-2025-0001',
                'tarikh_kutipan' => '2025-01-05',
                'jenis_kutipan' => 'Kutipan Kariah',
                'nama_penderma' => 'Ahmad bin Abdullah',
                'no_telefon_penderma' => '0123456789',
                'bulan_kutipan' => 'Januari 2025',
                'jumlah' => 50.00,
                'kaedah_bayaran' => 'Tunai',
                'no_resit' => 'RES-2025-0001',
                'catatan' => 'Yuran kariah bulanan Januari 2025',
            ],
            [
                'no_kutipan' => 'KUT-2025-0002',
                'tarikh_kutipan' => '2025-01-10',
                'jenis_kutipan' => 'Kutipan Kariah',
                'nama_penderma' => 'Fatimah binti Hassan',
                'no_telefon_penderma' => '0198765432',
                'bulan_kutipan' => 'Januari 2025',
                'jumlah' => 50.00,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250110001',
                'no_resit' => 'RES-2025-0002',
                'catatan' => 'Yuran kariah bulanan Januari 2025',
            ],
        ];

        foreach ($kutipanKariah as $data) {
            DB::table('kutipan_dana')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPendapatan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 2. DERMA & SUMBANGAN
        $dermaSumbangan = [
            [
                'no_kutipan' => 'KUT-2025-0003',
                'tarikh_kutipan' => '2025-01-15',
                'jenis_kutipan' => 'Derma & Sumbangan',
                'nama_penderma' => 'Syarikat ABC Sdn Bhd',
                'no_telefon_penderma' => '0312345678',
                'alamat_penderma' => 'No 123, Jalan Masjid, 50000 Kuala Lumpur',
                'jenis_derma_id' => $jenisDerma->id ?? null,
                'jumlah' => 5000.00,
                'kaedah_bayaran' => 'Cek',
                'no_rujukan' => 'CHQ123456',
                'no_resit' => 'RES-2025-0003',
                'tujuan' => 'Derma untuk pembinaan surau baru',
                'catatan' => 'Derma korporat dari Syarikat ABC',
            ],
            [
                'no_kutipan' => 'KUT-2025-0004',
                'tarikh_kutipan' => '2025-01-20',
                'jenis_kutipan' => 'Derma & Sumbangan',
                'nama_penderma' => 'Haji Mahmud bin Ali',
                'no_telefon_penderma' => '0167891234',
                'jenis_derma_id' => $jenisDerma->id ?? null,
                'jumlah' => 1000.00,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250120001',
                'no_resit' => 'RES-2025-0004',
                'tujuan' => 'Derma umum',
                'catatan' => 'Derma ikhlas untuk masjid',
            ],
        ];

        foreach ($dermaSumbangan as $data) {
            DB::table('kutipan_dana')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPendapatan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 3. KUTIPAN ZAKAT
        $kutipanZakat = [
            [
                'no_kutipan' => 'KUT-2025-0005',
                'tarikh_kutipan' => '2025-01-25',
                'jenis_kutipan' => 'Kutipan Zakat',
                'nama_pembayar' => 'Abdullah bin Omar',
                'no_kp_pembayar' => '850101011234',
                'jenis_zakat' => 'Zakat Fitrah',
                'jumlah' => 35.00,
                'kaedah_bayaran' => 'Tunai',
                'no_resit' => 'RES-2025-0005',
                'catatan' => 'Zakat fitrah untuk 5 orang',
            ],
            [
                'no_kutipan' => 'KUT-2025-0006',
                'tarikh_kutipan' => '2025-01-28',
                'jenis_kutipan' => 'Kutipan Zakat',
                'nama_pembayar' => 'Siti Aminah binti Yusof',
                'no_kp_pembayar' => '900505145678',
                'jenis_zakat' => 'Zakat Harta',
                'jumlah' => 500.00,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250128001',
                'no_resit' => 'RES-2025-0006',
                'catatan' => 'Zakat harta tahun 2024',
            ],
        ];

        foreach ($kutipanZakat as $data) {
            DB::table('kutipan_dana')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPendapatan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 4. KUTIPAN LAIN
        $kutipanLain = [
            [
                'no_kutipan' => 'KUT-2025-0007',
                'tarikh_kutipan' => '2025-02-01',
                'jenis_kutipan' => 'Kutipan Lain-lain',
                'nama_penderma' => 'Encik Razak bin Hamid',
                'no_telefon_penderma' => '0134567890',
                'jumlah' => 200.00,
                'kaedah_bayaran' => 'Tunai',
                'no_resit' => 'RES-2025-0007',
                'tujuan' => 'Sewa dewan untuk majlis perkahwinan',
                'catatan' => 'Sewa dewan 1 hari',
            ],
        ];

        foreach ($kutipanLain as $data) {
            DB::table('kutipan_dana')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPendapatan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 5. UTILITI & BIL
        $utilitiBil = [
            [
                'no_perbelanjaan' => 'BLJ-2025-0001',
                'tarikh_perbelanjaan' => '2025-01-05',
                'jenis_perbelanjaan' => 'Utiliti & Bil',
                'jenis_bil_id' => $jenisBil->id ?? null,
                'no_bil' => 'TNB-202501-001',
                'bacaan_meter_lama' => 1000.00,
                'bacaan_meter_baru' => 1250.00,
                'jumlah' => 180.50,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250105001',
                'pembekal_vendor' => 'Tenaga Nasional Berhad',
                'keterangan' => 'Bil elektrik Januari 2025',
                'catatan' => 'Penggunaan 250 kWh',
                'status_kelulusan' => 'Diluluskan',
            ],
            [
                'no_perbelanjaan' => 'BLJ-2025-0002',
                'tarikh_perbelanjaan' => '2025-01-10',
                'jenis_perbelanjaan' => 'Utiliti & Bil',
                'jenis_bil_id' => $jenisBil->id ?? null,
                'no_bil' => 'AIR-202501-001',
                'bacaan_meter_lama' => 500.00,
                'bacaan_meter_baru' => 550.00,
                'jumlah' => 45.00,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250110002',
                'pembekal_vendor' => 'Air Selangor',
                'keterangan' => 'Bil air Januari 2025',
                'catatan' => 'Penggunaan 50 m³',
                'status_kelulusan' => 'Diluluskan',
            ],
        ];

        foreach ($utilitiBil as $data) {
            DB::table('perbelanjaan')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPerbelanjaan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 6. PENYELENGGARAAN
        $penyelenggaraan = [
            [
                'no_perbelanjaan' => 'BLJ-2025-0003',
                'tarikh_perbelanjaan' => '2025-01-15',
                'jenis_perbelanjaan' => 'Penyelenggaraan',
                'jenis_penyelenggaraan' => 'Baik Pulih',
                'kontraktor' => 'Syarikat Kontraktor XYZ',
                'no_telefon_kontraktor' => '0312345678',
                'kerja_dilakukan' => 'Baik pulih bumbung masjid yang bocor',
                'jumlah' => 3500.00,
                'kaedah_bayaran' => 'Cek',
                'no_rujukan' => 'CHQ789012',
                'pembekal_vendor' => 'Syarikat Kontraktor XYZ',
                'keterangan' => 'Kerja baik pulih bumbung',
                'catatan' => 'Kerja siap dalam 3 hari',
                'status_kelulusan' => 'Diluluskan',
            ],
        ];

        foreach ($penyelenggaraan as $data) {
            DB::table('perbelanjaan')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPerbelanjaan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 7. GAJI & ELAUN
        $gajiElaun = [
            [
                'no_perbelanjaan' => 'BLJ-2025-0004',
                'tarikh_perbelanjaan' => '2025-01-31',
                'jenis_perbelanjaan' => 'Gaji & Elaun',
                'nama_kakitangan' => 'Ustaz Muhammad bin Ali',
                'jawatan' => 'Imam',
                'gaji_pokok' => 2500.00,
                'elaun' => 500.00,
                'potongan' => 0.00,
                'jumlah' => 3000.00,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250131001',
                'keterangan' => 'Gaji bulan Januari 2025',
                'catatan' => 'Gaji + elaun khas',
                'status_kelulusan' => 'Diluluskan',
            ],
            [
                'no_perbelanjaan' => 'BLJ-2025-0005',
                'tarikh_perbelanjaan' => '2025-01-31',
                'jenis_perbelanjaan' => 'Gaji & Elaun',
                'nama_kakitangan' => 'Encik Ahmad bin Hassan',
                'jawatan' => 'Bilal',
                'gaji_pokok' => 1500.00,
                'elaun' => 200.00,
                'potongan' => 0.00,
                'jumlah' => 1700.00,
                'kaedah_bayaran' => 'Online Banking',
                'no_rujukan' => 'TXN20250131002',
                'keterangan' => 'Gaji bulan Januari 2025',
                'catatan' => 'Gaji + elaun transport',
                'status_kelulusan' => 'Diluluskan',
            ],
        ];

        foreach ($gajiElaun as $data) {
            DB::table('perbelanjaan')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPerbelanjaan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 8. PERBELANJAAN LAIN
        $perbelanjaanLain = [
            [
                'no_perbelanjaan' => 'BLJ-2025-0006',
                'tarikh_perbelanjaan' => '2025-02-05',
                'jenis_perbelanjaan' => 'Perbelanjaan Lain',
                'jumlah' => 500.00,
                'kaedah_bayaran' => 'Tunai',
                'pembekal_vendor' => 'Kedai Alat Tulis ABC',
                'keterangan' => 'Pembelian alat tulis dan pejabat',
                'catatan' => 'Kertas A4, pen, fail, dll',
                'status_kelulusan' => 'Diluluskan',
            ],
            [
                'no_perbelanjaan' => 'BLJ-2025-0007',
                'tarikh_perbelanjaan' => '2025-02-08',
                'jenis_perbelanjaan' => 'Perbelanjaan Lain',
                'jumlah' => 300.00,
                'kaedah_bayaran' => 'Tunai',
                'pembekal_vendor' => 'Kedai Runcit Masjid',
                'keterangan' => 'Pembelian barangan pembersihan',
                'catatan' => 'Sabun, pencuci lantai, mop, dll',
                'status_kelulusan' => 'Diluluskan',
            ],
        ];

        foreach ($perbelanjaanLain as $data) {
            DB::table('perbelanjaan')->insert(array_merge($data, [
                'masjid_id' => $masjidId,
                'kategori_kewangan_id' => $kategoriPerbelanjaan->id ?? null,
                'akaun_bank_id' => $akaunBank->id ?? null,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // Create corresponding TransaksiKewangan records
        $this->createTransaksiKewangan($masjidId, $userId, $now);
    }

    private function createTransaksiKewangan($masjidId, $userId, $now)
    {
        // Get all kutipan_dana
        $kutipanDana = DB::table('kutipan_dana')
            ->where('masjid_id', $masjidId)
            ->whereNull('transaksi_kewangan_id')
            ->get();

        foreach ($kutipanDana as $kutipan) {
            $transaksiId = DB::table('transaksi_kewangan')->insertGetId([
                'masjid_id' => $masjidId,
                'no_transaksi' => $kutipan->no_kutipan,
                'tarikh_transaksi' => $kutipan->tarikh_kutipan,
                'jenis_transaksi' => 'Pendapatan',
                'kategori_kewangan_id' => $kutipan->kategori_kewangan_id,
                'akaun_bank_id' => $kutipan->akaun_bank_id,
                'jumlah' => $kutipan->jumlah,
                'kaedah_bayaran' => $kutipan->kaedah_bayaran,
                'no_rujukan' => $kutipan->no_rujukan,
                'keterangan' => $kutipan->catatan ?? $kutipan->jenis_kutipan,
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Update kutipan_dana with transaksi_kewangan_id
            DB::table('kutipan_dana')
                ->where('id', $kutipan->id)
                ->update(['transaksi_kewangan_id' => $transaksiId]);
        }

        // Get all perbelanjaan
        $perbelanjaan = DB::table('perbelanjaan')
            ->where('masjid_id', $masjidId)
            ->whereNull('transaksi_kewangan_id')
            ->get();

        foreach ($perbelanjaan as $belanja) {
            $transaksiId = DB::table('transaksi_kewangan')->insertGetId([
                'masjid_id' => $masjidId,
                'no_transaksi' => $belanja->no_perbelanjaan,
                'tarikh_transaksi' => $belanja->tarikh_perbelanjaan,
                'jenis_transaksi' => 'Perbelanjaan',
                'kategori_kewangan_id' => $belanja->kategori_kewangan_id,
                'akaun_bank_id' => $belanja->akaun_bank_id,
                'jumlah' => $belanja->jumlah,
                'kaedah_bayaran' => $belanja->kaedah_bayaran,
                'no_rujukan' => $belanja->no_rujukan,
                'keterangan' => $belanja->keterangan ?? $belanja->jenis_perbelanjaan,
                'status' => 'Selesai',
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Update perbelanjaan with transaksi_kewangan_id
            DB::table('perbelanjaan')
                ->where('id', $belanja->id)
                ->update(['transaksi_kewangan_id' => $transaksiId]);
        }
    }

    public function down(): void
    {
        // Delete sample data
        DB::table('transaksi_kewangan')
            ->where('no_transaksi', 'like', 'KUT-2025-%')
            ->orWhere('no_transaksi', 'like', 'BLJ-2025-%')
            ->delete();

        DB::table('kutipan_dana')
            ->where('no_kutipan', 'like', 'KUT-2025-%')
            ->delete();

        DB::table('perbelanjaan')
            ->where('no_perbelanjaan', 'like', 'BLJ-2025-%')
            ->delete();
    }
};
