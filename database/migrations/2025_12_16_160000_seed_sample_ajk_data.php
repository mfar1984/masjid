<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $masjidId = 1; // Administrator
        $now = Carbon::now();
        $tarikhLantikan = Carbon::create(2024, 1, 15);
        $tarikhTamat = Carbon::create(2026, 1, 14);

        // Check if AJK already exists for this masjid to avoid duplicates
        $existingCount = DB::table('ajk')->where('masjid_id', $masjidId)->count();
        
        // Sample AJK data with realistic Malaysian names for all jawatan
        $ajkData = [
            // Pengerusi
            [
                'nama' => 'Dato\' Haji Ahmad bin Mohd Yusof',
                'no_ic' => '650415-01-5523',
                'telefon' => '012-3456789',
                'email' => 'ahmad.yusof@email.com',
                'alamat' => 'No. 15, Jalan Mawar 3, Taman Bunga Raya, 50000 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Pengerusi',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Naib Pengerusi
            [
                'nama' => 'Haji Mohd Razali bin Abdullah',
                'no_ic' => '680722-01-6789',
                'telefon' => '013-4567890',
                'email' => 'razali.abdullah@email.com',
                'alamat' => 'No. 28, Jalan Melati 5, Taman Seri Indah, 50100 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Naib Pengerusi',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Setiausaha
            [
                'nama' => 'Encik Mohd Faizal bin Hassan',
                'no_ic' => '780305-01-4455',
                'telefon' => '014-5678901',
                'email' => 'faizal.hassan@email.com',
                'alamat' => 'No. 42, Jalan Cempaka 8, Taman Harmoni, 50200 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Setiausaha',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Bendahari
            [
                'nama' => 'Encik Kamaruddin bin Ismail',
                'no_ic' => '750918-01-3367',
                'telefon' => '016-6789012',
                'email' => 'kamaruddin.ismail@email.com',
                'alamat' => 'No. 55, Jalan Kenanga 12, Taman Damai, 50300 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Bendahari',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Penolong Setiausaha
            [
                'nama' => 'Encik Azman bin Othman',
                'no_ic' => '820614-01-2289',
                'telefon' => '017-7890123',
                'email' => 'azman.othman@email.com',
                'alamat' => 'No. 18, Jalan Anggerik 4, Taman Sejahtera, 50400 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Penolong Setiausaha',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Penolong Bendahari
            [
                'nama' => 'Encik Hafiz bin Zainal',
                'no_ic' => '850227-01-1145',
                'telefon' => '018-8901234',
                'email' => 'hafiz.zainal@email.com',
                'alamat' => 'No. 33, Jalan Orkid 7, Taman Bahagia, 50500 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Penolong Bendahari',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Imam 1
            [
                'nama' => 'Ustaz Haji Zulkifli bin Ahmad',
                'no_ic' => '700812-01-5567',
                'telefon' => '019-9012345',
                'email' => 'zulkifli.ahmad@email.com',
                'alamat' => 'No. 8, Jalan Teratai 2, Taman Mutiara, 50600 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Imam',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Imam 2
            [
                'nama' => 'Ustaz Mohd Syafiq bin Ramli',
                'no_ic' => '880503-01-7789',
                'telefon' => '012-2345678',
                'email' => 'syafiq.ramli@email.com',
                'alamat' => 'No. 21, Jalan Dahlia 9, Taman Permata, 50700 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Imam',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Bilal 1
            [
                'nama' => 'Encik Roslan bin Hamid',
                'no_ic' => '760129-01-4423',
                'telefon' => '013-3456789',
                'email' => 'roslan.hamid@email.com',
                'alamat' => 'No. 47, Jalan Seroja 6, Taman Sentosa, 50800 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Bilal',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Bilal 2
            [
                'nama' => 'Encik Shahrul bin Nizam',
                'no_ic' => '830716-01-6601',
                'telefon' => '014-4567890',
                'email' => 'shahrul.nizam@email.com',
                'alamat' => 'No. 12, Jalan Bakawali 3, Taman Mesra, 50900 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Bilal',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Siak
            [
                'nama' => 'Encik Baharuddin bin Kassim',
                'no_ic' => '720408-01-8845',
                'telefon' => '016-5678901',
                'email' => 'baharuddin.kassim@email.com',
                'alamat' => 'No. 39, Jalan Kemboja 11, Taman Aman, 51000 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Siak',
                'jawatan_custom' => null,
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Ahli Jawatankuasa - Penyelaras Program
            [
                'nama' => 'Encik Nazri bin Sulaiman',
                'no_ic' => '790921-01-2267',
                'telefon' => '017-6789012',
                'email' => 'nazri.sulaiman@email.com',
                'alamat' => 'No. 25, Jalan Tanjung 5, Taman Indah, 51100 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Ahli Jawatankuasa',
                'jawatan_custom' => 'Penyelaras Program',
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Ahli Jawatankuasa - Ketua Unit Dakwah
            [
                'nama' => 'Ustaz Imran bin Yusof',
                'no_ic' => '810115-01-9923',
                'telefon' => '018-7890123',
                'email' => 'imran.yusof@email.com',
                'alamat' => 'No. 61, Jalan Raya 8, Taman Jaya, 51200 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Ahli Jawatankuasa',
                'jawatan_custom' => 'Ketua Unit Dakwah',
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Ahli Jawatankuasa - Ketua Unit Kebajikan
            [
                'nama' => 'Encik Firdaus bin Mansor',
                'no_ic' => '840623-01-3345',
                'telefon' => '019-8901234',
                'email' => 'firdaus.mansor@email.com',
                'alamat' => 'No. 73, Jalan Perdana 14, Taman Maju, 51300 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Ahli Jawatankuasa',
                'jawatan_custom' => 'Ketua Unit Kebajikan',
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Ahli Jawatankuasa - Ketua Unit Penyelenggaraan
            [
                'nama' => 'Encik Rizal bin Talib',
                'no_ic' => '770830-01-5501',
                'telefon' => '012-9012345',
                'email' => 'rizal.talib@email.com',
                'alamat' => 'No. 9, Jalan Bestari 2, Taman Cemerlang, 51400 Kuala Lumpur',
                'jantina' => 'Lelaki',
                'jawatan' => 'Ahli Jawatankuasa',
                'jawatan_custom' => 'Ketua Unit Penyelenggaraan',
                'tarikh_lantikan' => $tarikhLantikan,
                'tarikh_tamat' => $tarikhTamat,
                'tempoh_jawatan' => '2 Tahun',
                'status' => 'Aktif',
                'is_archived' => false,
                'masjid_id' => $masjidId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert data
        foreach ($ajkData as $ajk) {
            // Check if this IC already exists
            $exists = DB::table('ajk')
                ->where('no_ic', $ajk['no_ic'])
                ->where('masjid_id', $masjidId)
                ->exists();
            
            if (!$exists) {
                DB::table('ajk')->insert($ajk);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove seeded AJK data for Administrator masjid
        $icNumbers = [
            '650415-01-5523', '680722-01-6789', '780305-01-4455', '750918-01-3367',
            '820614-01-2289', '850227-01-1145', '700812-01-5567', '880503-01-7789',
            '760129-01-4423', '830716-01-6601', '720408-01-8845', '790921-01-2267',
            '810115-01-9923', '840623-01-3345', '770830-01-5501'
        ];

        DB::table('ajk')
            ->where('masjid_id', 1)
            ->whereIn('no_ic', $icNumbers)
            ->delete();
    }
};
