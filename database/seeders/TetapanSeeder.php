<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tetapan;
use App\Models\Masjid;

class TetapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active masjids
        $masjids = Masjid::where('status', 'active')->get();

        // Default settings for each masjid
        $defaultSettings = [
            // Tetapan Umum
            [
                'kunci' => 'nama_sistem',
                'nama' => 'Nama Sistem',
                'nilai' => 'E-Masjid',
                'jenis' => 'text',
                'penerangan' => 'Nama rasmi sistem pengurusan masjid',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 1,
            ],
            [
                'kunci' => 'versi_sistem',
                'nama' => 'Versi Sistem',
                'nilai' => '1.0.0',
                'jenis' => 'text',
                'penerangan' => 'Versi semasa sistem (tidak boleh diubah)',
                'boleh_edit' => false,
                'kategori' => 'umum',
                'susunan' => 2,
            ],
            [
                'kunci' => 'alamat_sistem',
                'nama' => 'Alamat Sistem',
                'nilai' => '',
                'jenis' => 'text',
                'penerangan' => 'Alamat rasmi sistem',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 3,
            ],
            [
                'kunci' => 'default_latitude',
                'nama' => 'Latitude Default',
                'nilai' => '2.3000',
                'jenis' => 'number',
                'penerangan' => 'Latitude default untuk maps (Kuching: 2.3000)',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 4,
            ],
            [
                'kunci' => 'default_longitude',
                'nama' => 'Longitude Default',
                'nilai' => '111.8167',
                'jenis' => 'number',
                'penerangan' => 'Longitude default untuk maps (Kuching: 111.8167)',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 5,
            ],
            [
                'kunci' => 'prayer_zone',
                'nama' => 'Zon Waktu Solat (e‑Solat JAKIM)',
                'nilai' => 'SWK08',
                'jenis' => 'select',
                'penerangan' => 'Pilih zon waktu solat JAKIM untuk paparan widget e-Solat. Meliputi semua negeri di Malaysia.',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 6,
            ],
            [
                'kunci' => 'azan_enabled',
                'nama' => 'Auto-Play Azan',
                'nilai' => '1',
                'jenis' => 'boolean',
                'penerangan' => 'Automatik mainkan azan ketika masuk waktu solat fajr, zuhr, asr, maghrib dan isha',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 7,
            ],
            [
                'kunci' => 'azan_type',
                'nama' => 'Jenis Azan',
                'nilai' => 'makkah',
                'jenis' => 'select',
                'penerangan' => 'Pilih jenis azan yang akan dimainkan - Makkah (tradisional) atau Madinah (moden)',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 8,
            ],
            [
                'kunci' => 'azan_volume',
                'nama' => 'Volume Azan',
                'nilai' => '0.7',
                'jenis' => 'number',
                'penerangan' => 'Tahap volume azan (0.1 hingga 1.0). Nilai 0.7 adalah sederhana',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 9,
            ],
            [
                'kunci' => 'azan_fajr_type',
                'nama' => 'Azan Khusus Subuh/Fajr',
                'nilai' => 'madinah-fajr',
                'jenis' => 'select',
                'penerangan' => 'Pilih azan khusus untuk waktu Subuh/Fajr yang berbeza dari azan biasa',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 10,
            ],
            [
                'kunci' => 'azan_regular_type',
                'nama' => 'Azan Waktu Biasa (Zuhr/Asr/Maghrib/Isha)',
                'nilai' => 'makkah',
                'jenis' => 'select',
                'penerangan' => 'Pilih azan untuk waktu solat biasa - Zuhr, Asr, Maghrib, Isha',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 11,
            ],

            // Tetapan Sistem
            [
                'kunci' => 'max_login_attempts',
                'nama' => 'Maksimum Percubaan Login',
                'nilai' => '5',
                'jenis' => 'number',
                'penerangan' => 'Bilangan maksimum percubaan login sebelum akaun dikunci',
                'boleh_edit' => true,
                'kategori' => 'sistem',
                'susunan' => 1,
            ],
            [
                'kunci' => 'session_timeout',
                'nama' => 'Masa Tamat Sesi (minit)',
                'nilai' => '60',
                'jenis' => 'number',
                'penerangan' => 'Masa dalam minit sebelum sesi tamat secara automatik',
                'boleh_edit' => true,
                'kategori' => 'sistem',
                'susunan' => 2,
            ],

            // Tetapan reCAPTCHA
            [
                'kunci' => 'recaptcha_enabled',
                'nama' => 'Aktifkan reCAPTCHA',
                'nilai' => '0',
                'jenis' => 'boolean',
                'penerangan' => 'Aktifkan atau nyahaktifkan reCAPTCHA untuk melindungi feedback form dari spam',
                'boleh_edit' => true,
                'kategori' => 'recaptcha',
                'susunan' => 1,
            ],
            [
                'kunci' => 'recaptcha_site_key',
                'nama' => 'reCAPTCHA Site Key',
                'nilai' => '',
                'jenis' => 'text',
                'penerangan' => 'Public key dari Google reCAPTCHA Console',
                'boleh_edit' => true,
                'kategori' => 'recaptcha',
                'susunan' => 2,
            ],
            [
                'kunci' => 'recaptcha_secret_key',
                'nama' => 'reCAPTCHA Secret Key',
                'nilai' => '',
                'jenis' => 'text',
                'penerangan' => 'Private key dari Google reCAPTCHA Console',
                'boleh_edit' => true,
                'kategori' => 'recaptcha',
                'susunan' => 3,
            ],
        ];

        // Create settings for each masjid
        foreach ($masjids as $masjid) {
            foreach ($defaultSettings as $setting) {
                // Check if setting already exists for this masjid
                $exists = Tetapan::where('masjid_id', $masjid->id)
                    ->where('kunci', $setting['kunci'])
                    ->exists();

                if (!$exists) {
                    Tetapan::create(array_merge($setting, [
                        'masjid_id' => $masjid->id,
                        'created_by' => null,
                        'updated_by' => null,
                    ]));
                }
            }

            $this->command->info("Default settings created for masjid: {$masjid->nama}");
        }

        // Create default personal settings for Super Admin (masjid_id = null)
        $superAdminSettings = [
            [
                'kunci' => 'prayer_zone',
                'nama' => 'Zon Waktu Solat (e‑Solat JAKIM)',
                'nilai' => 'SWK08',
                'jenis' => 'select',
                'penerangan' => 'Zon waktu solat peribadi Super Admin untuk topbar display',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 1,
                'masjid_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'kunci' => 'azan_enabled',
                'nama' => 'Auto-Play Azan',
                'nilai' => '1',
                'jenis' => 'boolean',
                'penerangan' => 'Automatik mainkan azan ketika masuk waktu solat - Super Admin',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 2,
                'masjid_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'kunci' => 'azan_type',
                'nama' => 'Jenis Azan',
                'nilai' => 'makkah',
                'jenis' => 'select',
                'penerangan' => 'Pilih jenis azan - Super Admin personal',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 3,
                'masjid_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'kunci' => 'azan_volume',
                'nama' => 'Volume Azan',
                'nilai' => '0.7',
                'jenis' => 'number',
                'penerangan' => 'Tahap volume azan - Super Admin personal',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 4,
                'masjid_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'kunci' => 'azan_fajr_type',
                'nama' => 'Azan Khusus Subuh/Fajr',
                'nilai' => 'madinah-fajr',
                'jenis' => 'select',
                'penerangan' => 'Azan khusus Subuh/Fajr - Super Admin personal',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 5,
                'masjid_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'kunci' => 'azan_regular_type',
                'nama' => 'Azan Waktu Biasa',
                'nilai' => 'makkah',
                'jenis' => 'select',
                'penerangan' => 'Azan waktu biasa (Zuhr/Asr/Maghrib/Isha) - Super Admin personal',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 6,
                'masjid_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ]
        ];

        foreach ($superAdminSettings as $setting) {
            $exists = Tetapan::whereNull('masjid_id')
                ->where('kunci', $setting['kunci'])
                ->exists();

            if (!$exists) {
                Tetapan::create($setting);
            }
        }

        $this->command->info('Super Admin personal settings created!');
        $this->command->info('Tetapan seeder completed successfully!');
    }
}
