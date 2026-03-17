<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Get masjid_id = 1 (assuming this is the test masjid)
        $masjidId = 1;
        $userId = 1;

        // Get existing aset
        $asetList = DB::table('senarai_aset')
            ->where('masjid_id', $masjidId)
            ->whereNull('deleted_at')
            ->get();

        if ($asetList->isEmpty()) {
            return;
        }

        $now = Carbon::now();
        $pergerakanData = [];

        // Sample 1: Kerusi Plastik - Pinjaman ke luar (Belum Pulang)
        $kerusi = $asetList->firstWhere('nama_aset', 'Kerusi Plastik');
        if ($kerusi) {
            $pergerakanData[] = [
                'masjid_id' => $masjidId,
                'no_pergerakan' => 'PG-2025-0001',
                'senarai_aset_id' => $kerusi->id,
                'tarikh_pergerakan' => Carbon::now()->subDays(10),
                'jenis_pergerakan' => 'Pinjaman',
                'lokasi_asal' => $kerusi->lokasi_semasa,
                'lokasi_destinasi' => null,
                'is_lokasi_luaran' => true,
                'nama_tempat_luaran' => 'Dewan Komuniti Taman Melati',
                'alamat_luaran_1' => 'Jalan Melati 5',
                'alamat_luaran_2' => 'Taman Melati',
                'poskod_luaran' => '53100',
                'bandar_luaran' => 'Kuala Lumpur',
                'negeri_luaran' => 'WP Kuala Lumpur',
                'nama_peminjam' => 'Ahmad bin Hassan',
                'no_ic_peminjam' => '850612045678',
                'no_telefon_peminjam' => '012-3456789',
                'organisasi_peminjam' => 'Persatuan Penduduk Taman Melati',
                'tarikh_jangka_pulangan' => Carbon::now()->addDays(4),
                'tarikh_sebenar_pulangan' => null,
                'status_pulangan' => 'Belum Pulang',
                'kondisi_sebelum' => 'Baik',
                'kondisi_selepas' => null,
                'require_approval' => true,
                'diluluskan_oleh' => $userId,
                'tarikh_diluluskan' => Carbon::now()->subDays(10),
                'catatan_kelulusan' => 'Diluluskan untuk majlis kenduri',
                'sebab_pergerakan' => 'Pinjaman untuk majlis kenduri di Dewan Komuniti',
                'catatan' => '50 unit kerusi dipinjam',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Update aset status to Dipinjam
            DB::table('senarai_aset')
                ->where('id', $kerusi->id)
                ->update([
                    'lokasi_semasa' => 'Dewan Komuniti Taman Melati',
                    'status_aset' => 'Dipinjam',
                    'updated_at' => $now,
                ]);
        }

        // Sample 2: Meja Lipat - Pemindahan Dalaman (Selesai)
        $meja = $asetList->firstWhere('nama_aset', 'Meja Lipat');
        if ($meja) {
            $pergerakanData[] = [
                'masjid_id' => $masjidId,
                'no_pergerakan' => 'PG-2025-0002',
                'senarai_aset_id' => $meja->id,
                'tarikh_pergerakan' => Carbon::now()->subDays(5),
                'jenis_pergerakan' => 'Pemindahan Dalaman',
                'lokasi_asal' => 'Stor Utama',
                'lokasi_destinasi' => 'Dewan Serbaguna',
                'is_lokasi_luaran' => false,
                'nama_tempat_luaran' => null,
                'alamat_luaran_1' => null,
                'alamat_luaran_2' => null,
                'poskod_luaran' => null,
                'bandar_luaran' => null,
                'negeri_luaran' => null,
                'nama_peminjam' => null,
                'no_ic_peminjam' => null,
                'no_telefon_peminjam' => null,
                'organisasi_peminjam' => null,
                'tarikh_jangka_pulangan' => null,
                'tarikh_sebenar_pulangan' => null,
                'status_pulangan' => 'Sudah Pulang',
                'kondisi_sebelum' => 'Baik',
                'kondisi_selepas' => 'Baik',
                'require_approval' => false,
                'diluluskan_oleh' => null,
                'tarikh_diluluskan' => null,
                'catatan_kelulusan' => null,
                'sebab_pergerakan' => 'Pemindahan untuk persediaan majlis',
                'catatan' => 'Dipindahkan ke dewan untuk acara minggu ini',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Update aset lokasi
            DB::table('senarai_aset')
                ->where('id', $meja->id)
                ->update([
                    'lokasi_semasa' => 'Dewan Serbaguna',
                    'updated_at' => $now,
                ]);
        }

        // Sample 3: Projektor LCD - Pinjaman (Sudah Pulang)
        $projektor = $asetList->firstWhere('nama_aset', 'Projektor LCD');
        if ($projektor) {
            $pergerakanData[] = [
                'masjid_id' => $masjidId,
                'no_pergerakan' => 'PG-2025-0003',
                'senarai_aset_id' => $projektor->id,
                'tarikh_pergerakan' => Carbon::now()->subDays(20),
                'jenis_pergerakan' => 'Pinjaman',
                'lokasi_asal' => 'Pejabat Masjid',
                'lokasi_destinasi' => null,
                'is_lokasi_luaran' => true,
                'nama_tempat_luaran' => 'Sekolah Kebangsaan Taman Damai',
                'alamat_luaran_1' => 'Jalan Damai 1',
                'alamat_luaran_2' => null,
                'poskod_luaran' => '53000',
                'bandar_luaran' => 'Kuala Lumpur',
                'negeri_luaran' => 'WP Kuala Lumpur',
                'nama_peminjam' => 'Cikgu Fatimah binti Ali',
                'no_ic_peminjam' => '780315065432',
                'no_telefon_peminjam' => '019-8765432',
                'organisasi_peminjam' => 'SK Taman Damai',
                'tarikh_jangka_pulangan' => Carbon::now()->subDays(13),
                'tarikh_sebenar_pulangan' => Carbon::now()->subDays(14),
                'status_pulangan' => 'Sudah Pulang',
                'kondisi_sebelum' => 'Baik',
                'kondisi_selepas' => 'Baik',
                'require_approval' => true,
                'diluluskan_oleh' => $userId,
                'tarikh_diluluskan' => Carbon::now()->subDays(20),
                'catatan_kelulusan' => 'Diluluskan untuk program sekolah',
                'sebab_pergerakan' => 'Pinjaman untuk program motivasi pelajar',
                'catatan' => 'Dipulangkan dalam keadaan baik',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Projektor sudah pulang, lokasi kembali ke asal
            DB::table('senarai_aset')
                ->where('id', $projektor->id)
                ->update([
                    'lokasi_semasa' => 'Pejabat Masjid',
                    'status_aset' => 'Aktif',
                    'updated_at' => $now,
                ]);
        }

        // Sample 4: Sistem PA - Pinjaman (Lewat Pulang)
        $sistemPA = $asetList->firstWhere('nama_aset', 'Sistem PA (Public Address)');
        if ($sistemPA) {
            $pergerakanData[] = [
                'masjid_id' => $masjidId,
                'no_pergerakan' => 'PG-2025-0004',
                'senarai_aset_id' => $sistemPA->id,
                'tarikh_pergerakan' => Carbon::now()->subDays(15),
                'jenis_pergerakan' => 'Pinjaman',
                'lokasi_asal' => $sistemPA->lokasi_semasa,
                'lokasi_destinasi' => null,
                'is_lokasi_luaran' => true,
                'nama_tempat_luaran' => 'Padang Bola Taman Sentosa',
                'alamat_luaran_1' => 'Jalan Sentosa 3',
                'alamat_luaran_2' => 'Taman Sentosa',
                'poskod_luaran' => '53200',
                'bandar_luaran' => 'Kuala Lumpur',
                'negeri_luaran' => 'WP Kuala Lumpur',
                'nama_peminjam' => 'Encik Razak bin Osman',
                'no_ic_peminjam' => '820505087654',
                'no_telefon_peminjam' => '013-5678901',
                'organisasi_peminjam' => 'Kelab Belia Taman Sentosa',
                'tarikh_jangka_pulangan' => Carbon::now()->subDays(8), // Sudah lepas tarikh
                'tarikh_sebenar_pulangan' => null,
                'status_pulangan' => 'Lewat',
                'kondisi_sebelum' => 'Baik',
                'kondisi_selepas' => null,
                'require_approval' => true,
                'diluluskan_oleh' => $userId,
                'tarikh_diluluskan' => Carbon::now()->subDays(15),
                'catatan_kelulusan' => 'Diluluskan untuk sukan tahunan',
                'sebab_pergerakan' => 'Pinjaman untuk sukan tahunan taman',
                'catatan' => 'PERHATIAN: Sudah lewat pulang!',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Update aset status to Dipinjam
            DB::table('senarai_aset')
                ->where('id', $sistemPA->id)
                ->update([
                    'lokasi_semasa' => 'Padang Bola Taman Sentosa',
                    'status_aset' => 'Dipinjam',
                    'updated_at' => $now,
                ]);
        }

        // Insert all pergerakan data
        if (!empty($pergerakanData)) {
            DB::table('pergerakan_aset')->insert($pergerakanData);
        }
    }

    public function down(): void
    {
        // Delete sample pergerakan data
        DB::table('pergerakan_aset')
            ->where('masjid_id', 1)
            ->whereIn('no_pergerakan', [
                'PG-2025-0001',
                'PG-2025-0002',
                'PG-2025-0003',
                'PG-2025-0004',
            ])
            ->delete();

        // Reset aset status and lokasi
        $asetList = DB::table('senarai_aset')
            ->where('masjid_id', 1)
            ->whereNull('deleted_at')
            ->get();

        foreach ($asetList as $aset) {
            DB::table('senarai_aset')
                ->where('id', $aset->id)
                ->update([
                    'status_aset' => 'Aktif',
                    'updated_at' => now(),
                ]);
        }
    }
};
