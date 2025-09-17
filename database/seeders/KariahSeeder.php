<?php

namespace Database\Seeders;

use App\Models\Kariah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KariahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user as creator
        $user = User::first();

        $kariahData = [
            [
                'nama' => 'Ahmad bin Ali',
                'no_ic' => '900101-14-1234',
                'telefon' => '012-3456789',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2020-01-15',
                'status' => 'Aktif',
                'zon' => 'Zon A',
                'alamat' => 'No. 123, Jalan Masjid, 96000 Sibu, Sarawak',
                'email' => 'ahmad.ali@email.com',
            ],
            [
                'nama' => 'Siti binti Osman',
                'no_ic' => '920505-08-5678',
                'telefon' => '013-1122334',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2021-03-22',
                'status' => 'Aktif',
                'zon' => 'Zon B',
                'alamat' => 'No. 456, Jalan Pahlawan, 96000 Sibu, Sarawak',
                'email' => 'siti.osman@email.com',
            ],
            [
                'nama' => 'Rahman bin Salleh',
                'no_ic' => '880707-12-2468',
                'telefon' => '017-7788990',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2019-08-10',
                'status' => 'Tidak Aktif',
                'zon' => 'Zon C',
                'alamat' => 'No. 789, Jalan Merdeka, 96000 Sibu, Sarawak',
                'email' => 'rahman.salleh@email.com',
            ],
            [
                'nama' => 'Fatimah binti Hassan',
                'no_ic' => '950312-06-4321',
                'telefon' => '019-9988776',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2022-06-05',
                'status' => 'Aktif',
                'zon' => 'Zon A',
                'alamat' => 'No. 321, Jalan Utama, 96000 Sibu, Sarawak',
                'email' => 'fatimah.hassan@email.com',
            ],
            [
                'nama' => 'Mohd Zulkifli bin Ismail',
                'no_ic' => '870525-10-8765',
                'telefon' => '011-2233445',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2018-09-12',
                'status' => 'Aktif',
                'zon' => 'Zon B',
                'alamat' => 'No. 654, Jalan Sekolah, 96000 Sibu, Sarawak',
                'email' => 'zulkifli.ismail@email.com',
            ],
            [
                'nama' => 'Noraini binti Ahmad',
                'no_ic' => '930808-14-6543',
                'telefon' => '016-1122334',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2020-11-28',
                'status' => 'Tidak Aktif',
                'zon' => 'Zon C',
                'alamat' => 'No. 987, Jalan Hospital, 96000 Sibu, Sarawak',
                'email' => 'noraini.ahmad@email.com',
            ],
            [
                'nama' => 'Abdul Rahim bin Omar',
                'no_ic' => '890415-08-9876',
                'telefon' => '014-5566778',
                'bangsa' => 'Cina Hokkien',
                'tarikh_keahlian' => '2021-04-03',
                'status' => 'Aktif',
                'zon' => 'Zon A',
                'alamat' => 'No. 147, Jalan Pasar, 96000 Sibu, Sarawak',
                'email' => 'abdul.rahim@email.com',
            ],
            [
                'nama' => 'Khadijah binti Yusof',
                'no_ic' => '910620-12-3456',
                'telefon' => '018-9988776',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2019-07-17',
                'status' => 'Aktif',
                'zon' => 'Zon B',
                'alamat' => 'No. 258, Jalan Bandar, 96000 Sibu, Sarawak',
                'email' => 'khadijah.yusof@email.com',
            ],
            [
                'nama' => 'Hassan bin Abdullah',
                'no_ic' => '860303-16-7890',
                'telefon' => '013-4455667',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2017-12-25',
                'status' => 'Tidak Aktif',
                'zon' => 'Zon C',
                'alamat' => 'No. 369, Jalan Taman, 96000 Sibu, Sarawak',
                'email' => 'hassan.abdullah@email.com',
            ],
            [
                'nama' => 'Aminah binti Ibrahim',
                'no_ic' => '940909-20-1111',
                'telefon' => '017-3344556',
                'bangsa' => 'India Tamil',
                'tarikh_keahlian' => '2022-02-08',
                'status' => 'Aktif',
                'zon' => 'Zon A',
                'alamat' => 'No. 741, Jalan Kampung, 96000 Sibu, Sarawak',
                'email' => 'aminah.ibrahim@email.com',
            ],
            [
                'nama' => 'Zulkarnain bin Sulaiman',
                'no_ic' => '850625-18-2222',
                'telefon' => '012-7788990',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2016-03-14',
                'status' => 'Aktif',
                'zon' => 'Zon B',
                'alamat' => 'No. 852, Jalan Ladang, 96000 Sibu, Sarawak',
                'email' => 'zulkarnain.sulaiman@email.com',
            ],
            [
                'nama' => 'Rohana binti Kamal',
                'no_ic' => '920112-24-3333',
                'telefon' => '015-6677889',
                'bangsa' => 'Cina Cantonese',
                'tarikh_keahlian' => '2020-09-30',
                'status' => 'Tidak Aktif',
                'zon' => 'Zon C',
                'alamat' => 'No. 963, Jalan Sungai, 96000 Sibu, Sarawak',
                'email' => 'rohana.kamal@email.com',
            ],
            [
                'nama' => 'Mohd Firdaus bin Aziz',
                'no_ic' => '880918-26-4444',
                'telefon' => '019-1122334',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2018-11-22',
                'status' => 'Aktif',
                'zon' => 'Zon A',
                'alamat' => 'No. 159, Jalan Bukit, 96000 Sibu, Sarawak',
                'email' => 'firdaus.aziz@email.com',
            ],
            [
                'nama' => 'Norhafizah binti Razak',
                'no_ic' => '910425-28-5555',
                'telefon' => '016-9988776',
                'bangsa' => 'Melayu',
                'tarikh_keahlian' => '2019-07-07',
                'status' => 'Aktif',
                'zon' => 'Zon B',
                'alamat' => 'No. 357, Jalan Pantai, 96000 Sibu, Sarawak',
                'email' => 'norhafizah.razak@email.com',
            ],
            [
                'nama' => 'Ahmad Zulkarnain bin Omar',
                'no_ic' => '870630-30-6666',
                'telefon' => '011-4455667',
                'bangsa' => 'Iban',
                'tarikh_keahlian' => '2017-12-18',
                'status' => 'Tidak Aktif',
                'zon' => 'Zon C',
                'alamat' => 'No. 753, Jalan Gunung, 96000 Sibu, Sarawak',
                'email' => 'ahmad.zulkarnain@email.com',
            ],
        ];

        foreach ($kariahData as $data) {
            Kariah::updateOrCreate(
                ['no_ic' => $data['no_ic']], // Find by no_ic
                [
                    ...$data,
                    'created_by' => $user ? $user->id : 1,
                    'updated_by' => $user ? $user->id : 1,
                ]
            );
        }
    }
}
