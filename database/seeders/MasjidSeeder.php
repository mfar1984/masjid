<?php

namespace Database\Seeders;

use App\Models\Masjid;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MasjidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ms_MY');

        // Semenanjung Malaysia (11 negeri)
        $semenanjungMalaysia = [
            'Johor',
            'Kedah',
            'Kelantan',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Perak',
            'Perlis',
            'Pulau Pinang',
            'Selangor',
            'Terengganu',
        ];

        // Wilayah Persekutuan (3 wilayah)
        $wilayahPersekutuan = [
            'Kuala Lumpur',
            'Putrajaya',
            'Labuan',
        ];

        // Sabah & Sarawak (2 negeri)
        $sabahSarawak = [
            'Sabah',
            'Sarawak',
        ];

        // Combine all states and territories
        $negeriList = array_merge($semenanjungMalaysia, $wilayahPersekutuan, $sabahSarawak);

        $masjidNames = [
            'Al-Hidayah', 'Al-Nur', 'Al-Falah', 'Al-Ikhlas', 'Al-Taqwa',
            'Baitul Rahman', 'Baitul Makmur', 'Darul Ehsan', 'Darul Iman',
            'An-Nur', 'As-Salam', 'At-Taubah', 'Al-Furqan', 'Al-Huda',
            'Jamek', 'Sultan', 'Negara', 'Wilayah', 'Bandar'
        ];

        $kategoriList = ['masjid', 'surau', 'musolla'];
        $statusList = ['active', 'pending', 'rejected', 'inactive', 'suspended'];

        // Create sample masjids - ensure all negeri are represented
        $negeriIndex = 0;
        for ($i = 1; $i <= 25; $i++) {
            // Ensure each negeri gets at least one masjid
            if ($i <= count($negeriList)) {
                $negeri = $negeriList[$negeriIndex];
                $negeriIndex++;
            } else {
                $negeri = $faker->randomElement($negeriList);
            }
            $kategori = $faker->randomElement($kategoriList);
            $status = $faker->randomElement($statusList);
            $namaBase = $faker->randomElement($masjidNames);
            
            $nama = ucfirst($kategori) . ' ' . $namaBase;
            if ($kategori === 'masjid') {
                $nama = 'Masjid ' . $namaBase;
            } elseif ($kategori === 'surau') {
                $nama = 'Surau ' . $namaBase;
            } else {
                $nama = 'Musolla ' . $namaBase;
            }

            $masjid = Masjid::create([
                'nama' => $nama,
                'nama_penuh' => $nama . ' ' . $faker->city,
                'alamat' => $faker->address,
                'poskod' => $faker->postcode,
                'bandar' => $faker->city,
                'negeri' => $negeri,
                'telefon' => $faker->phoneNumber,
                'faks' => $faker->optional(0.3)->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'laman_web' => $faker->optional(0.4)->url,
                'latitude' => $faker->latitude(1.0, 7.0), // Malaysia coordinates range
                'longitude' => $faker->longitude(99.0, 119.0),
                'kategori' => $kategori,
                'status' => $status,
                'tarikh_ditubuhkan' => $faker->optional(0.8)->dateTimeBetween('-50 years', '-1 year'),
                'kapasiti_jemaah' => $faker->numberBetween(100, 2000),
                'pendaftar_nama' => $faker->name,
                'pendaftar_telefon' => $faker->phoneNumber,
                'pendaftar_email' => $faker->safeEmail,
                'pendaftar_jawatan' => $faker->randomElement([
                    'Imam', 'Pengerusi AJK', 'Setiausaha', 'Bendahari', 'Ahli AJK'
                ]),
                'diluluskan_oleh' => $status === 'active' ? 1 : null, // Assuming user ID 1 is super admin
                'tarikh_diluluskan' => $status === 'active' ? $faker->dateTimeBetween('-1 year', 'now') : null,
                'catatan_kelulusan' => $status === 'active' ? 'Diluluskan oleh Super Admin' : null,
                'settings' => [
                    'timezone' => 'Asia/Kuala_Lumpur',
                    'currency' => 'MYR',
                    'language' => 'ms',
                    'features_enabled' => $faker->randomElements([
                        'kewangan', 'kariah', 'program', 'aset', 'komunikasi'
                    ], $faker->numberBetween(3, 5)),
                    'notification_settings' => [
                        'email_enabled' => $faker->boolean(80),
                        'sms_enabled' => $faker->boolean(60),
                        'whatsapp_enabled' => $faker->boolean(70),
                    ],
                    'prayer_times_enabled' => $faker->boolean(90),
                    'donation_tracking' => $faker->boolean(85),
                ],
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);

            // Generate kod_masjid after creation
            $masjid->generateKodMasjid();
        }

        // Create authentic Malaysian mosque data
        $authenticMasjids = [
            // WILAYAH PERSEKUTUAN - KUALA LUMPUR
            [
                'nama' => 'Masjid Negara',
                'nama_penuh' => 'Masjid Negara Malaysia',
                'alamat' => 'Jalan Perdana, Tasik Perdana',
                'poskod' => '50480',
                'bandar' => 'Kuala Lumpur',
                'negeri' => 'Kuala Lumpur',
                'telefon' => '03-2693 0564',
                'email' => 'info@masjidnegara.gov.my',
                'laman_web' => 'https://www.masjidnegara.gov.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 15000,
                'pendaftar_nama' => 'Dato\' Ahmad Ibrahim',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(6),
                'tarikh_ditubuhkan' => '1965-08-27',
            ],
            [
                'nama' => 'Masjid Jamek',
                'nama_penuh' => 'Masjid Jamek Kuala Lumpur',
                'alamat' => 'Jalan Tun Perak',
                'poskod' => '50050',
                'bandar' => 'Kuala Lumpur',
                'negeri' => 'Kuala Lumpur',
                'telefon' => '03-2698 7461',
                'email' => 'info@masjidjamek.gov.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 3000,
                'pendaftar_nama' => 'Ustaz Abdullah Rahman',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(12),
                'tarikh_ditubuhkan' => '1909-12-23',
            ],
            [
                'nama' => 'Masjid Wilayah Persekutuan',
                'nama_penuh' => 'Masjid Wilayah Persekutuan Kuala Lumpur',
                'alamat' => 'Jalan Duta',
                'poskod' => '53100',
                'bandar' => 'Kuala Lumpur',
                'negeri' => 'Kuala Lumpur',
                'telefon' => '03-6201 3300',
                'email' => 'admin@masjidwilayah.gov.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 17000,
                'pendaftar_nama' => 'Dato\' Haji Mohd Yusof',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(8),
                'tarikh_ditubuhkan' => '2000-10-14',
            ],

            // WILAYAH PERSEKUTUAN - PUTRAJAYA
            [
                'nama' => 'Masjid Putra',
                'nama_penuh' => 'Masjid Putra Putrajaya',
                'alamat' => 'Presint 1',
                'poskod' => '62000',
                'bandar' => 'Putrajaya',
                'negeri' => 'Putrajaya',
                'telefon' => '03-8888 6000',
                'email' => 'info@masjidputra.gov.my',
                'laman_web' => 'https://www.masjidputra.gov.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 15000,
                'pendaftar_nama' => 'Dato\' Seri Ahmad Zahid',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(10),
                'tarikh_ditubuhkan' => '1999-08-20',
            ],
            [
                'nama' => 'Masjid Tuanku Mizan Zainal Abidin',
                'nama_penuh' => 'Masjid Tuanku Mizan Zainal Abidin (Masjid Besi)',
                'alamat' => 'Presint 3',
                'poskod' => '62000',
                'bandar' => 'Putrajaya',
                'negeri' => 'Putrajaya',
                'telefon' => '03-8889 2100',
                'email' => 'info@masjidbesi.gov.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 20000,
                'pendaftar_nama' => 'Ustaz Dr. Mohd Asri',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(5),
                'tarikh_ditubuhkan' => '2010-09-03',
            ],

            // SELANGOR
            [
                'nama' => 'Masjid Sultan Salahuddin Abdul Aziz Shah',
                'nama_penuh' => 'Masjid Sultan Salahuddin Abdul Aziz Shah (Masjid Biru)',
                'alamat' => 'Persiaran Masjid, Seksyen 14',
                'poskod' => '40000',
                'bandar' => 'Shah Alam',
                'negeri' => 'Selangor',
                'telefon' => '03-5519 7000',
                'email' => 'info@masjidbirushahalam.my',
                'laman_web' => 'https://www.masjidbirushahalam.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 24000,
                'pendaftar_nama' => 'Dato\' Haji Ismail',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(15),
                'tarikh_ditubuhkan' => '1988-03-11',
            ],
            [
                'nama' => 'Masjid Sultan Sulaiman',
                'nama_penuh' => 'Masjid Sultan Sulaiman Klang',
                'alamat' => 'Jalan Istana',
                'poskod' => '41000',
                'bandar' => 'Klang',
                'negeri' => 'Selangor',
                'telefon' => '03-3371 2912',
                'email' => 'info@masjidsultansulaiman.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 5000,
                'pendaftar_nama' => 'Ustaz Haji Rahman',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(18),
                'tarikh_ditubuhkan' => '1932-05-15',
            ],

            // JOHOR
            [
                'nama' => 'Masjid Sultan Abu Bakar',
                'nama_penuh' => 'Masjid Sultan Abu Bakar Johor Bahru',
                'alamat' => 'Jalan Gertak Merah',
                'poskod' => '80000',
                'bandar' => 'Johor Bahru',
                'negeri' => 'Johor',
                'telefon' => '07-223 4567',
                'email' => 'info@masjidsultanabubakar.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 8000,
                'pendaftar_nama' => 'Dato\' Haji Mahmud',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(20),
                'tarikh_ditubuhkan' => '1900-01-01',
            ],
            [
                'nama' => 'Masjid Negeri Johor',
                'nama_penuh' => 'Masjid Negeri Johor Bahru',
                'alamat' => 'Jalan Air Molek',
                'poskod' => '80000',
                'bandar' => 'Johor Bahru',
                'negeri' => 'Johor',
                'telefon' => '07-224 8901',
                'email' => 'info@masjidnegerijohor.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 6000,
                'pendaftar_nama' => 'Ustaz Ahmad Johari',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(14),
                'tarikh_ditubuhkan' => '1892-06-12',
            ],

            // KEDAH
            [
                'nama' => 'Masjid Zahir',
                'nama_penuh' => 'Masjid Zahir Alor Setar',
                'alamat' => 'Jalan Masjid',
                'poskod' => '05000',
                'bandar' => 'Alor Setar',
                'negeri' => 'Kedah',
                'telefon' => '04-731 2345',
                'email' => 'info@masjidzahir.my',
                'laman_web' => 'https://www.masjidzahir.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 5500,
                'pendaftar_nama' => 'Dato\' Ustaz Ibrahim',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(25),
                'tarikh_ditubuhkan' => '1912-10-15',
            ],
            [
                'nama' => 'Masjid Al-Bukhary',
                'nama_penuh' => 'Masjid Al-Bukhary Alor Setar',
                'alamat' => 'Jalan Langgar',
                'poskod' => '05460',
                'bandar' => 'Alor Setar',
                'negeri' => 'Kedah',
                'telefon' => '04-771 8888',
                'email' => 'info@masjidalbukhary.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 10000,
                'pendaftar_nama' => 'Tan Sri Syed Mokhtar',
                'pendaftar_jawatan' => 'Pengerusi',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(8),
                'tarikh_ditubuhkan' => '2007-11-23',
            ],

            // KELANTAN
            [
                'nama' => 'Masjid Kampung Laut',
                'nama_penuh' => 'Masjid Kampung Laut Tumpat',
                'alamat' => 'Kampung Laut',
                'poskod' => '16200',
                'bandar' => 'Tumpat',
                'negeri' => 'Kelantan',
                'telefon' => '09-725 1234',
                'email' => 'info@masjidkampunglaut.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 2000,
                'pendaftar_nama' => 'Tok Guru Nik Abdul Aziz',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(30),
                'tarikh_ditubuhkan' => '1730-01-01',
            ],
            [
                'nama' => 'Masjid Muhammadi',
                'nama_penuh' => 'Masjid Muhammadi Kota Bharu',
                'alamat' => 'Jalan Hilir Kota',
                'poskod' => '15000',
                'bandar' => 'Kota Bharu',
                'negeri' => 'Kelantan',
                'telefon' => '09-748 5678',
                'email' => 'info@masjidmuhammadi.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 4000,
                'pendaftar_nama' => 'Ustaz Nik Aziz',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(16),
                'tarikh_ditubuhkan' => '1867-03-20',
            ],

            // PERAK
            [
                'nama' => 'Masjid Ubudiah',
                'nama_penuh' => 'Masjid Ubudiah Kuala Kangsar',
                'alamat' => 'Jalan Istana, Bukit Chandan',
                'poskod' => '33000',
                'bandar' => 'Kuala Kangsar',
                'negeri' => 'Perak',
                'telefon' => '05-776 2345',
                'email' => 'info@masjidubudiah.my',
                'laman_web' => 'https://www.masjidubudiah.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 3500,
                'pendaftar_nama' => 'Dato\' Seri Ahmad Tajuddin',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(22),
                'tarikh_ditubuhkan' => '1917-10-28',
            ],
            [
                'nama' => 'Masjid Panglima Kinta',
                'nama_penuh' => 'Masjid Panglima Kinta Ipoh',
                'alamat' => 'Jalan Masjid',
                'poskod' => '30000',
                'bandar' => 'Ipoh',
                'negeri' => 'Perak',
                'telefon' => '05-254 7890',
                'email' => 'info@masjidpanglimarkinta.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 2500,
                'pendaftar_nama' => 'Ustaz Haji Yusof',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(12),
                'tarikh_ditubuhkan' => '1898-07-14',
            ],

            // PULAU PINANG
            [
                'nama' => 'Masjid Kapitan Keling',
                'nama_penuh' => 'Masjid Kapitan Keling George Town',
                'alamat' => 'Lebuh Buckingham',
                'poskod' => '10200',
                'bandar' => 'George Town',
                'negeri' => 'Pulau Pinang',
                'telefon' => '04-261 4516',
                'email' => 'info@masjidkapitankeling.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 1500,
                'pendaftar_nama' => 'Haji Abdul Rahman',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(28),
                'tarikh_ditubuhkan' => '1801-12-01',
            ],
            [
                'nama' => 'Masjid Negeri Pulau Pinang',
                'nama_penuh' => 'Masjid Negeri Pulau Pinang',
                'alamat' => 'Jalan Air Itam',
                'poskod' => '10460',
                'bandar' => 'George Town',
                'negeri' => 'Pulau Pinang',
                'telefon' => '04-228 9012',
                'email' => 'info@masjidnegeripp.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 5000,
                'pendaftar_nama' => 'Dato\' Ustaz Wan Salim',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(18),
                'tarikh_ditubuhkan' => '1978-08-31',
            ],

            // TERENGGANU
            [
                'nama' => 'Masjid Abidin',
                'nama_penuh' => 'Masjid Abidin Kuala Terengganu',
                'alamat' => 'Jalan Masjid Abidin',
                'poskod' => '20000',
                'bandar' => 'Kuala Terengganu',
                'negeri' => 'Terengganu',
                'telefon' => '09-622 1055',
                'email' => 'info@masjidabidin.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 4000,
                'pendaftar_nama' => 'Dato\' Ustaz Ahmad Awang',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(35),
                'tarikh_ditubuhkan' => '1808-01-01',
            ],
            [
                'nama' => 'Masjid Kristal',
                'nama_penuh' => 'Masjid Kristal Kuala Terengganu',
                'alamat' => 'Pulau Wan Man, Losong Panglima Perang',
                'poskod' => '21000',
                'bandar' => 'Kuala Terengganu',
                'negeri' => 'Terengganu',
                'telefon' => '09-630 8080',
                'email' => 'info@masjidkristal.my',
                'laman_web' => 'https://www.masjidkristal.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 1500,
                'pendaftar_nama' => 'Ustaz Dr. Zulkifli',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(3),
                'tarikh_ditubuhkan' => '2008-02-08',
            ],

            // MELAKA
            [
                'nama' => 'Masjid Kampung Hulu',
                'nama_penuh' => 'Masjid Kampung Hulu Melaka',
                'alamat' => 'Jalan Kampung Hulu',
                'poskod' => '75200',
                'bandar' => 'Melaka',
                'negeri' => 'Melaka',
                'telefon' => '06-284 8901',
                'email' => 'info@masjidkampunghulu.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 1000,
                'pendaftar_nama' => 'Haji Abdul Rahim',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(40),
                'tarikh_ditubuhkan' => '1728-01-01',
            ],
            [
                'nama' => 'Masjid Al-Azim',
                'nama_penuh' => 'Masjid Al-Azim (Masjid Negeri Melaka)',
                'alamat' => 'Jalan Hang Tuah',
                'poskod' => '75300',
                'bandar' => 'Melaka',
                'negeri' => 'Melaka',
                'telefon' => '06-232 1234',
                'email' => 'info@masjidalazim.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 3000,
                'pendaftar_nama' => 'Dato\' Haji Ismail',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(15),
                'tarikh_ditubuhkan' => '1982-08-31',
            ],

            // NEGERI SEMBILAN
            [
                'nama' => 'Masjid Negeri Sembilan',
                'nama_penuh' => 'Masjid Negeri Sembilan Seremban',
                'alamat' => 'Jalan Dato\' Abdul Kadir',
                'poskod' => '70100',
                'bandar' => 'Seremban',
                'negeri' => 'Negeri Sembilan',
                'telefon' => '06-763 9354',
                'email' => 'info@masjidnegerisembilan.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 4000,
                'pendaftar_nama' => 'Dato\' Ustaz Ahmad',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(20),
                'tarikh_ditubuhkan' => '1967-08-31',
            ],

            // PAHANG
            [
                'nama' => 'Masjid Sultan Ahmad Shah',
                'nama_penuh' => 'Masjid Sultan Ahmad Shah (Masjid Negeri Pahang)',
                'alamat' => 'Jalan Mahkota',
                'poskod' => '25000',
                'bandar' => 'Kuantan',
                'negeri' => 'Pahang',
                'telefon' => '09-515 1234',
                'email' => 'info@masjidsultanahmadshah.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 5000,
                'pendaftar_nama' => 'Dato\' Ustaz Mahmud',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(18),
                'tarikh_ditubuhkan' => '1991-02-14',
            ],

            // PERLIS
            [
                'nama' => 'Masjid Alwi',
                'nama_penuh' => 'Masjid Alwi Kangar',
                'alamat' => 'Jalan Kangar-Alor Setar',
                'poskod' => '01000',
                'bandar' => 'Kangar',
                'negeri' => 'Perlis',
                'telefon' => '04-976 1234',
                'email' => 'info@masjidalwi.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 2000,
                'pendaftar_nama' => 'Ustaz Ahmad Sirajuddin',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(25),
                'tarikh_ditubuhkan' => '1933-01-01',
            ],

            // SABAH
            [
                'nama' => 'Masjid Negeri Sabah',
                'nama_penuh' => 'Masjid Negeri Sabah Kota Kinabalu',
                'alamat' => 'Jalan Tunku Abdul Rahman',
                'poskod' => '88000',
                'bandar' => 'Kota Kinabalu',
                'negeri' => 'Sabah',
                'telefon' => '088-232 1234',
                'email' => 'info@masjidnegerisabah.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 6000,
                'pendaftar_nama' => 'Dato\' Haji Yusof',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(12),
                'tarikh_ditubuhkan' => '1977-08-31',
            ],

            // SARAWAK
            [
                'nama' => 'Masjid Negeri Sarawak',
                'nama_penuh' => 'Masjid Negeri Sarawak Kuching',
                'alamat' => 'Jalan Masjid',
                'poskod' => '93400',
                'bandar' => 'Kuching',
                'negeri' => 'Sarawak',
                'telefon' => '082-240 1234',
                'email' => 'info@masjidnegerisarawak.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 4500,
                'pendaftar_nama' => 'Dato\' Ustaz Ibrahim',
                'pendaftar_jawatan' => 'Imam Besar',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(16),
                'tarikh_ditubuhkan' => '1968-09-16',
            ],

            // LABUAN
            [
                'nama' => 'Masjid An-Najihin',
                'nama_penuh' => 'Masjid An-Najihin Labuan',
                'alamat' => 'Jalan Merdeka',
                'poskod' => '87000',
                'bandar' => 'Labuan',
                'negeri' => 'Labuan',
                'telefon' => '087-412 345',
                'email' => 'info@masjidannajihin.my',
                'kategori' => 'masjid',
                'status' => 'active',
                'kapasiti_jemaah' => 1500,
                'pendaftar_nama' => 'Haji Abdul Rahman',
                'pendaftar_jawatan' => 'Imam',
                'diluluskan_oleh' => 1,
                'tarikh_diluluskan' => now()->subMonths(10),
                'tarikh_ditubuhkan' => '1984-05-16',
            ],

            // Add some pending masjids for testing approve/reject
            [
                'nama' => 'Masjid Al-Hidayah',
                'nama_penuh' => 'Masjid Al-Hidayah Bangi',
                'alamat' => 'Jalan 3/1, Bandar Baru Bangi',
                'poskod' => '43650',
                'bandar' => 'Bangi',
                'negeri' => 'Selangor',
                'telefon' => '03-8925 4567',
                'email' => 'info@masjidalhidayah.my',
                'kategori' => 'masjid',
                'status' => 'pending',
                'kapasiti_jemaah' => 800,
                'pendaftar_nama' => 'Ustaz Ahmad Fauzi',
                'pendaftar_jawatan' => 'Imam',
                'pendaftar_telefon' => '019-234 5678',
                'pendaftar_email' => 'ahmad.fauzi@gmail.com',
                'tarikh_ditubuhkan' => '2023-01-15',
            ],
            [
                'nama' => 'Surau An-Nur',
                'nama_penuh' => 'Surau An-Nur Taman Melawati',
                'alamat' => 'Jalan Melawati 2/3',
                'poskod' => '53100',
                'bandar' => 'Kuala Lumpur',
                'negeri' => 'Kuala Lumpur',
                'telefon' => '03-4108 9876',
                'email' => 'info@surauannur.my',
                'kategori' => 'surau',
                'status' => 'pending',
                'kapasiti_jemaah' => 300,
                'pendaftar_nama' => 'Haji Mohd Yusof',
                'pendaftar_jawatan' => 'Pengerusi AJK',
                'pendaftar_telefon' => '012-345 6789',
                'pendaftar_email' => 'yusof.haji@yahoo.com',
                'tarikh_ditubuhkan' => '2023-06-20',
            ],
            [
                'nama' => 'Musolla At-Taqwa',
                'nama_penuh' => 'Musolla At-Taqwa Cyberjaya',
                'alamat' => 'Persiaran Cyberpoint Selatan',
                'poskod' => '63000',
                'bandar' => 'Cyberjaya',
                'negeri' => 'Selangor',
                'telefon' => '03-8318 5432',
                'email' => 'info@musollaattaqwa.my',
                'kategori' => 'musolla',
                'status' => 'pending',
                'kapasiti_jemaah' => 150,
                'pendaftar_nama' => 'Dr. Ahmad Zaki',
                'pendaftar_jawatan' => 'Pengerusi',
                'pendaftar_telefon' => '017-890 1234',
                'pendaftar_email' => 'ahmad.zaki@cyberjaya.my',
                'tarikh_ditubuhkan' => '2023-09-10',
            ],
        ];

        foreach ($authenticMasjids as $masjidData) {
            $masjid = Masjid::create($masjidData);
            $masjid->generateKodMasjid();
        }
    }
}
